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

	/**
	 * 查询子菜单
	 *
	 * @param array $menus 所有菜单
	 * @param int $pid 父级ID
	 * @param int $lev 层次
	 * @return array
	 */
	public function getSubMenus($menus = [], $pid = 0, $lev = 1)
	{
		$subs = []; // 子孙数组
		foreach ($menus as $v) {
			if ($v['pid'] == $pid) {
				$v['lev'] = $lev;
				$subs[] = $v;
				$subs = array_merge($subs, $this->getSubMenus($menus, $v['id'], $lev + 1));
			}
		}
		return $subs;
	}

	/**
	 * 查找子菜单的父级菜单
	 * @param array $menus 所有菜单
	 * @param int $id 子菜单id
	 * @return array
	 */
	public function getParentMenus($menus = [], $id = 0)
	{
		$tree = [];
		while ($id !== 0) {
			foreach ($menus as $v) {
				if ($v['id'] == $id) {
					$tree[] = $v;
					$id = $v['pid'];
					break;
				}
			}
		}
		return $tree;
	}

	/**
	 * 获取所有菜单
	 *
	 * @param int $status
	 * @return array
	 */
	public function getMenus($status = 0)
	{
		$where = [];
		switch ($status) {
			case 1:
				$where['status'] = self::STATUS_NORMAL;
				break;
			case -1:
				$where['status'] = self::STATUS_FORBID;
				break;
		}

		return $this->where($where)->order('sort', 'asc')->select()->toArray();
	}


	/**
	 * 菜单权限
	 *
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