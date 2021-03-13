<?php
// +----------------------------------------------------------------------
// | 控制台配置
// +----------------------------------------------------------------------
return [
	// 指令定义
	'commands' => [
		'send_mail' => 'app\command\Scripts',
		'comment' => 'app\command\Comment',
		'consumer' => 'app\command\Consumer',
		'register' => 'app\command\Register',
	],
];
