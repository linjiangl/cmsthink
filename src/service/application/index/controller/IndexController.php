<?php

namespace app\index\controller;

use app\common\model\UserModel;
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
		$model = new UserModel();

		$list = $model->listsByPk(4, ['status' => 10], 2);

		print_r($list);
	}

}


