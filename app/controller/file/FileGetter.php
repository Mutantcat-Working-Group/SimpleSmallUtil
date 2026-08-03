<?php

namespace app\controller\file;

use app\Trait\FileReaderTrait;

include dirname(__DIR__, 2) . '/common.php';

class FileGetter
{
    use FileReaderTrait;

    public function index()
    {
        return 'mutantcat.org';
    }

    public function getFile()
    {
        $rootPath = dirname(__DIR__, 3) . '/runtime/file';
        $filePath = $this->resolveStorageFile(
            (string) input('get.key'),
            FILE_PUBLIC_KEY,
            (string) input('get.name'),
            $rootPath,
            $rootPath . '/example_file.txt'
        );

        $mime = mime_content_type($filePath) ?: 'application/octet-stream';
        header("Content-Type: $mime");
        header("Content-Disposition: attachment; filename=" . basename($filePath));
        header("Content-Length: " . filesize($filePath));
        readfile($filePath);
        exit;
    }
}
