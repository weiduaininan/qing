<?php
namespace app\qingadmin\controller;

use app\BaseController;
use think\facade\Db;

class Order extends Base
{

   

    //列表
    public function index()
    {    
        echo '我是订单首页';
    }


    public function add()
    {    
        echo '我是添加订单';
    }

    public function edit()
    {    
        echo '我是修改订单';
    }

    public function del()
    {    
        echo '我是删除订单';
    }

   
}
