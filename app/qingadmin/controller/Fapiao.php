<?php
namespace app\qingadmin\controller;
use think\facade\Db;
use think\facade\Request;

/**
 * @authors LiuNan
 * @email   bj_liunan@126.com
 * @date    2021-01-31 16:03:53
 */

class Fapiao extends Base {

	//查看
	public function index() {
		$fapiaoData = Db::name('fapiao')->paginate(10);
		return view('', [
			'fapiaoData' => $fapiaoData,
		]);
	}

	/*查看详细信息*/
	public function edit() {
		//先取出填充的数据
		$id = Request::instance()->param('id');
		$fapiaoData = Db::name('fapiao')->find($id);
		//halt($fapiaoData);
		return view('', [
			'fapiaoData' => $fapiaoData,
		]);
	}
	/**
	 * 修改发票状态
	 */
	public function status() {
		//获取要修改的发票ID
		$id = input('id');
		$res = Db::name('fapiao')->where('id', $id)->update([
			'status' => 1,
		]);
		if ($res) {
			return alert('发票状态成功', 'index', '6');
		} else {
			return alert('发票状态失败', 'index', '5');
		}
	}
}