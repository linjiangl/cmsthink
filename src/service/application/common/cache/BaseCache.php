<?php
/**
 * Created by PhpStorm.
 * User: linJiangL
 * Mail: 8257796@qq.com
 * Date: 2018/1/3
 * Time: 上午11:56
 */

namespace app\common\cache;

use think\facade\Cache;

class BaseCache
{
	protected static $expire = 86400;

	public static function setCache($key, $data)
	{
		return self::cache($key, $data, 'set');
	}

	public static function getCache($key)
	{
		return self::cache($key);
	}

	public static function rmCache($key)
	{
		return self::cache($key, '', 'rm');
	}


	/**
	 * 全局缓存
	 * @param string $index
	 * @param string $type get,set,rm
	 * @param mixed $data set时保存的数据
	 * @return bool|mixed
	 */
	public static function cache($index, $data = '', $type = 'get')
	{
		switch ($type) {
			case 'set':
				$result = $data;
				Cache::set($index, $data, self::expire());
				break;
			case 'del':
				$result = Cache::rm($index);
				break;
			default:
				$result = Cache::get($index);
		}

		return $result;
	}

	public static function expire()
	{
		return self::$expire;
	}
}