<?php
/**
 * PublicModules.php
 * ---
 * Created on 2018/3/10 上午9:25
 * Created by linjiangl
 */

namespace app\api\modules\v2;

class PublicModules
{
	public function index()
	{
		$param = handle_params([
			'a' => [],
			'b' => []
		], 'get');

		return ['v2' => $param];
	}

	public function t2()
	{
		return ['custom' => 'v2'];
	}
}