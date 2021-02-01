<?php
namespace app\qingadmin\model;
use think\Model;

class AuthRule extends Model {

	public function sort($ruleRes, $parent_id = 0, $level = 0) {

		static $arr = array();
		foreach ($ruleRes as $k => $v) {
			if ($v['parent_id'] == $parent_id) {
				$v['level'] = $level;
				$arr[] = $v;
				$this->sort($ruleRes, $v['id'], $level + 1);
			}
		}
		return $arr;
	}

	//获取子栏目id

	// public function childrenids($ruleid) {
	// 	$data = $this->field('id,parent_id')->select();
	// 	return $this->_childrenids($data, $ruleid);
	// }

	// private function _childrenids($data, $ruleid) {
	// 	static $arr = array();
	// 	foreach ($data as $k => $v) {
	// 		if ($v['parent_id'] == $ruleid) {
	// 			$arr[] = $v['id'];
	// 			$this->_childrenids($data, $v['id']);
	// 		}
	// 	}

	// 	return $arr;
	// }
}
