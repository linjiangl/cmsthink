<?php

namespace app\api\controller;

use think\facade\Config;

class IndexController extends BaseController
{
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
    public function index()
    {
        echo url('index/index/hello', 'name=fff', false, Config::get('url_domain_pc'));
    }

    public function url()
    {
        return $this->returnMsg(404);
    }
}
