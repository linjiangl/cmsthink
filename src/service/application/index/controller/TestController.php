<?php
/**
 * TestController.php
 * ---
 * Created on 2018/3/14 下午2:44
 * Created by linjiangl
 */

namespace app\index\controller;


use app\common\libs\FootballScheduleLibs;
use think\Controller;

class TestController extends Controller
{
	public function index()
	{
		$libs = new FootballScheduleLibs();
		$rs = $libs->add($this->apiData());

		//$rs = $libs->info(11028);

		//$rs = $libs->lists();

		//$rs = $libs->rm(11028);

		//$rs = $libs->save($this->apiData());
		dump($rs);
	}

	protected function sqlData()
	{
		return [
			'game_id' => 11021,
			'status' => 1,
			'home_rank' => 2,
			'home_dian' => 0
		];
	}

	protected function apiData()
	{
		return [
			'id' => 11028,
			'status' => 3,
			'rank' => 2,
			'dian' => 0
		];
	}
}