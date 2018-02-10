<?php

namespace app\index\controller;

use app\common\cache\AuthCache;
use app\common\model\AuthGroupUserModel;
use app\common\model\AuthMenuModel;
use app\common\model\MenuModel;
use app\common\model\UserModel;
use app\common\service\SystemService;
use think\Controller;
use think\facade\Url;
use think\facade\Config;

class IndexController extends Controller
{
	public function index()
	{
		echo url('index/index/hello', 'name=thinkphp&dd=22', '', config('url_domain_pc'));
	}

	public function hello($name = 'ThinkPHP5')
	{

		print_r($this->request->param());
		return 'hello,' . $name;
	}

	public function tt()
	{
		$model = new MenuModel();


		$list = $model->getMenus();

		print_r(handle_tree($list, 0));
	}


}


