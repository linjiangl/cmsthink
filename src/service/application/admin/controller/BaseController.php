<?php
namespace app\admin\controller;

use think\Controller;

class BaseController extends Controller
{
	public function _empty()
	{
		http_error(404, 'Not Found');
	}
}