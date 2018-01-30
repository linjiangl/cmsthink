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
	const STATUS_NORMAL = 1;
	const STATUS_DISABLE = -1;

	protected $pk = 'id';
	protected $table = 'menu';
	protected $autoWriteTimestamp = false;
	protected $createTime = false;
	protected $updateTime = false;
	protected $resultSetType = 'collection';

	public function actions()
	{
		return $this->hasMany('MenuActionModel', 'menu_id');
	}

	public function getMenus($pid = 0, $status = 0)
	{
		$where['pid'] = $pid;
		switch ($status) {
			case 1:
				$where['status'] = self::STATUS_NORMAL;
				break;
			case -1:
				$where['status'] = self::STATUS_DISABLE;
				break;
		}
		return $this->where($where)->order('sort', 'asc')->select()->toArray();
	}

}