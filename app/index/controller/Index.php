<?php

namespace app\index\controller;

use app\common\model\Category as CategoryModel;
use think\Controller;
use think\exception\ValidateException;
use think\facade\Db;
use think\Request;

class Index extends Base {

	public function index() {

		if (request()->isMobile()) {
			return redirect('/mobile/index/index');
		}

		//轮播
		$bannerData = Db::name('adverts')->where('type_id', 1)->select();

		//热销推荐
		$goodsData5 = Db::name('goods')->where('ishot', 1)->limit(8)->order('goods_id desc')->order('listorder asc')->where('goods_status', 1)->select();

		//轮播分类
		$category_model = new CategoryModel();
		$indexCate = $category_model->getNavCateData();

		//获取手机商品
		$goodsData18 = Db::name('goods')->limit(8)->where('goods_cate_id', 'in', '16,17,18,26,27,28,29')->order('goods_id desc')->order('listorder asc')->where('goods_status', 1)->select();

		$goodsData4 = Db::name('goods')->limit(8)->where('goods_cate_id', 'in', '8,9,155,156,157,158')->order('goods_id desc')->order('listorder asc')->where('goods_status', 1)->select();

		//获取手机分类
		$goodsCate18 = Db::name('category')->field('id,cate_name')->where('parent_id', 18)->select();

		//获取手机分类
		$goodsCate4 = Db::name('category')->field('id,cate_name')->where('parent_id', 4)->select();

		return view('', [

			'goodsData5' => $goodsData5,
			'goodsData18' => $goodsData18,
			'indexCate' => $indexCate,
			'bannerData' => $bannerData,
			'goodsCate18' => $goodsCate18,
			'goodsData4' => $goodsData4,
			'goodsCate4' => $goodsCate4,
		]);

	}

	//优惠券专题
	// public function coupons(){

	//     //不能获取已经过期的优惠券
	//     $time=time();
	//     $couponsData=Db::name('coupons')->order('id desc')->where('time2','>',$time)->select();
	//     return view('',[
	//         'couponsData'=>$couponsData
	//     ]);
	// }

	//表单令牌
	public function message(Request $request) {
		if (request()->isPost()) {
			$data = input('post.');
			$check = $request->checkToken('__token__');
			if (false === $check) {

				throw new ValidateException('invalid token');
			}
			echo '令牌正确，请插入数据表';
		};
		return view();
	}
}