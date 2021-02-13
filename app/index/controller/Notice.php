<?php
namespace app\index\controller;
use think\facade\Db;

/**
 * @authors LiuNan
 * @email   bj_liunan@126.com
 * @date    2021-02-13 19:29:18
 */

class Notice extends Base {
	//全部设置为已读
	public function read_all() {
		//1、一刀切：把notice_read当前用户记录全部删除，notice(type 0)_全部插入notice_read
		//2、补丁：找到已读的notice_id，并转成字符串 1,2,3,再找出notice不在notin1,2,3,找到结果.再把4，5插入notice_read

		$userData = $this->isLogin();

		//查找当前用户已读数据
		$noticeIsreadData = Db::name('notice_read')->field('notice_id')->where('user_id', $userData['id'])->select()->toArray();

		//转一维数组
		$noticeIsreadData = array_column($noticeIsreadData, 'notice_id');

		//找出没有读的id
		$noticeData = Db::name('notice')->where('type', 0)->whereNotIn('id', $noticeIsreadData)->select();

		//插入的已读数据表中
		foreach ($noticeData as $k => $v) {
			Db::name('notice_read')->insert([
				'user_id' => $userData['id'],
				'notice_id' => $v['id'],
			]);
		}
	}

	//全部设置为已读，私信
	public function read_all_private() {
		$userData = $this->isLogin();
		$noticeIsreadData = Db::name('notice_read')->field('notice_id')->where('user_id', $userData['id'])->select()->toArray();
		$noticeIsreadData = array_column($noticeIsreadData, 'notice_id'); //转一维数组

		$noticeData = Db::name('notice')->where('type', 1)->whereFindInSet('user_id', $userData['id'])->whereNotIn('id', $noticeIsreadData)->select();
		foreach ($noticeData as $k => $v) {
			Db::name('notice_read')->insert([
				'user_id' => $userData['id'],
				'notice_id' => $v['id'],
			]);
		}
	}

	//消息通知，集体通知
	public function notice() {

		$sessionUserData = $this->isLogin();

		//全体消息列表
		$noticeData = Db::name('notice')->where('type', 0)->paginate(10);

		$noticeReadData = Db::name('notice_read')->field('notice_id')->where('user_id', $sessionUserData['id'])->select()->toArray();
		$noticeReadData = array_column($noticeReadData, 'notice_id');

		return view('', [
			'left_menu' => 43,
			'noticeData' => $noticeData,
			'noticeReadData' => $noticeReadData,
		]);

	}

	//消息通知，私信
	public function notice_private() {

		$sessionUserData = $this->isLogin();

		//私信消息列表
		$noticeData = Db::name('notice')->where('type', 1)->whereFindInSet('user_id', $sessionUserData['id'])->paginate(10);

		$noticeReadData = Db::name('notice_read')->field('notice_id')->where('user_id', $sessionUserData['id'])->select()->toArray();
		$noticeReadData = array_column($noticeReadData, 'notice_id');

		return view('', [
			'left_menu' => 43,
			'noticeData' => $noticeData,
			'noticeReadData' => $noticeReadData,
		]);

	}

	//消息详情
	public function notice_article() {
		$id = input('id');
		$noticeData = Db::name('notice')->find($id);
		$sessionUserData = $this->isLogin();

		//检测id合法性
		$noticeData = Db::name('notice')->find($id);
		if (empty($noticeData)) {
			return redirect('notice');
		}

		//标记为已读
		$noticeReadData = Db::name('notice_read')->where('user_id', $sessionUserData['id'])->where('notice_id', $id)->find();
		if (empty($noticeReadData)) {
			Db::name('notice_read')->insert([
				'user_id' => $sessionUserData['id'],
				'notice_id' => $id,
			]);
		}

		return view('', [
			'noticeData' => $noticeData,
			'left_menu' => 43,

		]);
	}
}