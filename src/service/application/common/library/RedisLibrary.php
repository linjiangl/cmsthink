<?php
/**
 * RedisLibrary.php
 * ---
 * Created on 2018/3/14 下午2:54
 * Created by linjiangl
 */

namespace app\common\library;


/**
 * 连接redis实例
 * Class RedisLibs
 *
 * @package app\common\libs
 */
class RedisLibrary
{
	/**
	 * @var \Redis
	 */
	protected static $ins = null;

	protected static $options = [
		'host'       => '127.0.0.1',
		'port'       => 6379,
		'password'   => '',
		'select'     => 0,
		'timeout'    => 0,
		'persistent' => false,
		'serialize'  => true,
	];

	final protected function __construct() { }

	public static function instance($option = [])
	{
		if (!empty($option)) {
			isset($option['host']) && self::$options['host'] = $option['host'];
			isset($option['port']) && self::$options['port'] = $option['port'];
			isset($option['password']) && self::$options['password'] = $option['password'];
			isset($option['select']) && self::$options['select'] = $option['select'];
			isset($option['timeout']) && self::$options['timeout'] = $option['timeout'];
		}
		if (self::$ins == null) {
			self::$ins = new \Redis;
			self::$ins->connect(self::$options['host'], self::$options['port'], self::$options['timeout']);
			self::$ins->select(self::$options['select']);
		}
		return self::$ins;
	}

	final protected function __clone() { }
}