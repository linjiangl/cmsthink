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
		http_ok(['id' => 1, 'name' => '343434']);
	}
}