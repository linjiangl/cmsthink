<?php
/**
 * Created by PhpStorm.
 * User: linJiangL
 * Mail: 8257796@qq.com
 * Date: 2018/1/26
 * Time: 下午2:46
 */

namespace app\admin\controller;

use app\common\cache\AuthCache;
use app\common\service\UserService;
use think\Controller;

class PublicController extends Controller
{

	/**
	 * @api {post} /public/register 用户注册
	 * @apiName UserRegister
	 * @apiGroup User
	 *
	 * @apiParam {string{2..30}} username 用户名
	 * @apiParam {string{6..30}} password 密码
	 *
	 * @apiSuccess (Success 2xx) {String} 201 用户ID
	 *
	 * @apiError {String} 422 验证错误
	 * @apiError {String} 400 注册失败
	 *
	 * @apiSuccessExample {json} Success-Response:
	 * "21"
	 *
	 * @apiErrorExample {json} Error-Response:
	 * {"error":"用户名已存在"}
	 */
	public function register()
	{
		$username = $this->request->post('username', '', 'trim');
		$password = $this->request->post('password', '', 'trim');

		$serUser = new UserService();
		$result = $serUser->register($username, $password);
		if ($result === false) {
			http_error($serUser->getCode(), $serUser->getError());
		} else {
			http_ok($result, 201);
		}
	}

	/**
	 * @api {post} /public/login 用户登录
	 * @apiName UserLogin
	 * @apiGroup User
	 *
	 * @apiParam {string{2..30}} username 用户名
	 * @apiParam {string{6..30}} password 密码
	 *
	 * @apiSuccess (Success 2xx) {String} 200 登录凭证
	 *
	 * @apiError {String} 422 验证错误
	 * @apiError {String} 412 用户名/密码错误
	 * @apiError {String} 400 登录失败
	 *
	 * @apiSuccessExample {json} Success-Response:
	 * "411b87ffea8ec24db63ad09cc05369b5c465d0d4"
	 *
	 * @apiErrorExample {json} Error-Response:
	 * {"error":"用户名\/密码错误"}
	 */
	public function login()
	{
		$username = $this->request->post('username', '', 'trim');
		$password = $this->request->post('password', '', 'trim');

		$serUser = new UserService();
		$authKey = $serUser->login($username, $password);
		if ($authKey === false) {
			http_error($serUser->getCode(), $serUser->getError());
		} else {
			$authCache = new AuthCache();
			$authCache->setAuth($authKey, $serUser->infoByAuthKey($authKey));

			http_ok($authKey);
		}
	}

	public function _empty()
	{
		http_error(404, 'Not Found');
	}
}