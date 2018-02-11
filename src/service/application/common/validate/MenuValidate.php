<?php
/**
 * MenuValidate.php
 * ---
 * Created on 2018/2/8 下午2:22
 * Created by linjiangl
 */

namespace app\common\validate;


use app\common\model\MenuModel;
use think\Validate;

class MenuValidate extends Validate
{
	protected $rule = [
		'id|ID'         => 'number|gt:0',
		'pid|PID'       => 'checkPid',
		'title|标题'      => 'require|max:50',
		'router|路由地址'   => 'require|max:50|unique:app\\common\\model\\MenuModel,router',
		'sort|排序'       => 'number|between:0,99',
		'status|状态'     => 'number|between:0,1',
		'group_ids' => 'regex:/^[1-9]\d*(,[1-9]\d*)*$/'
	];

	public function sceneUpdate()
	{
		return $this->append('id', 'checkId')
					->remove('router', 'unique')
					->remove('title', 'unique')
					->append('router', 'checkRouter');
	}

	protected function checkId($val)
	{
		$model = new MenuModel();
		return $model->where(['id' => $val])->find() ? true : '菜单不存在';
	}

	protected function checkRouter($val, $roule, $data)
	{
		$model = new MenuModel();
		$info = $model->where(['router' => $val])->find();
		if ($info && $info['id'] == $data['id']) {
			return true;
		} else {
			return '路由已存在';
		}
	}

	protected function checkPid($val)
	{
		$val = intval($val);
		if ($val > 0) {
			$model = new MenuModel();
			return $model->where(['id' => $val, 'pid' => 0])->find() ? true : '父级菜单不存在';
		}
		return true;
	}
}