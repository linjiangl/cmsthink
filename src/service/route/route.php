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

use think\facade\Route;
use app\common\route\WapRoute;
use app\common\route\PcRoute;
use app\common\route\AdminRoute;
use app\common\route\ApiRoute;

Route::bind('index'); //默认模块
PcRoute::init(); //默认
WapRoute::init(); //wap
AdminRoute::init(); //admin
ApiRoute::init(); //api