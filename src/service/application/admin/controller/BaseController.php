<?php
namespace app\admin\controller;

use think\Controller;

class BaseController extends Controller
{
	protected $limit = 20;

	public function _empty()
	{
		http_error(404, 'Not Found');
	}
}