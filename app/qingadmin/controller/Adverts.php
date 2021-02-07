<?php

namespace app\qingadmin\controller;

use think\facade\Db;
use think\facade\Request;

class Adverts extends Base {

	public function index() {
		$where = [];
		$type_id = input('type_id');
		if ($type_id) {
			$where = [
				['a.type_id', '=', $type_id],
			];
		}
		$adData = Db::name('adverts')->alias('a')->join('adverts_type b', 'a.type_id=b.id')->field('a.*,b.id type_id,b.name')->where($where)->order('listorder asc')->paginate(10);
		$typeData = Db::name('adverts_type')->select();
		return view('', [

			'type_id' => $type_id,
			'typeData' => $typeData,
			'adData' => $adData,

		]);

	}

	public function add() {

		if (request()->isPost()) {

			// 入库的逻辑

			$data = input('post.');

			$id = Db::name('Adverts')->insert($data);

			if ($id) {
				return alert('操作成功', 'index', 6, 3);
			} else {
				return alert('操作失败', 'index', 5, 3);
			}

		} else {

			// 获取推荐位类别

			$types = Db::name('adverts_type')->select();
			$adData['thumb'] = '';
			$adData['id'] = 0;

			return view('', [

				'types' => $types,
				'adData' => $adData,

			]);

		}

	}

	//编辑广告位

	public function edit() {

		$id = Request::instance()->param('id');

		$adData = Db::name('adverts')->find($id);

		// 获取推荐位类别

		$types = Db::name('adverts_type')->select();

		if (request()->isPost()) {

			$data = input('post.');

			$res = Db::name('adverts')->where('id', $data['id'])->update($data);

			if ($res) {
				return alert('操作成功', 'index', 6, 3);
			} else {
				return alert('操作失败', 'index', 5, 3);
			}

		}

		return view('', [

			'types' => $types,
			'adData' => $adData,
		]);

	}

	//广告分组列表
	public function adverts_type() {
		$adTypeData = Db::name('adverts_type')->order('id desc')->select();
		return view('', [
			'adTypeData' => $adTypeData,
		]);
	}

	//添加广告分组
	public function adverts_type_add() {
		if (request()->isPost()) {
			$data = input('post.');
			$res = Db::name('adverts_type')->insert($data);
			if ($res) {
				return alert('操作成功', 'adverts_type', 6, 3);
			} else {
				return alert('操作失败', 'adverts_type', 5, 3);
			}
		}

		return view();

	}

	//修改广告分组
	public function adverts_type_edit() {
		if (request()->isPost()) {
			$data = input('post.');
			$res = Db::name('adverts_type')->update($data);
			if ($res) {
				return alert('操作成功', 'index', 6, 3);
			} else {
				return alert('操作失败', 'index', 5, 3);
			}
		}
		$id = request()->param('id');
		$adTypeData = Db::name('adverts_type')->find($id);
		return view('', [
			'adTypeData' => $adTypeData,
		]);

	}

	public function adverts_type_del() {
		$id = request()->param('id');
		$res = Db::name('adverts_type')->delete($id);
		if ($res) {
			return alert('操作成功', 'adverts_type', 6, 3);
		} else {
			return alert('操作失败', 'adverts_type', 5, 3);
		}
	}

	public function del() {
		$id = Request::instance()->param('id');
		$image = Db::name('adverts')->where('id', $id)->value('thumb');
		delImg($image); //删除文件
		$res = Db::name('adverts')->where('id', $id)->delete();
		if ($res) {
			return alert('操作成功', 'index', 6, 3);
		} else {
			return alert('操作失败', 'index', 5, 3);
		}
	}

}
