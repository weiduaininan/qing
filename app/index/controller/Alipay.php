<?php

namespace app\index\controller;
use think\facade\Db;

class Alipay extends Base{

    ///www.a.com/index/Alipay/index?out_trade_no=e56dded96b8887646e45bdf0451ebd62
    public function index(){

        $sessionUserData=$this->isLogin();


        $out_trade_no=input('get.out_trade_no');
        $orderData=Db::name('order')->where('user_id',$sessionUserData['id'])->where('out_trade_no',$out_trade_no)->find();

        if(empty($orderData) || $orderData['status']!=0){
            return alert('订单异常','/',5);
        }

        require_once (root_path().'/extend/alipay/config.php');
        require_once (root_path().'/extend/alipay/pagepay/service/AlipayTradeService.php');
        require_once (root_path().'/extend/alipay/pagepay/buildermodel/AlipayTradePagePayContentBuilder.php');


        //订单名称，必填
        $subject = '订单支付';

        //付款金额，必填
        $total_amount = $orderData['total_price'];

        //商品描述，可空
        $body ='';

        //构造参数
        $payRequestBuilder = new \AlipayTradePagePayContentBuilder();
        $payRequestBuilder->setBody($body);
        $payRequestBuilder->setSubject($subject);
        $payRequestBuilder->setTotalAmount($total_amount);
        $payRequestBuilder->setOutTradeNo($out_trade_no);

        $aop = new \AlipayTradeService($config);

        /**
         * pagePay 电脑网站支付请求
         * @param $builder 业务参数，使用buildmodel中的对象生成。
         * @param $return_url 同步跳转地址，公网可以访问
         * @param $notify_url 异步通知地址，公网可以访问
         * @return $response 支付宝返回的信息
        */
        $response = $aop->pagePay($payRequestBuilder,$config['return_url'],$config['notify_url']);

        //输出表单
        var_dump($response);


       
    }


    //支付宝支付回调
    public function notify() {
       
        require_once (root_path().'/extend/alipay/config.php');
        require_once (root_path().'/extend/alipay/pagepay/service/AlipayTradeService.php');

        $arr=$_POST;
        $alipaySevice = new \AlipayTradeService($config); 
        $alipaySevice->writeLog(var_export($_POST,true));
        $result = $alipaySevice->check($arr);

        if($result) {//验证成功

            //请在这里加上商户的业务逻辑程序代
        
            
            //——请根据您的业务逻辑来编写程序（以下代码仅作参考）——
            
            //获取支付宝的通知返回参数，可参考技术文档中服务器异步通知参数列表
            
            //商户订单号
        
            $out_trade_no = $_POST['out_trade_no'];
        
            //支付宝交易号
        
            $trade_no = $_POST['trade_no'];
        
            //交易状态
            $trade_status = $_POST['trade_status'];

            //交易金额
            $total_amount = $_POST['total_amount'];
            
            //获取订单信息
            $orderData=Db::name('order')->where('out_trade_no',$out_trade_no)->find();
        
            if($_POST['trade_status'] == 'TRADE_FINISHED') {
        
                //判断该笔订单是否在商户网站中已经做过处理
                    //如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
                    //请务必判断请求时的total_amount与通知时获取的total_fee为一致的
                    //如果有做过处理，不执行商户的业务程序
                        
                //注意：
                //退款日期超过可退款期限后（如三个月可退款），支付宝系统发送该交易状态通知
            }
            else if ($_POST['trade_status'] == 'TRADE_SUCCESS') {
               if(!empty($orderData) && $orderData['total_price']==$total_amount){
                    Db::name('order')->where('out_trade_no',$out_trade_no)->update(['status'=>1,'pay_time'=>time(),'pay_method'=>2]);
               }
            }
            //——请根据您的业务逻辑来编写程序（以上代码仅作参考）——
            echo "success";	//请不要修改或删除
        }else {
            //验证失败
            echo "fail";
        
        }

        if($weixinData['result_code'] == 'SUCCESS'){
            
           $orderData=Db::name('order')->where('out_trade_no',$weixinData['out_trade_no'])->find();
           if(!empty($orderData) && $orderData['total_price']*100==$weixinData['total_fee']){
                Db::name('order')->where('out_trade_no',$weixinData['out_trade_no'])->update(['status'=>1,'pay_time'=>time()]);
           }else{
                $resultObj->setData('return_code', 'FAIL');
                $resultObj->setData('return_msg', 'error');
                return $resultObj->toXml();
           }
        }



        

    }

    // http://a.cn/index/alipay/return?charset=UTF-8&out_trade_no=9231edf55291b4218e1d6faae08e2d3c&method=alipay.trade.page.pay.return&total_amount=1.02&sign=dctis1NSuxU6FdwANL5%2FWaOae9SEpgdQZfBeU5zXukAN6QLY1OTqCIwe%2BBNegw78a%2F6qJvXRW8jxA%2Bl%2F0%2F3JZfFYRUOmzs0PhcJ4LULVRwPxGHes%2BnO6oBN0pI5hKe2wAEETxRwVxeId61DXNssuNrwu47qwx59jykA35m1hFOXsFF9CSk3lQiHDb2yjjZ3YY9sJ5Ybaw2dKM1%2Fkz1KlQP5jiyOQ6E7joKGZeb%2FWbjIZHXou9YwHrM73QDvCvaknC2LDMU8dnuBoo4WHQL6pNZffUDPfdR2TcaBEhStKJk8Nn4AkrijY8md4zTb2654saDR9JWK4B1SrXbdWItqgyw%3D%3D&trade_no=2020082222001460301429275645&auth_app_id=2021001175632458&version=1.0&app_id=2021001175632458&sign_type=RSA2&seller_id=2088831965609474&timestamp=2020-08-22+18%3A37%3A15

    //同步跳转
    public function return(){
     
        require_once (root_path().'/extend/alipay/config.php');
        require_once (root_path().'/extend/alipay/pagepay/service/AlipayTradeService.php');

        $arr=$_GET;
        $alipaySevice = new \AlipayTradeService($config); 
        $result = $alipaySevice->check($arr);

        $ispay=0;//1代表支付成功 0支付失败
        $out_trade_no='';

        if($result) {//验证成功
            /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            //请在这里加上商户的业务逻辑程序代码
            
            //——请根据您的业务逻辑来编写程序（以下代码仅作参考）——
            //获取支付宝的通知返回参数，可参考技术文档中页面跳转同步通知参数列表
        
            //商户订单号
            $out_trade_no = htmlspecialchars($_GET['out_trade_no']);
            
            $orderData=Db::name('order')->where('out_trade_no',$out_trade_no)->find();
            
            if($orderData['status']==1 && $orderData['pay_method']==2){
                $ispay=1;
            }
        
            //支付宝交易号
            //$trade_no = htmlspecialchars($_GET['trade_no']);
                
    
        }
        
        return view('',[
            'ispay'=>$ispay,
            'out_trade_no'=>$out_trade_no
        ]);
    }

    
}