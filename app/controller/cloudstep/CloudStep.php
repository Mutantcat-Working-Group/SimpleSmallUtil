<?php

namespace app\controller\cloudstep;

use app\Trait\FileReaderTrait;

include dirname(__DIR__, 2) . '/common.php';

class CloudStep
{
    use FileReaderTrait;

    public function index()
    {
        return 'mutantcat.org';
    }

    public function getCloudStep()
    {
        $rootPath = dirname(__DIR__, 3) . '/runtime/cloudstep';
        $filePath = $this->resolveStorageFile(
            (string) input('get.key'),
            CLOUDSTEP_PUBLIC_KEY,
            (string) input('get.name'),
            $rootPath,
            $rootPath . '/example_cloudstep.xml',
            '.xml'
        );

        try {
            $xmlObject = simplexml_load_file($filePath);
            if ($xmlObject === false) {
                return "-1";
            }

            $mode = (string) ($xmlObject->mode ?? '');

            switch ($mode) {
                case 'random':
                    $targets = $xmlObject->targets->target ?? [];
                    $count = count($targets);
                    if ($count === 0) {
                        return "-1";
                    }
                    $randomIndex = random_int(0, $count - 1);
                    return (string) $targets[$randomIndex];
                default:
                    return "-1";
            }
        } catch (\Exception $e) {
            return "-1";
        }
    }
}
