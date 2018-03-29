<?php
/**
 * PublicController.php
 * ---
 * Created on 2018/3/10 上午9:20
 * Created by linjiangl
 */

namespace app\api\controller;

class PublicController extends BaseController
{
	public function index()
	{
		$this->handleClass();
	}

	public function custom()
	{
		$this->handleClass('t2');
	}

	public function userInfo()
	{
		$this->handleClass('info', 'User');
	}
}