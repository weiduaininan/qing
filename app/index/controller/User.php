<?php
namespace app\index\controller;
use app\common\validate\User as UserValidate;
use think\exception\ValidateException;
use think\facade\Db;

/**
 * @authors LiuNan
 * @email   bj_liunan@126.com
 * @date    2021-02-12 14:17:56
 */

class User extends Base {
	//会员首页
	public function index() {
		$sessionUserData = $this->isLogin();
		echo '会员主页';
	}
	//会员登录
	public function login() {
		$sessionUserData = session('sessionUserData');
		if (!empty($sessionUserData)) {
			return redirect('index');
		}

		if (request()->isPost()) {
			$data = input('post.');

			try {
				validate(UserValidate::class)
					->scene('login')
					->check($data);
			} catch (ValidateException $e) {
				// 验证失败 输出错误信息
				return alert($e->getError(), 'login', 5);
			}

			//验证码用户名
			$userData = Db::name('user')->where('mobile', $data['mobile'])->find();
			if (!$userData) {
				return alert('手机号不存在或者错误', 'login', 5);
			}

			//如果管理员有状态，status=1合法  0禁止
			if ($userData['status'] != 1) {
				return alert('您的账号被禁止登录', 'login', 5);
			}

			//密码校验
			if ($userData['password'] != $this->password_salt($data['password'])) {
				return alert('密码错误', 'login', 5);
			}

			Db::name('user')->where('id', $userData['id'])->update(['last_login_time' => time()]);
			session('sessionUserData', $userData);

			return alert('登录成功', 'index', 6);

		} else {
			return view();
		}
	}
	public function register() {
		if (request()->isPost()) {
			$data = input('post.');

			try {
				validate(UserValidate::class)
					->scene('register')
					->check($data);
			} catch (ValidateException $e) {
				// 验证失败 输出错误信息
				return alert($e->getError(), 'register', 5);
			}

			//判断该手机号状态
			$userData = Db::name('user')->where('mobile', $data['mobile'])->find();
			if ($userData['status'] == 1) {
				return alert('该手机号已经注册过了，请登录', 'login', 5);
			}
			if ($userData['status'] == -1) {
				return alert('该手机账号已经封号，请更换其他账号', 'register', 5);
			}

			$smscode = '123456';
			//$smscode=Cache::store('redis')->get($data['mobile']);
			//redis
			if ($data['smscode'] != $smscode) {
				return alert('手机验证码错误', 'register', 5);
			}

			//密码加密
			$data['password'] = $this->password_salt($data['password']);
			$data['time'] = time();
			$data['username'] = $data['mobile'];
			unset($data['smscode']);
			$res = Db::name('user')->insert($data);
			if ($res) {
				return alert('注册成功，请登录', 'login', 6);
			} else {
				return alert('注册失败', 'register', 5);
			}

		} else {
			return view();
		}
	}
}