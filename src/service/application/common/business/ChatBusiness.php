<?php
/**
 * ChatBusiness.php
 * ---
 * Created on 2018/3/24 上午10:59
 * Created by linjiangl
 */

namespace app\common\business;


use app\common\cache\UserCache;

class ChatBusiness extends BaseBusiness
{
	protected $saveMax = 100;
	public $index = 'chat:room_id:';

	public function set($roomId, $userId, $content, $clientId = '')
	{
		$lIndex = $this->index . $roomId;
		$content = json_encode([
			'user_id' => $userId,
			'client_id' => $clientId,
			'content' => $content,
			'time' => time()
		]);
		if ($this->redis->lLen($lIndex) >= $this->saveMax) {
			$this->redis->lPop($lIndex);
			$this->redis->rPush($lIndex, $content);
		} else {
			$this->redis->rPush($lIndex, $content);
		}

		return $this->handleData($content);
	}

	public function getLists($roomId, $page = 1, $limit = 20)
	{
		$lIndex = $this->index . $roomId;
		$count = $this->redis->lLen($lIndex);
		if ($count > $limit) {
			$end = -1 - ($page - 1) * $limit;
			$start = $count - $page * $limit;
			$list = $this->redis->lRange($lIndex, $start, $end);
		} else {
			$list = $this->redis->lRange($lIndex, 0, -1);
		}

		return $list;
	}

	public function handleListData(array $list)
	{
		if (empty($list)) {
			return [];
		}
		foreach ($list as $k => $v) {
			$list[$k] = $this->handleData($v);
		}

		return $list;
	}

	public function handleData($data)
	{
		$data = json_decode($data, true);
		$data['user'] = UserCache::get($data['user_id']);

		return $data;
	}
}