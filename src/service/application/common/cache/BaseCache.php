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
     * 缓存周期
     */
    protected static $expiry = 3600;

	protected $beforeActionList = [
		'init',
	];

	public static function init()
	{
		self::$expiry = Config::get("cache.expire");
	}

	/**
     * 全局缓存
     * @param string $index
     * @param string $type get,set,rm
     * @param mixed $data set时保存的数据
     * @return bool|mixed
     */
    protected function cache($index, $type, $data)
    {
        switch ($type) {
            case 'set':
                $result = $data;
                Cache::set($index, $data, self::$expiry);
                break;
            case 'del':
                $result = Cache::rm($index);
                break;
            default:
                $result = Cache::get($index);
        }

        return $result;
    }
}