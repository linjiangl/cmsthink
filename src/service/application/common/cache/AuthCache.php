<?php
/**
 * Created by PhpStorm.
 * User: linJiangL
 * Mail: 8257796@qq.com
 * Date: 2018/1/26
 * Time: 上午10:19
 */

namespace app\common\cache;


class AuthCache extends BaseCache
{
	public static function getKey($key)
	{
		return 'user:auth_key:' . $key;
	}
}