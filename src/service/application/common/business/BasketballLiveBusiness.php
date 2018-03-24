<?php
/**
 * BasketballLiveBusiness.php
 * ---
 * Created on 2018/3/21 下午4:51
 * Created by linjiangl
 */

namespace app\common\business;


class BasketballLiveBusiness extends BaseBusiness
{
	public function autoApiData(array $apiData)
	{
		$data = $this->handleApiData($apiData);

		$del = [];
		$save = [];
		foreach ($data as $k => $v) {
			if ($v['status'] == $this->statusDel) {
				$del[$k] = $v['game_id'];
			} else {
				$save[$k] = $data[$k];
			}
		}

		$basketball = new BasketballBusiness();
		//删除
		if ($del) {
			foreach ($del as $k => $v) {
				$del[$k] = $basketball->rm($v);
			}
		}

		//更新
		if ($save) {
			foreach ($save as $k => $v) {
				$save[$k] = $basketball->save($this->handleSaveData($basketball->info($v['game_id']), $v));
			}
		}

		return ['del' => $del, 'save' => $save];
	}

	public function handleApiData(array $apiData)
	{
		$data = [];
		if (empty($apiData)) {
			return $data;
		}

		$fields = $this->getFields();

		foreach ($apiData as $k => $v) {
			$gameId = isset($v[0]) ? $v[0] : 0;
			$fields['game_id'] = $gameId;
			$fields['status'] = isset($v[1]) ? $v[1] : 0;
			$fields['real_time'] = isset($v[2]) ? $v[2] : 0;
			$fields['home_score1'] = isset($v[3][0]) ? $v[3][0] : 0;
			$fields['home_score2'] = isset($v[3][1]) ? $v[3][1] : 0;
			$fields['home_score3'] = isset($v[3][2]) ? $v[3][2] : 0;
			$fields['home_score4'] = isset($v[3][3]) ? $v[3][3] : 0;
			$fields['home_scorea'] = isset($v[3][4]) ? $v[3][4] : 0;
			$fields['away_score1'] = isset($v[4][0]) ? $v[4][0] : 0;
			$fields['away_score2'] = isset($v[4][1]) ? $v[4][1] : 0;
			$fields['away_score3'] = isset($v[4][2]) ? $v[4][2] : 0;
			$fields['away_score4'] = isset($v[4][3]) ? $v[4][3] : 0;
			$fields['away_scorea'] = isset($v[4][4]) ? $v[4][4] : 0;

			$data[$gameId] = $fields;
		}

		return $data;
	}

	protected function getFields()
	{
		return [
			'game_id'     => 0,
			'status'      => 0,
			'real_time'   => '',
			'home_score1' => 0,
			'home_score2' => 0,
			'home_score3' => 0,
			'home_score4' => 0,
			'home_scorea' => 0,
			'away_score1' => 0,
			'away_score2' => 0,
			'away_score3' => 0,
			'away_score4' => 0,
			'away_scorea' => 0,
		];
	}
}