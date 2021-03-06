<?php
namespace app\index\controller;
use think\facade\Db;

/**
 * liunan
 */
class Collect extends Base {
	//我的收藏列表

	public function collect() {

		$sessionUserData = $this->isLogin();

		$collectData = Db::name("collect")->alias("c")

			->join("goods b", "c.goods_id=b.goods_id")

			->where("c.user_id", $sessionUserData['id'])

			->field("c.*,b.goods_name,b.goods_thumb,goods_price,market_price")->order('time desc')

			->paginate(6);
		//echo Db::name('collect')->getLastSql();die;
		return view('', [
			'collectData' => $collectData,
			'left_menu' => 21,

		]);

	}
	//删除收藏

	public function delete_goods_collect() {

		$sessionUserData = $this->isLogin();

		$user_id = $sessionUserData['id'];

		$goods_id = input("request.goods_id", 'intval');
		$collectData = Db::name("collect")->where('user_id', $user_id)->where("goods_id", $goods_id)->find();
		if (empty($collectData)) {
			return alert('该商品已不存在', 'collect', 5, 3);
		}
		$res = Db::name("collect")->where('user_id', $user_id)->where("goods_id", $goods_id)->delete();

		if ($res) {
			return alert('成功删除收藏', 'collect', 6, 3);
		} else {
			return alert('删除收藏失败', 'collect', 5, 3);
		}
	}

	//加入收藏
	public function add_goods_collect() {

		$sessionUserData = session('sessionUserData');

		//如果没有登录，则返回登录
		if (empty($sessionUserData)) {
			return json(['status' => 0]);
		}

		$goods_id = input('post.goods_id');
		$goodsData = Db::name('goods')->find($goods_id);
		if (empty($goodsData)) {
			return json(['status' => -1]);
		}

		//当前收藏状态
		$collectData = Db::name('collect')->where('goods_id', $goods_id)->where('user_id', $sessionUserData['id'])->find();
		if ($collectData) {
			//取消收藏
			$res = Db::name('collect')->where('goods_id', $goods_id)->where('user_id', $sessionUserData['id'])->delete();
			if ($res) {
				return json(['status' => 2]);
			} else {
				return json(['status' => -1]);
			}
		} else {
			//加入收藏
			$res = Db::name('collect')->insert([
				'user_id' => $sessionUserData['id'],
				'goods_id' => $goods_id,
				'time' => time(),
			]);
			if ($res) {
				return json(['status' => 1]);
			} else {
				return json(['status' => -1]);
			}
		}

	}
}