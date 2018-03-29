<?php
/**
 * ErrorController.php
 * ---
 * Created on 2018/3/29 下午1:46
 * Created by linjiangl
 */

namespace app\api\controller;


/**
 * 未定义,错误控制器处理
 * Class ErrorController
 *
 * @package app\api\controller
 */
class ErrorController extends BaseController
{
	public function index()
	{
		http_error(404, 'Not Found');
	}
}