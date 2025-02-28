<?php

namespace app\controller;

use app\BaseController;

class Index extends BaseController
{
    public function index()
    {
        return '用于简单小工具(SSU)的请求接口';
    }

    public function ping(){
        return 'pong';
    }
}
