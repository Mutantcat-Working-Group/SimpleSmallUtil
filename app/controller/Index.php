<?php

namespace app\controller;

use app\BaseController;

class Index extends BaseController
{
    public function index()
    {
        return 'Welcome,SimpleSmallUtil.xml(SSU),mutantcat.org';
    }

    public function ping(){
        return 'pong';
    }
}
