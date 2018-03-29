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
		$info = self::getCache($userId);
		if (empty($info)) {
			$info = UserService::info($userId);
			self::setCache($userId, $info);
		}
		return $info ? : [];
	}
}