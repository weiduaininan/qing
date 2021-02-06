<?php
namespace app\qingadmin\controller;

use app\BaseController;
use think\facade\Db;

class Number extends Base
{

   

    //列表
    public function index()
    {   
        

        return view();
    }

    public function getOrder(){
        for ($i=0; $i <12; $i++) {
            if($i==0){
                $time12=strtotime("-".$i.' month');
                $date12=date('Ym',strtotime("-".$i.' month')); 
            }
            if($i==1){
                $time11=strtotime("-".$i.' month');
                $date11=date('Ym',strtotime("-".$i.' month')); 
            }
            if($i==2){
                $time10=strtotime("-".$i.' month');
                $date10=date('Ym',strtotime("-".$i.' month')); 
            }
            if($i==3){
                $time9=strtotime("-".$i.' month');
                $date9=date('Ym',strtotime("-".$i.' month')); 
            }
            if($i==4){
                $time8=strtotime("-".$i.' month');
                $date8=date('Ym',strtotime("-".$i.' month')); 
            }
            if($i==5){
                $time7=strtotime("-".$i.' month');
                $date7=date('Ym',strtotime("-".$i.' month')); 
            }
            if($i==6){
                $time6=strtotime("-".$i.' month');
                $date6=date('Ym',strtotime("-".$i.' month')); 
            }
            if($i==7){
                $time5=strtotime("-".$i.' month');
                $date5=date('Ym',strtotime("-".$i.' month')); 
            }
            if($i==8){
                $time4=strtotime("-".$i.' month');
                $date4=date('Ym',strtotime("-".$i.' month')); 
            }
            if($i==9){
                $time3=strtotime("-".$i.' month');
                $date3=date('Ym',strtotime("-".$i.' month')); 
            }
            if($i==10){
                $time2=strtotime("-".$i.' month');
                $date2=date('Ym',strtotime("-".$i.' month')); 
            }
            if($i==11){
                $time1=strtotime("-".$i.' month');
                $date1=date('Ym',strtotime("-".$i.' month')); 
            }
            
        }
        
        $where[]=['status','<>',0];
        $number1=Db::name('order')->whereBetween('time',[$time1,$time2])->where($where)->sum('total_price');
        $number2=Db::name('order')->whereBetween('time',[$time2,$time3])->where($where)->sum('total_price');
        $number3=Db::name('order')->whereBetween('time',[$time3,$time4])->where($where)->sum('total_price');
        $number4=Db::name('order')->whereBetween('time',[$time4,$time5])->where($where)->sum('total_price');
        $number5=Db::name('order')->whereBetween('time',[$time5,$time6])->where($where)->sum('total_price');
        $number6=Db::name('order')->whereBetween('time',[$time6,$time7])->where($where)->sum('total_price');
        $number7=Db::name('order')->whereBetween('time',[$time7,$time8])->where($where)->sum('total_price');
        $number8=Db::name('order')->whereBetween('time',[$time8,$time9])->where($where)->sum('total_price');
        $number9=Db::name('order')->whereBetween('time',[$time9,$time10])->where($where)->sum('total_price');
        $number10=Db::name('order')->whereBetween('time',[$time10,$time11])->where($where)->sum('total_price');
        $number11=Db::name('order')->whereBetween('time',[$time11,$time12])->where($where)->sum('total_price');
     
        $number=[$number1,$number2,$number3,$number4,$number5,$number6,$number7,$number8,$number9,$number10,$number11,];
        $date=[$date1,$date2,$date3,$date4,$date5,$date6,$date7,$date8,$date9,$date10,$date11];

        return json(['number'=>$number,'date'=>$date]);
    }


 

   
}
