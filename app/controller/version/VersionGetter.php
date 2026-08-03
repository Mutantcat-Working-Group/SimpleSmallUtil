<?php

namespace app\controller\version;

use app\Trait\FileReaderTrait;

include dirname(__DIR__, 2) . '/common.php';

class VersionGetter
{
    use FileReaderTrait;

    public function index()
    {
        return 'mutantcat.org';
    }

    public function getAllVersion()
    {
        $rootPath = dirname(__DIR__, 3) . '/runtime/version';
        $filePath = $this->resolveStorageFile(
            (string) input('get.key'),
            VERSION_PUBLIC_KEY,
            (string) input('get.name'),
            $rootPath,
            $rootPath . '/example_version.xml',
            '.xml'
        );

        try {
            $xmlObject = simplexml_load_file($filePath);
            if ($xmlObject === false) {
                return '{"versionInfo":{"name":{"zh-cn":"-1","en":"-1"},"lastest":"-1"}}';
            }
            return json(['versionInfo' => $xmlObject]);
        } catch (\Exception $e) {
            return '{"versionInfo":{"name":{"zh-cn":"-1","en":"-1"},"lastest":"-1"}}';
        }
    }

    public function getLastestVersion()
    {
        $rootPath = dirname(__DIR__, 3) . '/runtime/version';
        $filePath = $this->resolveStorageFile(
            (string) input('get.key'),
            VERSION_PUBLIC_KEY,
            (string) input('get.name'),
            $rootPath,
            $rootPath . '/example_version.xml',
            '.xml'
        );

        try {
            $xmlObject = simplexml_load_file($filePath);
            if ($xmlObject === false) {
                return "-1.0.0";
            }
            $version = (string) $xmlObject->lastest;
            return $version !== '' ? $version : "-1.0.0";
        } catch (\Exception $e) {
            return "-1.0.0";
        }
    }
}
