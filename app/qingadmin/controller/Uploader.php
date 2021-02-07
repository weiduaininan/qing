<?php

namespace app\qingadmin\controller;

use think\facade\Db;
use think\File;

class Uploader extends Base {
	/**
	 * 图片上传，ajax返回
	 */

	public function local_upload() {
		//10240单位是字节，10240是10kb，102400是100kb
		$file = request()->file('file');
		try {
			validate(['file' => [
				'filesize' => 102400000,
				'fileExt' => 'jpg,jpeg,png,bmp,gif',
				'fileMime' => 'image/jpeg,image/png,image/gif']])->check(['file' => $file]);

			$savename = \think\facade\Filesystem::putFile('upload', $file);
			$savename = str_replace('\\', '/', $savename);
			$savename = '/public/' . $savename;
			return json(['message' => $savename, 'code' => 1]);

		} catch (think\exception\ValidateException $e) {
			echo $e->getMessage();
		}

		//exit(json_encode($return));

		halt($savename);

	}

	//删除图片
	public function remove_img() {
		$path = input('post.cover'); //图片路径
		$res = delImg($path);
		if ($res) {
			return json(['info' => '删除成功', 'status' => 1]);
		} else {
			return json(['info' => '删除失败', 'status' => 0]);
		}

		// if(empty($path)){
		//     return json(['info'=>'没有图片','status'=>0]);
		// }

		// $path=app()->getRootPath().$path;
		// if(file_exists($path)){
		//     unlink($path);
		//     return json(['info'=>'删除成功','status'=>1]);
		// }else{
		//     return json(['info'=>'没有找到该图片','status'=>0]);
		// }

	}

	public function remove_goods_img() {
		$path = input('post.cover'); //图片路径
		$goods_id = input('goods_id');
		if ($goods_id != 0) {
			Db::name('goods_img')->where('goods_id', $goods_id)->where('image', $path)->delete();
		}
		$res = delImg($path);
		if ($res) {
			return json(['info' => '删除成功', 'status' => 1]);
		} else {
			return json(['info' => '删除失败', 'status' => 0]);
		}
	}

}