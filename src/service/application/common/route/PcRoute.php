<?php
namespace app\common\route;

use think\facade\Config;
use think\facade\Route;

class PcRoute
{
	public static function init()
	{
		Route::domain([Config::get('url_domain_pc'), Config::get('url_domain_root')], function () {
			Route::rule('hello/:name', 'index/index/hello')->pattern('name', '\w*');
		});
	}
}