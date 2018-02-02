<?php
namespace app\common\route;

use think\facade\Config;
use think\facade\Route;

class AdminRoute
{
	public static function init()
	{
		Route::domain(Config::get('url_domain_admin'), function () {
			//public
			Route::rule('login', 'admin/public/login');

			//user
			Route::rule('user/register', 'admin/user/register');
			Route::rule('user/info', 'admin/user/info');
			Route::rule('logout', 'admin/user/logout');

			Route::bind('admin');
		});
	}
}