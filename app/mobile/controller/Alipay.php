<?php
namespace app\mobile\controller;

use think\Controller;
use think\facade\Db;
use app\BaseController;

class Alipay extends  BaseController

{
        /**
     * 支付宝手机支付 授课专用2020
     *   @order 付款信息
     *   @bodys 付款内容
     **/
    public function index(){

        require_once (root_path().'/extend/alipaywap/wappay/service/AlipayTradeService.php');
        require_once (root_path().'/extend/alipaywap/wappay/buildermodel/AlipayTradeWapPayContentBuilder.php');
        require_once (root_path().'/extend/alipaywap/config.php');


        //商户订单号，商户网站订单系统中唯一订单号，必填
        $out_trade_no = time();

        //订单名称，必填
        $subject = '支付订单';

        //付款金额，必填
        $total_amount = 0.01;

        //商品描述，可空
        $body = '';

        //超时时间
        $timeout_express="1m";

        $payRequestBuilder = new \AlipayTradeWapPayContentBuilder();
        $payRequestBuilder->setBody($body);
        $payRequestBuilder->setSubject($subject);
        $payRequestBuilder->setOutTradeNo($out_trade_no);
        $payRequestBuilder->setTotalAmount($total_amount);
        $payRequestBuilder->setTimeExpress($timeout_express);

        $payResponse = new \AlipayTradeService($config);
        $result=$payResponse->wapPay($payRequestBuilder,$config['return_url'],$config['notify_url']);

        return ;

    }

    //异步通知地址
    public function notify_url(){
        require_once (root_path().'/extend/alipaywap/config.php');
        require_once (root_path().'/extend/wappay/service/AlipayTradeService.php');

        $arr=$_POST;
        $alipaySevice = new \AlipayTradeService($config); 
        $alipaySevice->writeLog(var_export($_POST,true));
        $result = $alipaySevice->check($arr);
        if($result) {
            //处理订单逻辑
            echo 'success';
        }
    }

    //同步跳转
    public function return(){
        require_once (root_path().'/extend/alipaywap/config.php');
        require_once (root_path().'/extend/alipaywap/wappay/service/AlipayTradeService.php');
        $arr=$_GET;
        $alipaySevice = new \AlipayTradeService($config); 
        $result = $alipaySevice->check($arr);
        if($result) {
            echo 'success';
        }
    }

}
