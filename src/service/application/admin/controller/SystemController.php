<?php
/**
 * SystemController.php
 * ---
 * Created on 2018/2/8 下午1:37
 * Created by linjiangl
 */

namespace app\admin\controller;


use app\common\model\AuthGroupUserModel;
use app\common\model\MenuModel;
use app\common\service\SystemService;

class SystemController extends AuthController
{
	/**
	 * @api {get} /system/getAuthGroup 获取权限分组
	 * @apiName getAuthGroup
	 * @apiGroup System
	 *
	 * @apiParam {String} auth_token 登录凭证
	 *
	 * @apiSuccess {String} status 状态{0:禁用,1正常}
	 *
	 * @apiSuccessExample {json} Success-Response:
	 * [{"id":1,"pid":0,"title":"管理员","name":"admin","status":1},{"id":2,"pid":0,"title":"编辑管理员","name":"su_editor","status":1},{"id":3,"pid":2,"title":"编辑员","name":"editor","status":1},{"id":4,"pid":0,"title":"广告管理员","name":"adv","status":1}]
	 *
	 */
	public function getAuthGroup()
	{
		http_ok(SystemService::getAuthGroups());
	}

	/**
	 * @api {get} /system/getAuthGroupUser 获取分组用户
	 * @apiName getAuthGroupUser
	 * @apiGroup System
	 *
	 * @apiParam {String} auth_token 登录凭证
	 * @apiParam {Number} [group_id] 分组ID
	 *
	 * @apiSuccessExample {json} Success-Response:
	 * [{"group_id":1,"user_id":1,"auth_group":{"id":1,"pid":0,"title":"管理员","name":"admin","status":1},"user":{"id":1,"username":"admin","nickname":"管理员","avatar":"","mobile":"","email":"","status":1,"role":1,"reg_ip":0,"last_login_ip":2130706433,"last_login_time":1518070203,"create_time":0,"update_time":1518070203}}]
	 */
	public function getAuthGroupUser()
	{
		$groupId = $this->request->post('group_id', 0, 'intval');
		$model = new AuthGroupUserModel();
		$list = $model->groupUsers($groupId);
		http_ok($list);
	}


	/**
	 * @api {get} /system/getMenus 获取分组用户
	 * @apiName getAuthGroupUser
	 * @apiGroup System
	 *
	 * @apiParam {String} auth_token 登录凭证
	 *
	 * @apiSuccessExample {json} Success-Response:
	 * [{"id":1,"pid":0,"title":"用户","router":"user","auth_group_ids":"","sort":0,"status":1,"create_time":0,"update_time":0,"lev":1},{"id":2,"pid":1,"title":"用户管理","router":"userIndex","auth_group_ids":"","sort":0,"status":1,"create_time":0,"update_time":0,"lev":2}]
	 *
	 */
	public function getMenus()
	{
		$model = new MenuModel();
		$list = handle_tree($model->getMenus());
		http_ok($list);
	}

	/**
	 * @api {post} /system/addMenu 添加菜单
	 * @apiName addMenu
	 * @apiGroup System
	 *
	 * @apiParam {String{..50}} title 标题
	 * @apiParam {String{..50}} router 路由地址
	 * @apiParam {Number{0-x}} [pid=0] 父级ID
	 * @apiParam {Number{0-99}} [sort=0] 排序
	 *
	 * @apiSuccessExample {json} Success-Response:
	 * "21"
	 *
	 * @apiError {String} error 错误信息
	 */
	public function addMenu()
	{
		$param = handle_params([
			'title' => [],
			'router' => [],
			'sort' => [0, 'abs'],
			'pid' => [0, 'abs']
		]);

		$result = SystemService::addMenu($param['title'], $param['router'], $param['pid'], $param['sort']);
		if ($result === false) {
			http_error(SystemService::getCode(), SystemService::getError());
		} else {
			http_ok($result, 201);
		}
	}

}