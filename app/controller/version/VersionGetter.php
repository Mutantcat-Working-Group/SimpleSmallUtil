<?php

namespace app\controller\version;

class VersionGetter
{
    public function index()
    {
        return 'mutantcat.org';
    }

    public function getAllVersion()
    {
        $rootPath = dirname(__DIR__, 3); // 项目根路径
        $public_key = input('get.key'); // 从GET请求中获取密钥
        $filename = input('get.name'); // 从GET请求中获取文件名

        if ($public_key != VERSION_PUBLIC_KEY) {
            $filePath = $rootPath . '/runtime/version/example_version.xml';
        }else if (!$filename) {
            $filePath = $rootPath . '/runtime/version/example_version.xml';
        } else {
            $filePath = $rootPath . '/runtime/version/' . $filename . '.xml';
        }

        // 验证文件是否存在且路径合法
        if (!file_exists($filePath) || strpos(realpath($filePath), realpath($rootPath . '/runtime/version/')) !== 0) {
            $filePath = $rootPath . '/runtime/version/example_version.xml';
        }

        // 读取并解析 XML 文件
        try {
            // 将 XML 文件加载为 PHP 对象
            $xmlObject = simplexml_load_file($filePath);

            // 返回 PHP 数组或对象
            return json([
                'versionInfo' => $xmlObject,
            ]);
        } catch (\Exception $e) {
            // 处理解析错误
            return '{"versionInfo":{"name":{"zh-cn":"-1","en":"-1"},"lastest":"-1"}}';
        }
    }

    public function getLastestVersion()
    {
        $rootPath = dirname(__DIR__, 3); // 项目根路径
        $public_key = input('get.key'); // 从GET请求中获取密钥
        $filename = input('get.name'); // 从GET请求中获取文件名

        if ($public_key != VERSION_PUBLIC_KEY) {
            $filePath = $rootPath . '/runtime/version/example_version.xml';
        }else if (!$filename) {
            $filePath = $rootPath . '/runtime/version/example_version.xml';
        } else {
            $filePath = $rootPath . '/runtime/version/' . $filename . '.xml';
        }

        // 检查文件是否存在
        if (!file_exists($filePath)) {
            $filePath = $rootPath . '/runtime/version/example_version.xml';
        }

        // 读取并解析 XML 文件
        try {
            // 将 XML 文件加载为 PHP 对象
            $xmlObject = simplexml_load_file($filePath);

            $version = $xmlObject->lastest;

            if (!$version) {
                return "-1.0.0";
            }

            // 返回 PHP 数组或对象
            return  $version;
        } catch (\Exception $e) {
            // 处理解析错误
            return "-1.0.0";
        }
    }
}