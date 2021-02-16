<?php

namespace app\index\controller;

use think\facade\Db;
use think\facade\Session;

class Order extends Base {

	//购物车-确认订单页面

	function index() {

		$sessionUserData = $this->isLogin();
		$addressDefaultData = Db::name('address')->where('user_id', $sessionUserData['id'])->where('isdefault', 1)->find();
		$addressData = Db::name('address')->where('user_id', $sessionUserData['id'])->where('isdefault', 0)->limit(3)->order('id desc')->select();
		$cartData = [];
		$cartDataTmp = Db::name('cart')->where('user_id', $sessionUserData['id'])->where('status', 1)->order('id desc')->select()->toArray();
		if ($cartDataTmp) {
			foreach ($cartDataTmp as $k => $v) {
				$cartData[$k]['id'] = $v['id'];
				$cartData[$k]['goods_id'] = $v['goods_id'];
				$cartData[$k]['amount'] = $v['amount'];
				$cartData[$k]['sku'] = $this->getAttrBySku($v['sku']);
				$goodsData = Db::name('goods')->find($v['goods_id']);
				$cartData[$k]['goods_thumb'] = $goodsData['goods_thumb'];
				$cartData[$k]['goods_name'] = $goodsData['goods_name'];
				$cartData[$k]['post_money'] = $goodsData['post_money'];
				if ($goodsData['single_standard'] == 1) {
					$cartData[$k]['goods_price'] = $goodsData['goods_price'];
				} else {
					$cartData[$k]['goods_price'] = Db::name('goods_standard')->where('goods_id', $v['goods_id'])->where('sku', $v['sku'])->value('goods_price');
				}

			}
		}

		return view('', [
			'cartData' => $cartData,
			'sessionUserData' => $sessionUserData,
			'addressDefaultData' => $addressDefaultData,
			'addressData' => $addressData,
		]);

	}

	//创建订单,购物车购买

	function order_create() {
		$sessionUserData = session('sessionUserData');
		if (empty($sessionUserData)) {
			return json(['msg' => '请登录', 'status' => -2]);
		}

		//order $data
		$data['address_id'] = input('post.address_id');
		if (!intval($data['address_id'])) {
			return json(['msg' => '请完善收货地址信息', 'status' => 0]);
		}

		$data['user_id'] = $sessionUserData['id'];
		$data['time'] = time();
		$data['pay_method'] = input('post.pay_method');
		$data['content'] = input('post.content');
		$data['out_trade_no'] = md5(time() . 'ab');

		//order_goods $data2
		$total_price = 0;
		$cartDataTmp = Db::name('cart')->where('user_id', $sessionUserData['id'])->where('status', 1)->order('id desc')->select()->toArray();
		if (empty($cartDataTmp)) {
			return json(['msg' => '订单异常', 'status' => -1]);
		}
		foreach ($cartDataTmp as $k => $v) {
			$data2[$k]['goods_id'] = $v['goods_id'];
			$data2[$k]['amount'] = $v['amount'];
			$data2[$k]['sku'] = $this->getAttrBySku($v['sku']);
			$goodsData = Db::name('goods')->find($v['goods_id']);
			$data2[$k]['post_money'] = $goodsData['post_money'];
			if ($goodsData['single_standard'] == 1) {
				$data2[$k]['goods_price'] = $goodsData['goods_price'];
			} else {
				$data2[$k]['goods_price'] = Db::name('goods_standard')->where('goods_id', $v['goods_id'])->where('sku', $v['sku'])->value('goods_price');
			}

			$price = $data2[$k]['goods_price'] * $v['amount'];
			$total_price = $total_price + $price + $goodsData['post_money'];

		}

		$data['total_price'] = $total_price;

		//入库
		Db::startTrans();
		try {
			$order_id = Db::name('order')->insertGetId($data);
			if ($order_id) {
				foreach ($data2 as $k => $v) {
					$v['order_id'] = $order_id;
					$res = Db::name('order_goods')->insertGetId($v);
				}

			}
			//删除购物车信息
			Db::name('cart')->where('user_id', $sessionUserData['id'])->where('status', 1)->delete();

			//减少库存操作

			//提交事务
			Db::commit();
		} catch (\Exception $e) {
			// 回滚事务
			Db::rollback();
			return json(['msg' => '订单异常', 'status' => -1]);
		}

		if ($order_id && $res) {

			//微信支付

			if ($data['pay_method'] == 1) {

				return json(['msg' => '订单提交成功！', 'status' => 1, 'out_trade_no' => $data['out_trade_no']]);

			}

			//支付宝支付

			if ($data['pay_method'] == 2) {

				return json(['msg' => '订单提交成功！', 'status' => 2, 'out_trade_no' => $data['out_trade_no']]);

			}

		}

	}

	/* 从商品详情页直接购买，方法提交到这里

		        ** 提交过来参数：goods_id、amount、sku

	*/

	public function buy() {

		$sessionUserData = $this->isLogin();

		$goods_id = input('get.goods_id');
		$goodsData = Db::name('goods')->field('goods_id,goods_thumb,goods_name,goods_price,goods_status,post_money,single_standard')->find($goods_id);
		if (empty($goodsData) || $goodsData['goods_status'] != 1) {
			return alert('商品信息异常', '/', 5);
		}

		$sku = input('get.sku');
		$amount = input('get.amount');
		$goodsData['skustr'] = '';
		$goodsData['sku'] = '';
		if (!empty($sku) || $goodsData['single_standard'] == 2) {
			$goodsStandardData = Db::name('goods_standard')->field('sku,goods_price')->where('goods_id', $goods_id)->where('sku', $sku)->find();
			$goodsData['goods_price'] = $goodsStandardData['goods_price'];
			$goodsData['skustr'] = $this->getAttrBySku($sku);
			$goodsData['sku'] = $sku;
		}

		$goodsData['amount'] = $amount;

		//收货信息
		$addressDefaultData = Db::name('address')->where('user_id', $sessionUserData['id'])->where('isdefault', 1)->find();
		$addressData = Db::name('address')->where('user_id', $sessionUserData['id'])->where('isdefault', 0)->limit(3)->order('id desc')->select();

		return view('', [
			'goodsData' => $goodsData,
			'sessionUserData' => $sessionUserData,
			'addressDefaultData' => $addressDefaultData,
			'addressData' => $addressData,
		]);

	}

	//buy直接购买确认订单
	public function order_create2() {

		$sessionUserData = session('sessionUserData');
		if (empty($sessionUserData)) {
			return json(['msg' => '请登录', 'status' => -2]);
		}

		//order data
		//order_goods  data2

		$data['address_id'] = input('post.address_id');
		if (!intval($data['address_id'])) {
			return json(['msg' => '请完善收货地址信息', 'status' => 0]);
		}

		$data['pay_method'] = input('post.pay_method');
		$data['user_id'] = $sessionUserData['id'];
		$data['time'] = time();
		$data['content'] = input('post.content');
		$data['out_trade_no'] = md5(time() . 'ab');

		$sku = input('post.sku');
		$data2['amount'] = $amount = input('post.amount');
		$data2['goods_id'] = $goods_id = input('post.goods_id');
		$goodsData = Db::name('goods')->field('goods_price,post_money,goods_status,single_standard')->find($goods_id);
		if (empty($goodsData) || $goodsData['goods_status'] != 1) {
			return json(['msg' => '订单异常', 'status' => -1]);
		}
		if (!empty($sku) && $goodsData['single_standard'] == 2) {
			$goodsStandardData = Db::name('goods_standard')->field('goods_price')->where('goods_id', $goods_id)->where('sku', $sku)->find();
			$data2['goods_price'] = $goodsStandardData['goods_price'];
		} else {
			$data2['goods_price'] = $goodsData['goods_price'];
		}
		$data2['sku'] = $this->getAttrBySku($sku);
		$data2['post_money'] = $goodsData['post_money'];

		//计算总价
		$data['total_price'] = $data2['goods_price'] * $data2['amount'] + $data2['post_money'];

		//入库
		Db::startTrans();
		try {
			$order_id = Db::name('order')->insertGetId($data);
			if ($order_id) {
				$data2['order_id'] = $order_id;
				$res = Db::name('order_goods')->insertGetId($data2);

			}

			//减少库存操作

			//提交事务
			Db::commit();

		} catch (\Exception $e) {
			// 回滚事务
			Db::rollback();
			return json(['msg' => '订单异常', 'status' => -1]);
		}

		if ($order_id && $res) {

			//微信支付

			if ($data['pay_method'] == 1) {

				return json(['msg' => '订单提交成功！', 'status' => 1, 'out_trade_no' => $data['out_trade_no']]);

			}

			//支付宝支付

			if ($data['pay_method'] == 2) {

				return json(['msg' => '订单提交成功！', 'status' => 2, 'out_trade_no' => $data['out_trade_no']]);

			}

		}

	}

	//获取用户订单状态
	public function get_pay_status() {
		$sessionUserData = session('sessionUserData');

		//没有登录
		if (empty($sessionUserData)) {
			return json(['status' => -2]);
		}

		$out_trade_no = input('post.out_trade_no');

		//订单异常
		$orderData = Db::name('order')->where('out_trade_no', $out_trade_no)->find();
		if (empty($orderData)) {
			return json(['status' => -1]);
		}

		//已经支付成功
		if ($orderData['status'] == 1) {
			return json(['status' => 1]);
		}
		//未支付
		return json(['status' => 0]);

	}

	//取消订单

	public function order_cancel() {

		$mid = $this->is_login();

		$id = input("request.id", 0);

		Db::name('order')->where("user_id={$mid} and id={$id}")->delete();

		Db::name('order_goods')->where("order_id={$id}")->delete();

		ajaxmsg('取消成功', 1);

	}

	//查看订单物流信息

	public function showOrderPost() {

		$order_id = input("request.order_id", 1); //订单号

		$postcode = input("request.postcode", 1); //物流电话

		//获取订单信息填充

		$orderData = db('order')->field('shou_address,express_code,postcode')->where('id', $order_id)->find();

		//找下物流公司代号

		$expressCompanyCode = db('express')->field('code')->where('id', $orderData['express_code'])->find();

		header("Content-type:text/html;charset=utf-8");

		vendor('express.bird');

		$config = array(

			'EBusinessID' => '1321786', //请到快递鸟官网申请http://kdniao.com/reg

			'AppKey' => '0cae0728-a610-42e3-98d2-6e954e0a771c', //请到快递鸟官网申请http://kdniao.com/reg

		);

		$obj = new \express_bird($config);

		$data = array(

			'OrderCode' => $order_id, //订单编号

			'ShipperCode' => $expressCompanyCode['code'], //快递类型

			'LogisticCode' => $orderData['postcode'], //物流单号

		);

		$res = $obj->getOrderTracesByJson($data);

		$res = json_decode($res);

		if (is_object($res)) {

			$array = (array) $res;

		}

		$echoData = array();

		foreach ($array['Traces'] as $k => $v) {

			if (is_object($v)) {

				$info[$k] = (array) $v;

			}

		}

		if (empty($info)) {

			$info = '';

		} else {

			$info = array_reverse($info);

		}

		ajaxmsg('true', 1, $info);

	}

	//我的订单-确认收货
	public function order_status2() {
		$id = input('id');
		$res = Db::name('order')->where('id', $id)->update(['status' => 2]);
		if ($res) {
			return alert('操作成功', 'myorder', 6);
		} else {
			return alert('操作失败', 'myorder', 5);
		}
	}

	//我的订单-取消订单-删除订单
	public function order_delete() {
		$id = input('id');
		$res = Db::name('order')->where('id', $id)->delete();
		Db::name('order_goods')->where('order_id', $id)->delete();
		if ($res) {
			return alert('操作成功', 'myorder', 6);
		} else {
			return alert('操作失败', 'myorder', 5);
		}
	}

	//我的订单发表评价
	public function myorder_comment() {
		$sessionUserData = $this->isLogin();
		$id = input('id');
		$orderData = Db::name('order')->find($id);

		//做下判断
		if (empty($orderData) || $orderData['status'] != 2) {
			return redirect('myorder');
		}

		//获取商品数据
		$orderGoodsData = Db::name('order_goods')->alias('a')->field('a.order_id,b.goods_id,b.goods_thumb')->join('goods b', 'a.goods_id=b.goods_id')->where('order_id', $id)->where('a.iscomment', 0)->select();

		return view('', [
			'left_menu' => 11,
			'orderGoodsData' => $orderGoodsData,
		]);
	}

	//我的评论-发布评论
	public function myorder_comment_add() {
		$sessionUserData = $this->isLogin();
		$data = input('post.');
		$data['time'] = time();
		$data['user_id'] = $sessionUserData['id'];
		$res = Db::name('comment')->insert($data);
		if ($res) {
			//更改order_goods表中评论状态
			Db::name('order_goods')->where('order_id', $data['order_id'])->where('goods_id', $data['goods_id'])->update(['iscomment' => 1]);

			//查找是否还有没有评论的商品，如果都评论了，更新order中的iscomment
			$orderGoods = Db::name('order_goods')->where('order_id', $data['order_id'])->where('iscomment', 0)->find();
			if (empty($orderGoods)) {
				Db::name('order')->where('id', $data['order_id'])->update(['iscomment' => 1]);
			}

			return alert('操作成功', 'myorder', 6);
		} else {
			return alert('操作失败', 'myorder', 5);
		}

		halt($data);

	}

}