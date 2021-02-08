<?php

namespace app\qingadmin\controller;

use think\Controller;
use think\facade\Db;
use think\facade\Request;

class Standard extends Base {

	public function index() {

		$type_id = Request::instance()->param('type_id');

		$attrData = Db::name('Standard')->alias('a')->field('a.*,b.type_name')->join('type b', 'b.id=a.type_id')->where('a.type_id', $type_id)->order('id desc')->paginate(15);

		return view('', [

			'attrData' => $attrData,

			'type_id' => $type_id,

		]);

	}

	/*添加属性*/

	public function add() {

		//获取属性列表

		$type_id = Request::instance()->param('type_id');

		if (request()->isPost()) {

			$data = request()->post();

			$standardId = Db::name('Standard')->insertGetId(array('type_id' => $type_id, 'name' => $data['name']));

			//得到刚插入的属性id，然后再插入属性搜索表

			if ($standardId) {

				$attrArr = explode(',', $data['attr_option_values']);

				foreach ($attrArr as $k => $v) {

					$data1['standard_id'] = $standardId;

					$data1['standard_value'] = $v;

					$res = Db::name('standard_value')->insert($data1);

				}

			}

			if ($res) {
				return alert('添加成功', 'index?type_id=' . $type_id, 6, 3);
			} else {
				return alert('添加失败', 'index?type_id=' . $type_id, 5, 3);
			}

		}

		return view('', [

			'type_id' => $type_id,

		]);

	}

	/*修改类型*/

	public function edit() {

		//先取出填充的数据

		$id = Request::instance()->param('id');

		$standardData = Db::name('standard')->where('id', $id)->find();

		$attrData = Db::name('standard_value')->where('standard_id', $id)->select();

		return view('', [

			'attrData' => $attrData,

			'standardData' => $standardData,

		]);

	}

	/*更新操作*/

	public function update() {

		if (request()->isPost()) {

			$data = request()->post();

			Db::name('Standard')->where('id', $data['id'])->update(array('name' => $data['name']));

			//考虑到属性值多变少，少变多的问题，直接是先删除原来的，再加新的

			Db::name('standard_value')->where('standard_id', $data['id'])->delete();

			$attrArr = explode(',', $data['attr_option_values']);

			foreach ($attrArr as $k => $v) {

				$data1['standard_id'] = $data['id'];

				$data1['standard_value'] = $v;

				$res = Db::name('standard_value')->insert($data1);

			}

			$typeData = Db::name('standard')->field('type_id')->find($data['id']);

			if ($res) {
				return alert('操作成功', 'index?type_id=' . $type_id, 6, 3);
			} else {
				return alert('操作失败', 'index?type_id=' . $type_id, 5, 3);
			}

		}

	}

	/* 删除属性操作

		    ** 删除属性的同时删除该属性拥有的属性值

	*/

	public function del() {

		//如果属性id是空，则跳转到属性列表页面

		$id = $this->request->param('id', 'intval');
		$type_id = $this->request->param('type_id', 'intval');

		if (empty($id)) {

			$this->redirect('index');

		}
		$res = Db::name('standard')->where('id', $id)->delete();

		$rers = Db::name('standard_value')->where('standard_id', $id)->delete();

		if ($res) {
			return alert('操作成功', 'index?type_id=' . $type_id, 6, 3);
		} else {
			return alert('操作失败', 'index?type_id=' . $type_id, 5, 3);
		}

	}

	public function add_standard_value() {
		$data = input('post.');
		$res = Db::name('standard_value')->insert($data);
		if ($res) {
			return ['status' => 1];
		}
	}

	public function update_standard_value() {
		$data = input('post.');
		$res = Db::name('standard_value')->update($data);
		if ($res) {
			return ['status' => 1];
		}
	}

	public function del_standard_value() {
		$data = input('post.');
		$res = Db::name('standard_value')->where('id', $data['id'])->delete();
		if ($res) {
			return ['status' => 1];
		}
	}

}
