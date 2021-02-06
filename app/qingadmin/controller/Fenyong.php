<?php
namespace app\qingadmin\controller;
use think\facade\Db;

/**
 * 分佣管理
 */
class Fenyong extends Base {

	//分佣
	//用户A(1)推荐用户B注册网站
	//用户A给他50积分
	//用户B给他30积分
	//A parent_id 0
	//B parent_id 1
	//
	public function index() {

		$userData = Db::name('fenyong')->alias('f')->join('user u', 'u.id=f.user_id')->field('f.id,u.username,u.id as user_id,u.parent_id,u.time')->where('u.parent_id', '<>', 0)->paginate(10);
		return view('', [

			'userData' => $userData,

		]);

	}
}