<?php

namespace app\controller;

use app\BaseController;

class Index extends BaseController
{
    public function index()
    {
        return 'Welcome,SimpleSmallUtil.xml(SSU),由异猫工作群（mutantcat.org）发行 · https://github.com/Mutantcat-Working-Group';
    }

    public function ping(){
        return 'pong';
    }
}
