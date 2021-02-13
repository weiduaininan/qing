<?php
namespace app\index\controller;

use app\common\validate\User as UserValidate;
use think\facade\Db;

/**
 * liunan
 */
class Fapiao extends Base {
	public function fapiao() {
		$sessionUserData = $this->isLogin();

		//订单数据 1、当前用户 2、已支付 3、没有开过发票
		$orderData = Db::name('order')->where('user_id', $sessionUserData['id'])->where('status', '>', 1)->where('isfapiao', 0)->paginate(10);
		return view('', [
			'left_menu' => 44,
			'orderData' => $orderData,
		]);
	}
	//已开发票
	public function is_fapiao() {
		$sessionUserData = $this->isLogin();
		$fapiaoData = Db::name('fapiao')->where('user_id', $sessionUserData['id'])->paginate(10);
		return view('', [
			'left_menu' => 44,
			'fapiaoData' => $fapiaoData,
		]);
	}
	//发票表单
	public function fapiao_form() {
		$sessionUserData = $this->isLogin();

		$id = input('id');
		$orderData = Db::name('order')->field('total_price,out_trade_no')->find($id);
		if (empty($orderData)) {
			return redirect('fapiao');
		}

		if (request()->isPost()) {
			$data = input('post.');
			$data['time'] = time();
			$data['money'] = $orderData['total_price'];
			$data['out_trade_no'] = $orderData['out_trade_no'];
			$data['user_id'] = $sessionUserData['id'];

			//验证字段合法性
			//validate验证
			try {
				validate(UserValidate::class)
					->scene('fapiao')
					->check(['email' => $data['email']]);
			} catch (ValidateException $e) {
				// 验证失败 输出错误信息
				return alert($e->getError(), 'fapiao_form', 5);
			}

			$res = Db::name('fapiao')->insert($data);
			if ($res) {
				return alert('操作成功', 'is_fapiao', 6);
			} else {
				return alert('操作失败', 'fapiao', 5);
			}
		}

		return view('', [
			'left_menu' => 44,
			'orderData' => $orderData,
		]);
	}
}