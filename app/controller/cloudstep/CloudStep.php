<?php

namespace app\controller\cloudstep;

class CloudStep
{
    public function index()
    {
        return 'mutantcat.org';
    }

    public function getCloudStep()
    {
        $rootPath = dirname(__DIR__, 3); // 项目根路径
        $public_key = input('get.key'); // 从GET请求中获取密钥
        $filename = input('get.name'); // 从GET请求中获取文件名

        if ($public_key != CLOUDSTEP_PUBLIC_KEY) {
            $filePath = $rootPath . '/runtime/cloudstep/example_cloudstep.xml';
        }else if (!$filename) {
            $filePath = $rootPath . '/runtime/cloudstep/example_cloudstep.xml';
        } else {
            $filePath = $rootPath . '/runtime/cloudstep/' . $filename . '.xml';
        }

        // 验证文件是否存在且路径合法
        if (!file_exists($filePath) || strpos(realpath($filePath), realpath($rootPath . '/runtime/cloudstep/')) !== 0) {
            $filePath = $rootPath . '/runtime/cloudstep/example_cloudstep.xml';
        }

        // 读取并解析 XML 文件
        try {
            // 将 XML 文件加载为 PHP 对象
            $xmlObject = simplexml_load_file($filePath);

            switch ($xmlObject->mode){
                case "random":
                    // 先获得targets的数量
                    $targetCount = count($xmlObject->targets->target);
                    // 随机选择一个
                    $randomIndex = rand(0, $targetCount - 1);
                    return $xmlObject->targets->target[$randomIndex];
                    break;
                default:
                    return "-1";
            }
        } catch (\Exception $e) {
            // 处理解析错误
            return "-1";
        }
    }
}