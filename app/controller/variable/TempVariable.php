<?php

namespace app\controller\variable;

use think\facade\Db;
use think\db\exception\DbException;

include dirname(__DIR__, 2) . '/common.php';

class TempVariable
{
    public function index()
    {
        return 'mutantcat.org';
    }

    public function addTempVariable()
    {
        $public_key = (string) input('get.public_key');
        $private_key = (string) input('get.private_key');
        $key = (string) input('get.key');
        $value = (string) input('get.value');
        $expiration_date = (string) input('get.expiration_date');
        $once = (string) input('get.once');

        if (
            $public_key === '' || $private_key === '' || $key === '' || $value === '' || $expiration_date === ''
            || ($once !== '0' && $once !== '1')
            || $public_key !== VALUE_PUBLIC_KEY
        ) {
            return $this->errorResponse();
        }

        // 毫秒转过期时间；0 表示永不过期（用一个远超当前时间的日期表示）
        $expire = null;
        if ($expiration_date !== '0') {
            $expire = date('Y-m-d H:i:s', time() + ((int) $expiration_date) / 1000);
        }

        try {
            Db::startTrans();

            $existing = Db::table('temp_value')
                ->where('t_key', $key)
                ->where('private_key', $private_key)
                ->find();

            if ($existing) {
                $update = [
                    't_value' => $value,
                    'once' => (int) $once,
                ];
                // null 表示保留原过期时间
                if ($expire !== null) {
                    $update['expiration_date'] = $expire;
                }
                Db::table('temp_value')
                    ->where('id', $existing['id'])
                    ->update($update);
                $data = Db::table('temp_value')
                    ->where('id', $existing['id'])
                    ->find();
            } else {
                // 新建时 0 表示永不过期，用一个远超当前时间的日期代表
                $newId = Db::table('temp_value')->insertGetId([
                    't_key' => $key,
                    't_value' => $value,
                    'expiration_date' => $expire ?? '2099-12-31 23:59:59',
                    'private_key' => $private_key,
                    'once' => (int) $once,
                ]);
                $data = Db::table('temp_value')
                    ->where('id', $newId)
                    ->find();
            }

            Db::commit();
        } catch (DbException $e) {
            Db::rollback();
            return $this->errorResponse(-2);
        }

        $this->checkAndClean();

        return json_encode($data);
    }

    public function getTempVariable()
    {
        $public_key = (string) input('get.public_key');
        $private_key = (string) input('get.private_key');
        $key = (string) input('get.key');
        $destory = (string) input('get.destory');

        if ($public_key !== VALUE_PUBLIC_KEY) {
            return $this->errorResponse();
        }

        try {
            Db::startTrans();

            $data = Db::table('temp_value')
                ->where('t_key', $key)
                ->where('private_key', $private_key)
                ->find();

            if (!$data) {
                Db::commit();
                return $this->errorResponse();
            }

            if (!empty($data['once']) || $destory === '1') {
                Db::table('temp_value')->where('id', $data['id'])->delete();
            }

            Db::commit();
        } catch (DbException $e) {
            Db::rollback();
            return $this->errorResponse(-2);
        }

        $this->checkAndClean();

        return json_encode($data);
    }

    public function clean()
    {
        return $this->checkAndClean() ? "1" : "0";
    }

    public function checkAndClean()
    {
        try {
            Db::startTrans();
            Db::table('temp_value')
                ->where('expiration_date', '<', date('Y-m-d H:i:s'))
                ->delete();
            Db::commit();
        } catch (DbException $e) {
            Db::rollback();
            return false;
        }

        return true;
    }

    private function errorResponse(int $code = -1): string
    {
        return json_encode([
            'id' => $code,
            't_key' => 'null',
            't_value' => 'null',
            'expiration_date' => '2099-99-99 00:00:00',
            'private_key' => 'null',
            'once' => 0,
        ]);
    }
}
