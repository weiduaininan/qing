<?php
namespace app\mobile\controller;

use think\Controller;
use think\facade\Db;
use app\common\model\Category as CategoryModel;


class Lists extends  Base

{

        //商品分类
        public function index()

        {   
            
            $cate_id=input('cate_id');
            
            $categoryData=$categoryTopData=Db::name('category')->field('id,cate_name,thumb')->where('parent_id',0)->where('status',1)->select()->toArray();
            foreach($categoryData as $k=>$v){
                $categoryData[$k]['children']=Db::name('category')->field('id,cate_name,thumb')->where('parent_id',$v['id'])->where('status',1)->select()->toArray();
            }

        

            return view('',[
                'cate_id'=>$cate_id,
                'categoryData'=>$categoryData,
                'categoryTopData'=>$categoryTopData
            ]);

        }

    
        public function search(){
            $keywords=input('keywords');
            $keywords=trim($keywords);//空格去掉

             //排序
             $orderArr=[];
             $order=input('order');
             if(empty($order)){
                 $order=1;
             }
             if($order==1){
                 $orderArr=['goods_id'=>'desc'];
             }
             if($order==2){
                 $orderArr=['selnumber'=>'desc'];
             }
             if($order==3){
                 $orderArr=['goods_price'=>'desc'];
             }
             if($order==4){
                 $orderArr=['goods_price'=>'asc'];
             }

             $goodsData=Db::name('goods')->where('goods_name','like','%'.$keywords.'%')->order($orderArr)->paginate(['list_rows'=> 1,'query'=>request()->param()]);

             return view('',[
                'keywords'=>$keywords,
                'goodsData'=>$goodsData,
                'order'=>$order
            ]);
        }

   





}

