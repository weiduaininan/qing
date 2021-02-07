<?php

namespace app\qingadmin\controller;

use think\Controller;

use think\facade\Request;
use think\facade\Db;
class Message extends  Base

{

    /**

     * @return 留言管理

     */

    

    //单页面列表

    public function index()

    {

      $messageData=Db::name('message')->paginate(10);

      return view('',[

         'messageData'=>$messageData,



        ]);



    }





    public function del(){

       $id=input('get.id');

       if($id){

          $res=Db::name('message')->where('id',$id)->delete();

          if($res){

            return alert('删除成功','index',6,3);

          }else{

            return alert('删除失败','index',5,3);

          }

       }

    }



    public function edit(){

       $id=Request::instance()->param('id');

       $messageData=Db::name('message')->where('id',$id)->find();

       return view('',[

         'messageData'=>$messageData,

        ]);

    }

    

    

}

