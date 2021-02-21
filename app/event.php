<?php
// 事件定义文件
return [
	'bind' => [
		'UserLogin' => 'app\event\User',
	],

	'listen' => [
		'AppInit' => [],
		'HttpRun' => [],
		'HttpEnd' => [],
		'LogLevel' => [],
		'LogWrite' => [],
		'UserLogin' => ['app\listener\User'],
	],

	'subscribe' => [
	],
];
