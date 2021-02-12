<?php
namespace app\index\controller;
use think\facade\Db;

/**
 * @authors LiuNan
 * @email   bj_liunan@126.com
 * @date    2021-02-13 00:13:19
 */

class Address extends Base {
	//地址列表
	public function address_list() {

		$sessionUserData = $this->isLogin();

		$addressData = Db::name("address")->where('user_id', $sessionUserData['id'])->paginate(10);
		return view('', [

			'addressData' => $addressData,
			'left_menu' => 22,

		]);

	}

	//添加地址
	public function add_address_save() {
		$sessionUserData = $this->isLogin();

		$data = input('post.');
		$data['user_id'] = $sessionUserData['id'];

		//validate自行验证字段合法性

		//第一次添加，当前地址就是默认
		//第n次添加，非默认
		$count = Db::name('address')->where('user_id', $sessionUserData['id'])->count();
		if ($count == 0) {
			$data['isdefault'] = 1; //默认地址
		}
		$res = Db::name('address')->insert($data);
		if ($res) {
			return alert('操作成功！', 'address_list', 6);
		} else {
			return alert('操作失败！', 'address_list', 5);
		}

	}

	//设置默认地址
	public function address_set_default() {
		$sessionUserData = $this->isLogin();

		//接收id
		$id = input('id');

		$addressData = Db::name('address')->find($id);
		if ($addressData['isdefault'] == 1 || empty($addressData)) {
			return redirect('address_list');
		}

		Db::name('address')->where('user_id', $sessionUserData['id'])->update(['isdefault' => 0]);
		Db::name('address')->where('id', $id)->update(['isdefault' => 1]);
		return redirect('address_list');

	}
}