<?php

namespace app\qingadmin\controller;

use think\facade\Db;
use think\facade\Request;

class Search extends Base {

	/**

	 * @return 热门搜索关键词

	 */

	//列表

	public function index() {

		$searchData = Db::name('search')->order('id desc')->paginate(10);

		return view('', [

			'searchData' => $searchData,

		]);

	}

	public function add() {

		if (request()->isPost()) {

			$data = input('post.');

			$id = Db::name('search')->where('name', $data['name'])->value('id');
			if ($id) {
				return alert('已经添加过了', 'index', 5, 3);
			}

			$res = Db::name('search')->insert($data);

			if ($res) {

				return alert('添加成功', 'index', 6, 3);

			} else {

				return alert('添加失败', 'index', 5, 3);

			}

		}

		return view();

	}

	public function edit() {

		$id = Request::instance()->param('id');

		$searchData = Db::name('search')->where('id', $id)->find();

		if (request()->isPost()) {

			$data = input('post.');

			$name = Db::name('search')->where('name', $data['name'])->value('id');

			if ($name) {

				return alert('已经存在该关键词', 'index', 5, 3);

			}

			$res = Db::name('search')->update($data);

			if ($res) {

				return alert('修改成功', 'index', 6, 3);

			} else {

				return alert('修改失败', 'index', 5, 3);

			}

		}

		return view('', [

			'searchData' => $searchData,

		]);

	}

}
