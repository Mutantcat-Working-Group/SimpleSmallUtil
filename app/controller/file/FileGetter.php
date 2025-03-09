<?php

namespace app\controller\file;

class FileGetter
{
    public function index()
    {
        return 'mutantcat.org';
    }

    public function getFile()
    {
        $rootPath = dirname(__DIR__, 3); // 项目根路径
        $public_key = input('get.key'); // 从GET请求中获取密钥
        $filename = input('get.name'); // 从GET请求中获取文件名

        // 验证密钥
        if ($public_key != FILE_PUBLIC_KEY) {
            $filePath = $rootPath . '/runtime/file/example_file.txt';
        }else if (!$filename) {
            $filePath = $rootPath . '/runtime/file/example_file.txt';
        } else {
            $filePath = $rootPath . '/runtime/file/' . $filename;
        }
        // 验证文件是否存在且路径合法
        if (!file_exists($filePath) || strpos(realpath($filePath), realpath($rootPath . '/runtime/file/')) !== 0) {
            $filePath = $rootPath . '/runtime/file/example_file.txt';
        }

        // 获取文件的 MIME 类型并设置响应头
        $fileInfo = mime_content_type($filePath);
        header("Content-Type: $fileInfo");

        // 设置下载头，指定下载文件名
        header("Content-Disposition: attachment; filename=" . basename($filePath));
        header("Content-Length: " . filesize($filePath));

        // 输出文件内容
        readfile($filePath);

        // 终止脚本执行
        exit;
    }
}