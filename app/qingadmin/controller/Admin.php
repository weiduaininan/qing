<?php
namespace app\qingadmin\controller;
use think\facade\Db;
use think\facade\Request;

class Admin extends Base {
	/**
	 * @return 管理员管理
	 */

	//删除、更新状态都提取了公共的方法
	//好处就是减少代码量
	//不好的地方：权限无法监控到

	public function index() {

		//判断当前管理员是不是超级管理员
		//如果是超级管理员，则有权限查看其他的管理员，有更改、删除其他管理员的权限
		//否则只能查看自己的信息
		$loginAdmin = session('adminSessionData');
		if ($loginAdmin['group_id'] == 1) {
			$adminData = Db::name('admin')->alias('a')->field('a.*,b.title')->join('auth_group b', 'a.group_id=b.id')->select();
		} else {
			$adminData = Db::name('admin')->alias('a')->field('a.*,b.title')->join('auth_group b', 'a.group_id=b.id')->where('a.id', $loginAdmin['id'])->select();
		}

		return view('', [
			'adminData' => $adminData,
		]);
	}

	public function add() {
		$authGroup = Db::name('auth_group')->field('id,title')->select();
		if (request()->isPost()) {
			$data = input('post.');
			if (!empty($data['password'])) {
				$data['password'] = $this->password_salt($data['password']);
			}
			$data['last_login_time'] = time();
			$res = Db::name('admin')->insert($data);
			if ($res) {
				return alert('操作成功！', 'index', 6, 3);
			} else {
				return alert('操作失败！', 'index', 5, 3);
			}
		}
		return view('', [
			'authGroup' => $authGroup,
		]);
	}

	public function edit() {
		$id = input('id');
		if (request()->post()) {
			$data = input('post.');
			//$data1 = input('get.');
			if (!empty($data['password'])) {
				$data['password'] = $this->password_salt($data['password']);
			} else {
				unset($data['password']);
			}

			$res = Db::name('admin')->update($data);
			if ($res) {
				return alert('修改成功！', 'index', 6, 3);
			} else {
				return alert('修改失败！', 'index', 5, 3);
			}
		}

		$adminData = Db::name('admin')->find($id);
		$authGroupData = Db::name('auth_group')->select();
		return view('', [
			'adminData' => $adminData,
			'authGroupData' => $authGroupData,
		]);

	}

	public function del() {
		$id = input('id');
		$res = Db::name('admin')->delete($id);
		if ($res) {
			return alert('删除成功！', 'index', 6, 3);
		} else {
			return alert('删除失败！', 'index', 5, 3);
		}
	}

}
