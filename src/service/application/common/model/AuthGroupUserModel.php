<?php
/**
 * AuthGroupUserModel.php
 * ---
 * Created on 2018/1/30 上午10:38
 * Created by linjiangl
 */

namespace app\common\model;

/**
 * Class AuthGroupUserModel
 *
 * @package app\common\model
 */
class AuthGroupUserModel extends BaseModel
{
	protected $pk = '';
	protected $table = 'auth_group_user';
	protected $autoWriteTimestamp = false;
	protected $createTime = false;
	protected $updateTime = false;
	protected $resultSetType = 'collection';

	public function authGroup()
	{
		return $this->hasOne('AuthGroupModel', 'id', 'group_id');
	}

	public function user()
	{
		return $this->hasOne('UserModel', 'id', 'user_id');
	}

	public function infoBy($groupId = 0, $userId = 0)
	{
		$where = [];
		$groupId && $where['group_id'] = $groupId;
		$userId && $where['user_id'] = $userId;
		$groupId && $userId && $where = ['group_id' => $groupId, 'user_id' => $groupId];

		return $this->objToArray($this->where($where)->find());
	}

	/**
	 * 用户权限
	 *
	 * @param $userId
	 * @return array
	 */
	public function userGroup($userId)
	{
		return $this->objToArray($this->with('authGroup')->where('user_id', $userId)->find());
	}

	/**
	 * 组用户
	 * @param int $groupId
	 * @return array
	 */
	public function groupUsers($groupId = 0)
	{
		$where = [];
		$groupId && $where['group_id'] = $groupId;
		return $this->objToArray($this->with(['authGroup','user'])->where($where)->select());
	}

}