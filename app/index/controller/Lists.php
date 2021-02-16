<?php
namespace app\index\controller;

use app\common\model\Category as CategoryModel;
use think\Controller;
use think\facade\Db;

class Lists extends Base {

	//商品分类
	public function index() {
		$cate_id = input('cate_id', 1, 'intval');

		//当前位置
		$positionData = $this->getPositionByCatId($cate_id);

		//子类数据
		$cateChildrenData = Db::name('category')->field('id,cate_name')->where('parent_id', $cate_id)->order('listorder asc')->select()->toArray();

		//当前分类信息
		$categoryData = Db::name('category')->find($cate_id);

		//当前分类下的所有子类字符串
		$category_model = new CategoryModel();
		$categorys = Db::name('category')->field('id,parent_id')->select()->toArray();
		$cateChildrenStr = $category_model->getChildrenIdStr($categorys, $cate_id);
		$cateChildrenStr = $cateChildrenStr . ',' . $cate_id;

		$where[] = [
			'goods_cate_id', 'in', $cateChildrenStr,
		];

		//排序
		$orderArr = [];
		$order = input('order');
		if (empty($order)) {
			$order = 1;
		}
		if ($order == 1) {
			$orderArr = ['goods_id' => 'desc'];
		}
		if ($order == 2) {
			$orderArr = ['selnumber' => 'desc'];
		}
		if ($order == 3) {
			$orderArr = ['goods_price' => 'desc'];
		}
		if ($order == 4) {
			$orderArr = ['goods_price' => 'asc'];
		}

		//属性数据
		$standardDataTemp = Db::name('standard')->field('name,id')->where('type_id', $categoryData['type_id'])->select()->toArray();
		$standardData = [];
		$filter = '';
		if ($standardDataTemp) {
			foreach ($standardDataTemp as $k => $v) {
				$standardData[$v['name']] = Db::name('standard_value')->field('standard_id,id,standard_value')->where('standard_id', $v['id'])->select()->toArray();
			}

			//去除掉没有商品的属性
			foreach ($standardData as $k => $v) {
				foreach ($v as $k1 => $v1) {
					$res = Db::name('goods_standard')->whereFindInSet('sku', $v1['id'])->find();
					if (empty($res)) {
						unset($standardData[$k][$k1]);
					}
				}
			}

			//获取属性数量
			$standardCount = Db::name('standard')->field('name,id')->where('type_id', $categoryData['type_id'])->count();

			$filter = input('get.filter');
			if (empty($filter)) {
				for ($i = 1; $i <= $standardCount; $i++) {
					$filterArrTmp0[$i] = 0;
				}
				$filter = implode(',', $filterArrTmp0);
			}

			$filterArrTmp = explode(',', $filter);
			$filterArr = [];
			foreach ($filterArrTmp as $k => $v) {
				if ($v != 0) {
					$filterArr[] = $v;
				}
			}

			$filterArrCount = count($filterArr);
		}

		$fieldStr = 'b.goods_id,b.goods_name,b.goods_price,b.goods_thumb,b.selnumber';

		if ($standardDataTemp) {
			//商品数据  whereFindInSet不支持下划线，改成英文逗号
			//1、0 0_0 0_0_0

			if ($filterArrCount == 0) {
				$goodsCount = Db::name('goods_standard')->alias('a')->group('b.goods_id')->distinct(true)->field($fieldStr)->join('goods b', 'a.goods_id=b.goods_id')->where($where)->count();

				$goodsData = Db::name('goods_standard')->alias('a')->distinct(true)->field($fieldStr)->join('goods b', 'a.goods_id=b.goods_id')->where($where)->order($orderArr)->paginate(['list_rows' => 10, 'total' => $goodsCount, 'query' => request()->param()]);
			}

			//2、1,1_0,1_0_0,0_0_1
			if ($filterArrCount == 1) {
				$goodsCount = Db::name('goods_standard')->alias('a')->distinct(true)->field($fieldStr)->join('goods b', 'a.goods_id=b.goods_id')->where($where)->whereFindInSet('sku', $filterArr[0])->count();
				$goodsData = Db::name('goods_standard')->alias('a')->distinct(true)->field($fieldStr)->join('goods b', 'a.goods_id=b.goods_id')->where($where)->whereFindInSet('sku', $filterArr[0])->order($orderArr)->paginate(['list_rows' => 10, 'total' => $goodsCount, 'query' => request()->param()]);
			}

			//3、1_2,1_2_0,0_1_2
			if ($filterArrCount == 2) {
				$goodsCount = Db::name('goods_standard')->alias('a')->distinct(true)->field($fieldStr)->join('goods b', 'a.goods_id=b.goods_id')->where($where)->whereFindInSet('sku', $filterArr[0])->whereFindInSet('sku', $filterArr[1])->count();
				$goodsData = Db::name('goods_standard')->alias('a')->distinct(true)->field($fieldStr)->join('goods b', 'a.goods_id=b.goods_id')->where($where)->whereFindInSet('sku', $filterArr[0])->whereFindInSet('sku', $filterArr[1])->order($orderArr)->paginate(['list_rows' => 10, 'total' => $goodsCount, 'query' => request()->param()]);
			}

			//4、1_2_3
			if ($filterArrCount == 3) {
				$goodsCount = Db::name('goods_standard')->alias('a')->distinct(true)->field($fieldStr)->join('goods b', 'a.goods_id=b.goods_id')->where($where)->where('sku', $filter)->count();
				$goodsData = Db::name('goods_standard')->alias('a')->distinct(true)->field($fieldStr)->join('goods b', 'a.goods_id=b.goods_id')->where($where)->where('sku', $filter)->order($orderArr)->paginate(['list_rows' => 10, 'total' => $goodsCount, 'query' => request()->param()]);
			}
		} else {
			// $goodsCount=Db::name('goods_standard')->alias('a')->distinct(true)->field($fieldStr)->join('goods b','a.goods_id=b.goods_id')->where($where)->count();

			// $goodsData=Db::name('goods_standard')->alias('a')->distinct(true)->field($fieldStr)->join('goods b','a.goods_id=b.goods_id')->where($where)->order($orderArr)->paginate(['list_rows'=> 10,'total'=> $goodsCount,'query'=>request()->param()]);
			$goodsData = Db::name('goods')->where($where)->order($orderArr)->paginate(['list_rows' => 10, 'query' => request()->param()]);

		}

		return view('', [
			'positionData' => $positionData,
			'cateChildrenData' => $cateChildrenData,
			'cate_id' => $cate_id,
			'standardData' => $standardData,
			'filter' => $filter,
			'goodsData' => $goodsData,
			'order' => $order,
		]);

	}

	public function search() {
		$keywords = input('keywords');
		$keywords = trim($keywords); //空格去掉

		//排序
		$orderArr = [];
		$order = input('order');
		if (empty($order)) {
			$order = 1;
		}
		if ($order == 1) {
			$orderArr = ['goods_id' => 'desc'];
		}
		if ($order == 2) {
			$orderArr = ['selnumber' => 'desc'];
		}
		if ($order == 3) {
			$orderArr = ['goods_price' => 'desc'];
		}
		if ($order == 4) {
			$orderArr = ['goods_price' => 'asc'];
		}

		$goodsData = Db::name('goods')->where('goods_name', 'like', '%' . $keywords . '%')->order($orderArr)->paginate(['list_rows' => 1, 'query' => request()->param()]);

		return view('', [
			'keywords' => $keywords,
			'goodsData' => $goodsData,
			'order' => $order,
		]);
	}

}
