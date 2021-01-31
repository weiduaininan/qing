<?php
namespace app\qingadmin\controller;
use think\facade\Db;

/**
 * @authors LiuNan
 * @email   bj_liunan@126.com
 * @date    2021-01-31 14:45:21
 */

class Flink extends Base {
	//友情链接查看
	public function index() {
		$flinkData = Db::name('flink')->paginate(10); //数据表名Data
		return view('', [
			'flinkData' => $flinkData,
		]);
	}

	//添加
	public function add() {
		if (request()->isPost()) {
			$data = input('post.');
			$res = Db::name('flink')->insert($data);
			if ($res) {
				return alert('添加友情链接成功', 'index', '6');
			} else {
				return alert('添加友情链接失败', 'index', '5');
			}

		} else {
			return view();
		}
	}
	//编辑
	public function edit() {
		if (request()->isPost()) {
			$data = input('post.');
			$res = Db::name('flink')->update($data);
			if ($res) {
				return alert('更新友情链接成功', 'index', '6');
			} else {
				return alert('更新友情链接失败', 'index', '5');
			}

		} else {
			$id = input('id');
			$flinkData = Db::name('flink')->find($id);
			return view('', [
				'flinkData' => $flinkData,
			]);

		}
	}
}