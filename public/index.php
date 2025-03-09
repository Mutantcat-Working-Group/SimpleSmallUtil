<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2019 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

use think\App;

// [ 应用入口文件 ]

require __DIR__ . '/../vendor/autoload.php';

// 支持跨域
//header('Access-Control-Allow-Origin: *');
//header('Access-Control-Allow-Methods: *');
//header('Access-Control-Allow-Headers: x-requested-with, content-type, token');

// 执行HTTP应用并响应
$http = (new App())->http;

$response = $http->run();

$response->send();

$http->end($response);
