<?php
namespace app\qingadmin\controller;

use think\facade\Db;

class Order extends Base
{

	public function index(){	

		$start_time0=$end_time0='';
		$start_time0=input('get.start_time');
		$end_time0=input('get.end_time');
		
		
		//2020-01-02 时间戳
		$start_time=strtotime($start_time0);
		$end_time=strtotime($end_time0);
		
		$where=[];


        if($start_time && $end_time){
			$where[] = [
				['time','between',[$start_time, $end_time]]
			];
		}elseif($start_time){
			$where[] = [
				['time','>',$start_time]
			];
		}elseif($end_time){
			$where[] = [
				['time','<',$end_time]
			];
		}


		//order order_goods goods

		$orderData=Db::name('order')->where($where)->order('id desc')->paginate(['list_rows'=>10,'query'=>request()->param()]);
		
		//分页
		$page = $orderData->render();

		$orderData1=$orderData->items();

		foreach($orderData1 as $k=>$v){
			$orderData1[$k]['goods']=Db::name('order_goods')->alias('a')->field('a.*,b.goods_name,b.goods_thumb')->join('goods b','a.goods_id=b.goods_id')->where('a.order_id',$v['id'])->select();
		}
		
		
		return view('',[
			'orderData1'=>$orderData1,
			'page'=>$page,
			'start_time'=>$start_time0,
			'end_time'=>$end_time0
		]);


	}





	//订单详情
	public function edit(){


		$id=input('id');



		//订单详情
		$orderData=Db::name('order')->find($id);

		//收货地址
		$addressData=Db::name('address')->find($orderData['address_id']);

		//会员信息
		$userData=Db::name('user')->field('mobile')->find($orderData['user_id']);


		//商品信息
		$orderGoods=Db::name('order_goods')->alias('a')->field('a.*,b.goods_name,b.goods_thumb')->join('goods b','a.goods_id=b.goods_id')->where('a.order_id',$id)->select();


		//获取所有快递公司信息

		$expressData=Db::name('express')->field('id,name')->select();

		if(request()->isPost()){
			$data=input('post.');
			$data['status']=2;
			$res=Db::name('order')->update($data);
			if($res){
				return alert('操作成功','index',6);
			}else{
				return alert('操作失败','index',5);
			}
		}


		return view('',[

            'orderData'=>$orderData,
            'order_id'=>$id,
            'addressData'=>$addressData,
			'orderGoods'=>$orderGoods,
			'expressData'=>$expressData,
			'userData'=>$userData
        ]);







	}
   
}
