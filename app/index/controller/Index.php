<?php

namespace app\index\controller;

use think\Controller;
use think\exception\ValidateException;
use think\Request;

class Index extends Base {

	public function index() {
		return view();
	}

	//优惠券专题
	// public function coupons(){

	//     //不能获取已经过期的优惠券
	//     $time=time();
	//     $couponsData=Db::name('coupons')->order('id desc')->where('time2','>',$time)->select();
	//     return view('',[
	//         'couponsData'=>$couponsData
	//     ]);
	// }

	//表单令牌
	public function message(Request $request) {
		if (request()->isPost()) {
			$data = input('post.');
			$check = $request->checkToken('__token__');
			if (false === $check) {

				throw new ValidateException('invalid token');
			}
			echo '令牌正确，请插入数据表';
		};
		return view();
	}
}