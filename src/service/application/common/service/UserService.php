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
	 * @param string $mobile
	 * @param string $avatar
	 * @param int $role
	 *
	 * @return bool|string
	 */
	public static function register($username, $password, $mobile = '', $nickname = '', $avatar = '', $role = UserModel::ROLE_NORMAL)
	{
		$data = [
			'username' => $username,
			'password' => $password,
			'mobile'   => $mobile,
			'nickname' => $nickname ? : self::generateNickname($nickname),
			'avatar'   => $avatar,
			'role'     => $role
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
	 *
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
			AuthCache::setCache($authKey, $info->toArray());
			AuthCache::rmCache($oldAuthKey);

			return $authKey;
		} else {
			self::setHttpMsg(self::BAD_REQUEST, '登录失败');
			return false;
		}
	}

	/**
	 * 退出
	 *
	 * @param $authKey
	 *
	 * @return bool
	 */
	public static function logout($authKey)
	{
		return AuthCache::rmCache($authKey);
	}

	/**
	 * 登录行为记录
	 *
	 * @param $userId
	 *
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
	 *
	 * @return string
	 */
	public static function generateNickname($nickname)
	{
		return $nickname ? : '在路上行走';
	}

	/**
	 * 获取用户信息
	 *
	 * @param $userId
	 *
	 * @return array
	 */
	public static function info($userId)
	{
		$userModel = new UserModel();
		return $userModel->infoBy($userId);
	}

	/**
	 * 获取用户信息
	 *
	 * @param $authKey
	 *
	 * @return array
	 */
	public static function infoByAuthKey($authKey)
	{
		$userModel = new UserModel();
		return $userModel->infoBy(0, '', '', $authKey);
	}

	/**
	 * 修改用户信息
	 * @param $userId
	 * @param string $nickname
	 * @param string $password
	 * @param string $avatar
	 * @param array $extend
	 *
	 * @return bool
	 */
	public static function update($userId, $nickname = '', $password = '', $avatar = '', $extend = [])
	{
		$data['id'] = $userId;
		$nickname && $data['nickname'] = $nickname;
		$password && $data['password'] = $password;
		$avatar && $data['avatar'] = $avatar;
		$extend && $data = array_merge($data, $extend);
		$validate = new UserValidate();
		if (!$validate->scene('update')->check($data)) {
			self::setHttpMsg(self::UNPROCESSABLE_ENTITY, $validate->getError());
			return false;
		}

		$model = new UserModel();
		$password && $data['password'] = generate_pwd($password);
		if ($model->modify($data['id'], $data)) {
			return true;
		} else {
			self::setHttpMsg(self::BAD_REQUEST, '保存失败');
			return false;
		}
	}

	/**
	 * 修改密码
	 * @param $userId
	 * @param $password
	 *
	 * @return bool
	 */
	public static function updateByPassword($userId, $password)
	{
		return self::update($userId, '', $password);
	}

	/**
	 * 修改手机号码
	 * @param $userId
	 * @param $mobile
	 *
	 * @return bool
	 */
	public static function updateByMobile($userId, $mobile)
	{
		return self::update($userId, '', '', '', ['mobile' => $mobile]);
	}
}