<?php
declare (strict_types = 1);

namespace app\command;

use think\console\Command;
use think\console\Input;
use think\console\Output;

class Scripts extends Command {
	protected function configure() {
		// 指令配置
		$this->setName('send_mail')
			->setDescription('the send_mail command');
	}

	protected function execute(Input $input, Output $output) {
		sendMail('bj_liunan@126.com', '测试邮箱发送', '内容', '/Users/liunan/htdocs/qing/public/static/index/images/ad1.jpg', 'ad1.jpg');
		sendMail('bj_liunan@126.com', '测试邮箱发送', '内容', '/Users/liunan/htdocs/qing/public/static/index/css/cart.css', 'cart.css');
	}
}
