<?php
namespace app\index\controller;

use think\Controller;


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
    	echo strlen(generate_auth_key());
        //echo url('/tt/dd', '', false, config('url_domain_wap'));
    }

}
