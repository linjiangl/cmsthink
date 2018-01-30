<?php
// +----------------------------------------------------------------------
// | Qipa
// +----------------------------------------------------------------------
// | Copyright (c) 2017 http://www.qipa.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 翱翔蔚蓝 <271714539@qq.com>
// +----------------------------------------------------------------------
// | Index.php 2017-05-25
// +----------------------------------------------------------------------
namespace app\wap\controller;


class IndexController
{
    public function index()
    {
        echo 'wap';
    }

    public function tt($name = 'fff')
    {
        echo 'ttt-wap: ' . $name;
    }

    public function ttt()
    {
        echo 'ttt';
    }
}
