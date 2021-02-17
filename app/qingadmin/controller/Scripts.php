<?php
namespace app\qingadmin\controller;
use app\BaseController;
use think\facade\Log;

/**
 * 每日发送日志脚本
 */
class Scripts extends BaseController {
	public function __construct() {
		$cli = Request()->isCli();
		if (!$cli) {
			//echo "请在命令行下运行";
			//die;
		}
	}
	public function index() {
		sendMail('bj_liunan@126.com', '测试邮箱发送', '内容', '/Users/liunan/htdocs/qing/public/static/index/images/ad1.jpg', 'ad1.jpg');
		$status = sendMail('bj_liunan@126.com', '测试邮箱发送', '内容', '/Users/liunan/htdocs/qing/public/static/index/css/cart.css', 'cart.css');
		if ($status) {
			Log::write('邮件发送成功', 'mail');
		}
	}
}