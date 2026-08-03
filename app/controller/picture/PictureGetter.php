<?php

namespace app\controller\picture;

use app\Trait\FileReaderTrait;

include dirname(__DIR__, 2) . '/common.php';

class PictureGetter
{
    use FileReaderTrait;

    public function index()
    {
        return 'mutantcat.org';
    }

    public function getPicture()
    {
        $rootPath = dirname(__DIR__, 3) . '/runtime/picture';
        $filePath = $this->resolveStorageFile(
            (string) input('get.key'),
            PICTURE_PUBLIC_KEY,
            (string) input('get.name'),
            $rootPath,
            $rootPath . '/example_picture.jpg'
        );

        $mime = mime_content_type($filePath) ?: 'application/octet-stream';
        header("Content-Type: $mime");
        readfile($filePath);
        exit;
    }
}
