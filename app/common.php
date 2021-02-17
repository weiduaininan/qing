<?php
use PHPMailer\PHPMailer\PHPMailer;
use think\facade\Db;
// 应用公共文件

/**

 * $msg 待提示的消息

 * $url 待跳转的链接

 * $icon 这里主要有两个，5和6，代表两种表情（哭和笑）

 * $time 弹出维持时间（单位秒）

 */

function alert($msg = '', $url = '', $icon = '', $time = 3) {

	$str = '<meta name="viewport" content="initial-scale=1, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black" />
    <meta http-equiv="Access-Control-Allow-Origin" content="*" />
    <meta http-equiv="pragma" content="no-cache" />
    <script type="text/javascript" src="/public/static/index/js/jquery-3.4.1.min.js"></script><script type="text/javascript" src="/public/static/index/layer/2.4/layer.js"></script>'; //加载jquery和layer

	$str .= '<script>$(function(){layer.msg("' . $msg . '",{icon:' . $icon . ',time:' . ($time * 1000) . '});setTimeout(function(){self.location.href="' . $url . '"},2000)});</script>'; //主要方法

	return $str;

}

//删除目录及文件，传入目录
function delFileByDir($dir) {
	$dh = opendir($dir);
	while ($file = readdir($dh)) {
		if ($file != "." && $file != "..") {

			$fullpath = $dir . "/" . $file;
			if (is_dir($fullpath)) {
				delFileByDir($fullpath);
			} else {
				if ($fullpath !== '.gitignore') {
					unlink($fullpath);
				}

			}
		}
	}
	closedir($dh);
}

//通过id获取用户信息
function getUserById($id) {
	$userData = Db::name('user')->find($id);
	if (empty($userData)) {
		return '其他途径';
	}
	return $userData['username'];
}

//删除图片,传递过来图片路径
function delImg($path) {
	if (empty($path)) {
		return false;
	}

	$path = app()->getRootPath() . $path;
	if (file_exists($path)) {
		unlink($path);
		return true;
	} else {
		return false;
	}
}

//二维数组根据某个元素去重复
function second_array_unique_bykey($arr, $key) {
	$tmp_arr = array();
	foreach ($arr as $k => $v) {
		if (in_array($v[$key], $tmp_arr)) //搜索$v[$key]是否在$tmp_arr数组中存在，若存在返回true
		{
			unset($arr[$k]); //销毁一个变量  如果$tmp_arr中已存在相同的值就删除该值
		} else {
			$tmp_arr[$k] = $v[$key]; //将不同的值放在该数组中保存
		}
	}
	//ksort($arr); //ksort函数对数组进行排序(保留原键值key)  sort为不保留key值
	return $arr;
}

//   //二维数组根据某个元素去重复
//   function arrcomb($arr1,$arr2){
//    foreach($arr1 as $key1=>$value1){
//        foreach($arr2 as $key2=>$value2){
//            if($value1['id']==$value2['id']){
//                $arr3[]=$value2;
//            }
//        }
//    }
//    return $arr3;
// }

//二维数组根据某个元素去重复
function second_array_unique_bykey1($arr, $key) {
	$tmp_arr = array();
	$tmp_arr1 = array();
	foreach ($arr as $k => $v) {
		if (in_array($v[$key], $tmp_arr)) //搜索$v[$key]是否在$tmp_arr数组中存在，若存在返回true
		{
			$tmp_arr1[$k] = $v; //销毁一个变量  如果$tmp_arr中已存在相同的值就删除该值
		} else {
			$tmp_arr[$k] = $v[$key]; //将不同的值放在该数组中保存
		}
	}
	//ksort($arr); //ksort函数对数组进行排序(保留原键值key)  sort为不保留key值
	return $tmp_arr1;
}

// 应用公共文件

/*发送邮件方法
 *@param $to：接收者 $title：标题 $content：邮件内容
 *@return bool true:发送成功 false:发送失败
 */

function sendMail($to, $title, $content, $addr = '', $filename = '') {

	//实例化PHPMailer核心类
	$mail = new PHPMailer();

	//是否启用smtp的debug进行调试 开发环境建议开启 生产环境注释掉即可 默认关闭debug调试模式
	$mail->SMTPDebug = 1;

	//使用smtp鉴权方式发送邮件
	$mail->isSMTP();

	//smtp需要鉴权 这个必须是true
	$mail->SMTPAuth = true;

	//链接qq域名邮箱的服务器地址
	$mail->Host = 'smtp.qq.com';

	//设置使用ssl加密方式登录鉴权
	$mail->SMTPSecure = 'ssl';

	//设置ssl连接smtp服务器的远程服务器端口号，以前的默认是25，但是现在新的好像已经不可用了 可选465或587
	$mail->Port = 465;

	//设置smtp的helo消息头 这个可有可无 内容任意
	// $mail->Helo = 'Hello smtp.qq.com Server';

	//设置发件人的主机域 可有可无 默认为localhost 内容任意，建议使用你的域名
	$mail->Hostname = '';

	//设置发送的邮件的编码 可选GB2312 我喜欢utf-8 据说utf8在某些客户端收信下会乱码
	$mail->CharSet = 'utf-8';

	//设置发件人姓名（昵称） 任意内容，显示在收件人邮件的发件人邮箱地址前的发件人姓名
	$mail->FromName = '小刘同学';

	//smtp登录的账号 这里填入字符串格式的qq号即可
	$mail->Username = '396034074@qq.com';

	//smtp登录的密码 使用生成的授权码（就刚才叫你保存的最新的授权码）
	$mail->Password = 'bqtcjtbquwmrcabb';

	//设置发件人邮箱地址 这里填入上述提到的“发件人邮箱”
	$mail->From = '396034074@qq.com';

	//邮件正文是否为html编码 注意此处是一个方法 不再是属性 true或false
	$mail->isHTML(true);

	//设置收件人邮箱地址 该方法有两个参数 第一个参数为收件人邮箱地址 第二参数为给该地址设置的昵称 不同的邮箱系统会自动进行处理变动 这里第二个参数的意义不大
	$mail->addAddress($to, '');

	//添加多个收件人 则多次调用方法即可
	// $mail->addAddress('xxx@163.com','');

	//添加该邮件的主题
	$mail->Subject = $title;

	//添加邮件正文 上方将isHTML设置成了true，则可以是完整的html字符串 如：使用file_get_contents函数读取本地的html文件
	$mail->Body = $content;

	//为该邮件添加附件 该方法也有两个参数 第一个参数为附件存放的目录（相对目录、或绝对目录均可） 第二参数为在邮件附件中该附件的名称
	//$mail->addAttachment('./d.jpg','mm.jpg');
	$mail->addAttachment($addr, $filename);
	//同样该方法可以多次调用 上传多个附件
	// $mail->addAttachment('./Jlib-1.1.0.js','Jlib.js');
	//halt($mail);
	$status = $mail->send();

	//简单的判断与提示信息
	if ($status) {
		return true;
	} else {
		return false;
	}
}
