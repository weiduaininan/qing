<?php
namespace app\qingadmin\controller;

use app\BaseController;
use think\facade\Db;

class Goods extends Base
{

   

    //列表
    public function index()
    {    
        echo '我是商品首页';
    }


    public function add()
    {    
        echo '我是添加商品';
    }

    public function edit()
    {    
        echo '我是修改商品';
    }

    public function del()
    {    
        echo '我是删除商品';
    }

   
}
