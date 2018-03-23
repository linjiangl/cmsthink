<?php
/**
 * FootballSchedule.php
 * ---
 * Created on 2018/3/14 下午2:43
 * Created by linjiangl
 */

namespace common\business;

use cache\FootballComCache;
use cache\FootballTeamsCache;

class FootballBusiness extends BaseBusiness
{
	public $index = 'football_schedule';
	public $search = ['status', 'date', 'com_id', 'jc_date', 'jc_num', 'dc_date', 'dc_num', 'fc_date', 'fc_num'];
	public $pKey = 'game_id';

	/**
	 * 自动处理接口数据
	 * @param array $apiData
	 * @return bool
	 */
	public function autoApiData(array $apiData)
	{
		if (!isset($apiData['matches'])) {
			return false;
		}
		$data = $this->handleApiData($apiData);

		$old = $this->redis->sMembers($this->index);
		$now = array_column($data, 'game_id');
		$del = array_diff($old, $now);
		$add = array_diff($now, $old);
		$save = array_intersect($old, $now);
		//需要删除数据
		if ($del) {
			foreach ($del as $v) {
				$this->rm($v);
			}
		}
		//需要新增的数据
		if ($add) {
			foreach ($add as $v) {
				$this->add($data[$v]);
			}
		}
		//更新数据
		if ($save) {
			foreach ($save as $v) {
				$this->save($data[$v]);
			}
		}

		return true;
	}

	/**
	 * 详情
	 * @param $id
	 * @return array
	 */
	public function info($id)
	{
		$hIndex = $this->handelHIndex($id);
		$info = $this->redis->hGetAll($hIndex);
		if ($info) {
			$info['home'] = $this->handleData(FootballTeamsCache::get($info['home_id']));
			$info['away'] = $this->handleData(FootballTeamsCache::get($info['away_id']));
			$info['com'] = $this->handleData(FootballComCache::get($info['com_id']));
		}

		return $info;
	}

	/**
	 * 添加
	 * @param array $data
	 * @return bool
	 */
	public function add(array $data)
	{
		if (empty($data)) {
			return false;
		}
		$id = $data['game_id'];
		//已存在, 直接返回
		if ($this->redis->sIsMember($this->index, $id)) {
			return false;
		}
		$this->redis->sAdd($this->index, $id);
		$hIndex = $this->handelHIndex($id);
		$this->redis->hMset($hIndex, $data);
		//搜索字段
		$this->setSearch($data);

		//缓存其他信息
		FootballTeamsCache::get($data['home_id']);
		FootballTeamsCache::get($data['away_id']);
		FootballComCache::get($data['com_id']);

		return true;
	}

	/**
	 * 接口数据处理 matches odds events teams
	 *
	 * @param array $apiData
	 * @return array
	 */
	public function handleApiData(array $apiData)
	{
		$data = [];
		if (!isset($apiData['matches'])) {
			return $data;
		}
		$fields = $this->getFields();
		foreach ($apiData['matches'] as $k => $v) {
			$gameId = isset($v[0]) ? $v[0] : 0;
			$homeId = isset($v[5][0]) ? $v[5][0] : 0;
			$awayId = isset($v[6][0]) ? $v[6][0] : 0;
			$comId = isset($v[1]) ? $v[1] : 0;
			$fields['game_id'] = $gameId;
			$fields['com_id'] = $comId;
			$fields['home_id'] = $homeId;
			$fields['away_id'] = $awayId;
			$fields['status'] = isset($v[2]) ? $v[2] : 0;
			$fields['home_rank'] = isset($v[5][1]) ? $v[5][1] : '';
			$fields['home_score'] = isset($v[5][2]) ? $v[5][2] : 0;
			$fields['home_half'] = isset($v[5][3]) ? $v[5][3] : 0;
			$fields['home_red_card'] = isset($v[5][4]) ? $v[5][4] : 0;
			$fields['home_yellow_card'] = isset($v[5][5]) ? $v[5][5] : 0;
			$fields['home_jiao'] = isset($v[5][6]) ? $v[5][6] : 0;
			$fields['home_ascore'] = isset($v[5][7]) ? $v[5][7] : 0;
			$fields['home_dian'] = isset($v[5][8]) ? $v[5][8] : 0;
			$fields['away_rank'] = isset($v[6][1]) ? $v[6][1] : '';
			$fields['away_score'] = isset($v[6][2]) ? $v[6][2] : 0;
			$fields['away_half'] = isset($v[6][3]) ? $v[6][3] : 0;
			$fields['away_red_card'] = isset($v[6][4]) ? $v[6][4] : 0;
			$fields['away_yellow_card'] = isset($v[6][5]) ? $v[6][5] : 0;
			$fields['away_jiao'] = isset($v[6][6]) ? $v[6][6] : 0;
			$fields['away_ascore'] = isset($v[6][7]) ? $v[6][7] : 0;
			$fields['away_dian'] = isset($v[6][8]) ? $v[6][8] : 0;
			$fields['round'] = isset($v[7][2]) ? $v[7][2] : 0;
			$fields['n'] = isset($v[7][1]) ? $v[7][1] : 0;
			$fields['date'] = isset($v[3]) ? $v[3] : 0;
			$fields['real_date'] = isset($v[4]) ? $v[4] : 0;
			//$fields['season_id'] = isset($v[5][3]) ? : 0;
			//$fields['season'] = isset($v[5][3]) ? : '';
			$fields['odds_init'] = isset($apiData['odds'][$gameId][2][0][0][0]) ? $apiData['odds'][$gameId][2][0][0][0] : '';
			$fields['odds_ji'] = isset($apiData['odds'][$gameId][2][1][0][0]) ? $apiData['odds'][$gameId][2][1][0][0] : '';
			$fields['odds_gun'] = isset($apiData['odds'][$gameId][2][2][0][0]) ? $apiData['odds'][$gameId][2][2][0][0] : '';
			$fields['odds_ou_init'] = isset($apiData['odds'][$gameId][2][0][1][0]) ? $apiData['odds'][$gameId][2][0][1][0] : '';
			$fields['odds_ou_ji'] = isset($apiData['odds'][$gameId][2][1][1][0]) ? $apiData['odds'][$gameId][2][1][1][0] : '';
			$fields['odds_ou_gun'] = isset($apiData['odds'][$gameId][2][2][1][0]) ? $apiData['odds'][$gameId][2][2][1][0] : '';
			$fields['odds_da_init'] = isset($apiData['odds'][$gameId][2][0][2][0]) ? $apiData['odds'][$gameId][2][0][2][0] : '';
			$fields['odds_da_ji'] = isset($apiData['odds'][$gameId][2][1][2][0]) ? $apiData['odds'][$gameId][2][1][2][0] : '';
			$fields['odds_da_gun'] = isset($apiData['odds'][$gameId][2][2][2][0]) ? $apiData['odds'][$gameId][2][2][2][0] : '';
			$fields['note'] = isset($v[7][0]) ? $v[7][0] : '';
			$fields['action'] = isset($v[8][2]) ? $v[8][2] : 0;
			//$fields['live'] = isset($v[5][3]) ? : '';
			//$fields['report'] = isset($v[5][3]) ? : 0;
			//$fields['recommend'] = isset($v[5][3]) ? : 0;

			if (isset($v[7][3]) && $v[7][3]) {
				$res = explode('_', $v[7][3]);
				$fields['jc_date'] = $res[0];
				$fields['jc_num'] = $res[1];
			}
			if (isset($v[7][4]) && $v[7][4]) {
				$res = explode('_', $v[7][4]);
				$fields['dc_date'] = $res[0];
				$fields['dc_num'] = $res[1];
			}
			if (isset($v[7][5]) && $v[7][5]) {
				$res = explode('_', $v[7][5]);
				$fields['fc_date'] = $res[0];
				$fields['fc_num'] = $res[1];
			}
			$data[$gameId] = $fields;
		}

		return $data;
	}


	protected function getFields()
	{
		return [
			'game_id'          => 0,
			'com_id'           => 0,
			'home_id'          => 0,
			'away_id'          => 0,
			'status'           => 0,
			'home_rank'        => '',
			'home_score'       => 0,
			'home_half'        => 0,
			'home_red_card'    => 0,
			'home_yellow_card' => 0,
			'home_jiao'        => 0,
			'home_ascore'      => 0,
			'home_dian'        => 0,
			'away_rank'        => '',
			'away_score'       => 0,
			'away_half'        => 0,
			'away_red_card'    => 0,
			'away_yellow_card' => 0,
			'away_jiao'        => 0,
			'away_ascore'      => 0,
			'away_dian'        => 0,
			'round'            => 0,
			'jc_date'          => '',
			'jc_num'           => '',
			'dc_date'          => '',
			'dc_num'           => '',
			'fc_date'          => '',
			'fc_num'           => '',
			'n'                => 0,
			'date'             => 0,
			'real_date'        => 0,
			'season_id'        => 0,
			'season'           => '',
			'odds_init'        => '',
			'odds_ji'          => '',
			'odds_gun'         => '',
			'odds_ou_init'     => '',
			'odds_ou_ji'       => '',
			'odds_ou_gun'      => '',
			'odds_da_init'     => '',
			'odds_da_ji'       => '',
			'odds_da_gun'      => '',
			'note'             => '',
			'action'           => 0,
			'live'             => '',
			'report'           => 0,
			'recommend'        => 0
		];
	}
}