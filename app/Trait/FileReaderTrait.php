<?php

namespace app\Trait;

trait FileReaderTrait
{
    /**
     * 解析受密钥保护的存储文件路径（防路径穿越，带回退）。
     *
     * @param string $publicKey  请求中的公钥
     * @param string $expected   期望的公钥常量值
     * @param string $filename   请求中的文件名（可为空）
     * @param string $storageDir 存储目录的真实绝对路径
     * @param string $fallback   回退文件的真实绝对路径
     * @param string $ext        自动追加的文件后缀（如 '.xml'；传空串则不追加）
     * @return string 解析后的真实文件路径
     */
    protected function resolveStorageFile(
        string $publicKey,
        string $expected,
        string $filename,
        string $storageDir,
        string $fallback,
        string $ext = ''
    ): string {
        $realStorage = realpath($storageDir);
        $realFallback = realpath($fallback);

        // 存储目录或回退文件本身异常时，只能回退
        if ($realStorage === false || $realFallback === false) {
            return $realFallback ?: $fallback;
        }

        $filePath = $realFallback;

        if ($publicKey === $expected && $filename !== '') {
            $filePath = $realStorage . DIRECTORY_SEPARATOR . $filename . $ext;
        }

        $realPath = realpath($filePath);
        if ($realPath === false || strpos($realPath, $realStorage . DIRECTORY_SEPARATOR) !== 0) {
            return $realFallback;
        }

        return $realPath;
    }
}
