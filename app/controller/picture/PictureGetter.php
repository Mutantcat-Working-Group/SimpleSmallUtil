<?php

namespace app\controller\picture;

include dirname(__DIR__, 2) . '/common.php';


class PictureGetter
{
    public function index()
    {
        return 'mutantcat.org';
    }

    public function getPicture()
    {
        $rootPath = dirname(__DIR__, 3);
        $public_key = input('get.key');
        $filename = input('get.name');

        // 不可缺少 缺少返回example图片
        if ($public_key != PICTURE_PUBLIC_KEY) {
            $filePath = $rootPath . '/runtime/picture/example_picture.jpg';
        } else if (!$filename) {
            $filePath = $rootPath . '/runtime/picture/example_picture.jpg';
        } else {
            $filePath = $rootPath . '/runtime/picture/' . $filename;
        }

        // 验证文件是否存在且是否为安全路径
        if (!file_exists($filePath) || strpos(realpath($filePath), realpath($rootPath . '/runtime/picture/')) !== 0) {
            $filePath = $rootPath . '/runtime/picture/example_picture.jpg';
        }

        // 获取文件的 MIME 类型并设置响应头
        $fileInfo = mime_content_type($filePath);
        header("Content-Type: $fileInfo");

        // 设置文件为可下载（可选）
        // header("Content-Disposition: attachment; filename=" . basename($filePath));

        // 输出文件内容
        readfile($filePath);

        // 终止脚本执行
        exit;
    }

}