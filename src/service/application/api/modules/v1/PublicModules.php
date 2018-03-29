<?php
/**
 * PublicModules.php
 * ---
 * Created on 2018/3/10 上午9:23
 * Created by linjiangl
 */

namespace app\api\modules\v1;

class PublicModules
{
	public function index()
	{
		$param = handle_params([
			'a' => [],
			'b' => []
		], 'get');

		return ['v1' => $param];
	}

	public function t2()
	{
		return ['custom' => 'v1'];
	}
}