<?php
/**
 * CacheController.php
 * ---
 * Created on 2018/3/14 下午2:44
 * Created by linjiangl
 */

namespace app\index\controller;

use app\common\cache\UserCache;
use app\common\business\ChatBusiness;
use think\Controller;
use GatewayClient\Gateway;

class TestController extends Controller
{
	public function index()
	{
		$param = handle_params([
			'uid' => [0, 'int'],
			'rid' => [0, 'int'],
		], 'get');

		$this->assign('param', $param);
		return $this->fetch();
	}

	public function ttt()
	{
		dump(UserCache::get(104));
	}

	public function chat()
	{
		$param = handle_params([
			'type'      => ['login', 'str'],
			'user_id'   => [0, 'int'],
			'group_id'  => [0, 'int'],
			'client_id' => [],
			'message'   => [],
		]);

		$chat = new ChatBusiness();
//		Gateway::$registerAddress = '116.62.161.206:11100';
		Gateway::$registerAddress = '192.168.1.222:11100';

		switch ($param['type']) {
			case 'login':
				$isOnline = Gateway::isUidOnline($param['user_id']);
				$user = UserCache::get($param['user_id']);
				$count = Gateway::getClientCountByGroup($param['group_id']);
				Gateway::bindUid($param['client_id'], $param['user_id']);
				Gateway::joinGroup($param['client_id'], $param['group_id']);

				//推送历史消息
				$msgList = $chat->handleListData($chat->getLists($param['group_id']));
				if ($msgList) {
					Gateway::sendToClient($param['client_id'], $this->handleData($msgList, $param['group_id'], $count));
				}

				//推送登录消息
				$message = [
					'user'      => $user,
					'time'      => time(),
					'content'   => $user['nickname'] . '进入该聊天室',
					'client_id' => $param['client_id']
				];
				$data = $this->handleData([$message], $param['group_id'], $count, 'login');
				if ($isOnline) {
					Gateway::sendToClient($param['client_id'], $data);
				} else {
					Gateway::sendToGroup($param['group_id'], $data);
				}
				break;
			default:
				$content = nl2br(htmlspecialchars($param['message']));
				$count = Gateway::getClientCountByGroup($param['group_id']);
				$data = $chat->set($param['group_id'], $param['user_id'], $content, $param['client_id']);
				Gateway::sendToGroup($param['group_id'], $this->handleData([$data], $param['group_id'], $count));
				break;
		}
	}

	protected function handleData($msgList, $groupId, $count, $type = 'say')
	{
		$data = [
			'type'     => $type,
			'group_id' => $groupId,
			'count'    => $count,
			'msgList'  => $msgList
		];

		return json_encode($data);
	}

}