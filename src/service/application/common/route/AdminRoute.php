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
			Route::rule('menuRoles', 'admin/public/menuRoles');

			//user
			Route::rule('user/register', 'admin/user/register');
			Route::rule('logout', 'admin/user/logout');
			Route::rule('user/info', 'admin/user/info');
			Route::rule('user/lists', 'admin/user/lists');

			Route::bind('admin');
		});
	}
}