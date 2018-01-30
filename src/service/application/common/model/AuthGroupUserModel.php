<?php
/**
 * AuthGroupUserModel.php
 * ---
 * Created on 2018/1/30 上午10:38
 * Created by linjiangl
 */

namespace app\common\model;


class AuthGroupUserModel extends BaseModel
{
	protected $pk = '';
	protected $table = 'auth_group_user';
	protected $autoWriteTimestamp = false;
	protected $createTime = false;
	protected $updateTime = false;
	protected $resultSetType = 'collection';

}