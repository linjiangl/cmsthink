<?php
/**
 * MenuModel.php
 * ---
 * Created on 2018/1/30 上午10:40
 * Created by linjiangl
 */

namespace app\common\model;


class MenuModel extends BaseModel
{
	protected $pk = 'id';
	protected $table = 'menu';
	protected $autoWriteTimestamp = false;
	protected $createTime = false;
	protected $updateTime = false;
	protected $resultSetType = 'collection';
}