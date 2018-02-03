<?php

namespace app\index\controller;

use app\common\model\AuthGroupUserModel;
use app\common\model\AuthMenuModel;
use app\common\model\UserModel;
use app\common\service\AuthService;
use think\Controller;
use think\facade\Url;

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
		http_ok(AuthService::menuToGroups());
	}

}


