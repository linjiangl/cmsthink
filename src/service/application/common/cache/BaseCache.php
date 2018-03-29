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
use think\facade\Config;

class BaseCache
{
	/**
	 * 缓存有效期
	 * @var int
	 */
	protected static $expire = 86400;

	/**
	 * 设置缓存
	 * @param $key
	 * @param $data
	 * @return bool|mixed
	 */
	public static function setCache($key, $data)
	{
		return self::cache(self::getKey($key), $data, 'set');
	}

	/**
	 * 获取缓存
	 * @param $key
	 * @return bool|mixed
	 */
	public static function getCache($key)
	{
		return self::cache(self::getKey($key));
	}

	/**
	 * 删除缓存
	 * @param $key
	 * @return bool|mixed
	 */
	public static function rmCache($key)
	{
		return self::cache(self::getKey($key), '', 'rm');
	}

	/**
	 * 获取缓存键
	 * @param $index
	 * @return mixed
	 */
	public static function getKey($index)
	{
		return $index;
	}

	/**
	 * 获取缓存有效期
	 * @return int
	 */
	public static function expire()
	{
		$expire = Config::get('cache.expire');
		return $expire ? : self::$expire;
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
			case 'rm':
				$result = Cache::rm($index);
				break;
			default:
				$result = Cache::get($index);
		}

		return $result;
	}
}