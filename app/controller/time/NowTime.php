<?php

namespace app\controller\time;

include dirname(__DIR__, 2) . '/common.php';

class NowTime
{
    public function index()
    {
        $public_key = input('get.key');
        if ($public_key != TIME_PUBLIC_KEY) {
            return '0-0-0 0:0:0';
        }
        return date('Y-m-d H:i:s');
    }

    public function getTimeStamp()
    {
        $public_key = input('get.key');
        if ($public_key != TIME_PUBLIC_KEY) {
            return '0';
        }
        return time();
    }
}