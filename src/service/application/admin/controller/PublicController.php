<?php
/**
 * Created by PhpStorm.
 * User: linJiangL
 * Mail: 8257796@qq.com
 * Date: 2018/1/26
 * Time: 下午2:46
 */

namespace app\admin\controller;

use app\common\service\UserService;
use Md\MDAvatars;

class PublicController extends BaseController
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

	/**
	 * @api {get} /public/avatar 生成头像
	 * @apiName UserAvatar
	 * @apiGroup User
	 *
	 * @apiParam {string} char 字符
	 * @apiParam {number} size 大小
	 */
	public function avatar()
	{
		$txt = $this->request->get('char', 'O', 'trim');
		$size = $this->request->get('size', 96, 'intval');
		$txt = mb_substr($txt, 0, 1);
		$size = $size < 24 ? 24 : $size;

		$avatar = new MDAvatars($txt, $size);

		ob_start();
		$avatar->Output2Browser();
		$content = ob_get_clean();
		$avatar->Free();

		return response($content, 200, ['Content-Length' => strlen($content)])->contentType('image/png');
	}
}