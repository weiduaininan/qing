<?php
namespace app\qingadmin\controller;

class Config extends Base {
	public function index() {
		return view();
	}

	//清空runtime下的缓存
	public function del_cache() {
		//$path=runtime_path();//应用运行时目录runtime/qingadmin
		$path = root_path() . 'runtime'; //runtime
		delFileByDir($path);
		//return alert('清空缓存成功','index/index',6);
		return alert('清空缓存成功', '/qingadmin/index/welcome', 6);
	}

}
