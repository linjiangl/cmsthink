<?php
/**
 * Created by PhpStorm.
 * User: linJiangL
 * Mail: 8257796@qq.com
 * Date: 2018/1/29
 * Time: 下午1:07
 */

namespace app\admin\controller;


use app\common\model\MenuModel;

class UserController extends BaseController
{
	public function __construct()
	{
		parent::__construct();
	}


	public function info()
	{
		$menuModel = new MenuModel();
//
//		$menus = $menuModel->getMenus(2, 1);
//
//		print_r($menus);

		$info = $menuModel->with('actions')->where('pid', 1)->select()->toArray();

		print_r($info);
	}
}