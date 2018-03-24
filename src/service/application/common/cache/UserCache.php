<?php
/**
 * UserCache.php
 * ---
 * Created on 2018/3/24 下午1:47
 * Created by linjiangl
 */

namespace app\common\cache;


use app\common\service\UserService;

class UserCache extends BaseCache
{
	public static function getKey($userId)
	{
		return 'user:id:' . $userId;
	}

	public static function get($userId)
	{
		$key = self::getKey($userId);
		$info = self::getCache($key);
		if (empty($info)) {
			$info = UserService::info($userId);
			self::setCache($key, $info);
		}
		return $info ? : [];
	}
}