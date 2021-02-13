<?php
namespace app\index\controller;
use think\facade\Db;

/**
 * @authors LiuNan
 * @email   bj_liunan@126.com
 * @date    2021-02-13 22:54:00
 */

class Score extends Base {
	//我的积分
	public function myscore() {
		$sessionUserData = $this->isLogin();

		//总积分
		$total_socre = Db::name('score')->where('user_id', $sessionUserData['id'])->sum('score');

		$scoreData = Db::name('score')->order('id desc')->where('user_id', $sessionUserData['id'])->paginate(10);
		return view('', [
			'left_menu' => 31,
			'scoreData' => $scoreData,
			'total_socre' => $total_socre,
		]);
	}

	public function myscore_record() {
		$sessionUserData = $this->isLogin();

		$scoreGoodsRecordData = Db::name('score_record')->alias('a')->field('a.*,b.thumb')->join('score_goods b', 'a.goods_id=b.id')->where('a.user_id', $sessionUserData['id'])->order('a.id desc')->paginate(10);

		//总积分
		$total_socre = Db::name('score')->where('user_id', $sessionUserData['id'])->sum('score');

		return view('', [
			'left_menu' => 31,
			'total_socre' => $total_socre,
			'scoreGoodsRecordData' => $scoreGoodsRecordData,
		]);
	}

	//积分商城
	public function score_shop() {
		$sessionUserData = $this->isLogin();
		$scoreGoodsData = Db::name('score_goods')->paginate(10);
		return view('', [
			'scoreGoodsData' => $scoreGoodsData,
			'left_menu' => 32,
		]);
	}

	//积分商品详情
	public function score_goods_detail() {
		$sessionUserData = $this->isLogin();
		$id = input('id');

		$scoreGoodsData = Db::name('score_goods')->find($id);
		if (empty($scoreGoodsData)) {
			return alert('没有改商品', 'score_shop', 5);
		}

		$total_socre = Db::name('score')->where('user_id', $sessionUserData['id'])->sum('score');
		return view('', [
			'scoreGoodsData' => $scoreGoodsData,
			'total_socre' => $total_socre,
			'left_menu' => 32,
		]);
	}

	//积分换购
	public function score_exchange() {
		$sessionUserData = $this->isLogin();
		$id = input('id');
		$scoreGoodsData = Db::name('score_goods')->find($id);
		if (empty($scoreGoodsData)) {
			return json(['status' => -1, 'msg' => '没有该商品']);
		}
		$total_socre = Db::name('score')->where('user_id', $sessionUserData['id'])->sum('score');
		if ($total_socre > $scoreGoodsData['score']) {

			try {
				//积分换购表
				Db::name('score_record')->insert([
					'user_id' => $sessionUserData['id'],
					'score' => $scoreGoodsData['score'],
					'time' => time(),
					'goods_id' => $id,
				]);

				Db::name('score')->insert([
					'user_id' => $sessionUserData['id'],
					'score' => 0 - $scoreGoodsData['score'],
					'time' => time(),
					'info' => '商品换购',
				]);
			} catch (\Exception $e) {
				return json(['status' => -1, 'msg' => '服务端异常，换购失败']);
			}

			return json(['status' => 1, 'msg' => '商品换购成功']);

		} else {
			return json(['status' => -1, 'msg' => '积分不足']);
		}
	}
}