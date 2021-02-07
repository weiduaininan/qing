<?php

namespace app\qingadmin\controller;

use think\facade\Db;

class Comment extends Base {

	/**

	 * @return 评论管理

	 */

	//商品评价

	public function index() {

		//获取有没有搜索关键字，用于填充

		$search_key = input('request.search_key');

		$commentData = Db::name('Comment')->alias('c')->field('c.*,u.mobile,u.username,g.goods_thumb,g.goods_id')

			->join('user u', 'c.user_id=u.id')->join('goods g', 'c.goods_id=g.goods_id')->where('u.mobile', 'like', '%' . $search_key . '%')->where('c.status = 0')->order('id desc')->paginate(10);

		return view('', [

			'commentData' => $commentData,

			'search_key' => $search_key,

		]);

	}

	public function del() {

		$id = input('get.id');

		if ($id) {

			//$res = Db::name('comment')->where('id', $id)->delete();
			$res = Db::name('comment')->where('id', $id)->update(['status' => 1]);

			if ($res) {

				return alert('删除成功', 'index', 6, 3);

			} else {

				return alert('删除失败', 'index', 5, 3);

			}

		}

	}

}
