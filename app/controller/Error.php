<?php

namespace app\controller;

// 没开启强制路由的情况下，访问不存在的控制器方法时，会调用这个类的方法
class Error
{
    public function __call($name, $arguments)
    {
        return '404';
    }
}