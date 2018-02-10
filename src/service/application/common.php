<?php

use think\facade\Config;

/**
 * 生成头像地址
 *
 * @param $char
 * @param int $size
 * @return string
 */
function get_avatar_url($char, $size = 96)
{
	return build_url('api/index/avatar', ['char' => $char, 'size' => $size], 'api');
}

/**
 * 构建url
 *
 * @param $url
 * @param array $param
 * @param string $type
 * @return string
 */
function build_url($url, Array $param, $type = 'api')
{
	if (!is_array($param)) {
		return '/';
	}

	switch ($type) {
		case 'pc':
			$domain = Config::get('url_domain_pc');
			break;
		case 'wap':
			$domain = Config::get('url_domain_wap');
			break;
		case 'admin':
			$domain = Config::get('url_domain_admin');
			break;
		default:
			$domain = Config::get('url_domain_api');
	}
	return url($url, http_build_query($param), false, $domain);
}

/**
 * 接口错误返回
 *
 * @param $code
 * @param string $msg
 */
function http_error($code, $msg = '')
{
	json(['error' => $msg])->code($code)->send();
}

/**
 * 接口正确返回
 *
 * @param $data
 * @param int $code
 */
function http_ok($data = 'ok', $code = 200)
{
	json($data)->code($code)->send();
}

/**
 * 生成随机的key
 */
function generate_auth_key()
{
	return sha1(str_shuffle('123456789abcdefghijklmnopqrstuwxyzABCDEFGHJKLMNOPQRSTUVWXYZ') . microtime(true));
}

/**
 * 生成密码
 *
 * @param string $pwd 密码
 * @return bool|string
 */
function generate_pwd($pwd)
{
	return password_hash($pwd, PASSWORD_DEFAULT);
}

/**
 * 验证密码
 *
 * @param string $pwd 密码明文
 * @param string $hash 密码密文
 * @return bool
 */
function verify_pwd($pwd, $hash)
{
	return password_verify($pwd, $hash);
}

/**
 * 对提供的数据进行urlsafe的base64编码。
 *
 * @param string $data 待编码的数据，一般为字符串
 * @return string 编码后的字符串
 * @link http://developer.qiniu.com/docs/v6/api/overview/appendix.html#urlsafe-base64
 */
function base64_urlSafeEncode($data)
{
	$find = ['+', '/'];
	$replace = ['-', '_'];
	return str_replace($find, $replace, base64_encode($data));
}

/**
 * 对提供的urlsafe的base64编码的数据进行解码
 *
 * @param string $str 待解码的数据，一般为字符串
 * @return string 解码后的字符串
 */
function base64_urlSafeDecode($str)
{
	$find = ['-', '_'];
	$replace = ['+', '/'];
	return base64_decode(str_replace($find, $replace, $str));
}

/**
 * $config = [
 *        'page' => [1, 'abs'],
 *        'title' => [], //['', 'str']
 *        'id' => [0, 'int']
 * ]
 *
 * @param array $config
 * @param string $method
 * @return mixed
 */
function handle_params($config = [], $method = 'post')
{
	if ($method == 'post') {
		$param = request()->post('', null, 'trim');
	} else {
		$param = request()->get('', null, 'trim');
	}
	$param = $param ? : [];
	if ($config) {
		foreach ($config as $k => $v) {
			$param[$k] = isset($param[$k]) ? $param[$k] : '';
			$v = $v ? : ['', 'str'];
			switch ($v[1]) {
				case 'int':
					if ($param[$k] != '') {
						$param[$k] = intval($param[$k]);
					} else {
						$param[$k] = intval($param[$k]) ? : $v[0];
					}
					break;
				case 'abs':
					$param[$k] = abs(intval($param[$k])) ? : $v[0];
					break;
				default:
					$param[$k] = $param[$k] ? : $v[0];
			}
		}
	}

	return $param;
}

/**
 * 变成数结构数据
 * @param $data
 * @param int $pid
 *
 * @return array
 */
function handle_tree($data, $pid = 0)
{
	$tree = [];
	foreach ($data as $k => $v) {
		if ($v['pid'] == $pid) {
			$v['children'] = handle_tree($data, $v['id']);
			if($v['children'] == null){
				unset($v['children']);
			}
			$tree[] = $v;
		}
	}
	return $tree;
}

