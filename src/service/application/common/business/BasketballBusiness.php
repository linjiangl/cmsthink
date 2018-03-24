<?php
/**
 * BasketballBusiness.php
 * ---
 * Created on 2018/3/21 上午10:28
 * Created by linjiangl
 */

namespace app\common\business;


use cache\BasketballComCache;
use cache\BasketballTeamsCache;

class BasketballBusiness extends BaseBusiness
{
	public $index = 'basketball_schedule';
	public $search = ['status', 'date', 'com_id', 'level', 'jc_num'];
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


	public function info($id)
	{
		$hIndex = $this->handelHIndex($id);
		$info = $this->redis->hGetAll($hIndex);
		if ($info) {
			$info['home'] = $this->handleData(BasketballTeamsCache::get($info['home_id']));
			$info['away'] = $this->handleData(BasketballTeamsCache::get($info['away_id']));
			$info['com'] = $this->handleData(BasketballComCache::get($info['com_id']));
		}

		return $info;
	}

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
		BasketballTeamsCache::get($data['home_id']);
		BasketballTeamsCache::get($data['away_id']);
		BasketballComCache::get($data['com_id']);

		return true;
	}


	public function handleApiData(array $apiData)
	{
		$data = [];
		if (!isset($apiData['matches'])) {
			return $data;
		}
		$fields = $this->getFields();
		foreach ($apiData['matches'] as $k => $v) {
			$gameId = isset($v[0]) ? $v[0] : 0;
			$comId = isset($v[2]) ? $v[2] : 0;
			$fields['game_id'] = $gameId;
			$fields['level'] = isset($v[1]) ? $v[1] : 0;
			$fields['com_id'] = $comId;
			$fields['status'] = isset($v[3]) ? $v[3] : 0;
			$fields['date'] = isset($v[4]) ? $v[4] : 0;
			$fields['real_time'] = isset($v[5]) ? $v[5] : '';

			$homeId = isset($v[6][0]) ? $v[6][0] : 0;
			$fields['home_id'] = $homeId;
			$fields['home_rank'] = isset($v[6][1]) ? $v[6][1] : '';
			$fields['home_score1'] = isset($v[6][2]) ? $v[6][2] : 0;
			$fields['home_score2'] = isset($v[6][3]) ? $v[6][3] : 0;
			$fields['home_score3'] = isset($v[6][4]) ? $v[6][4] : 0;
			$fields['home_score4'] = isset($v[6][5]) ? $v[6][5] : 0;
			$fields['home_scorea'] = isset($v[6][6]) ? $v[6][6] : 0;

			$awayId = isset($v[7][0]) ? $v[7][0] : 0;
			$fields['away_id'] = $awayId;
			$fields['away_rank'] = isset($v[7][1]) ? $v[7][1] : '';
			$fields['away_score1'] = isset($v[7][2]) ? $v[7][2] : 0;
			$fields['away_score2'] = isset($v[7][3]) ? $v[7][3] : 0;
			$fields['away_score3'] = isset($v[7][4]) ? $v[7][4] : 0;
			$fields['away_score4'] = isset($v[7][5]) ? $v[7][5] : 0;
			$fields['away_scorea'] = isset($v[7][6]) ? $v[7][6] : 0;

			$fields['odds_init'] = isset($apiData['odds'][$gameId][2][0][0][0]) ? $apiData['odds'][$gameId][2][0][0][0] : '';
			$fields['odds_ji'] = isset($apiData['odds'][$gameId][2][1][0][0]) ? $apiData['odds'][$gameId][2][1][0][0] : '';
			$fields['odds_gun'] = isset($apiData['odds'][$gameId][2][2][0][0]) ? $apiData['odds'][$gameId][2][2][0][0] : '';
			$fields['odds_ou_init'] = isset($apiData['odds'][$gameId][2][0][1][0]) ? $apiData['odds'][$gameId][2][0][1][0] : '';
			$fields['odds_ou_ji'] = isset($apiData['odds'][$gameId][2][1][1][0]) ? $apiData['odds'][$gameId][2][1][1][0] : '';
			$fields['odds_ou_gun'] = isset($apiData['odds'][$gameId][2][2][1][0]) ? $apiData['odds'][$gameId][2][2][1][0] : '';
			$fields['odds_da_init'] = isset($apiData['odds'][$gameId][2][0][2][0]) ? $apiData['odds'][$gameId][2][0][2][0] : '';
			$fields['odds_da_ji'] = isset($apiData['odds'][$gameId][2][1][2][0]) ? $apiData['odds'][$gameId][2][1][2][0] : '';
			$fields['odds_da_gun'] = isset($apiData['odds'][$gameId][2][2][2][0]) ? $apiData['odds'][$gameId][2][2][2][0] : '';

			$fields['jc_num'] = isset($v[8][1]) ? $v[8][1] : '';
			$fields['action'] = isset($v[8][2]) ? $v[8][2] : 0;
			//$fields['report'] = isset($v[8][2]) ? $v[8][2] : 0;
			//$fields['live'] = isset($v[8][2]) ? $v[8][2] : 0;
			$fields['note'] = isset($v[8][0]) ? $v[8][0] : '';

			$data[$gameId] = $fields;
		}

		return $data;
	}

	public function getFields()
	{
		return [
			'game_id' => 0,
			'level' => 0,
			'com_id' => 0,
			'status' => 0,
			'date' => 0,
			'real_time' => '',
			'home_id' => 0,
			'home_rank' => '',
			'home_score1' => 0,
			'home_score2' => 0,
			'home_score3' => 0,
			'home_score4' => 0,
			'home_scorea' => 0,
			'away_id' => 0,
			'away_rank' => '',
			'away_score1' => 0,
			'away_score2' => 0,
			'away_score3' => 0,
			'away_score4' => 0,
			'away_scorea' => 0,
			'odds_init' => '',
			'odds_ji' => '',
			'odds_gun' => '',
			'odds_ou_init' => '',
			'odds_ou_ji' => '',
			'odds_ou_gun' => '',
			'odds_da_init' => '',
			'odds_da_ji' => '',
			'odds_da_gun' => '',
			'jc_num' => '',
			'action' => 0,
			'report' => 0,
			'live' => 0,
			'note' => '',
		];
	}
}