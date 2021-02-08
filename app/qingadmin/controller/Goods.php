<?php
namespace app\qingadmin\controller;

class Goods extends Base {

	//列表
	public function goodslist() {

		return view();
	}

	public function add() {
		echo '我是添加商品';
	}

	public function edit() {
		echo '我是修改商品';
	}

	public function del() {
		echo '我是删除商品';
	}

}
