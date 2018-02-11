<?php

namespace app\api\controller;

use Md\MDAvatars;

class IndexController extends BaseController
{
	/**
	 * @api {get} /avatar 生成头像
	 * @apiName PublicAvatar
	 * @apiGroup Public
	 *
	 * @apiParam {string} char 字符
	 * @apiParam {number} size 大小
	 *
	 * @apiSuccess (Success 2xx) {String} 200 图片内容,直接引用
	 */
	public function avatar()
	{
		$txt = $this->request->get('char', 'O', 'trim');
		$size = $this->request->get('size', 96, 'intval');
		$txt = mb_substr($txt, 0, 1);
		$size = $size < 24 ? 24 : $size;

		$avatar = new MDAvatars($txt, $size);

		ob_start();
		$avatar->Output2Browser();
		$content = ob_get_clean();
		$avatar->Free();
		ob_end_flush();

		return response($content, 200, ['Content-Length' => strlen($content), 'Cache-Control' => 'max-age=86400'])->contentType('image/png');
	}

}
