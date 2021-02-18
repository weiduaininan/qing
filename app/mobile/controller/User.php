<?php
namespace app\mobile\controller;

use think\Controller;
use think\facade\Db;
use app\common\model\Category as CategoryModel;


class User extends  Base

{
        //会员主页
        public function index(){
            echo '会员主页';
        }

        //登录
        public function login()

        {   
            return view();

        }

        //微信授权登录
        public function wechat_login(){
            $sessionUserData=session('sessionUserData');
            if(!empty($sessionUserData)){
                return redirect('index');
            }
            
            $appid='wxff5b68b241a4fb11';//公众号基本配置中获取
            $redirect_uri='http://a.xx.cn/mobile/user/weixin_m.html';//用户授权后跳转处理的地址
            $url="https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$appid."&redirect_uri=".$redirect_uri."&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect";
            return redirect($url);
        }

        //微信授权登录回调
        public function weixin_m(){
            $code=input('get.code');
            $appid='wxff5b68b241a4fb11';//公众号基本配置中获取
            $appsecret='412a24b17e61310d589b8bf92f374ffc';
            $url="https://api.weixin.qq.com/sns/oauth2/access_token?appid=".$appid."&secret=".$appsecret."&code=".$code."&grant_type=authorization_code";
            $res=json_decode(file_get_contents($url),true);//json转数组
            // array:6 [
            //     "access_token" => "37_3RRxoQZKuECSpCfGMYLcO-1ZXu_uhTkdku_m29u4XfSq9-Ve_0Fn5_K6vUBpkiL1iXRpEBepOfeMZZA7TGm-bg"
            //     "expires_in" => 7200
            //     "refresh_token" => "37_CjmxjzqBCqiIVH3aKjR22RQniCr_7DYYJYgodMONV5822FnfKuq0VwOS0B9dfucHf6GxTjXbczruwS5NIkGAWw"
            //     "openid" => "oaq-51XAHNaj9qUxVwYu3-elVTa0"
            //     "scope" => "snsapi_userinfo"
            //     "unionid" => "oO0Bhv6ZSw4ZYV60CMzi2p4eUO7s"
            // ]
           

            //拉取用户信息(需scope为 snsapi_userinfo)
            $user_url="https://api.weixin.qq.com/sns/userinfo?access_token=".$res['access_token']."&openid=".$res['openid']."&lang=zh_CN";
            $userData=json_decode(file_get_contents($user_url),true);//json转数组
            halt($userData);
        }

    

   





}

