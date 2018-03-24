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

	public static function set($authKey, $user)
	{
		self::setCache(self::getKey($authKey), $user);
		return $authKey;
	}

	public static function get($authKey)
	{
		return self::getCache(self::getKey($authKey));
	}

	public static function rm($authKey)
	{
		self::rmCache(self::getKey($authKey));
	}
}