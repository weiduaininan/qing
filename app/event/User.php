<?php
declare (strict_types = 1);

namespace app\event;

use app\common\model\User as UserModel;

class User {
	public $user;

	public function __construct(UserModel $user) {
		$this->user = $user;
	}

	//给用户登录次数+1
	public function setLoginCount() {
		$session = app('session');
		$ses = $session->get('sessionUserData');
		$userInfo = $this->user->getUserInfo($ses['id']);
		$login_count = $userInfo['login_count'] + 1;
		$this->user->where('id', $ses['id'])->update(['login_count' => $login_count]);
		$userInfo->login_count += 1;
		return $userInfo;
	}
}
