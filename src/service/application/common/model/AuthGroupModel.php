<?php
/**
 * AuthGroupModel.php
 * ---
 * Created on 2018/1/30 上午10:38
 * Created by linjiangl
 */

namespace app\common\model;

/**
 * Class AuthGroupModel
 *
 * @package app\common\model
 */
class AuthGroupModel extends BaseModel
{
	const STATUS_NORMAL = 1; //正常
	const STATUS_FORBID = -1; //禁用

	protected $pk = 'id';
	protected $table = 'auth_group';
	protected $autoWriteTimestamp = false;
	protected $createTime = false;
	protected $updateTime = false;
	protected $resultSetType = 'collection';

	/**
	 * 获取权限组列表
	 * @param int $status
	 * @return array
	 */
	public function authGroups($status = self::STATUS_NORMAL)
	{
		switch ($status) {
			case 1:
				$where['status'] = self::STATUS_NORMAL;
				break;
			case -1:
				$where['status'] = self::STATUS_FORBID;
				break;
			default:
				$where = [];
		}

		return $this->where($where)->select()->toArray();
	}
}