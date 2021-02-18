<?php

namespace app\index\controller;
use think\facade\Db;
use weixinpay\database\WxPayUnifiedOrder;
use weixinpay\NativePay;
use weixinpay\WxPayConfig;
use weixinpay\WxPayResults;

class Weixinpay extends Base {

	///www.a.com/index/Weixinpay/index?out_trade_no=e56dded96b8887646e45bdf0451ebd62
	public function index() {
		$sessionUserData = $this->isLogin();
		$out_trade_no = input('get.out_trade_no');
		$orderData = Db::name('order')->where('user_id', $sessionUserData['id'])->where('out_trade_no', $out_trade_no)->find();

		if (empty($orderData) || $orderData['status'] != 0) {
			return alert('订单异常', '/', 5);
		}
		$notify = new NativePay();
		$input = new WxPayUnifiedOrder();
		$input->SetBody("支付订单");
		$input->SetAttach("test");
		$input->SetOut_trade_no($orderData['out_trade_no']);
		$input->SetTotal_fee($orderData['total_price'] * 100); //单位是分
		$input->SetTime_start(date("YmdHis"));
		$input->SetTime_expire(date("YmdHis", time() + 600));
		$input->SetGoods_tag("test");
		$input->SetNotify_url("http://a.x.cn/index/Weixinpay/notify");
		$input->SetTrade_type("NATIVE");
		$input->SetProduct_id("123456789");

		$result = $notify->GetPayUrl($input);
		$url2 = $result["code_url"];

		//生成二维码
		//二维码链接$url2
		//生成二维码的函数qrcode.php
		return view('', [
			'url2' => $url2, //二维码链接
			'orderData' => $orderData,
		]);

	}

	//微信支付回调
	public function notify() {
		//接收数据流
		$weixinData = file_get_contents("php://input");
		//数据流放在2.txt
		file_put_contents('2.txt', $weixinData, FILE_APPEND);

		// $weixinData="<xml><appid><![CDATA[wxff5b68b241a4fb11]]></appid>
		// <attach><![CDATA[test]]></attach>
		// <bank_type><![CDATA[OTHERS]]></bank_type>
		// <cash_fee><![CDATA[3]]></cash_fee>
		// <fee_type><![CDATA[CNY]]></fee_type>
		// <is_subscribe><![CDATA[Y]]></is_subscribe>
		// <mch_id><![CDATA[1522223331]]></mch_id>
		// <nonce_str><![CDATA[fx83fw4ujd0jgui2zc5tlajbimxfknqr]]></nonce_str>
		// <openid><![CDATA[oaq-51Xdeym7tCsyCrLTFi-1Nylk]]></openid>
		// <out_trade_no><![CDATA[23dedeed29cfc09b158b7e7bcd81a988]]></out_trade_no>
		// <result_code><![CDATA[SUCCESS]]></result_code>
		// <return_code><![CDATA[SUCCESS]]></return_code>
		// <sign><![CDATA[FC9FE181DED2103C60ECD2A806F6459FFE146D818FA4C7A5749EA49601EDB3EA]]></sign>
		// <time_end><![CDATA[20200820130058]]></time_end>
		// <total_fee>3</total_fee>
		// <trade_type><![CDATA[NATIVE]]></trade_type>
		// <transaction_id><![CDATA[4200000592202008206237058424]]></transaction_id>
		// </xml>";

		$config = new WxPayConfig();
		try {
			$resultObj = new WxPayResults();
			$weixinData = $resultObj->Init($config, $weixinData);
			echo '解析正确';
		} catch (\Exception $e) {
			$resultObj->setData('return_code', 'FAIL');
			$resultObj->setData('return_msg', $e->getMessage());
			return $resultObj->toXml();
		}
		//return_code此字段是通信标识，非交易标识，交易是否成功需要查看result_code来判断
		if ($weixinData['return_code'] === 'FAIL' || $weixinData['result_code'] !== 'SUCCESS') {
			$resultObj->setData('return_code', 'FAIL');
			$resultObj->setData('return_msg', 'error');
			return $resultObj->toXml();
		}
		if ($weixinData['result_code'] == 'SUCCESS') {
			$orderData = Db::name('order')->where('out_trade_no', $weixinData['out_trade_no'])->find();
			if (!empty($orderData) && $orderData['total_price'] * 100 == $weixinData['total_fee']) {
				Db::name('order')->where('out_trade_no', $weixinData['out_trade_no'])->update(['status' => 1, 'pay_time' => time()]);
			} else {
				$resultObj->setData('return_code', 'FAIL');
				$resultObj->setData('return_msg', 'error');
				return $resultObj->toXml();
			}
			//通知微信支付成功
			$resultObj->setData('return_code', 'SUCCESS');
			$resultObj->setData('return_msg', 'OK');
			return $resultObj->toXml();
		}
	}
}