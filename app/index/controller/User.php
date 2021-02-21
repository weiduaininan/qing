<?php

namespace app\index\controller;

use app\common\lib\Uploader;
use app\common\model\User as UserModel;
use app\common\validate\User as UserValidate;
use think\Controller;
use think\exception\ValidateException;
use think\facade\Db;
use think\facade\Event;
use think\facade\view;

class User extends Base {
	//会员首页
	public function index() {
		$sessionUserData = $this->isLogin();
		$collectCount = Db::name('collect')->where('user_id', $sessionUserData['id'])->count();
		$myorder = Db::name('order')->where('user_id', $sessionUserData['id'])->count();
		$myorder0 = Db::name('order')->where('user_id', $sessionUserData['id'])->where('status', '0')->count();
		$myorder4 = Db::name('order')->where('user_id', $sessionUserData['id'])->where('status', '4')->count();
		return view('', [
			'left_menu' => 0,
			'collectCount' => $collectCount,
			'myorder' => $myorder,
			'myorder0' => $myorder0,
			'myorder4' => $myorder4,
		]);
	}

	//会员登录
	public function login() {
		$sessionUserData = session('sessionUserData');
		if (!empty($sessionUserData)) {
			return redirect('index');
		}

		if (request()->isPost()) {
			$data = input('post.');

			try {
				validate(UserValidate::class)
					->scene('login')
					->check($data);
			} catch (ValidateException $e) {
				// 验证失败 输出错误信息
				return alert($e->getError(), 'login', 5);
			}

			//验证码用户名
			//$userData = UserModel::where('mobile', $data['mobile'])->find();
			//$userData = Db::name('user')->where('mobile', $data['mobile'])->find();
			//使用服务+容器的形式
			$user_m = app('user_m');
			$userData = $user_m->where('mobile', $data['mobile'])->find();
			if (!$userData) {
				return alert('手机号不存在或者错误', 'login', 5);
			}

			//如果管理员有状态，status=1合法  0禁止
			if ($userData['status'] != 1) {
				return alert('您的账号被禁止登录', 'login', 5);
			}

			//密码校验
			if ($userData['password'] != $this->password_salt($data['password'])) {
				return alert('密码错误', 'login', 5);
			}
			Db::name('user')->where('id', $userData['id'])->update(['last_login_time' => time(), 'last_login_ip' => $_SERVER['REMOTE_ADDR']]);
			session('sessionUserData', $userData);
			//使用服务容器+事件+监听 用户的登录次数
			// halt($userData['id']);
			$userInfo = $user_m->getUserInfo($userData['id']);
			//echo '控制器输出：' . json_encode($userInfo, JSON_UNESCAPED_UNICODE) . '<br />';
			//Event::listen('UserLogin', 'app\listener\User');
			Event::trigger('UserLogin');

			return alert('登录成功', 'index', 6);

		} else {
			return view();
		}

	}

	public function wechat() {
		$appid = 'wx868f988d79a4f25b';
		$REDIRECT_URI = urlEncode('http://qing.cn/index/user/weixin.html');
		$url = 'https://open.weixin.qq.com/connect/qrconnect?appid=' . $appid . '&redirect_uri=' . $REDIRECT_URI . '&response_type=code&scope=snsapi_login&state=STATE#wechat_redirect';
		return redirect($url);
	}

	public function weixin() {

		$code = input('get.code');
		$appid = 'wx868f988d79a4f25b';
		$appsecret = 'a2b426f2882b6a1398b8312cc1de037b';
		$url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=' . $appid . '&secret=' . $appsecret . '&code=' . $code . '&grant_type=authorization_code';

		//$res=file_get_contents($url);
		// {"access_token":"35_HINwxRbVGwM3R4PbOyoINNVTzH5S8tNqsATXDYGh9NUQWj7Z7lbYjOzVb_tl80JrdlKfihY0e4CtFpjobLHW14P_YRWOWKMnx5pIB8BhOqY","expires_in":7200,"refresh_token":"35_uIxPmh4MHNn-2r9-GBPn2ZRt9H0coLrwO3R-sefGEoB_AcisodzL_NZAuTH4ABCJty0pOUV41dAWJqzi5UUAxoxwsz18T3T6d_mCjsFU24o","openid":"oP19r1PgUR1_k4Efu8tSoJBdRn78","scope":"snsapi_login","unionid":"oO0Bhv0llJvwqSMmA0OwS8q-NBn4"}

		//json转数组
		$res = json_decode(file_get_contents($url), true);

		// [
		//     "access_token" => "35_e50x27-4SDubvqqlS-PrajOIbKtN7sxV6od5x3yF_mjhbgijjbwcT-ZZcklLk8KoehCqLt0OXWqW8wetPKLk-JLkNtiki5OtS1Me9QNWkJM"
		//     "expires_in" => 7200
		//     "refresh_token" => "35_nFXGDJ1JTO7z9rHG0Ziec-NRnoWvzAPLaLg5DhOetngglknNZtjfDoU6ydd3FlcgOOS9D9o8XBPLa4ibsF9W-3KXQYLsNMaxZD1MH2TZK3g"
		//     "openid" => "oP19r1PgUR1_k4Efu8tSoJBdRn78"
		//     "scope" => "snsapi_login"
		//     "unionid" => "oO0Bhv0llJvwqSMmA0OwS8q-NBn4"
		// ]
		$access_token = $res['access_token'];
		$openid = $res['openid'];
		$url = 'https://api.weixin.qq.com/sns/userinfo?access_token=' . $access_token . '&openid=' . $openid;

		//$userInfo=file_get_contents($url);//json格式
		// {"openid":"oP19r1PgUR1_k4Efu8tSoJBdRn78","nickname":"阿芹","sex":2,"language":"zh_CN","city":"Texas","province":"Shandong","country":"CN","headimgurl":"http:\/\/thirdwx.qlogo.cn\/mmopen\/vi_32\/Q0j4TwGTfTIrKg5HHPu3UA5bria41g6a8W76DibHwK2lwwAteR4KO8nEH1cpBhjcbD7b61SGOHuicMLrUBvTHNmKg\/132","privilege":[],"unionid":"oO0Bhv0llJvwqSMmA0OwS8q-NBn4"}

		//json转数组
		$userInfo = json_decode(file_get_contents($url), true);
		// $userInfo=[
		//     "openid" => "oP19r1PgUR1_k4Efu8tSoJBdRn78",
		//     "nickname" => "阿芹",
		//     "sex" => 2,
		//     "language" => "zh_CN",
		//     "city" => "Texas",
		//     "province" => "Shandong",
		//     "country" => "CN",
		//     "headimgurl" => "http://thirdwx.qlogo.cn/mmopen/vi_32/Q0j4TwGTfTIrKg5HHPu3UA5bria41g6a8W76DibHwK2lwwAteR4KO8nEH1cpBhjcbD7b61SGOHuicMLrUBvTHNmKg/132",
		//     "privilege" => [],
		//     "unionid" => "oO0Bhv0llJvwqSMmA0OwS8q-NBn4",
		// ];

		//halt($userInfo);

		//数据库查找unionid=oO0Bhv0llJvwqSMmA0OwS8q这个用户，在数据库中是否存在？
		//存在：就更新下最后一次登录时间、存缓存，跳转到用户主页
		//不存在，第一次微信扫码登录，跳转到绑定手机号页面，输入手机号，提交过来
		//提交后处理：186手机号，查找186是否在数据库中，
		//186在数据库中，一直手机号登录，第一次微信登陆，绑定
		//186没有在数据库中，手机号、微信注册入库……

		$userData = Db::name('user')->where('unionid', $userInfo['unionid'])->find();
		if (empty($userData)) {
			//绑定手机号
			session('wechatData', $userInfo);
			return redirect('wechat_mobile');

		} else {
			//直接跳转到会员主页
			Db::name('user')->where('unionid', $userInfo['unionid'])->update(['last_login_time' => time()]);
			session('sessionUserData', $userData);
			return redirect('index');
		}

	}

	//会员注册
	public function register() {
		if (request()->isPost()) {
			$data = input('post.');
			$code = input('get.code'); //推荐码

			try {
				validate(UserValidate::class)
					->scene('register')
					->check($data);
			} catch (ValidateException $e) {
				// 验证失败 输出错误信息
				return alert($e->getError(), 'register', 5);
			}

			//判断该手机号状态
			// $userData=Db::name('user')->where('mobile',$data['mobile'])->find();
			$userData = UserModel::where('mobile', $data['mobile'])->find();
			if ($userData['status'] == 1) {
				return alert('该手机号已经注册过了，请登录', 'login', 5);
			}
			if ($userData['status'] == -1) {
				return alert('该手机账号已经封号，请联系管理员', 'register', 5);
			}

			$smscode = '123456';
			$smscode = Cache::store('redis')->get($data['mobile']);
			//redis
			if ($data['smscode'] != $smscode) {
				//return alert('手机验证码错误', 'register', 5);
			}

			//密码加密
			$data['password'] = $this->password_salt($data['password']);
			$data['time'] = time();
			$data['username'] = $data['mobile'];
			$data['code'] = 'YJ' . time();
			$data['last_login_time'] = time();
			unset($data['smscode']);
			if ($code) {
				Db::startTrans();
				try {
					$newUserId = Db::name('user')->insertGetId($data);
					$this->fenyongScore($newUserId, $code);

					Db::name('score')->insert([
						'user_id' => $newUserId,
						'score' => 50,
						'time' => time(),
						'source' => 2,
						'info' => '新用户奖励',
					]);

					// 提交事务
					Db::commit();
				} catch (\Exception $e) {
					// 回滚事务
					Db::rollback();
					return alert('注册失败', 'register', 5);
				}

				return alert('注册成功，请登录', 'login', 6);

			} else {
				$newUserId = Db::name('user')->insertGetId($data);
				if ($newUserId) {
					return alert('注册成功，请登录', 'login', 6);
				} else {
					return alert('注册失败', 'register', 5);
				}

			}

		} else {
			return view();
		}
	}
	//推荐返佣积分逻辑
	public function fenyongScore($newUserId, $code) {
		//推荐人数据
		$userDataT = Db::name('user')->where('code', $code)->find();

		//更新新用户的parent_id
		Db::name('user')->where('id', $newUserId)->update(['parent_id' => $userDataT['id']]);

		//添加新用户积分
		Db::name('score')->insert([
			'user_id' => $newUserId,
			'score' => 300,
			'time' => time(),
			'source' => 2,
			'info' => '小伙伴奖励',
		]);

		//推荐人积分
		Db::name('score')->insert([
			'user_id' => $userDataT['id'],
			'score' => 500,
			'source' => 2,
			'time' => time(),
			'info' => '推荐返佣',
		]);
		//推荐人积分
		Db::name('fenyong')->insert([
			'user_id' => $newUserId, //新用户ID
			'code' => $code,
		]);
		return true;
	}

	//微信登录绑定手机号
	public function wechat_mobile() {

		//微信扫码后的用户数据
		$wechatData = session('wechatData');
		if (empty($wechatData)) {
			return alert('请登录', 'login', 5);
		}

		if (request()->isPost()) {

			$data = input('post.'); //表单提交数据

			//validate验证
			try {
				validate(UserValidate::class)
					->scene('bind_mobile')
					->check($data);
			} catch (ValidateException $e) {
				// 验证失败 输出错误信息
				return alert($e->getError(), 'wechat_mobile', 5);
			}

			//验证码
			$smscode = Cache::store('redis')->get($data['mobile']);
			//redis
			if ($data['smscode'] != $smscode) {
				//return alert('手机验证码错误', 'wechat_mobile', 5);
			}

			$userData = Db::name('user')->where('mobile', $data['mobile'])->find();
			if (empty($userData)) {
				//新用户，插入记录
				Db::name('user')->insert([
					'mobile' => $data['mobile'],
					'username' => $data['mobile'],
					'unionid' => $wechatData['unionid'],
					'last_login_time' => time(),
					'face' => $wechatData['headimgurl'],
				]);

				$userData = Db::name('user')->where('mobile', $data['mobile'])->find();
				session('sessionUserData', $userData);
				return redirect('edit_password');

			} else {
				//老用户绑定，更新记录
				Db::name('user')->where('mobile', $data['mobile'])->update(['last_login_time' => time(), 'unionid' => $wechatData['unionid']]);
				session('sessionUserData', $userData);
				return redirect('index');
			}

		} else {
			return view();
		}

	}
	//用户详情
	public function info() {

		$sessionUserData = $this->isLogin();

		$userData = Db::name('user')->find($sessionUserData['id']);

		return view('', [

			'userData' => $userData,
			'left_menu' => 41,
		]);
	}

	//全网昵称唯一
	public function checkUsername() {
		$data = input('post.');
		$userData = Db::name('user')->where('username', $data['username'])->where('id', '<>', $data['id'])->find();
		if ($userData) {
			return json(['code' => 1]);
		} else {
			return json(['code' => 0]);
		}
	}
	//修改会员资料更新

	public function index_update() {

		if (request()->isPost()) {

			$sessionUserData = $this->isLogin();
			$user_id = $sessionUserData['id'];
			$data = input('post.');
			unset($data['file']);
			//validate校验
			$validate = new \app\common\validate\User();
			if (!$validate->scene('info')->check($data)) {
				return alert($validate->getError(), 'info', 5, 3);
			}
			//更新数据库操作
			$res = Db::name('user')->where('id', $user_id)->update($data);
			$userData = Db::name('user')->find($user_id);
			session('sessionUserData', $userData);
			if ($res) {
				return alert('操作成功！', 'index', 6, 3);
			} else {
				return alert('操作失败！', 'index', 5, 3);
			}
		}
	}

	//修改密码
	public function edit_password() {
		$sessionUserData = $this->isLogin();

		if (request()->isPost()) {
			$data = input('post.');
			//validate验证
			try {
				validate(UserValidate::class)
					->scene('edit_password')
					->check($data);
			} catch (ValidateException $e) {
				// 验证失败 输出错误信息
				return alert($e->getError(), 'edit_password', 5);
			}

			$password = $this->password_salt($data['password']);
			$res = Db::name('user')->where('id', $sessionUserData['id'])->update(['password' => $password]);
			if ($res) {
				return alert('操作成功', 'index', 6);
			} else {
				return alert('操作失败', 'login', 5);
			}

		} else {
			return view('', [
				'left_menu' => 42,
			]);
		}
	}
	//我的评价
	public function comment_list() {
		$sessionUserData = $this->isLogin();
		$commentData = Db::name('comment')
			->alias('c')
			->join('goods g', 'c.goods_id = g.goods_id')
			->join('user u', 'c.user_id = u.id')
			->where('user_id', $sessionUserData['id'])
			->field('c.*,u.username,g.goods_thumb')
			->order('c.id desc')
			->paginate(3);
		// echo Db::name('comment')->getLastSql();die;
		return view('', [
			'left_menu' => 23,
			'commentData' => $commentData,
		]);
	}
	//  //我的评论列表
	// public function comment_list() {
	// 	$sessionUserData = $this->isLogin();
	// 	$commentData = Db::view('comment', 'id,content,star,time')
	// 		->view('goods', 'goods_thumb,goods_id', 'comment.goods_id=goods.goods_id')
	// 		->view('user', 'username', 'comment.user_id=user.id')
	// 		->where('comment.user_id', $sessionUserData['id'])
	// 		->order('comment.id desc')
	// 		->paginate(2);
	// 	View::assign(
	// 		[
	// 			'left_menu' => 23,
	// 			'commentData' => $commentData,
	// 		]);
	// 	return View::fetch();
	// 	// return view('', [
	// 	// 	'left_menu' => 23,
	// 	// 	'commentData' => $commentData,
	// 	// ]);
	// }

	//会员签到
	public function sign() {
		$sessionUserData = $this->isLogin();

		//先检测今天是否签到
		$scoreData = Db::name('score')->where('user_id', $sessionUserData['id'])->whereDay('time')->where('source', 1)->order('id desc')->find();
		if (!empty($scoreData)) {
			return alert('你今天已经签到了', 'index', 5);
		}

		$res = Db::name('score')->insert([
			'user_id' => $sessionUserData['id'],
			'time' => time(),
			'score' => 10,
			'info' => '签到赚取积分',
		]);
		if ($res) {
			return alert('签到成功', 'index', 6);
		} else {
			return alert('签到失败', 'index', 5);
		}
	}
	//头像上传
	public function upload() {
		$upload = new Uploader();
		$upload->upload();
	}
	//会员退出
	public function login_out() {
		session('sessionUserData', null);
		return redirect('/');
	}

	//我的分佣
	public function fenyong() {
		$sessionUserData = $this->isLogin();
		$code = 'http://qing.cn/index/user/register?code=' . $sessionUserData['code'];

		return view('', [
			'code' => $code,
			'left_menu' => 25,

		]);
	}

	//优惠券
	public function coupons() {
		$sessionUserData = $this->isLogin();
		$time = time();

		//未使用，时间没有过期
		$couponsData0 = Db::name('coupons_user')->alias('a')->field('b.*,a.status')->join('coupons b', 'a.coupons_id=b.id')->where('a.status', 0)->where('a.user_id', $sessionUserData['id'])->order('id desc')->where('b.time2', '>', $time)->select();

		//已经使用
		$couponsData1 = Db::name('coupons_user')->alias('a')->field('b.*,a.status')->join('coupons b', 'a.coupons_id=b.id')->where('a.status', 1)->where('a.user_id', $sessionUserData['id'])->order('id desc')->select();

		//时间过期的：包括已使用和未使用
		$couponsData2 = Db::name('coupons_user')->alias('a')->field('b.*,a.status')->join('coupons b', 'a.coupons_id=b.id')->where('a.user_id', $sessionUserData['id'])->where('b.time2', '<', $time)->order('id desc')->select();

		return view('', [
			'left_menu' => 24,
			'couponsData0' => $couponsData0,
			'couponsData2' => $couponsData2,
			'couponsData1' => $couponsData1,
		]);
	}

	//领取优惠券
	public function add_coupons() {
		$sessionUserData = session('sessionUserData');
		//如果没有登录，则返回登录
		if (empty($sessionUserData)) {
			return json(['status' => 0]);
		}

		//判断是否有该优惠券
		$coupons_id = input('post.id');
		$couponsData = Db::name('coupons')->find($coupons_id);
		if (empty($couponsData) || $couponsData['status'] != 1) {
			return json(['status' => -1]);
		}

		//查找是否领取优惠券
		$couponsUserrData = Db::name('coupons_user')->where('user_id', $sessionUserData['id'])->where('coupons_id', $coupons_id)->find();

		if ($couponsUserrData) {
			//已经领取了
			return json(['status' => 1]);
		} else {
			//领取优惠券
			$res = Db::name('coupons_user')->insert([
				'user_id' => $sessionUserData['id'],
				'coupons_id' => $coupons_id,
			]);
			if ($res) {
				if ($couponsData['count'] == 1) {
					Db::name('coupons')->where('id', $coupons_id)->update([
						'count' => 0,
						'status' => 0,
					]);
				} else {
					Db::name('coupons')->where('id', $coupons_id)->update([
						'count' => $couponsData['count'] - 1,
					]);
				}

				return json(['status' => 3]);
			}
		}
	}
	//商品浏览足迹
	public function mytrace() {
		$sessionUserData = $this->isLogin();

		$mytraceData = Db::name('user_trace')->alias('a')->join('goods b', 'a.goods_id=b.goods_id')->where('a.user_id', $sessionUserData['id'])->order('a.time desc')->paginate(10);

		return view('', [
			'left_menu' => 26,
			'mytraceData' => $mytraceData,
		]);
	}

	//删除商品足迹
	public function delete_mytrace() {
		$sessionUserData = $this->isLogin();

		$id = input('id');

		//检测是否有该记录
		$traceData = Db::name('user_trace')->find($id);
		if (!empty($traceData)) {
			$res = Db::name('user_trace')->delete($id);
		}
		if ($res) {
			return alert('成功删除记录', 'mytrace', 6, 3);
		} else {
			return alert('删除删除记录', 'mytrace', 5, 3);
		}
	}
}
