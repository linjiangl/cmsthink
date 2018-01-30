<?php
/**
 * MenuActionModel.php
 * ---
 * Created on 2018/1/30 上午10:40
 * Created by linjiangl
 */

namespace app\common\model;


class MenuActionModel extends BaseModel
{
	protected $pk = 'id';
	protected $table = 'menu_action';
	protected $autoWriteTimestamp = false;
	protected $createTime = false;
	protected $updateTime = false;
	protected $resultSetType = 'collection';
}