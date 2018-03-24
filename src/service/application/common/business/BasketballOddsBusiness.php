<?php
/**
 * BaskballOddsBusiness.php
 * ---
 * Created on 2018/3/21 下午5:07
 * Created by linjiangl
 */

namespace app\common\business;


class BasketballOddsBusiness extends BaseBusiness
{
	public function autoApiData(array $apiData)
	{
		$data = $this->handleApiData($apiData);
		//更新
		if ($data) {
			$basketball = new BasketballBusiness();
			foreach ($data as $k => $v) {
				$data[$k] = $basketball->save($this->handleSaveData($basketball->info($v['game_id']), $v));
			}
		}
		return $data;
	}

	public function handleApiData(array $apiData)
	{
		$data = [];
		if (empty($apiData)) {
			return $data;
		}

		$fields = $this->getFields();

		foreach ($apiData as $k => $v) {
			$fields['game_id'] = $k;
			$fields['odds_init'] = isset($v[0][0][0]) ? $v[0][0][0] : '';
			$fields['odds_ji'] = isset($v[1][0][0]) ? $v[1][0][0] : '';
			$fields['odds_gun'] = isset($v[2][0][0]) ? $v[2][0][0] : '';
			$fields['odds_ou_init'] = isset($v[0][1][0]) ? $v[0][1][0] : '';
			$fields['odds_ou_ji'] = isset($v[1][1][0]) ? $v[1][1][0] : '';
			$fields['odds_ou_gun'] = isset($v[2][1][0]) ? $v[2][1][0] : '';
			$fields['odds_da_init'] = isset($v[0][2][0]) ? $v[0][2][0] : '';
			$fields['odds_da_ji'] = isset($v[1][2][0]) ? $v[1][2][0] : '';
			$fields['odds_da_gun'] = isset($v[2][2][0]) ? $v[2][2][0] : '';

			$data[$k] = $fields;
		}

		return $data;
	}

	protected function getFields()
	{
		return [
			'game_id'      => 0,
			'odds_init'    => '',
			'odds_ji'      => '',
			'odds_gun'     => '',
			'odds_ou_init' => '',
			'odds_ou_ji'   => '',
			'odds_ou_gun'  => '',
			'odds_da_init' => '',
			'odds_da_ji'   => '',
			'odds_da_gun'  => '',
		];
	}
}