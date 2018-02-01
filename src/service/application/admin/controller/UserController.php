<?php
/**
 * UserController.php
 * ---
 * Created on 2018/1/31 下午1:28
 * Created by linjiangl
 */

namespace app\admin\controller;


class UserController extends AuthController
{

	public function info()
	{
		//$token = $this->request->get('auth_token');
		http_ok($this->user);
	}

	public function test()
	{

		http_ok([$this->request->get()]);
	}
}