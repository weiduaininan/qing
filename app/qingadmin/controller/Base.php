<?php
namespace app\qingadmin\controller;

use app\BaseController;
//use think\exception\HttpResponseException;
use think\facade\Db;
use think\facade\Request;
use think\facade\View;

class Base extends BaseController {
	public function initialize() {
		$loginAdmin = session('adminSessionData');
		View::assign('loginUser', $loginAdmin['user_name']);

		//权限控制
		$this->adminAuth($loginAdmin);

		//左侧菜单数据
		$authRuleMenuData = $this->getLeftMenu();
		View::assign('authRuleMenuData', $authRuleMenuData);

		//当前用户组的权限
		$rulesArrTmp = Db::name('auth_group')->field('rules')->find($loginAdmin['group_id']);
		$rulesArr = explode(',', $rulesArrTmp['rules']);
		View::assign('rulesArr', $rulesArr);

	}

	//后台公共删除
	public function del() {
		$id = input('id');
		$dbname = input('dbname');
		$res = Db::name($dbname)->delete($id);
		if ($res) {
			return alert('操作成功', $_SERVER['HTTP_REFERER'], '6');
		} else {
			return alert('操作失败', $_SERVER['HTTP_REFERER'], '5');
		}
	}

	//后台密码加密盐
	public function password_salt($str) {
		$salt = 'zxcvbn';
		return md5($salt . $str);
	}

	//更改状态
	public function status() {
		$id = Request::instance()->param('id', 'intval');
		$status = Request::instance()->param('status', 'intval');
		$dbname = input('dbname');
		$res = Db::name($dbname)->where('id', $id)->update(['status' => $status]);
		if ($res) {
			return alert('操作成功！', $_SERVER['HTTP_REFERER'], 6, 3);
		} else {
			return alert('操作失败！', $_SERVER['HTTP_REFERER'], 5, 3);
		}
	}

	//抛出异常的方式进行跳转
	//https://www.jianshu.com/p/c2a1f983fe35
	// public function redirect(...$args) {
	// 	throw new HttpResponseException(redirect(...$args));
	// }

	//权限控制

	public function adminAuth($loginAdmin) {

		$currentRule = request()->controller() . '/' . request()->action();

		$rulesArrTmp = Db::name('auth_group')->field('rules')->find($loginAdmin['group_id']);

		$rulesArr = explode(',', $rulesArrTmp['rules']);
		//halt($rulesArr);
		if ($currentRule == 'Login/index') {
			return true;
		}
		foreach ($rulesArr as $k => $v) {
			$authRuleData = Db::name('auth_rule')->find($v);
			if ($authRuleData['name'] == $currentRule) {
				return true;
			}
		}
		//return alert('你没有权限!', '/qingadmin/index/welcome', '5', 3);die;
		//halt('您没有权限,请向管理员申请该权限，谢谢！');
		//如果没有权限，我们就跳转到后台首页
		//$this->redirect('/qingadmin/index/welcome');
		echo "<script>alert('您没有权限,请向管理员申请该权限，谢谢！');</script>";
		echo "<meta http-equiv='Refresh' content='0;URL=/qingadmin/index/welcome'>";die;
	}

	//左侧菜单数据
	public function getLeftMenu() {
		$authRuleData = Db::name('auth_rule')->where('parent_id', 0)->where('status', 1)->where('is_left', 1)->order('listorder asc')->select()->toArray();
		//echo Db::name('auth_rule')->getLastSql();die;
		foreach ($authRuleData as $k => $v) {
			$authRuleData[$k]['children'] = Db::name('auth_rule')->where('parent_id', $v['id'])->where('status', 1)->where('is_left', 1)->order('listorder asc')->select()->toArray();
		}
		//halt($authRuleData);
		return $authRuleData;
	}

	/*

		      **公共排序方法

		      **需要在排序html中传入下面三个参数

	*/

	public function listorder($id, $model, $listorder) {

		$data = input('post.');
		$res = Db::name($model)->where('id', $id)->update(['listorder' => $listorder]);
		//halt($res);
		if ($res) {

			return ['data' => $_SERVER['HTTP_REFERER'], 'code' => 1];

		} else {

			return ['data' => $_SERVER['HTTP_REFERER'], 'code' => 0, 'msg' => '失败！'];

		}

	}

}