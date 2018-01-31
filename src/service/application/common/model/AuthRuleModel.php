<?php
/**
 * AuthRuleModel.php
 * ---
 * Created on 2018/1/30 上午10:30
 * Created by linjiangl
 */

namespace app\common\model;

/**
 * Class AuthRuleModel
 *
 * @package app\common\model
 */
class AuthRuleModel extends BaseModel
{
	protected $pk = 'id';
	protected $table = 'auth_rule';
	protected $autoWriteTimestamp = false;
	protected $createTime = false;
	protected $updateTime = false;
	protected $resultSetType = 'collection';


}