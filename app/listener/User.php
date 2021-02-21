<?php
declare (strict_types = 1);

namespace app\listener;

class User {
	/**
	 * 事件监听处理
	 *
	 * @return mixed
	 */
	public function handle(\app\event\User $event) {
		//$session = app('session');
		//$loginAdmin = $session->get('sessionUserData');
		//$userInfo = $event->user->getUserInfo($loginAdmin['id']);
		//echo 'listen监听器输出：' . json_encode($userInfo, JSON_UNESCAPED_UNICODE) . '<br />';
		//echo 'listen监听器输出：' . json_encode($event->setLoginCount(), JSON_UNESCAPED_UNICODE) . '<br />';
		$event->setLoginCount();
	}
}
