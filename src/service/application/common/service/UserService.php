<?php

namespace app\common\service;

use app\common\model\UserModel;
use app\common\validate\UserValidate;
use app\common\cache\AuthCache;

class UserService extends BaseService
{

	/**
	 * 账号注册
	 *
	 * @param $username
	 * @param $password
	 * @param string $nickname
	 * @param string $avatar
	 * @return bool|string
	 */
	public static function register($username, $password, $nickname = '', $avatar = '')
	{
		$data = [
			'username' => $username,
			'password' => $password,
			'nickname' => $nickname ? : self::generateNickname($nickname),
			'avatar' => $avatar
		];

		$validate = new UserValidate();
		if (!$validate->scene('register')->check($data)) {
			self::setHttpMsg(self::UNPROCESSABLE_ENTITY, $validate->getError());
			return false;
		}

		$userModel = new UserModel();
		$insId = $userModel->add($data);
		if ($insId === false) {
			self::setHttpMsg(self::BAD_REQUEST, '注册失败');
			return false;
		} else {
			self::setHttpMsg(self::CREATED);
			return $insId;
		}
	}

	/**
	 * 用户登录, 支持账号/手机号 + 密码
	 *
	 * @param $username
	 * @param $password
	 * @return bool|string
	 */
	public static function login($username, $password)
	{
		$data = [
			'username' => $username,
			'password' => $password
		];

		$validate = new UserValidate();
		if (!$validate->scene('login')->check($data)) {
			self::setHttpMsg(self::UNPROCESSABLE_ENTITY, $validate->getError());
			return false;
		}

		$userModel = new UserModel();
		$info = $userModel->where('username', $username)->find();
		if (empty($info)) {
			$info = $userModel->where('mobile', $username)->find();
		}

		// 用户名/手机号不存在
		if (empty($info) || !verify_pwd($password, $info['password'])) {
			self::setHttpMsg(self::PRECONDITION_FAILED, '用户名/密码错误');
			return false;
		}

		// 每次登录更新auth_key
		$oldAuthKey = $info['auth_key'];
		$authKey = $userModel->generateAuthKey();
		if ($info->save(['auth_key' => $authKey])) {
			self::setHttpMsg(self::OK);

			//记录行为
			self::loginAction($info['id']);

			//头像
			$info['avatar'] = $info['avatar'] ? : get_avatar_url($info['nickname']);

			//记录登录状态,检查登录用
			AuthCache::setAuth($authKey, $info->toArray());
			AuthCache::rmAuth($oldAuthKey);

			return $authKey;
		} else {
			self::setHttpMsg(self::BAD_REQUEST, '登录失败');
			return false;
		}
	}

	/**
	 * 退出
	 * @param $authKey
	 * @return bool
	 */
	public static function logout($authKey)
	{
		return AuthCache::rmAuth($authKey);
	}

	/**
	 * 登录行为记录
	 * @param $userId
	 * @return bool
	 */
	public static function loginAction($userId)
	{
		//用户行为
		$data['last_login_time'] = time();
		$data['last_login_ip'] = ip2long(request()->ip());

		$userModel = new UserModel();
		return $userModel->modify($userId, $data);
	}

	/**
	 * 生成昵称
	 *
	 * @param $nickname
	 * @return string
	 */
	public static function generateNickname($nickname)
	{
		return $nickname ? : '在路上行走';
	}

	/**
	 * 获取用户信息
	 * @param $userId
	 * @return array
	 */
	public static function info($userId)
	{
		return self::infoBy($userId);
	}

	/**
	 * 获取用户信息
	 * @param $authKey
	 * @return array
	 */
	public static function infoByAuthKey($authKey)
	{
		return self::infoBy(0, '', '', $authKey);
	}

	/**
	 * 获取用户信息
	 * @param int $userId
	 * @param string $username
	 * @param string $mobile
	 * @param string $authKey
	 * @return array
	 */
	protected static function infoBy($userId = 0, $username='', $mobile='', $authKey='')
	{
		$where = [];
		$userId && $where['id'] = $userId;
		$username && $where['username'] = $username;
		$mobile && $where['mobile'] = $mobile;
		$authKey && $where['auth_key'] = $authKey;

		$userModel = new UserModel();
		$info = $userModel->where($where)->find();
		if ($info) {
			$info['avatar'] = $info['avatar'] ? : get_avatar_url($info['nickname']);
		}

		return $info ? $info->toArray() : [];
	}
}