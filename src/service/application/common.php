<?php

function generate_avatar($char, $size = 96)
{
	return url('admin/public/avatar', "char={$char}&size={$size}", false, config('url_domain_admin'));
}

/**
 * 接口错误返回
 * @param $code
 * @param string $msg
 */
function http_error($code, $msg = '')
{
	json(['error' => $msg])->code($code)->send();
}

/**
 * 接口正确返回
 * @param $data
 * @param int $code
 */
function http_ok($data, $code = 200)
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
 *
 * @return string 编码后的字符串
 * @link http://developer.qiniu.com/docs/v6/api/overview/appendix.html#urlsafe-base64
 */
function base64_urlSafeEncode($data)
{
	$find = array('+', '/');
	$replace = array('-', '_');
	return str_replace($find, $replace, base64_encode($data));
}

/**
 * 对提供的urlsafe的base64编码的数据进行解码
 *
 * @param string $str 待解码的数据，一般为字符串
 *
 * @return string 解码后的字符串
 */
function base64_urlSafeDecode($str)
{
	$find = array('-', '_');
	$replace = array('+', '/');
	return base64_decode(str_replace($find, $replace, $str));
}

