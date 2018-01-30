<?php
/**
 * AuthGroupRuleModel.php
 * ---
 * Created on 2018/1/30 上午10:39
 * Created by linjiangl
 */

namespace app\common\model;


class AuthGroupRuleModel extends BaseModel
{
	protected $pk = '';
	protected $table = 'auth_group_rule';
	protected $autoWriteTimestamp = false;
	protected $createTime = false;
	protected $updateTime = false;
	protected $resultSetType = 'collection';

}