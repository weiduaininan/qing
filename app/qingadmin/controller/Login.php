<?php
namespace app\qingadmin\controller;
use think\facade\Db;

class Login extends Base {
	public function index() {
		//1589448132
		//root_qing  0d734ea736e18b582050e3b990636001
		//halt(md5('zxcvbn1234567'));//加密盐
		//后台密码1234567
		if (session('adminSessionData')) {
			return redirect('/qingadmin/index');
		}

		if (request()->isPost()) {
			$data = input('post.');

			//验证码校验
			if (!captcha_check($data['verifycode'])) {
				return alert('验证码错误', 'login', 5);
			};

			//验证码用户名
			$adminData = Db::name('admin')->where('user_name', $data['username'])->find();
			if (!$adminData) {
				return alert('用户名错误', 'login', 5);
			}

			//如果管理员有状态，status=1合法  0禁止
			if ($adminData['status'] == 0) {
				return alert('您的账号被禁止登录', 'login', 5);
			}

			//密码校验
			if ($adminData['password'] != $this->password_salt($data['password'])) {
				return alert('密码错误', 'login', 5);
			}

			Db::name('admin')->where('id', $adminData['id'])->update(['last_login_time' => time()]);
			session('adminSessionData', $adminData);

			return alert('登录成功', '/qingadmin/index/index', 6);

		} else {
			return view();
		}

	}

}
