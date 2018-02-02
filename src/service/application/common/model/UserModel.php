<?php
namespace app\common\model;

/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property string $username
 * @property string $nickname
 * @property string $avatar
 * @property string $auth_key
 * @property string $password
 * @property string $mobile
 * @property string $email
 * @property integer $status
 * @property integer $type
 * @property integer $reg_ip
 * @property integer $last_login_ip
 * @property integer $last_login_time
 * @property integer $create_time
 * @property integer $update_time
 */
class UserModel extends BaseModel
{
	protected $pk = 'id';
	protected $table = 'user';
	protected $autoWriteTimestamp = true;
	protected $createTime = 'create_time';
	protected $updateTime = 'update_time';
	protected $resultSetType = 'collection';

	protected $readonly = ['username'];
	protected $hidden = ['auth_key', 'password'];

	/**
	 * 新增数据
	 * @param $data
	 * @return bool|string
	 */
	public function add($data)
	{
		$data['password'] = generate_pwd($data['password']);
		$data['auth_key'] = $this->generateAuthKey();
		$data['reg_ip'] = ip2long(request()->ip());

		if ($this->save($data)) {
			return $this->getLastInsID();
		} else {
			return false;
		}
	}

	/**
	 * 获取auth_key
	 * @return string
	 */
	public function getAuthKey()
	{
		return $this->auth_key;
	}

	/**
	 * 生成 auth_key, 保证库是唯一的值
	 * @return string
	 */
	public function generateAuthKey()
	{
		$authKey = generate_auth_key();
		if ($this->where('auth_key', $authKey)->find()) {
			return $this->generateAuthKey();
		}

		return $authKey;
	}

}