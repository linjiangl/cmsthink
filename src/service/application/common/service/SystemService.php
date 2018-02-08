<?php
/**
 * SystemService.php
 * ---
 * Created on 2018/2/2 下午4:03
 * Created by linjiangl
 */

namespace app\common\service;


use app\common\model\AuthGroupModel;
use app\common\model\AuthGroupUserModel;
use app\common\model\MenuModel;
use app\common\validate\MenuValidate;

class SystemService extends BaseService
{
	/**
	 * 获取权限组
	 * @param int $status
	 * @return array
	 */
	public static function getAuthGroups($status = 1)
	{
		$model = new AuthGroupModel();
		return $model->authGroups($status);
	}

	/**
	 * 获取用户权限
	 * @param $userId
	 * @return array
	 */
	public static function getUserGroup($userId)
	{
		$data = [
			'user_id' => $userId,
			'group_id' => 0,
			'group_name' => ''
		];
		$model = new AuthGroupUserModel();
		$info = $model->userGroup($userId);
		if ($info) {
			$data['group_id'] = $info['group_id'];
			$data['group_name'] = $info['auth_group'] ? $info['auth_group']['name'] : '';
		}

		return $data;
	}


	/**
	 * 菜单权限
	 * @param bool $is group_ids为空是否显示
	 * @return array
	 */
	public static function menuToGroups($is = false)
	{
		$model = new MenuModel();
		$list = $model->menuGroups();
		$data = [];
		if ($is) {
			$data = array_combine(array_column($list, 'name'), array_column($list, 'groups'));
		} else {
			foreach ($list as $key => $val) {
				if ($val['group_ids']) {
					$data[$val['name']] = $val['groups'];
				}
			}
		}

		return $data;
	}

	/**
	 * 添加菜单
	 * @param $title
	 * @param $router
	 * @param int $pid
	 * @param int $sort
	 * @return bool|string
	 */
	public static function addMenu($title, $router, $pid = 0, $sort = 0)
	{
		$data = [
			'title' => $title,
			'router' => $router,
			'pid' => $pid,
			'sort' => $sort
		];
		$validate = new MenuValidate();
		if (!$validate->check($data)) {
			self::setHttpMsg(self::UNPROCESSABLE_ENTITY, $validate->getError());
			return false;
		}

		$model = new MenuModel();
		return $model->add($data);
	}
}