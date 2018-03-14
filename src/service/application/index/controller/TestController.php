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
		//$rs = $libs->add($this->apiData());

		//$rs = $libs->info(11030);

		$rs = $libs->lists(['date' => '20010']);

		//$libs->rm(11030);

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
			'id' => 11032,
			'status' => 4,
			'rank' => 2,
			'dian' => 0,
			'date' => '20011'
		];
	}
}