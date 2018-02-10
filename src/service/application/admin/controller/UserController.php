<?php
/**
 * UserController.php
 * ---
 * Created on 2018/1/31 下午1:28
 * Created by linjiangl
 */

namespace app\admin\controller;

use app\common\model\UserModel;
use app\common\service\BaseService;
use app\common\service\SystemService;
use app\common\service\UserService;
use app\common\validate\UserValidate;

class UserController extends AuthController
{
	/**
	 * @api {post} /user/register 用户注册
	 * @apiName UserRegister
	 * @apiGroup User
	 * @apiParam {string} auth_token 登录凭证
	 * @apiParam {string{2..30}} username 用户名
	 * @apiParam {string{6..30}} password 密码
	 * @apiParam {string{..20}} [nickname] 昵称
	 * @apiParam {string{11}} [mobile] 手机号
	 * @apiParam {string{..255}} [avatar] 头像
	 * @apiSuccess (Success 201) {Number} id 用户id
	 * @apiSuccessExample {json} Success-Response:
	 * "21"
	 * @apiError {String} error 错误信息
	 * @apiErrorExample {json} Error-Response:
	 * {"error":"用户名已存在"}
	 */
	public function register()
	{
		$param = handle_params([
			'username' => [],
			'password' => [],
			'nickname' => [],
			'avatar'   => [],
			'mobile'   => [],
		]);

		$result = UserService::register($param['username'], $param['password'], $param['mobile'], $param['nickname'], $param['avatar'], UserModel::ROLE_MANAGE);
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
	 * @apiParam {string} auth_token 登录凭证
	 * @apiSuccessExample {json} Success-Response:
	 * "ok"
	 */
	public function logout()
	{
		UserService::logout($this->authKey);
		http_ok('ok');
	}

	/**
	 * @api {post} /user/info 用户信息
	 * @apiName UserInfo
	 * @apiGroup User
	 * @apiParam {String} auth_token 登录凭证
	 * @apiParam {Number} [user_id] 用户ID
	 * @apiSuccess {Number} id 用户ID
	 * @apiSuccess {String} username 用户名
	 * @apiSuccess {Number} status 状态{0:禁用,1:已通过,2:未审核,3:未通过}
	 * @apiSuccess {Number} role 角色{1:管理,2:普通}
	 * @apiSuccessExample {json} Success-Response:
	 * {"id":1,"username":"admin","nickname":"管理员","avatar":"http:\/\/api.tp.exp\/avatar?char=%E7%AE%A1%E7%90%86%E5%91%98&size=96","mobile":"","email":"","status":1,"role":1,"reg_ip":0,"last_login_ip":2130706433,"last_login_time":1518058149,"create_time":0,"update_time":1518066382,"group":["admin"]}
	 * @apiError {String} error 错误信息
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

	public function update()
	{
		$param = handle_params([
			'id'       => [0, 'int'],
			'nickname' => [],
			'role'     => [2, 'int'],
			'status'   => [0, 'int'],
			'avatar'   => [],
			'mobile'   => []
		]);
		$expand = [
			'status' => $param['status'],
			'role'   => $param['role'],
			'mobile' => $param['mobile']
		];
		$result = UserService::update($param['id'], $param['nickname'], '', $param['avatar'], $expand);
		if ($result === false) {
			http_error(UserService::getCode(), UserService::getError());
		} else {
			http_ok();
		}
	}

	public function lists()
	{
		$param = handle_params([
			'page'     => [1, 'abs'],
			'limit'    => [$this->limit, 'abs'],
			'nickname' => [],
			'role'     => [-1, 'int'],
			'status'   => [-1, 'int']
		]);

		$where = [];
		$param['nickname'] && $where['nickname'] = $param['nickname'];
		$param['role'] >= 0 && $where['role'] = $param['role'];
		$param['status'] >= 0 && $where['status'] = $param['status'];

		$model = new UserModel();
		$list = $model->lists($where, $param['page'], $param['limit']);
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