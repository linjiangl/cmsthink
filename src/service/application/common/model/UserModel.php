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
 * @property integer $role
 * @property integer $reg_ip
 * @property integer $last_login_ip
 * @property integer $last_login_time
 * @property integer $create_time
 * @property integer $update_time
 */
class UserModel extends BaseModel
{
	const STATUS_FORBID = 0;
	const STATUS_NORMAL = 1;
	const STATUS_UNAUDITED = 2;
	const STATUS_REFUSE = 3;

	const ROLE_MANAGE = 1;
	const ROLE_NORMAL = 2;

	protected $pk = 'id';
	protected $table = 'user';
	protected $autoWriteTimestamp = true;
	protected $createTime = 'create_time';
	protected $updateTime = 'update_time';
	protected $resultSetType = 'collection';

	protected $readonly = ['username'];
	protected $hidden = ['auth_key', 'password'];

	public function getAttrStatus()
	{
		return [
			self::STATUS_FORBID => '已禁用',
			self::STATUS_NORMAL => '已通过',
			self::STATUS_UNAUDITED => '未审核',
			self::STATUS_REFUSE => '已拒绝'
		];
	}

	public function getAttrRole()
	{
		return [
			self::ROLE_NORMAL => '普通',
			self::ROLE_MANAGE => '管理'
		];
	}

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

	/**
	 * 获取用户信息
	 * @param int $userId
	 * @param string $username
	 * @param string $mobile
	 * @param string $authKey
	 * @return array
	 */
	public function infoBy($userId = 0, $username='', $mobile='', $authKey='')
	{
		$where = [];
		$userId && $where['id'] = $userId;
		$username && $where['username'] = $username;
		$mobile && $where['mobile'] = $mobile;
		$authKey && $where['auth_key'] = $authKey;

		$info = $this->objToArray($this->where($where)->find());
		if (isset($info['avatar']) && $info['avatar'] == '') {
			$info['avatar'] = get_avatar_url($info['nickname']);
		}

		return $info;
	}

}