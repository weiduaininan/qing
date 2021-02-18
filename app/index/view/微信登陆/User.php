<?php

namespace app\index\controller;

use app\BaseController;
use think\facade\Db;
use think\facade\Cache;

class User extends  BaseController

{

    // $appid='wx868f988d79a4f25b';
    // $appsecret='a2b426f2882b6a1398b8312cc1de037b';
    public function login(){
        return view();
    }

    public function wechat(){
        $appid='wx868f988d79a4f25b';
        $REDIRECT_URI=urlEncode('http://www.xxx.cn/index/user/weixin.html');
        $url='https://open.weixin.qq.com/connect/qrconnect?appid='.$appid.'&redirect_uri='.$REDIRECT_URI.'&response_type=code&scope=snsapi_login&state=STATE#wechat_redirect';
        return redirect($url);
    }


    public function weixin(){
        $code=input('get.code');
        $appid='wx868f988d79a4f25b';
        $appsecret='a2b426f2882b6a1398b8312cc1de037b';
        $url='https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$appid.'&secret='.$appsecret.'&code='.$code.'&grant_type=authorization_code';

        //$res=file_get_contents($url);
        // {"access_token":"35_HINwxRbVGwM3R4PbOyoINNVTzH5S8tNqsATXDYGh9NUQWj7Z7lbYjOzVb_tl80JrdlKfihY0e4CtFpjobLHW14P_YRWOWKMnx5pIB8BhOqY","expires_in":7200,"refresh_token":"35_uIxPmh4MHNn-2r9-GBPn2ZRt9H0coLrwO3R-sefGEoB_AcisodzL_NZAuTH4ABCJty0pOUV41dAWJqzi5UUAxoxwsz18T3T6d_mCjsFU24o","openid":"oP19r1PgUR1_k4Efu8tSoJBdRn78","scope":"snsapi_login","unionid":"oO0Bhv0llJvwqSMmA0OwS8q-NBn4"} 

        //json转数组
        $res=json_decode(file_get_contents($url),true);

        // [
        //     "access_token" => "35_e50x27-4SDubvqqlS-PrajOIbKtN7sxV6od5x3yF_mjhbgijjbwcT-ZZcklLk8KoehCqLt0OXWqW8wetPKLk-JLkNtiki5OtS1Me9QNWkJM"
        //     "expires_in" => 7200
        //     "refresh_token" => "35_nFXGDJ1JTO7z9rHG0Ziec-NRnoWvzAPLaLg5DhOetngglknNZtjfDoU6ydd3FlcgOOS9D9o8XBPLa4ibsF9W-3KXQYLsNMaxZD1MH2TZK3g"
        //     "openid" => "oP19r1PgUR1_k4Efu8tSoJBdRn78"
        //     "scope" => "snsapi_login"
        //     "unionid" => "oO0Bhv0llJvwqSMmA0OwS8q-NBn4"
        // ]
        $access_token=$res['access_token'];
        $openid=$res['openid'];
        $url='https://api.weixin.qq.com/sns/userinfo?access_token='.$access_token.'&openid='.$openid;
        
        //$userInfo=file_get_contents($url);//json格式
        // {"openid":"oP19r1PgUR1_k4Efu8tSoJBdRn78","nickname":"阿芹","sex":2,"language":"zh_CN","city":"Texas","province":"Shandong","country":"CN","headimgurl":"http:\/\/thirdwx.qlogo.cn\/mmopen\/vi_32\/Q0j4TwGTfTIrKg5HHPu3UA5bria41g6a8W76DibHwK2lwwAteR4KO8nEH1cpBhjcbD7b61SGOHuicMLrUBvTHNmKg\/132","privilege":[],"unionid":"oO0Bhv0llJvwqSMmA0OwS8q-NBn4"}
        
        //json转数组
        $userInfo=json_decode(file_get_contents($url),true);

        //  [
        //     "openid" => "oP19r1PgUR1_k4Efu8tSoJBdRn78"
        //     "nickname" => "阿芹"
        //     "sex" => 2
        //     "language" => "zh_CN"
        //     "city" => "Texas"
        //     "province" => "Shandong"
        //     "country" => "CN"
        //     "headimgurl" => "http://thirdwx.qlogo.cn/mmopen/vi_32/Q0j4TwGTfTIrKg5HHPu3UA5bria41g6a8W76DibHwK2lwwAteR4KO8nEH1cpBhjcbD7b61SGOHuicMLrUBvTHNmKg/132"
        //     "privilege" => []
        //     "unionid" => "oO0Bhv0llJvwqSMmA0OwS8q-NBn4"
        // ]

        halt($userInfo);
    }


}

