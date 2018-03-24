<?php
/**
 * BaseBusiness.php
 * ---
 * Created on 2018/3/20 下午1:36
 * Created by linjiangl
 */

namespace app\common\business;

use app\common\libs\RedisLibs;
use think\exception\HttpException;

class BaseBusiness
{
	public $redis = null;
	public $index = '';
	public $search = [];
	public $pKey = '';
	public $statusDel = 8;

	public function __construct()
	{
		$this->redis = RedisLibs::instance();
	}

	/**
	 * 查询
	 * @param array $search
	 * @param int $page
	 * @param int $limit
	 * @param string $order {倒序:desc, 顺序:asc} 例如: date desc
	 * @return array
	 *
	 * $search 参数格式:
	 *
	 * [
	 * 		['status', '=', 2],
	 * 		['id', 'in', [1,2,3,4]],  // in操作
	 * 		['status', 'between', [1, 8]], // between操作
	 * ]
	 *
	 */
	public function lists($search = [], $page = 1, $limit = 5000, string $order = '')
	{
		//没有条件
		if (empty($search)) {
			return $this->handleListsData($this->redis->sMembers($this->index), $page, $limit);
		}

		//主键处理
		$pIds = [];
		foreach ($search as $k => $v) {
			if (!is_array($v) || count($v) !== 3) {
				throw new HttpException(412, 'search 参数格式错误');
			}
			if ($this->pKey == $v[0]) {
				switch ($v[1]) {
					case 'in':
						$pIds = $v[2];
						break;
					case 'between':
						throw new HttpException(412, $this->pKey . '参数不支持between查询,等我优化');
						break;
					default:
						$pIds[] = $v[2];
				}
				unset($search[$k]);
				break;
			}
		}

		if ($pIds && empty($search)) {
			return $this->handleListsData($pIds, $page, $limit);
		}

		$where = [];
		foreach ($search as $k => $v) {
			switch ($v[1]) {
				case 'in':
					$tmpKey = $this->index . ':search:sUnion:' . $v[1];
					$tmpWhere = [];
					foreach (array_unique($v[2]) as $vv) {
						$tmpWhere[] = $this->handelSearchIndex($v[0], $vv);
					}
					$this->redis->sUnionStore($tmpKey, ...$tmpWhere);
					$where[] = $tmpKey;
					break;

				case 'between':
					if (count($v[2]) != 2) {
						throw new HttpException(412, 'between类型值错误');
					}
					if ($v[2][0] > $v[2][1]) {
						$v[2][0] = $v[2][0] + $v[2][1];
						$v[2][1] = $v[2][0] - $v[2][1];
						$v[2][0] = $v[2][0] - $v[2][1];
					}
					$tmpIndex = str_replace('999', '', $this->handelSearchIndex($v[0], '999'));
					$tmpKeys = $this->redis->keys($tmpIndex . '*');
					$zAddIndex = $this->index . ':search:zAdd:' . $v[0];
					$this->redis->zRem($zAddIndex, ...$tmpKeys);
					foreach ($tmpKeys as $zVal) {
						$this->redis->zAdd($zAddIndex, str_replace($tmpIndex, '', $zVal), $zVal);
					}

					$tmpRange = $this->redis->zRangeByScore($zAddIndex, $v[2][0], $v[2][1]);
					if ($tmpRange) {
						$tmpKey = $this->index . ':search:sUnion:' . $v[1];
						$tmpWhere = [];
						foreach ($tmpRange as $rangeVal) {
							$tmpWhere[] = $rangeVal;
						}
						$this->redis->sUnionStore($tmpKey, ...$tmpWhere);
						$where[] = $tmpKey;
					}
					break;

				default:
					$where[] = $this->handelSearchIndex($v[0], $v[2]);
			}
		}

		$ids = [];
		if ($where) {
			$ids = $this->redis->sInter(...$where);
		}
		if ($pIds) {
			$ids = array_intersect($ids, $pIds);
		}

		return $this->handleListsData($ids, $page, $limit, $order);
	}

	/**
	 * 详情
	 * @param $id
	 * @return array
	 */
	public function info($id)
	{
		$hIndex = $this->handelHIndex($id);
		$info = $this->redis->hGetAll($hIndex);
		return $info;
	}

	/**
	 * 添加
	 * @param array $data
	 * @return bool
	 */
	public function add(array $data)
	{
		if (empty($data)) {
			return false;
		}
		$id = $data['game_id'];
		//已存在, 直接返回
		if ($this->redis->sIsMember($this->index, $id)) {
			return false;
		}
		$this->redis->sAdd($this->index, $id);
		$hIndex = $this->handelHIndex($id);
		$this->redis->hMset($hIndex, $data);
		//搜索字段
		$this->setSearch($data);

		return true;
	}

	/**
	 * 保存数据
	 * @param array $data
	 * @return bool
	 */
	public function save(array $data)
	{
		if (empty($data)) {
			return false;
		}
		$id = $data['game_id'];
		$oldData = $this->info($id);
		//数据不存在
		if (!$oldData) {
			return false;
		}
		$hIndex = $this->handelHIndex($id);
		$this->redis->hMset($hIndex, $data);
		//搜索字段
		$this->setSearch($oldData, 'rm');
		$this->setSearch($data);

		return true;
	}

	/**
	 * 删除数据
	 * @param $id
	 * @return bool
	 */
	public function rm($id)
	{
		if (!$this->info($id)) {
			return false;
		}
		$hIndex = $this->handelHIndex($id);
		$hKeys = $this->redis->hKeys($hIndex);
		$this->setSearch($this->info($id), 'rm');
		$this->redis->hDel($hIndex, ...$hKeys);
		$this->redis->sRem($this->index, $id);

		return true;
	}


	/**
	 * 清空数据
	 */
	public function rmAll()
	{
		$this->redis->flushDB();
	}

	/**
	 * 处理列表数据
	 * @param $ids
	 * @param $page
	 * @param $limit
	 * @param string $order
	 * @return array
	 */
	protected function handleListsData(array $ids, $page, $limit, string $order = '')
	{
		$list = [];
		if ($ids) {
			sort($ids);
			$page = $page > 0 ? $page : 1;
			$offset = ($page - 1) * $limit;
			$ids = array_slice($ids, $offset, $limit);
			foreach ($ids as $val) {
				$list[] = $this->info($val);
			}

			if ($order && $list) {
				$sign = '|';
				$order = preg_replace('/\s+/', $sign, $order);
				$order = explode($sign, $order);
				if (count($order) == 2) {
					$list = $this->handleDataSort($list, $order[0], $order[1]);
				}
			}
		}
		return $list;
	}

	/**
	 * 处理明细数据
	 * @param $data
	 * @return mixed
	 */
	protected function handleData($data)
	{
		if (!$data) {
			return $data;
		}
		$data['logo'] = $data['logo'] . '?imageView2/2/w/40';
		return $data;
	}

	/**
	 * 数据排序
	 * @param $data
	 * @param $filed
	 * @param string $type
	 * @return array
	 */
	public function handleDataSort($data, $filed, $type = 'desc')
	{
		$tmp = [];
		foreach ($data as $k => $v) {
			$tmp[$k] = $v[$filed];
		}
		if ($type == 'desc') {
			arsort($tmp);
		} else {
			asort($tmp);
		}

		$sortData = [];
		foreach ($tmp as $k => $v) {
			$sortData[] = $data[$k];
		}

		return $sortData;
	}

	/**
	 * 主键索引
	 * @param $id
	 * @return string
	 */
	public function handelHIndex($id)
	{
		return $this->index . ':id:' . $id;
	}

	/**
	 * 处理搜索数据
	 * @param $type
	 * @param $val
	 * @return string
	 */
	public function handelSearchIndex($type, $val)
	{
		$index = '';
		if (!preg_match('/^[0-9a-zA-Z]+$/', $val)) {
			$val = md5($val);
		}
		foreach ($this->search as $keyword) {
			if ($type == $keyword) {
				$index = $this->index . ":search:{$keyword}:" . $val;
			}
		}
		return $index;
	}

	/**
	 * 设置搜索条件
	 * @param $data
	 * @param string $type
	 * @return bool
	 */
	public function setSearch($data, $type = 'add')
	{
		if (!$data) {
			return false;
		}
		switch ($type) {
			case 'rm':
				foreach ($this->search as $v) {
					$this->redis->sRem($this->handelSearchIndex($v, $data[$v]), $data['game_id']);
				}
				break;
			default:
				foreach ($this->search as $v) {
					$this->redis->sAdd($this->handelSearchIndex($v, $data[$v]), $data['game_id']);
				}
		}
	}

	/**
	 * 处理保存数据
	 * @param array $save
	 * @param array $update
	 * @return array
	 */
	protected function handleSaveData(array $save, array $update)
	{
		if (!$save) {
			return [];
		}
		foreach ($update as $k => $v) {
			$save[$k] = $update[$k];
		}

		return $save;
	}
}