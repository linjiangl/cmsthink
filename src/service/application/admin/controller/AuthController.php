<?php
/**
 * AuthController.php
 * ---
 * Created on 2018/1/31 下午1:21
 * Created by linjiangl
 */

namespace app\admin\controller;


use app\common\cache\AuthCache;

class AuthController extends BaseController
{
	/**
	 * 当前登录用户信息
	 * @var array
	 */
	protected $user = [];

	protected $authKey = '';

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
		$this->authKey = $authKey;
		$authCache = new AuthCache();
		if ($user = $authCache->getAuth($authKey)) {
			$this->user = $user;
		} else {
			http_error(401, '验证失败');
			die();
		}
	}

}