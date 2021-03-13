<?php

/**
 * @Author: liunan
 * @Date:   2021-03-13 12:54:41
 * @Last Modified by:   liunan
 * @Last Modified time: 2021-03-13 13:00:13
 */

namespace app\rabbit\controller;
use app\BaseController;
use app\MqProducer;

class Index extends BaseController
{
    public function send()
    {
        $consumer = new MqProducer();//生产者
        $consumer->pushMessage('time:'.time().' your mother call you back home ');
    }
}