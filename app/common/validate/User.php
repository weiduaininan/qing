<?php
declare (strict_types = 1);

namespace app\common\validate;

use think\Validate;

class User extends Validate {
	/**
	 * 定义验证规则
	 * 格式：'字段名' =>  ['规则1','规则2'...]
	 *
	 * @var array
	 */
	protected $rule = [
		'mobile' => 'require|mobile',
		'password' => 'require|length:6,25',
		'smscode' => 'number',
		'repassword' => 'require|length:6,25|confirm:password',
		'email' => 'require|email',
	];

	/**
	 * 定义错误信息
	 * 格式：'字段名.规则名' =>  '错误信息'
	 *
	 * @var array
	 */
	protected $message = [
		'mobile.require' => '手机号必须填写',
		'mobile.mobile' => '手机号必须合法',
		'password.require' => '密码必须填写',
		'password.length' => '密码长度6-25位',
		'smscode.number' => '必须是数字',
		'email.require' => '邮箱必须填写',
		'email.email' => '邮箱格式不正确',
	];

	/**
	 * 具体的使用场景
	 * @var [type]
	 */
	protected $scene = [
		'login' => ['mobile', 'password'],
		'register' => ['mobile', 'password', 'smscode'],
		'bind_mobile' => ['mobile', 'smscode'],
		'edit_password' => ['password', 'repassword'],
		'info' => ['mobile', 'email'],
		'fapiao' => ['email'],
	];
}
