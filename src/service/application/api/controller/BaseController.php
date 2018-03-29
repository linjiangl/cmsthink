<?php
namespace app\api\controller;

use think\Controller;

class BaseController extends Controller
{
	protected $version = 1;

	public function _empty()
	{
		http_error(404, 'Not Found');
	}

	/**
	 * 调用对应版本的类方法
	 * @param string $action 方法名称
	 * @param string $controller 类名称
	 */
	protected function handleClass($action = '', $controller = '')
	{
		$version = 'v' . $this->request->input('v', $this->version, 'intval');
		$classPrefix = 'app\api\modules\\' . $version . '\\';
		$controller = $controller ? : $this->request->controller();
		$className = $classPrefix . $controller . 'Modules';
		if (!class_exists($className)) {
			http_error('404', 'Not Found Class');
		}
		$class = new $className();
		$action = $action ? : $this->request->action();
		http_ok($class->$action());
	}
}