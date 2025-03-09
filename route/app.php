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

// 若需要全局允许跨需要前往public/index.php
// 若需要单个允许跨域需要加上->allowCrossDomain() 例如Route::get('index', 'index/index')->allowCrossDomain();

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
    Route::get('/', 'variable.TempVariable/index'); // 空接口
    Route::get('/add', 'variable.TempVariable/addTempVariable'); // 添加云变量
    Route::get('/get', 'variable.TempVariable/getTempVariable'); // 获取云变量
    Route::get('/clean', 'variable.TempVariable/clean'); // 触发清理
});

// 图片模块
Route::group('picture', function () {
    Route::get('/', 'picture.PictureGetter/index'); // 空接口
    Route::get('/get', 'picture.PictureGetter/getPicture'); // 获取当前时间戳
});

// 文件模块
Route::group('file', function () {
    Route::get('/', 'file.FileGetter/index'); // 空接口
    Route::get('/get', 'file.FileGetter/getFile'); // 获取文件
});

// 版本模块
Route::group('version', function () {
    Route::get('/', 'version.VersionGetter/index'); // 获取版本信息
    Route::get('/all', 'version.VersionGetter/getAllVersion'); // 获得所有版本的信息
    Route::get('/lastest', 'version.VersionGetter/getLastestVersion'); // 获得最新版本号
});

// 云阶模块
Route::group('cloudstep', function () {
    Route::get('/', 'cloudstep.CloudStep/index'); // 空接口
    Route::get('/get', 'cloudstep.CloudStep/getCloudStep'); // 获取云阶结果
});

