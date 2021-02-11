<?php

namespace app\qingadmin\controller;

use app\common\model\Category as CategoryModel;
use app\common\model\Goods as GoodsModel;
use think\facade\Db;
use think\facade\Request;
use think\helper\Arr;

class Goods extends Base {

	private $models = [];
	private $index = 0;

	//多图上传方式1
	public function image1() {
		return view('public/image1');
	}

	//多图上传方式2
	public function image2() {
		return view('public/image2');
	}

	//商品列表
	public function goodslist() {

		$category_model = new CategoryModel();
		$cate_list1 = $category_model->getTree('', '', 1);

		//获取当前搜索的参数，用于填充当前状态
		$goods_status = input('goods_status') ? input('goods_status') : 1;

		$search_key = input('request.goods_name') ? input('request.goods_name') : '';

		$cate_id = input('goods_cate_id') ? input('goods_cate_id') : '';

		//商品列表
		$goods_model = new GoodsModel();
		$goodsData = $goods_model->search1($cate_id, $goods_status, $search_key);

		return view('', [

			'cate_list1' => $cate_list1, //商品分类信息
			'goodsData' => $goodsData, //商品信息
			'goods_status' => $goods_status,
			'search_key' => $search_key,
			'cate_id' => $cate_id,
		]);

	}

	/*添加商品界面*/

	public function add() {

		//获取无限级分类列表
		$categoryData = Db::name('category')->field('id,cate_name,parent_id')->where('parent_id', 0)->select();

		//获取类型数据
		$types = Db::name('Type')->select();

		return view('add', [

			'types' => $types,
			'image' => '',
			'categoryData' => $categoryData,
		]);

	}

	/* 商品添加提交到此方法

		    ** 商品缩略图为第一张

		    ** by qing

	*/

	public function save() {

		$data = request()->post();

		//图集处理
		$imageArr = [];
		if (!empty($data['image']) && isset($data['image'])) {
			$imageArr = $data['image'];
		}

		//商品详情处理
		$content = '';
		if (!empty($data['content']) && isset($data['content'])) {
			$content = $data['content'];
		}

		//商品分类
		$goodsData['cate_path'] = $data['cate1'] . '_' . $data['cate2'] . '_' . $data['goods_cate_id'];

		//商品SKU
		if ($data['single_standard'] == 2 && !isset($data['standard_value_id'])) {
			return alert('请选择商品属性', 'add', 5);
		}

		//处理多规格
		if ($data['single_standard'] == 2 && !empty($data['standard_value_id'])) {

			//print_r($data['standard_value_id']);
			foreach ($data['standard_value_id'] as $k => $v) {
				$standArr[] = $v;
			}
			//$a=[['1','2'],['5','6']];
			//print_r($a);
			//print_r($standArr);
			$this->models = $standArr;
			$standard_value_id = $this->combineAttributes();
			//halt($standard_value_id);

			$count = count($data['goods_price']);
			for ($i = 0; $i < $count; $i++) {
				$goodsStandardData[$i]['goods_price'] = $data['goods_price'][$i];
				$goodsStandardData[$i]['market_price'] = $data['market_price'][$i];
				$goodsStandardData[$i]['stock'] = $data['stock'][$i];
				$goodsStandardData[$i]['sku'] = $standard_value_id[$i];
			}
		}

		$goodsData['goods_name'] = $data['goods_name'];
		$goodsData['goods_short_name'] = $data['goods_short_name'];
		$goodsData['goods_cate_id'] = $data['goods_cate_id'];
		$goodsData['description'] = $data['description'];
		$goodsData['keywords'] = $data['keywords'];
		$goodsData['post_money'] = $data['post_money'];
		$goodsData['isbest'] = $data['isbest'];
		$goodsData['ishot'] = $data['ishot'];
		$goodsData['single_standard'] = $data['single_standard'];
		$goodsData['goods_price'] = $data['goods_price'];
		$goodsData['market_price'] = $data['market_price'];
		$goodsData['stock'] = $data['stock'];
		$goodsData['type_id'] = $data['type_id'];
		if (!empty($imageArr)) {
			$goodsData['goods_thumb'] = $imageArr[0];
		}

		$goodsData['time'] = time();

		//事务操作
		//我给朋友打钱1000元，账号输入错了，实际我的钱没有变，可以说退回来
		//1、银行先从我的账户扣除1000元，2、朋友的账户加上1000元钱 3、打钱不成功，我的账户再加上1000元钱

		// 启动事务
		Db::startTrans();
		try {
			//插入基本数据
			$goods_id = Db::name('goods')->insertGetId($goodsData);
			if (is_numeric($goods_id)) {

				//商品详情入库
				$res = Db::name('goods_content')->insert(['goods_id' => $goods_id, 'content' => $content]);

				//商品图集入库
				foreach ($imageArr as $k => $v) {
					$res = Db::name('goods_img')->insert(['goods_id' => $goods_id, 'image' => $v]);
				}

				//SKU入库
				if ($data['single_standard'] == 2) {
					foreach ($goodsStandardData as $k => $v) {
						if ($k == 0) {
							Db::name('goods')->where('goods_id', $goods_id)->update(['goods_price' => $v['goods_price'], 'market_price' => $v['market_price'], 'stock' => $v['stock']]);
						}
						$v['goods_id'] = $goods_id;
						$res = Db::name('goods_standard')->insert($v);
					}
				}
			}
			// 提交事务
			Db::commit();
		} catch (\Exception $e) {
			// 回滚事务
			Db::rollback();
			return alert('操作失败', 'goodslist', 5);
		}

		if ($res) {

			return alert('操作成功', 'goodslist', 6);

		} else {

			return alert('操作失败', 'goodslist', 5);

		}

	}

	/**
	 * 组合
	 */
	public function combineAttributes() {
		$m_len = count($this->models);
		if ($m_len >= 2) {
			$result = $this->recurse($this->models[$this->index], $this->models[++$this->index], $m_len);
		} else {
			$result = $this->models[0];
		}
		return $result;
	}

	/**
	 * 递归拼接属性
	 * @param $array1
	 * @param $array2
	 * @param $m_len
	 * @return array
	 */
	public function recurse($array1, $array2, $m_len) {
		$res = [];
		foreach ($array1 as $a1) {
			foreach ($array2 as $a2) {
				array_push($res, $a1 . ',' . $a2);
			}
		}
		$this->index++;
		if ($this->index <= $m_len - 1) {
			return $this->recurse($res, $this->models[$this->index], $m_len);
		} else {
			return $res;
		}
	}

	/* 修改商品的状态，如果goods_status是1，则变成-1，如果是-1，则变成1

		    ** 不删除商品下的图片

	*/

	public function status() {

		$goods_id = Request::instance()->param('goods_id');

		//先查找当前商品状态

		$goodsData = Db::name('Goods')->where('goods_id', $goods_id)->find();

		if ($goodsData['goods_status'] == 1) {

			$res = Db::name('Goods')->where('goods_id', $goods_id)->update(array('goods_status' => -1));

		}

		if ($goodsData['goods_status'] == -1) {

			$res = Db::name('Goods')->where('goods_id', $goods_id)->update(array('goods_status' => 1));

		}

		if ($res) {

			return alert('状态修改成功', 'goodslist', 6, 3);

		} else {

			return alert('状态修改失败', 'goodslist', 5, 3);

		}

		//$this->redirect('goodslist');

	}

	/* 商品修改界面显示

     */

	public function edit() {

		$goods_id = Request::instance()->param('goods_id');

		if (!is_numeric($goods_id)) {

			return alert('参数不合法', 'goodslist', 5);

		}

		//获取商品基本信息

		$goodsData = Db::name('Goods')->find($goods_id);
		$cateId1 = $cateId2 = $cateId3 = '';
		//'18_17_16'
		$cateArr = explode('_', $goodsData['cate_path']);
		$cateId1 = $cateArr[0];
		$cateId2 = $cateArr[1];
		$cateId3 = $cateArr[2];

		//获取分类列表
		$categoryData1 = Db::name('Category')->field('id,parent_id,cate_name')->where('parent_id', 0)->select();
		$categoryData2 = Db::name('Category')->field('id,parent_id,cate_name')->find($cateId2);
		$categoryData3 = Db::name('Category')->field('id,parent_id,cate_name')->find($cateId3);

		//获取类型数据

		$types = Db::name('Type')->select();

		//获取商品细节图信息

		$image = Db::name('goods_img')->where('goods_id', $goods_id)->select();

		//获取商品详情内容

		$goodsContentData = Db::name('goods_content')->where('goods_id', $goods_id)->find();

		//根据商品类型type_id,找到该类型的属性

		$standardData = Db::name('standard')->where('type_id', $goodsData['type_id'])->select()->toArray();

		//根据属性id，找到相应的属性值

		foreach ($standardData as $k => $v) {

			$standardData[$k]['standard_value'] = Db::name('standard_value')->where('standard_id', $v['id'])->select()->toArray();

		}

		//找到该商品拥有的属性值、价钱、库存

		$goodsStandardData = Db::name('goods_standard')->field('goods_price,stock,sku,market_price')->where('goods_id', $goods_id)->select()->toArray();
		//echo json_encode($goodsStandardData);
		$attrStr = '';
		foreach ($goodsStandardData as $k => $v) {

			$attrStr = $v['sku'] . ',' . $attrStr;

		}

		//1,5_1_6_1_10
		//1_5_6_10
		$attrArr = explode(',', $attrStr);
		$attrArr = array_unique($attrArr);

		return view('', [

			'goodsData' => $goodsData,
			'content' => $goodsContentData['content'],
			'categoryData1' => $categoryData1,
			'categoryData2' => $categoryData2,
			'categoryData3' => $categoryData3,
			'cateId1' => $cateId1,
			'cateId2' => $cateId2,
			'cateId3' => $cateId3,
			'image' => $image,

			'types' => $types,

			'standardData' => $standardData,

			'goodsStandardData' => $goodsStandardData,
			'attrArr' => $attrArr,

		]);

	}

	//通过goods_id获取sku
	public function ajaxGetAttrPrice() {
		$goods_id = Request::instance()->param('goods_id');
		$goodsStandardData = Db::name('goods_standard')->field('goods_price,stock,sku,market_price')->where('goods_id', $goods_id)->order('id asc')->select();
		return $goodsStandardData;
	}

	/*修改商品提交界面*/
	public function update() {

		$data = request()->post();
		$goods_id = $data['goods_id'];

		//图集处理
		$imageArr = [];
		if (!empty($data['image']) && isset($data['image'])) {
			$imageArr = $data['image'];
		}

		//商品详情处理
		$content = '';
		if (!empty($data['content']) && isset($data['content'])) {
			$content = $data['content'];
		}

		//商品分类
		$goodsData['cate_path'] = $data['cate1'] . '_' . $data['cate2'] . '_' . $data['goods_cate_id'];

		//商品SKU
		if ($data['single_standard'] == 2 && !isset($data['standard_value_id'])) {
			return alert('请选择商品属性', 'add', 5);
		}

		//处理多规格
		if ($data['single_standard'] == 2 && !empty($data['standard_value_id'])) {

			foreach ($data['standard_value_id'] as $k => $v) {
				$standArr[] = $v;
			}
			$this->models = $standArr;
			$standard_value_id = $this->combineAttributes();

			$count = count($data['goods_price']);
			for ($i = 0; $i < $count; $i++) {
				$goodsStandardData[$i]['goods_price'] = $data['goods_price'][$i];
				$goodsStandardData[$i]['market_price'] = $data['market_price'][$i];
				$goodsStandardData[$i]['stock'] = $data['stock'][$i];
				$goodsStandardData[$i]['sku'] = $standard_value_id[$i];
			}

		}

		$goodsData['goods_name'] = $data['goods_name'];
		$goodsData['goods_short_name'] = $data['goods_short_name'];
		$goodsData['goods_cate_id'] = $data['goods_cate_id'];
		$goodsData['description'] = $data['description'];
		$goodsData['keywords'] = $data['keywords'];
		$goodsData['post_money'] = $data['post_money'];
		$goodsData['isbest'] = $data['isbest'];
		$goodsData['ishot'] = $data['ishot'];
		$goodsData['single_standard'] = $data['single_standard'];
		$goodsData['goods_price'] = $data['goods_price'];
		$goodsData['market_price'] = $data['market_price'];
		$goodsData['stock'] = $data['stock'];
		$goodsData['type_id'] = $data['type_id'];
		if (!empty($imageArr)) {
			$goodsData['goods_thumb'] = $imageArr[0];
		}

		$goodsData['time'] = time();

		$whereGoodsID[] = ['goods_id', '=', $goods_id];

		try {
			//更新基本数据
			Db::name('goods')->where($whereGoodsID)->update($goodsData);

			//商品详情入库
			Db::name('goods_content')->where($whereGoodsID)->update(['content' => $content]);

			//商品图集入库
			//1、把goods_id旧的记录全删除，重新添加入库
			Db::name('goods_img')->where($whereGoodsID)->delete();
			foreach ($imageArr as $k => $v) {
				Db::name('goods_img')->insert(['goods_id' => $goods_id, 'image' => $v]);
			}

			//SKU入库
			if ($data['single_standard'] == 2) {

				Db::name('goods_standard')->where($whereGoodsID)->delete();
				foreach ($goodsStandardData as $k => $v) {
					if ($k == 0) {
						Db::name('goods')->where('goods_id', $goods_id)->update(['goods_price' => $v['goods_price'], 'market_price' => $v['market_price'], 'stock' => $v['stock']]);
					}
					$v['goods_id'] = $goods_id;
					Db::name('goods_standard')->insert($v);
				}

			}

		} catch (\Exception $e) {
			return alert('服务端异常', 'goodslist', 5);
		}

		return alert('操作成功', 'goodslist', 6);

	}

	/*AJAX实时通过类型获取商品属性*/

	public function ajaxGetAttr() {

		$typeid = Request::instance()->param('type_id');

		$arrtData = Db::name('standard')->where('type_id', $typeid)->select()->toArray();

		foreach ($arrtData as $k => $v) {

			$arrtData[$k]['standard_value'] = Db::name('standard_value')->where('standard_id', $v['id'])->select();

		}

		return $arrtData;

	}

	// AJAX删除商品属性

	public function ajaxDelGoodsAttr() {

		$gaid = input('get.gaid');

		Db::name('goods_attr')->where('id', $gaid)->delete();

	}

	/*AJAX实时通过类型获取商品属性*/

	public function goods_number() {

		$goodsId = input('get.id');

		// 根据商品ID取出这件商品同一个属性有多个值的属性

		// 原理：先取出这件商品有多个值的属性ID，再套SQL只取出这些有些属性的值 的记录

		// 还要连属性表取出属性的名称，因为表单中要用

		return $this->fetch('', [

			'types' => $types,

		]);

	}

	public function goods_listorder($goods_id, $listorder) {

		$data = input('post.');

		$res = Db::name('goods')->where('goods_id', $goods_id)->update(['listorder' => $listorder]);

		if ($res) {

			return ['data' => $_SERVER['HTTP_REFERER'], 'code' => 1];

		} else {

			return ['data' => $_SERVER['HTTP_REFERER'], 'code' => 0, 'msg' => '失败！'];

		}
	}

}

?>