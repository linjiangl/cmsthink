<?php
namespace app\admin\controller;

use think\Controller;

class BaseController extends Controller
{
	protected function handleParam()
	{
		$param = $this->request->param();
		if (isset($param['page'])) {
			$param['page'] = abs(intval($param['page'])) ? : 1;
		}
		if (isset($param['limit'])) {
			$param['limit'] = abs(intval($param['limit'])) ? : 20;
		}
		if (isset($param['id'])) {
			$param['id'] = abs(intval($param['limit'])) ? : 0;
		}
		if (isset($param['status'])) {
			$param['status'] = intval($param['limit']);
		}

		return $param;
	}

	public function _empty()
	{
		http_error(404, 'Not Found');
	}
}