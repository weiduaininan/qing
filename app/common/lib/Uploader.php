<?php

namespace app\common\lib;

use think\facade\Db;
use think\facade\Config;
use think\facade\Env;
use think\File;
use Qiniu\Auth;
use Qiniu\Storage\UploadManager;
class Uploader 
{
    /**
     * 图片上传，ajax返回
     * @author 
     */
    public function upload(){

        $file = request()->file('file');
        
        //$info = $file->move(Env::get('root_path') . 'public' . DIRECTORY_SEPARATOR . 'upload'. DIRECTORY_SEPARATOR);
        $savename = '\public\\'.\think\facade\Filesystem::putFile( 'upload', $file);
        //获取当前的域名
        //$domian =db('config')->field('value')->where('id',9)->find();

        if($savename){
            $return['path'] =$savename;
        }else{
            // 上传失败获取错误信息
            $return['error']   = 1;
            $return['success'] = 0;
            $return['message'] = '上传出错'.$file->getError();
        }
        exit(json_encode($return));
    }
   

  

    //公共删除图片
    public function remove_goods_pic(){
        $path = input("request.cover",0);
        delFile($path);//实际删除图片
        return json(['status' => 1, 'info' => '删除成功']);
    }

   


    public function imgcut(){
        //获取文件对象
        $file = $this->request->file('file');
        //验证并上传
        $info = $file->validate(['size'=>'5242880','ext'=>'jpg,gif,png'])
            ->move(ROOT_PATH . 'public' . DS . 'upload'. DS .'pic');
        //判断是否成功
        if($info){
            $data['src'] = DS.'public' . DS . 'upload'. DS .'pic'.DS.$info->getSaveName();
            $this->result($data,0,'上传成功');
        }else{
            $this->ajaxmsg('',200,$file->getError());
        }
    }

   

}