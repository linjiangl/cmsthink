<?php
/**
 * Created by PhpStorm.
 * User: linJiangL
 * Mail: 8257796@qq.com
 * Date: 2018/1/26
 * Time: 下午1:04
 */

namespace app\common\validate;

use app\common\model\UserModel;
use think\Validate;

class UserValidate extends Validate
{
	protected $rule = [
		'id'           => 'number|gt:0',
		'username|用户名' => 'require|alphaDash|min:2|max:30',
		'nickname|昵称'  => 'require|max:20',
		'avatar|头像'    => 'max:255',
		'mobile|手机'    => 'mobile',
		'email|邮箱'     => 'email',
		'password|密码'  => 'require|min:6|max:30',
		'role'         => 'number|between:1,2'
	];

	protected $scene = [
		//'login' => ['username', 'password']
	];

	public function sceneRegister()
	{
		return $this->only(['username', 'password', 'nickname', 'avatar', 'role'])
			->remove('nickname', 'require')
			->append('username', 'unique:app\\common\\model\\UserModel,username');
	}

	public function sceneLogin()
	{
		return $this->only(['username', 'password'])
			->remove('username', 'alphaDash')
			->remove('username', 'max:30');
	}

	public function sceneUpdate()
	{
		return $this->only(['id', 'nickname', 'password', 'role'])
			->append('id', 'require')
			->remove('password', 'require')
			->append('id', 'checkUserId');
	}

	protected function checkUserId($val)
	{
		$val = intval($val);
		if ($val > 0) {
			$model = new UserModel();
			return $model->where(['id' => $val])->find() ? true : '用户不存在';
		}
		return true;
	}
}