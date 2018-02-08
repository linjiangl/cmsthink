<?php
namespace app\admin\controller;

use app\common\service\UserService;
use app\common\service\SystemService;

class PublicController extends BaseController
{
	/**
	 * @api {post} /login 用户登录
	 * @apiName PublicLogin
	 * @apiGroup Public
	 *
	 * @apiParam {string{2..30}} username 用户名
	 * @apiParam {string{6..30}} password 密码
	 *
	 * @apiSuccess {String} auth_token 登录凭证
	 *
	 * @apiSuccessExample {json} Success-Response:
	 * "411b87ffea8ec24db63ad09cc05369b5c465d0d4"
	 *
	 * @apiError {String} error 错误信息
	 *
	 * @apiErrorExample {json} Error-Response:
	 * {"error":"用户名\/密码错误"}
	 */
	public function login()
	{
		$username = $this->request->post('username', '', 'trim');
		$password = $this->request->post('password', '', 'trim');

		$authKey = UserService::login($username, $password);
		if ($authKey === false) {
			http_error(UserService::getCode(), UserService::getError());
		} else {
			http_ok($authKey);
		}
	}

	public function menuRoles()
	{
		http_ok(SystemService::menuToGroups());
	}
}