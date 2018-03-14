<?php
/**
 * RedisLibs.php
 * ---
 * Created on 2018/3/14 下午2:54
 * Created by linjiangl
 */

namespace app\common\libs;


/**
 * 连接redis实例
 * Class RedisLibs
 *
 * @package app\common\libs
 */
class RedisLibs
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
		'expire'     => 0,
		'persistent' => false,
		'prefix'     => '',
		'serialize'  => true,
	];

	final protected function __construct() { }

	public static function instance()
	{
		if (self::$ins == null) {
			self::$ins = new \Redis;
			self::$ins->connect(self::$options['host'], self::$options['port'], self::$options['timeout']);
			self::$ins->select(self::$options['select']);
		}
		return self::$ins;
	}

	final protected function __clone() {}
}