<?php
namespace app\qingadmin\controller;

class Index extends Base {
	public function index() {
		return view();
	}

	public function welcome() {
		return view('', [
			'count1' => 100,
			'count2' => 100,
			'count3' => 100,
			'count4' => 100,
		]);
	}

	//后台登录退出
	public function logout() {
		session('adminSessionData', null);
		return redirect('/qingadmin/login/index');
	}

}
