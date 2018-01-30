<?php
/**
 * Created by PhpStorm.
 * User: linJiangL
 * Mail: 8257796@qq.com
 * Date: 2018/1/26
 * Time: 上午10:19
 */

namespace app\common\cache;

use think\facade\Cache;

class AuthCache extends BaseCache
{
	public function setAuth($authKey, $user)
	{
		$index = "user:auth_key:" . $authKey;
		Cache::set($index, $user, $this->expiry);
		return $authKey;
	}

	public function getAuth($authKey)
	{
		$index = "user:auth_key:" . $authKey;
		return Cache::get($index);
	}

	public function rmAuth($authKey)
	{
		$index = "user:auth_key:" . $authKey;
		Cache::rm($index);
	}
}