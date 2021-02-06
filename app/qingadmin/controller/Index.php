<?php
namespace app\qingadmin\controller;
use think\facade\Db;

class Index extends Base {
	public function index() {
		return view();
	}

	public function welcome() {
		$count1 = Db::name('goods')->count();
		$count2 = Db::name('user')->count();
		$count3 = Db::name('order')->count();
		$count4 = Db::name('comment')->count();
		return view('', [
			'count1' => $count1,
			'count2' => $count2,
			'count3' => $count3,
			'count4' => $count4,
		]);
	}

	//后台登录退出
	public function logout() {
		session('adminSessionData', null);
		return redirect('/qingadmin/login/index');
	}

}
