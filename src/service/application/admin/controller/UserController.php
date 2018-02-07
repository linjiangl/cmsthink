<?php
/**
 * UserController.php
 * ---
 * Created on 2018/1/31 下午1:28
 * Created by linjiangl
 */

namespace app\admin\controller;

use app\common\model\UserModel;
use app\common\service\SystemService;
use app\common\service\UserService;

class UserController extends AuthController
{
	/**
	 * @api {post} /user/register 用户注册
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

		$result = UserService::register($username, $password);
		if ($result === false) {
			http_error(UserService::getCode(), UserService::getError());
		} else {
			http_ok($result, 201);
		}
	}

	/**
	 * @api {post} /user/logout 用户退出
	 * @apiName UserLogout
	 * @apiGroup User
	 *
	 * @apiParam {string} auth_token 登录凭证
	 *
	 * @apiSuccess (Success 2xx) {String} 200 退出成功
	 */
	public function logout()
	{
		UserService::logout($this->authKey);
		http_ok('');
	}

	/**
	 * @api {post} /user/info 用户信息
	 * @apiName UserInfo
	 * @apiGroup User
	 *
	 * @apiParam {string} auth_token 登录凭证
	 * @apiParam {number} [user_id] 用户ID
	 *
	 * @apiSuccess (Success 2xx) {String} 200 用户信息
	 *
	 * @apiError {String} 404 用户不存在
	 *
	 * @apiSuccessExample {json} Success-Response:
	 * {"id":1,"username":"admin","nickname":"管理员","avatar":"http:\/\/admin.tp.exp\/public\/avatar?char=%E7%AE%A1%E7%90%86%E5%91%98&size=96","mobile":"","email":"","status":10,"type":11,"reg_ip":0,"last_login_ip":2130706433,"last_login_time":1517449833,"create_time":0,"update_time":1517450394}
	 *
	 * @apiErrorExample {json} Error-Response:
	 * {"error":"用户不存在"}
	 */
	public function info()
	{
		$userId = $this->request->post('user_id', 0, 'intval');
		if ($userId) {
			$user = UserService::info($userId);
			if ($user) {
				http_ok($user);
			} else {
				http_error(404, '用户不存在');
			}
		} else {
			$userGroup = SystemService::getUserGroup($this->user['id']);
			$this->user['group'] = [$userGroup['group_name']];
			http_ok($this->user);
		}
	}

	public function lists()
	{
		$param = $this->handleParam();
		$model = new UserModel();
		$list = $model->lists([], $param['page'], $param['limit']);
		$list['param'] = $param;
		http_ok($list);
	}

	public function condition()
	{
		$model = new UserModel();
		$data['status'] = $model->getAttrStatus();
		$data['role'] = $model->getAttrRole();
		http_ok($data);
	}
}