<?php

namespace app\qingadmin\controller;

use think\facade\Db;
use think\facade\Request;



class Coupons extends  Base

{
    

    //优惠券

    public function index()

    {

      $couponsData=Db::name('coupons')->order('id desc')->paginate(15);

      return view('',[

         'couponsData'=>$couponsData,

        ]);



    }



    /*添加*/

    public function add(){

       if(request()->isPost()){

         $data=request()->post();

         //时间戳插件 添加入库的是需要把2020-05-20 12：12：12 转成时间戳入库 strtotime
         //编辑的时候，把时间戳转成时间格式 2020-05-20 12：12：12

         if(!empty($data['time1'])){
            $data['time1']=strtotime($data['time1']);
        }else{
            $data['time1']=time();//当前时间戳
        }

        if(!empty($data['time2'])){
            $data['time2']=strtotime($data['time2']);
        }else{
            $data['time2']=time();//当前时间戳
        }

         $res=Db::name('coupons')->insert($data);

         if($res){

            return alert('操作成功','index',6);

         }else{

            return alert('操作失败','index',5);

         }

       }

       return view();

    }



    /*修改*/

    public function edit(){

       //先取出填充的数据

       $id=Request::instance()->param('id');

       $couponsData=Db::name('coupons')->find($id);       

       

       return view('',[

         'couponsData'=>$couponsData,



        ]);



    }





    public function update(){

      //处理post过来的数据

       if(request()->isPost()){

         $data=request()->post();

         if(!empty($data['time1'])){
            $data['time1']=strtotime($data['time1']);
        }else{
            $data['time1']=time();//
        }

        if(!empty($data['time2'])){
            $data['time2']=strtotime($data['time2']);
        }else{
            $data['time2']=time();//
        }

         $res=Db::name('coupons')->update($data);

         if($res){

            return alert('操作成功','index',6);

         }else{

            return alert('操作失败','index',5);

         }

       }

    }

    

    

}

