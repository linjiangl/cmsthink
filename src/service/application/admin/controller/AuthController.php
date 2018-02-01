<?php
/**
 * AuthController.php
 * ---
 * Created on 2018/1/31 下午1:21
 * Created by linjiangl
 */

namespace app\admin\controller;


use app\common\cache\AuthCache;
use think\Controller;

class AuthController extends Controller
{
	/**
	 * 当前登录用户信息
	 * @var array
	 */
	protected $user = [];

	protected $beforeActionList = [
		'init',
	];

	public function init()
	{
		$this->isLogin();
	}

	/**
	 * 判断是否登录
	 */
	public function isLogin()
	{
		$authKey = $this->request->request('auth_token', '', 'trim');
		$authCache = new AuthCache();
		if ($user = $authCache->getAuth($authKey)) {
			$this->user = $user;
		} else {
			http_error(401, '验证失败');
		}
	}

	public function _empty()
	{
		http_error(404, 'Not Found');
	}
}