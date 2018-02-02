<?php
/**
 * AuthMenuModel.php
 * ---
 * Created on 2018/2/2 下午3:46
 * Created by linjiangl
 */

namespace app\common\model;


class AuthMenuModel extends BaseModel
{
	protected $pk = 'id';
	protected $table = 'auth_menu';
	protected $autoWriteTimestamp = true;
	protected $createTime = 'create_time';
	protected $updateTime = 'update_time';
	protected $resultSetType = 'collection';
}