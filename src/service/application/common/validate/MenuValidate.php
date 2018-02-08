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
		'pid|父级ID'    => 'checkPid',
		'title|标题'    => 'require|max:50',
		'router|路由地址' => 'require|max:50|unique:app\\common\\model\\MenuModel,router',
		'sort|排序'     => 'integer|max:99',
	];

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