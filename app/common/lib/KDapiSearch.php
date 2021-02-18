<?php
namespace app\common\lib;
/**
 *
 */
class KDapiSearch {

/**
 * Json方式 查询订单物流轨迹
 */
	function getOrderTracesByJson($code, $post_code) {
//电商ID
		$EBusinessID = '1321786'; //请到快递鸟官网申请http://kdniao.com/reg
		//电商加密私钥，快递鸟提供，注意保管，不要泄漏
		$AppKey = '0cae0728-a610-42e3-98d2-6e954e0a771c'; //请到快递鸟官网申请http://kdniao.com/reg
		//请求url
		$ReqURL = 'https://api.kdniao.com/Ebusiness/EbusinessOrderHandle.aspx';
		//$ReqURL = 'http://sandboxapi.kdniao.com:8080/kdniaosandbox/gateway/exterfaceInvoke.json';

		$requestData = "{'OrderCode':'','ShipperCode':'" . $code . "','LogisticCode':'" . $post_code . "'}";

		$datas = array(
			'EBusinessID' => $EBusinessID,
			'RequestType' => '1002',
			'RequestData' => urlencode($requestData),
			'DataType' => '2',
		);
		$datas['DataSign'] = $this->encrypt($requestData, $AppKey);
		$result = $this->sendPost($ReqURL, $datas);

		//根据公司业务处理返回的信息......

		return $result;
	}

/**
 *  post提交数据
 * @param  string $url 请求Url
 * @param  array $datas 提交的数据
 * @return url响应返回的html
 */
	function sendPost($url, $datas) {
		$postdata = http_build_query($datas);
		$options = array(
			'http' => array(
				'method' => 'POST',
				'header' => 'Content-type:application/x-www-form-urlencoded',
				'content' => $postdata,
				'timeout' => 15 * 60, // 超时时间（单位:s）
			),
		);
		$context = stream_context_create($options);
		$result = file_get_contents($url, false, $context);
		return $result;
	}

/**
 * 电商Sign签名生成
 * @param data 内容
 * @param appkey Appkey
 * @return DataSign签名
 */
	function encrypt($data, $appkey) {
		return urlencode(base64_encode(md5($data . $appkey)));
	}
}