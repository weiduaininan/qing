<?php
declare (strict_types = 1);

namespace app\command;

use think\console\Command;
use think\console\Input;
use think\console\Output;
use think\facade\Db;

class Comment extends Command {
	protected function configure() {
		// 指令配置
		$this->setName('app\command\comment')
			->setDescription('the app\command\comment command');
	}

	protected function execute(Input $input, Output $output) {
		while (true) {
			$this->comment_add_auto();
			$this->order_status_auto();
			sleep(1);
		}
	}

	//已经确认收货 完成订单，没有评论，默认添加评论
	public function comment_add_auto() {
		$time = time() - 60 * 60 * 24; //一天24小时
		$orderGoodsData = Db::view('order_goods', 'id,order_id,goods_id')
			->view('order', 'time,user_id', 'order_goods.order_id=order.id')
			->where('order.status', 2)
			->where('order_goods.iscomment', 0)
			->where('order.time', '<', $time)
			->order('order.time asc')
			->find();
		//好评 comment iscoment 1

		if ($orderGoodsData) {
			$data['order_id'] = $orderGoodsData['order_id'];
			$data['user_id'] = $orderGoodsData['user_id'];
			$data['goods_id'] = $orderGoodsData['goods_id'];
			$data['time'] = time();
			$data['star'] = 5;
			$data['content'] = '好评';

			$res = Db::name('comment')->insert($data);
			if ($res) {
				//更改order_goods表中评论状态
				Db::name('order_goods')->where('order_id', $data['order_id'])->where('goods_id', $data['goods_id'])->update(['iscomment' => 1]);

				//查找是否还有没有评论的商品，如果都评论了，更新order中的iscomment
				$orderGoods = Db::name('order_goods')->where('order_id', $data['order_id'])->where('iscomment', 0)->find();
				if (empty($orderGoods)) {
					Db::name('order')->where('id', $data['order_id'])->update(['iscomment' => 1]);
				}

			}
		}
	}

	//已发货未签收了, 7天未签收自动完成订单 更新订单状态 完成默认评论
	public function order_status_auto() {
		$time = time() - 60 * 60 * 24 * 7; //一天24小时  7天
		$orderGoodsData = Db::view('order_goods', 'id,order_id,goods_id')
			->view('order', 'time,user_id', 'order_goods.order_id=order.id')
			->where('order.status', 4)
			->where('order_goods.iscomment', 0)
			->where('order.time', '<', $time)
			->order('order.time asc')
			->find();
		//好评 comment iscoment 1

		if ($orderGoodsData) {

			$data['order_id'] = $orderGoodsData['order_id'];
			$data['user_id'] = $orderGoodsData['user_id'];
			$data['goods_id'] = $orderGoodsData['goods_id'];
			$data['time'] = time();
			$data['star'] = 5;
			$data['content'] = '好评';

			$res = Db::name('comment')->insert($data);
			Db::name('order')->where('id', $orderGoodsData['order_id'])->update(['status' => 2]);
			if ($res) {
				//更改order_goods表中评论状态
				Db::name('order_goods')->where('order_id', $data['order_id'])->where('goods_id', $data['goods_id'])->update(['iscomment' => 1]);

				//查找是否还有没有评论的商品，如果都评论了，更新order中的iscomment
				$orderGoods = Db::name('order_goods')->where('order_id', $data['order_id'])->where('iscomment', 0)->find();
				if (empty($orderGoods)) {
					Db::name('order')->where('id', $data['order_id'])->update(['iscomment' => 1]);
				}

			}
		}
	}

}
