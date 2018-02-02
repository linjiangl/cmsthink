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
	const STATUS_OK = 1; //正常
	const STATUS_FORBID = -1; //禁用

	protected $pk = 'id';
	protected $table = 'auth_group';
	protected $autoWriteTimestamp = false;
	protected $createTime = false;
	protected $updateTime = false;
	protected $resultSetType = 'collection';
}