<?php
/**
 * Created by PhpStorm.
 * User: linJiangL
 * Mail: 8257796@qq.com
 * Date: 2018/1/26
 * Time: 上午11:21
 */

namespace app\common\service;

use app\common\model\UserModel;
use app\common\validate\UserValidate;

class UserService extends BaseService
{
	/**
	 * 账号注册
	 *
	 * @param $username
	 * @param $password
	 * @param string $nickname
	 * @return bool|string
	 */
	public function register($username, $password, $nickname = '')
	{
		$data = [
			'username' => $username,
			'password' => $password,
			'nickname' => $this->generateNickname($nickname)
		];

		$validate = new UserValidate();
		if (!$validate->scene('register')->check($data)) {
			$this->setHttpMsg(self::UNPROCESSABLE_ENTITY, $validate->getError());
			return false;
		}

		$userModel = new UserModel();
		$insId = $userModel->add($data);
		if ($insId === false) {
			$this->setHttpMsg(self::BAD_REQUEST, '注册失败');
			return false;
		} else {
			$this->setHttpMsg(self::CREATED);
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
	public function login($username, $password)
	{
		$data = [
			'username' => $username,
			'password' => $password
		];

		$validate = new UserValidate();
		if (!$validate->scene('login')->check($data)) {
			$this->setHttpMsg(self::UNPROCESSABLE_ENTITY, $validate->getError());
			return false;
		}

		$userModel = new UserModel();
		$info = $userModel->where('username', $username)->find();
		if (empty($info)) {
			$info = $userModel->where('mobile', $username)->find();
		}

		// 用户名/手机号不存在
		if (empty($info) || !verify_pwd($password, $info['password'])) {
			$this->setHttpMsg(self::PRECONDITION_FAILED, '用户名/密码错误');
			return false;
		}

		// 每次登录更新auth_key
		$authKey = $userModel->generateAuthKey();
		if ($info->save(['auth_key' => $authKey])) {
			$this->loginAction($info['id']);
			$this->setHttpMsg(self::OK);
			return $authKey;
		} else {
			$this->setHttpMsg(self::BAD_REQUEST, '登录失败');
			return false;
		}
	}

	/**
	 * 记录登录行为
	 * @param $userId
	 */
	public function loginAction($userId)
	{
		//用户行为
		$data['last_login_time'] = time();
		$data['last_login_ip'] = ip2long(request()->ip());

		UserModel::update($data, ['id' => $userId]);
	}

	/**
	 * 生成昵称
	 *
	 * @param $nickname
	 * @return string
	 */
	public function generateNickname($nickname)
	{
		return $nickname ? : '在路上行走';
	}

	/**
	 * 获取用户信息
	 * @param $userId
	 * @return array
	 */
	public function info($userId)
	{
		return $this->infoBy($userId);
	}

	/**
	 * 获取用户信息
	 * @param $authKey
	 * @return array
	 */
	public function infoByAuthKey($authKey)
	{
		return $this->infoBy(0, '', '', $authKey);
	}

	/**
	 * 获取用户信息
	 * @param int $userId
	 * @param string $username
	 * @param string $mobile
	 * @param string $authKey
	 * @return array
	 */
	protected function infoBy($userId = 0, $username='', $mobile='', $authKey='')
	{
		$where = [];
		$userId && $where['id'] = $userId;
		$username && $where['username'] = $username;
		$mobile && $where['mobile'] = $mobile;
		$authKey && $where['auth_key'] = $authKey;

		$userModel = new UserModel();
		$info = $userModel->where($where)->find();
		return $info ? $info->toArray() : [];
	}

	public function setNickname($userId, $nickname)
	{
		$info = UserModel::get($userId);
		return $info->save(['nickname' => $nickname]) ? $info->toArray() : null;
	}
}