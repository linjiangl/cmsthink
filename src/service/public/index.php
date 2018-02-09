<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// [ 应用入口文件 ]
namespace think;

header('X-Frame-Options: deny');
$origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : '';
$allowOrigin = [
	'http://192.168.1.222:8089',
	'http://localhost:63342',
	'http://localhost:8089',
	'http://admin.tp.app'
];
if (in_array($origin, $allowOrigin)) {
	header("Access-Control-Allow-Origin: {$origin}");
	header("Access-Control-Allow-Methods: *");
	header("Access-Control-Request-Headers: *");
	header("Access-Control-Allow-Credentials: true");
	header("Access-Control-Max-Age: 86400");
	header("Access-Control-Allow-Headers: x-requested-with,content-type");
}
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
	exit;
}

// 加载基础文件
require __DIR__ . '/../thinkphp/base.php';

// 支持事先使用静态方法设置Request对象和Config对象

// 执行应用并响应
Container::get('app')->run()->send();