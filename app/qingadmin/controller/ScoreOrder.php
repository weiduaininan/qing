<?php

namespace app\qingadmin\controller;

use think\facade\Request;
use think\facade\Db;


class ScoreOrder extends  Base

{


    //列表

    public function index()

    {



     $searchkey=input('searchkey');



     if(input('searchkey')){

        $where[] = [

                     ['title', 'like', '%'.$searchkey.'%'],

                 ];

     }



     $scoreRecordeData=Db::name('score_record')->alias('a')->field('b.title,b.thumb,a.*')->join('score_goods b','b.id=a.goods_id')->order('id desc')->paginate(10);
     
      return view('',[

         'scoreRecordeData'=>$scoreRecordeData,

        ]);



    }



    /*修改文章*/

    public function edit()

    {

        $id=input('id');



        $scoreGoodsData=Db::name('score_record')->alias('a')->field('b.title,a.*')->where('a.id',$id)->join('score_goods b','b.id=a.goods_id')->find();



        if(request()->isPost()){



            $data=input('post.');

            $res=Db::name('score_record')->update($data);



            if($res){

                return alert('操作成功！','index',6,3);

            }else{

                return alert('操作失败！','index',5,3);

            }


        }



        return view('',[
            'scoreGoodsData'=>$scoreGoodsData
        ]);

    }






    //删除

    public function del(){

      $id=input('id');

      $res=Db::name('score_record')->delete($id);


      if($res){

        return alert('操作成功！','index',6,3);

        }else{

            return alert('操作失败！','index',5,3);

        }
    }


    public function status(){

        $id=input('id');
  
        $res=Db::name('score_record')->where('id',$id)->update(['status'=>1]);

        return redirect('index');
      }
    

}

