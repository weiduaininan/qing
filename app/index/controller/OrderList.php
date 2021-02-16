<?php
namespace app\index\controller;
use Think\facade\Db;

/**
 * liunan 订单列表相关
 */
class OrderList extends Base {

	//我的订单
	public function myorder() {
		$sessionUserData = $this->isLogin();
		$this->clearOrderStatus0();

		$orderData = Db::view('order', 'id,total_price,status,time,out_trade_no,pay_method,iscomment')
			->view('address', 'shou_name', 'address.id=order.address_id')
			->where('order.user_id', $sessionUserData['id'])->order('order.id desc')
			->paginate(['list_rows' => 2, 'query' => request()->param()]);

		//分页
		$page = $orderData->render();

		$orderData1 = $orderData->items();

		foreach ($orderData1 as $k => $v) {
			$orderData1[$k]['goods'] = Db::name('order_goods')->alias('a')->field('a.*,b.goods_name,b.goods_thumb')->join('goods b', 'a.goods_id=b.goods_id')->where('a.order_id', $v['id'])->select()->toArray();
		}
		//halt($orderData1);
		return view('', [
			'left_menu' => 11,
			'page' => $page,
			'orderData1' => $orderData1,
			'searchkey' => '',
		]);
	}

	//用户订单搜索
	public function myorder_search() {
		$sessionUserData = $this->isLogin();
		$this->clearOrderStatus0();
		$searchkey = input('searchkey');
		//先找出有该关键字的所有订单id ,order数据表中的id 1,2
		//order数据表中查id in 1，2 分页

		$orderDataTmp = Db::view('order', 'id')
			->view('order_goods', 'goods_id', 'order.id=order_goods.order_id')
			->view('goods', 'goods_name', 'order_goods.goods_id=goods.goods_id')
			->where('order.user_id', $sessionUserData['id'])
			->where('goods.goods_name', 'like', '%' . $searchkey . '%')->order('order.id desc')
			->select()->toArray();

		if (empty($orderDataTmp)) {
			return alert('没有找到' . $searchkey . '相关搜索结果', 'myorder', 5);
		}

		foreach ($orderDataTmp as $k => $v) {
			$idArr[] = $v['id'];
		}
		$idStr = implode(',', $idArr);

		$orderData = Db::view('order', 'id,total_price,status,time,out_trade_no,pay_method,iscomment')
			->view('address', 'shou_name', 'address.id=order.address_id')
			->where('order.user_id', $sessionUserData['id'])
			->where('order.id', 'in', $idStr)
			->paginate(['list_rows' => 1, 'query' => request()->param()]);

		//分页
		$page = $orderData->render();

		$orderData1 = $orderData->items();

		foreach ($orderData1 as $k => $v) {
			$orderData1[$k]['goods'] = Db::name('order_goods')->alias('a')->field('a.*,b.goods_name,b.goods_thumb')->join('goods b', 'a.goods_id=b.goods_id')->where('a.order_id', $v['id'])->select()->toArray();
		}
		//halt($orderData1);

		return view('orderlist/myorder', [
			'left_menu' => 11,
			'page' => $page,
			'orderData1' => $orderData1,
			'searchkey' => $searchkey,
		]);
	}

	//清除24小时过时待支付订单
	public function clearOrderStatus0() {

		$sessionUserData = $this->isLogin();

		//待支付订单如何处理
		//1、被动处理
		//2、轮回 定时器 占资源
		//3、队列 redis

		//24小时是86400秒

		$time = time() - 86400;
		$orderDataTmp = Db::name('order')->where('user_id', $sessionUserData['id'])->where('status', 0)->where('time', '<', $time)->select();
		foreach ($orderDataTmp as $k => $v) {
			Db::name('order')->where('id', $v['id'])->delete();
			Db::name('order_goods')->where('order_id', $v['id'])->delete();
		}
	}

	//我的订单--待收货4
	public function myorder4() {
		$sessionUserData = $this->isLogin();
		$orderData = Db::view('order', 'id,total_price,status,time,out_trade_no,pay_method')
			->view('address', 'shou_name', 'address.id=order.address_id')
			->where('order.user_id', $sessionUserData['id'])
			->where('order.status', 4)->order('order.id desc')
			->paginate(['list_rows' => 1, 'query' => request()->param()]);

		//分页
		$page = $orderData->render();

		$orderData1 = $orderData->items();

		foreach ($orderData1 as $k => $v) {
			$orderData1[$k]['goods'] = Db::name('order_goods')->alias('a')->field('a.*,b.goods_name,b.goods_thumb')->join('goods b', 'a.goods_id=b.goods_id')->where('a.order_id', $v['id'])->select()->toArray();
		}
		//halt($orderData1);
		return view('', [
			'left_menu' => 12,
			'page' => $page,
			'orderData1' => $orderData1,
		]);
	}

	//我的订单--待支付0
	public function myorder0() {
		$sessionUserData = $this->isLogin();

		//清除24小时过时待支付订单
		$this->clearOrderStatus0();

		$orderData = Db::view('order', 'id,total_price,status,time,out_trade_no,pay_method')
			->view('address', 'shou_name', 'address.id=order.address_id')
			->where('order.user_id', $sessionUserData['id'])
			->where('order.status', 0)->order('order.id desc')
			->paginate(['list_rows' => 1, 'query' => request()->param()]);

		//分页
		$page = $orderData->render();

		$orderData1 = $orderData->items();

		foreach ($orderData1 as $k => $v) {
			$orderData1[$k]['goods'] = Db::name('order_goods')->alias('a')->field('a.*,b.goods_name,b.goods_thumb')->join('goods b', 'a.goods_id=b.goods_id')->where('a.order_id', $v['id'])->select()->toArray();
		}
		//halt($orderData1);
		return view('', [
			'left_menu' => 13,
			'page' => $page,
			'orderData1' => $orderData1,
		]);
	}
}