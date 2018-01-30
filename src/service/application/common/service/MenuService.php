<?php
/**
 * MenuService.php
 * ---
 * Created on 2018/1/30 上午11:51
 * Created by linjiangl
 */

namespace app\common\service;


use app\common\model\MenuModel;

class MenuService extends BaseService
{
	/**
	 * 获取菜单列表
	 * @param int $pid
	 * @param int $status
	 * @return array
	 */
	public function getMenus($pid = 0, $status = 0)
	{
		$model = new MenuModel();
		return $model->getMenus($pid, $status);
	}

	/**
	 * 菜单对应的操作
	 * @param $menuId
	 * @return array
	 */
	public function menuActions($menuId)
	{
		$model = new MenuModel();
		return $model->with('actions')->find($menuId)->toArray();
	}

	/**
	 * 一级菜单下子菜单对应的操作
	 * @param $pid
	 * @return array
	 */
	public function childMenuActions($pid)
	{
		$model = new MenuModel();
		return $model->with('actions')->where('pid', $pid)->select()->toArray();
	}
}