<?php
/**
 * AuthService.php
 * ---
 * Created on 2018/2/2 下午4:03
 * Created by linjiangl
 */

namespace app\common\service;


use app\common\model\AuthGroupModel;

class AuthService extends BaseService
{
	public static function getAuthGroups()
	{
		$model = new AuthGroupModel();
		return $model->where('status', $model::STATUS_OK)->select()->toArray();
	}

	public static function getUserGroups($userId)
	{

	}
}