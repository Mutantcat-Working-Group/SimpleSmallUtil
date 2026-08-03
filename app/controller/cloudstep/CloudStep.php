<?php

namespace app\controller\cloudstep;

use app\Trait\FileReaderTrait;
use think\facade\Db;

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
                    return $this->modeRandom($xmlObject);

                case 'hash':
                    return $this->modeHash($xmlObject);

                case 'static':
                    return $this->modeStatic($xmlObject);

                case 'static_cloud_var':
                    return $this->modeStaticCloudVar($xmlObject);

                default:
                    return "-1";
            }
        } catch (\Exception $e) {
            return "-1";
        }
    }

    /**
     * random: 纯随机返回一个 target（动静态随机中的"动态随机"）
     */
    private function modeRandom(\SimpleXMLElement $xml): string
    {
        $targets = $xml->targets->target ?? [];
        $count = count($targets);
        if ($count === 0) {
            return "-1";
        }
        return (string) $targets[random_int(0, $count - 1)];
    }

    /**
     * hash: 基于客户端特征的一致性哈希（负载均衡 + 无感更新）
     *
     * 用法：用「客户端IP + 请求里的 key 参数」做哈希，再按 target 数取模。
     * 同一个客户端（同 IP、同 key）始终落到同一台 target；增减 target 时只有少量客户端迁移（无感更新）。
     */
    private function modeHash(\SimpleXMLElement $xml): string
    {
        $targets = $xml->targets->target ?? [];
        $count = count($targets);
        if ($count === 0) {
            return "-1";
        }

        // 客户端特征：优先用用户传入的 key 参数做稳定分桶，回退到 IP
        $clientKey = (string) input('get.key');
        if ($clientKey === '' || $clientKey === CLOUDSTEP_PUBLIC_KEY) {
            $clientKey = (string) input('ip', '0.0.0.0');
        }

        $bucket = crc32($clientKey) % $count;
        return (string) $targets[$bucket];
    }

    /**
     * static: 直接返回 XML 中定义的固定值（动静态随机中的"静态"、静态云变量的最简单形式）
     */
    private function modeStatic(\SimpleXMLElement $xml): string
    {
        $value = trim((string) ($xml->value ?? ''));
        return $value !== '' ? $value : "-1";
    }

    /**
     * static_cloud_var: 把云变量值作为云阶结果返回（静态云变量 = 值通过 /variable/接口管理，云阶只负责取出）
     *
     * XML 结构：
     * <cloudvar>
     *   <private_key>...</private_key>
     *   <key>...</key>
     * </cloudvar>
     */
    private function modeStaticCloudVar(\SimpleXMLElement $xml): string
    {
        $private_key = (string) ($xml->cloudvar->private_key ?? '');
        $key = (string) ($xml->cloudvar->key ?? '');

        if ($private_key === '' || $key === '') {
            return "-1";
        }

        try {
            $row = Db::table('temp_value')
                ->where('t_key', $key)
                ->where('private_key', $private_key)
                ->find();

            if (!$row) {
                return "-1";
            }

            return (string) $row['t_value'];
        } catch (\Exception $e) {
            return "-1";
        }
    }
}
