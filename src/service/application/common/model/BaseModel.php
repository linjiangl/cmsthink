<?php

namespace app\common\model;

use think\Model;

class BaseModel extends Model
{
	/**
	 * 新增数据
	 *
	 * @param $data
	 * @return bool|string
	 */
	public function add($data)
	{
		if (!$data) return false;

		if ($this->save($data)) {
			return $this->getLastInsID();
		} else {
			return false;
		}
	}

	/**
	 * 修改数据
	 * @param mixed $condition int:pk, array:自定义条件
	 * @param array $data
	 * @return bool
	 */
	public function modify($condition, $data = [])
	{
		if (!$data) return false;

		if (is_numeric($condition) && is_string($this->getPk())) {
			$condition = [$this->getPk() => $condition];
		}

		if ($this->save($data, $condition)) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * 获取数据列表
	 *
	 * @param array $condition
	 * @param int $page
	 * @param int $limit
	 * @param string $field
	 * @return array
	 */
	public function lists($condition = [], $page = 1, $limit = 20, $field = '*')
	{
		$total = $this->where($condition)->count();
		$list = [];
		if ($total) {
			$offset = ($page - 1) * $limit;
			$list = $this->field($field)->where($condition)->limit($offset, $limit)->select()->toArray();
		}

		return ['total' => $total, 'list' => $list, 'page' => $page];
	}

	/**
	 * 通过主键获取列表数据
	 * @param int $lastPk
	 * @param array $condition
	 * @param int $limit
	 * @param string $field
	 * @return array
	 */
	public function listsByPk($lastPk = 0, $condition = [], $limit = 20, $field = '*')
	{
		$total = 0;
		$list = [];
		$pk = $lastPk;
		$tablePk = $this->getPk();

		if (is_string($tablePk)) {
			$total = $this->where($condition)->count();

			if ($total) {
				$list = $this->field($field)->where($tablePk, '>', $lastPk)->where($condition)->limit($limit)->select()->toArray();
				if ($list) {
					$pks = array_column($list, $tablePk);
					$pk = end($pks);
				}
			}
		}

		return ['total' => $total, 'list' => $list, 'id' => $pk];
	}
}
