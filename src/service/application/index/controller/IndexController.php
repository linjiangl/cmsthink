<?php

namespace app\index\controller;

use app\common\cache\AuthCache;
use app\common\model\AuthGroupUserModel;
use app\common\model\AuthMenuModel;
use app\common\model\MenuModel;
use app\common\model\UserModel;
use app\common\service\SystemService;
use app\common\validate\MenuValidate;
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
		$param = handle_params([
			'title'  => [],
			'router' => [],
			'sort'   => [0, 'abs'],
			'pid'    => [0, 'abs'],
			'status' => [0, 'int', false],
			'group_ids' => []
		]);

		$validate = new MenuValidate();

		if (!$validate->scene('update')->check($param)) {
			print_r($validate->getError());
		}
		print_r($param);
	}


}


