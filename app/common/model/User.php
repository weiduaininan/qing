<?php
namespace app\common\model;
use think\Model;

class User extends Model {
	protected $name = 'user';

	//获取用户ID的用户信息
	public function getUserInfo($id) {
		return self::field('id,username,login_count')->where('id', $id)->find();
	}
}