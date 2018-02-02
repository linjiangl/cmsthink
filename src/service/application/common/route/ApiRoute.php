<?php
namespace app\common\route;

use think\facade\Config;
use think\facade\Route;

class ApiRoute
{
	public static function init()
	{
		Route::domain(Config::get('url_domain_api'), function () {
			Route::rule('avatar', 'api/index/avatar');
			Route::bind('api');
		});
	}
}