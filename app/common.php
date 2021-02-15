<?php
use think\facade\Db;
// 应用公共文件

/**

 * $msg 待提示的消息

 * $url 待跳转的链接

 * $icon 这里主要有两个，5和6，代表两种表情（哭和笑）

 * $time 弹出维持时间（单位秒）

 */

function alert($msg = '', $url = '', $icon = '', $time = 3) {

	$str = '<meta name="viewport" content="initial-scale=1, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black" />
    <meta http-equiv="Access-Control-Allow-Origin" content="*" />
    <meta http-equiv="pragma" content="no-cache" />
    <script type="text/javascript" src="/public/static/index/js/jquery-3.4.1.min.js"></script><script type="text/javascript" src="/public/static/index/layer/2.4/layer.js"></script>'; //加载jquery和layer

	$str .= '<script>$(function(){layer.msg("' . $msg . '",{icon:' . $icon . ',time:' . ($time * 1000) . '});setTimeout(function(){self.location.href="' . $url . '"},2000)});</script>'; //主要方法

	return $str;

}

//删除目录及文件，传入目录
function delFileByDir($dir) {
	$dh = opendir($dir);
	while ($file = readdir($dh)) {
		if ($file != "." && $file != "..") {

			$fullpath = $dir . "/" . $file;
			if (is_dir($fullpath)) {
				delFileByDir($fullpath);
			} else {
				if ($fullpath !== '.gitignore') {
					unlink($fullpath);
				}

			}
		}
	}
	closedir($dh);
}

//通过id获取用户信息
function getUserById($id) {
	$userData = Db::name('user')->find($id);
	if (empty($userData)) {
		return '其他途径';
	}
	return $userData['username'];
}

//删除图片,传递过来图片路径
function delImg($path) {
	if (empty($path)) {
		return false;
	}

	$path = app()->getRootPath() . $path;
	if (file_exists($path)) {
		unlink($path);
		return true;
	} else {
		return false;
	}
}

//二维数组根据某个元素去重复
function second_array_unique_bykey($arr, $key) {
	$tmp_arr = array();
	foreach ($arr as $k => $v) {
		if (in_array($v[$key], $tmp_arr)) //搜索$v[$key]是否在$tmp_arr数组中存在，若存在返回true
		{
			unset($arr[$k]); //销毁一个变量  如果$tmp_arr中已存在相同的值就删除该值
		} else {
			$tmp_arr[$k] = $v[$key]; //将不同的值放在该数组中保存
		}
	}
	//ksort($arr); //ksort函数对数组进行排序(保留原键值key)  sort为不保留key值
	return $arr;
}

//   //二维数组根据某个元素去重复
//   function arrcomb($arr1,$arr2){
//    foreach($arr1 as $key1=>$value1){
//        foreach($arr2 as $key2=>$value2){
//            if($value1['id']==$value2['id']){
//                $arr3[]=$value2;
//            }
//        }
//    }
//    return $arr3;
// }

//二维数组根据某个元素去重复
function second_array_unique_bykey1($arr, $key) {
	$tmp_arr = array();
	$tmp_arr1 = array();
	foreach ($arr as $k => $v) {
		if (in_array($v[$key], $tmp_arr)) //搜索$v[$key]是否在$tmp_arr数组中存在，若存在返回true
		{
			$tmp_arr1[$k] = $v; //销毁一个变量  如果$tmp_arr中已存在相同的值就删除该值
		} else {
			$tmp_arr[$k] = $v[$key]; //将不同的值放在该数组中保存
		}
	}
	//ksort($arr); //ksort函数对数组进行排序(保留原键值key)  sort为不保留key值
	return $tmp_arr1;
}