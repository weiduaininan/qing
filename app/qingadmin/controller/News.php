<?php

namespace app\qingadmin\controller;

use think\Controller;
use think\facade\Db;
use think\facade\Request;

class News extends Base {

	//新闻列表

	public function index() {

		$newsData = Db::name('news')->paginate(15);

		return view('', [

			'newsData' => $newsData,

		]);

	}

	/*添加*/

	public function add() {

		if (request()->isPost()) {

			$data = request()->post();
			$data['time'] = time();

			$res = Db::name('news')->insert($data);

			if ($res) {

				return alert('操作成功', 'index', 6);

			} else {

				return alert('操作失败', 'index', 5);

			}

		}

		$newsData['thumb'] = '';
		$newsData['id'] = 0;
		return view('', [
			'newsData' => $newsData,
		]);

	}

	/*修改*/

	public function edit() {

		//先取出填充的数据

		$id = Request::instance()->param('id');

		$newsData = Db::name('news')->find($id);

		return view('', [

			'newsData' => $newsData,

		]);

	}

	public function layui() {
		return view();
	}

	public function update() {

		//处理post过来的数据

		if (request()->isPost()) {

			$data = request()->post();

			$res = Db::name('news')->update($data);

			if ($res) {

				return alert('操作成功', 'index', 6);

			} else {

				return alert('操作失败', 'index', 5);

			}

		}

	}

	//删除 先删除封面图，再删除文章
	public function del() {
		$id = input('id');
		$newsData = Db::name('news')->find($id);

		delImg($path);

		$res = Db::name('news')->delete($id);
		if ($res) {

			return alert('操作成功', 'index', 6);

		} else {

			return alert('操作失败', 'index', 5);

		}

	}

}
