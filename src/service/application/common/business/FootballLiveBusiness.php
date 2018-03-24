<?php
/**
 * FootballLiveBusiness.php
 * ---
 * Created on 2018/3/20 下午1:35
 * Created by linjiangl
 */

namespace app\common\business;


class FootballLiveBusiness extends BaseBusiness
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

		$football = new FootballBusiness();
		//删除
		if ($del) {
			foreach ($del as $k => $v) {
				$del[$k] = $football->rm($v);
			}
		}

		//更新
		if ($save) {
			foreach ($save as $k => $v) {
				$save[$k] = $football->save($this->handleSaveData($football->info($v['game_id']), $v));
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
			$fields['home_score'] = isset($v[2][0]) ? $v[2][0] : 0;
			$fields['home_half'] = isset($v[2][1]) ? $v[2][1] : 0;
			$fields['home_red_card'] = isset($v[2][2]) ? $v[2][2] : 0;
			$fields['home_yellow_card'] = isset($v[2][3]) ? $v[2][3] : 0;
			$fields['home_jiao'] = isset($v[2][4]) ? $v[2][4] : 0;
			$fields['home_ascore'] = isset($v[2][5]) ? $v[2][5] : 0;
			$fields['home_dian'] = isset($v[2][6]) ? $v[2][6] : 0;
			$fields['away_score'] = isset($v[3][0]) ? $v[3][0] : 0;
			$fields['away_half'] = isset($v[3][1]) ? $v[3][1] : 0;
			$fields['away_red_card'] = isset($v[3][2]) ? $v[3][2] : 0;
			$fields['away_yellow_card'] = isset($v[3][3]) ? $v[3][3] : 0;
			$fields['away_jiao'] = isset($v[3][4]) ? $v[3][3] : 0;
			$fields['away_ascore'] = isset($v[3][5]) ? $v[3][3] : 0;
			$fields['away_dian'] = isset($v[3][6]) ? $v[3][3] : 0;
			$fields['real_date'] = isset($v[4]) ? $v[4] : 0;

			$data[$gameId] = $fields;
		}

		return $data;
	}

	protected function getFields()
	{
		return [
			'game_id'          => 0,
			'status'           => 0,
			'home_score'       => 0,
			'home_half'        => 0,
			'home_red_card'    => 0,
			'home_yellow_card' => 0,
			'home_jiao'        => 0,
			'home_ascore'      => 0,
			'home_dian'        => 0,
			'away_score'       => 0,
			'away_half'        => 0,
			'away_red_card'    => 0,
			'away_yellow_card' => 0,
			'away_jiao'        => 0,
			'away_ascore'      => 0,
			'away_dian'        => 0,
			'real_date'        => 0,
		];
	}
}