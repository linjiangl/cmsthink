<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------
// | 缓存设置
// +----------------------------------------------------------------------

use think\facade\Env;

return [
	// 驱动方式
	'type'     => Env::get('cache.type', ''),
	// 缓存前缀
	'prefix'   => Env::get('cache.prefix', ''),
	// 缓存有效期 0表示永久缓存
	'expire'   => Env::get('cache.expire', 3600),
	'host'     => Env::get('cache.host', '127.0.0.1'),
	'port'     => Env::get('cache.port', 6379),
	'select'   => Env::get('cache.select', 0),
];


