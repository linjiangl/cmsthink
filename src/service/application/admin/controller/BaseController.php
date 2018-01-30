<?php
/**
 * Created by PhpStorm.
 * User: linJiangL
 * Mail: 8257796@qq.com
 * Date: 2018/1/26
 * Time: 上午9:59
 */

namespace app\admin\controller;

use app\common\cache\AuthCache;
use think\Controller;
use think\facade\Env;

class BaseController extends Controller
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
		$authKey = $this->request->request('auth_key', '', 'trim');
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