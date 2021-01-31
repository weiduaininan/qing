<?php
namespace app\qingadmin\controller;
use think\facade\Db;

class Config extends Base {
	//清空runtime下的缓存
	public function del_cache() {
		//$path=runtime_path();//应用运行时目录runtime/qingadmin
		$path = root_path() . 'runtime'; //runtime
		delFileByDir($path);
		//return alert('清空缓存成功','index/index',6);
		return alert('清空缓存成功', '/qingadmin/index/welcome', 6);
	}

	/**
	 * 系统设置
	 * @Author   liunan
	 * @DateTime 2021-01-31T17:02:42+0800
	 * @Email    bj_liunan@126.com
	 * @return   [type]                   [description]
	 */
	public function index() {
		$configData1 = Db::name('config')->where('config_type', 1)->select();
		$configData2 = Db::name('config')->where('config_type', 2)->select();

		if (request()->isPost()) {
			$data = input('post.');
			//halt($data);
			foreach ($data as $k => $v) {
				Db::name('config')->where('id', $k)->update(['value' => $v]);
			}

			return alert('操作成功', 'index', 6);
		} else {
			return view('', [
				'configData1' => $configData1,
				'configData2' => $configData2,
			]);
		}
	}
}
