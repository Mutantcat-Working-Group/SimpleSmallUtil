<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
use think\facade\Route;

Route::get('think', function () {
    return 'hello,ThinkPHP8!';
});

Route::miss(function () {
    return '404';
});

// index模块
route::get('', 'index/index');
Route::get('ping', 'index/ping'); // ping

// time模块
Route::group('time', function () {
    Route::get('/', 'time.NowTime/index'); // 获取当前时间
    Route::get('timestamp', 'time.NowTime/getTimeStamp'); // 获取当前时间戳
});

// 云变量模块
Route::group('variable', function () {
    Route::get('/', 'variable.TempVariable/index'); // 获取当前时间
    Route::get('/add', 'variable.TempVariable/addTempVariable'); // 获取当前时间戳
    Route::get('/get', 'variable.TempVariable/getTempVariable'); // 获取当前时间戳
    Route::get('/clean', 'variable.TempVariable/clean'); // 获取当前时间戳
});


