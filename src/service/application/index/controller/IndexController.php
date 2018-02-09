<?php

namespace app\index\controller;

use app\common\cache\AuthCache;
use app\common\model\AuthGroupUserModel;
use app\common\model\AuthMenuModel;
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
//		$param = handle_params([
//			'page' => [1, 'abs'],
//			'limit' => [20, 'abs'],
//			'nickname' => [],
//			'role' => [-1, 'int'],
//			'status' => [-1, 'int']
//		]);
//
//		print_r($param);

		$model = new UserModel();

		$rs = $model->modify(6, ['nickname' => 123123]);

		//var_dump($model->save(['nickname' => 123123], ['id' => 5]));

		var_dump($rs);
	}

}


