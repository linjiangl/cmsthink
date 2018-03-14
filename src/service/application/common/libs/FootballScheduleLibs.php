<?php
/**
 * FootballScheduleLibs.php
 * ---
 * Created on 2018/3/14 下午2:43
 * Created by linjiangl
 */

namespace app\common\libs;


class FootballScheduleLibs
{
	protected $redis = null;
	protected $index = 'football_schedule';

	public function __construct()
	{
		$this->redis = RedisLibs::instance();
	}

	public function lists($search = [])
	{
		if ($search) {
			$where = [];
			foreach ($search as $k => $v) {
				$where[] = $this->handelSearchIndex($k, $v);
			}
			$ids = $this->redis->sInter(...$where);
		} else {
			$ids = $this->redis->sMembers($this->index);
		}

		$list = [];
		if ($ids) {
			foreach ($ids as $val) {
				$list[] = $this->info($val);
			}
		}
		return $list;
	}

	public function info($id)
	{
		$hIndex = $this->handelHIndex($id);
		return $this->redis->hGetAll($hIndex);
	}

	public function add($apiData)
	{
		$data = $this->handleApiData($apiData);
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

	public function save($apiData)
	{
		$data = $this->handleApiData($apiData);
		$id = $data['game_id'];
		$hIndex = $this->handelHIndex($id);
		$oldData = $this->info($id);

		//数据不存在
		if (!$oldData) {
			return false;
		}

		$this->redis->hMset($hIndex, $data);

		//搜索字段
		$this->setSearch($oldData, 'rm');
		$this->setSearch($data);

		return true;
	}

	public function rm($id)
	{
		$hIndex = $this->handelHIndex($id);
		$hKeys = $this->redis->hKeys($hIndex);
		$this->setSearch($this->info($id), 'rm');
		$this->redis->hDel($hIndex, ...$hKeys);
		$this->redis->sRem($this->index, $id);
	}

	public function handleApiData($apiData)
	{
		$fields = [
			'id'     => 'game_id',
			'status' => 'status',
			'rank'   => 'home_rank',
			'dian'   => 'home_dian',
			'date'   => 'date',
		];

		$data = [];
		foreach ($apiData as $k => $v) {
			$data[$fields[$k]] = $v;
		}

		return $data;
	}

	protected function setSearch($data, $type = 'add')
	{
		if (!$data) {
			return false;
		}

		$search = ['status', 'date'];
		switch ($type) {
			case 'rm':
				foreach ($search as $v) {
					$this->redis->sRem($this->handelSearchIndex($v, $data[$v]), $data['game_id']);
				}
				break;
			default:
				foreach ($search as $v) {
					$this->redis->sAdd($this->handelSearchIndex($v, $data[$v]), $data['game_id']);
				}
		}
	}

	protected function handelSearchIndex($type, $val)
	{
		switch ($type) {
			case 'status':
				$index = $this->index . ':search:status:' . $val;
				break;
			case 'date':
				$index = $this->index . ':search:date:' . $val;
				break;
			default:
				$index = '';
		}

		return $index;
	}

	protected function handelHIndex($id)
	{
		return $this->index . ':id:' . $id;
	}
}