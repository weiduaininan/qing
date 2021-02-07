<?php

namespace app\qingadmin\controller;

use think\facade\Db;
use think\facade\Request;


class Page extends  Base

{



    

    //列表

    public function index()

    {



     $pageData=Db::name('page')->paginate(10);



      return view('',[

         'pageData'=>$pageData,

        ]);



    }





    /*添加*/

    public function add(){



        if(request()->isPost()){

           $data=request()->post();

           $res=Db::name('page')->insert($data);



           if($res){

                return alert('操作成功','index',6);

            }else{

                return alert('操作失败','index',5);

            }

        }







       return view();

    }





    /*修改文章*/

    public function edit()

    {

        $id=$id=input('id');



        $pageData=Db::name('page')->find($id);



        if(request()->isPost()){



            $data=input('post.');

            

            



            $res=Db::name('page')->update($data);


            if($res){

                return alert('操作成功','index',6);

            }else{

                return alert('操作失败','index',5);

            }

           

        }



        return view('',[
            'pageData'=>$pageData,
        ]);

    }







    

    //文章删除

    public function delect(){

      $id=input('id');





      $res=Db::name('page')->delete($id);//删除主表信息

      if($res){

        return alert('操作成功','index',6);

        }else{

            return alert('操作失败','index',5);

        }

    }

    

}

