<?php

namespace app\common\model;
use think\Model;

class Category extends Model {

	protected $name = 'category'; //数据表

	/*无限极分类列表 by qing*/
	public function getTree($data = '', $cate_id = '', $status = '') {

		//$data = $this->order('listorder')->field('id,parent_id,cate_name,listorder,status')->where('status',$status)->select()->toArray();
		$data = $this->order('listorder')->field('id,parent_id,cate_name,listorder,status')->select()->toArray();
		return $this->_reSort($data, $cate_id);

	}

	//无限极分类树状结构，修改level值

	public function _reSort($data, $parent_id = 0, $cate_level = 0) {

		static $ret = array();

		foreach ($data as $k => $v) {

			if ($v['parent_id'] == $parent_id) {

				$v['cate_level'] = $cate_level;

				$ret[] = $v; //越来越大 塞数据
				unset($data[$k]); //越来越小  删数据 处理一个删一个

				$this->_reSort($data, $v['id'], $cate_level + 1);

			}

		}

		return $ret;

	}

	/*获取某栏目的顶级父类，返回顶级栏目id*/
	public function getTopCategory($id) {

		$data = $this->field('id,parent_id')->find($id);
		if ($data['parent_id'] != '0') {
			$this->getTopCategory($data['parent_id']);
		}
		return $data['parent_id'];

	}

	//由父类id得到全部子类，返回字符串
	public function getChildrenIdStr($cate_list, $parent_id = 0) {

		static $str = '';
		foreach ($cate_list as $k => $v) {

			if ($v['parent_id'] == $parent_id) {

				$str = $str . ',' . $v['id'];

				$this->getChildrenIdStr($cate_list, $v['id']);

			}

		}

		$str = ltrim($str, ','); //去除字符串左边的，
		$str = rtrim($str, ','); //去除字符串右边的，
		//print_r($str);
		return $str;

	}

	//得到全部子级，多维数组
	public function getChildren($cate_list, $parent_id = 0) {

		$arr = array();

		foreach ($cate_list as $key => $value) {

			if ($value['parent_id'] == $parent_id) {

				$value['children'] = $this->getChildren($cate_list, $value['id']);

				$arr[] = $value;

			}

		}

		return $arr;

	}

	//获取多级分类数据
	public function getNavCateData() {
		$data = array();
		$allcate = $this->order('listorder asc')->field('id,parent_id,cate_name')->select();
		$allcate = $allcate->toArray();
		foreach ($allcate as $k => $v) {
			if ($v['parent_id'] == 0) {

				foreach ($allcate as $k1 => $v1) {
					if ($v1['parent_id'] == $v['id']) {

						foreach ($allcate as $k2 => $v2) {
							if ($v2['parent_id'] == $v1['id']) {
								$v1['children'][] = $v2;
							}
						}

						$v['children'][] = $v1;
					}
				}

				$data[] = $v;
			}
		}
		return $data;
	}

}

?>