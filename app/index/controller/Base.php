<?php
namespace app\index\controller;
use app\BaseController;
use think\facade\Db;
use think\facade\View;
use app\common\lib\Alicms;
use think\exception\HttpResponseException;
class Base extends BaseController
{
    
    public function initialize() {

      $this->getConfig();//获取配置信息
      

    }



    //配置信息
   public function getConfig(){

      $configData=Db::name('config')->field('value')->select();

      foreach($configData as $k=>$v){

        if($k==0){

         View::assign('web_title', $v['value']);//首页title

        }

        if($k==2){

            View::assign('web_keywords', $v['value']);//首页关键字

        }

        if($k==12){

            View::assign('web_description', $v['value']);//首页描述

        }

        if($k==3){

            View::assign('copyright', $v['value']);//版权信息

        }

        if($k==4){

            View::assign('beian', $v['value']);//备案号

        }

        if($k==6){

            View::assign('address', $v['value']);//地址

        }

        if($k==10){

            View::assign('email', $v['value']);//邮箱

        }

        if($k==7){

            View::assign('tel1', $v['value']);//电话

        }

        if($k==11){

            View::assign('fax', $v['value']);//传真

        }

      }
   }


   //前台密码加密盐
   public function password_salt($str){
        $salt='zxc69vbn';
        return md5($salt.$str);
    }

    //发送验证码
    public function SendMobileCode(){
       $mobile=input('post.mobile');
    
       //自行判断手机号是否合法
       $sendcode=new Alicms();
       $res=$sendcode->index($mobile);
       if($res){
           return json(['status'=>1]);
       }else{
            return json(['status'=>0]);
       }

    }

    public function isLogin(){
        $sessionUserData=session('sessionUserData');
        if(empty($sessionUserData)){
            $this->redirect('/index/user/login');
        }if($sessionUserData['status']!=1){
            session('sessionUserData',null);
            $this->redirect('/index/user/login');
        }else{
            return $sessionUserData;
        }
    }

    public function redirect(...$args){
        throw new HttpResponseException(redirect(...$args));
    }
  
  

    
}
