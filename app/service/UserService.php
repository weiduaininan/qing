<?php
declare (strict_types = 1);

namespace app\service;

class UserService extends \think\Service {

	/**
	 * 注册服务
	 *
	 * @return mixed
	 */
	public function register() {
		//echo '注册服务==》';
		$this->app->bind('user_m', \app\common\model\User::class);
		$this->app->bind('user_c', \app\qingadmin\controller\User::class);
		$this->app->bind('session', \think\Session::class);
	}

	/**
	 * 执行服务
	 *
	 * @return mixed
	 */
	public function boot() {
		//echo '执行服务==》';
	}
}
