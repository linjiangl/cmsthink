<?php
/**
 * AuthMenuModel.php
 * ---
 * Created on 2018/2/2 下午3:46
 * Created by linjiangl
 */

namespace app\common\model;


class AuthMenuModel extends BaseModel
{
	const STATUS_NORMAL = 1; //正常
	const STATUS_FORBID = -1; //禁用

	protected $pk = 'id';
	protected $table = 'auth_menu';
	protected $autoWriteTimestamp = true;
	protected $createTime = 'create_time';
	protected $updateTime = 'update_time';
	protected $resultSetType = 'collection';

	public function getLevelMenus($pid = 0, $status = 0, $lev = 1)
	{
		$where['pid'] = $pid;
		switch ($status) {
			case 1:
				$where['status'] = self::STATUS_NORMAL;
				break;
			case -1:
				$where['status'] = self::STATUS_FORBID;
				break;
		}
		$list = $this->where($where)->order('sort', 'asc')->select()->toArray();

		$data = [];
		foreach ($list as $k => $v) {
			$v['lev'] = $lev;

			$data[$v['id']] = $v;
		}

		return $data;
	}

	/**
	 * 菜单权限
	 * @return array
	 */
	public function menuGroups()
	{
		$model = new AuthGroupModel();
		$groups = $model->authGroups();
		$handleGroups = array_combine(array_column($groups, 'id'), array_column($groups, 'name'));
		$list = $this->where('status', self::STATUS_NORMAL)->order('sort', 'asc')->select()->toArray();
		foreach ($list as $k => $v) {
			if ($v['group_ids']) {
				$v['group_ids'] = explode(',', $v['group_ids']);
				foreach ($v['group_ids'] as $vv) {
					$v['groups'][] = $handleGroups[$vv];
				}
			} else {
				$v['group_ids'] = [];
				$v['groups'] = [];
			}
			$list[$k] = $v;
		}
		return $list;
	}

}