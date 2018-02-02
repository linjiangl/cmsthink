<?php
namespace app\common\route;

use think\facade\Config;
use think\facade\Route;

class WapRoute
{
	public static function init()
	{
		Route::domain(Config::get('url_domain_wap'), function () {
			Route::rule('tt/:name', 'wap/index/tt');
			Route::bind('wap');
		});
	}
}