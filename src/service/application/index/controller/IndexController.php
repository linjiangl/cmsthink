<?php

namespace app\index\controller;

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
//		//Url::build('admin/public/avatar', 'char=sfs&size=98');
//		echo url('admin/public/avatar', 'char=sfs&size=98', false, config('url_domain_admin'));

		echo generate_avatar('放假我方');
	}

}


