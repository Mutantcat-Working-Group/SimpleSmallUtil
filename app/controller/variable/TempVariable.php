<?php

namespace app\controller\variable;

use think\facade\Db;

include dirname(__DIR__, 2) . '/common.php';

class TempVariable
{
    public function index()
    {
        return date('Y-m-d H:i:s');
    }

    public function addTempVariable()
    {
        // 获得请求参数
        $public_key = input('get.public_key');
        $private_key = input('get.private_key');
        $key = input('get.key');
        $value = input('get.value');
        // 传入为时间毫秒数 表示有效的时间
        $expiration_date = input('get.expiration_date');
        // 是否是一次性的 0为否 1为是
        $once = input('get.once');

        // 缺一不可 缺就反回默认-1
        if (!$public_key || !$private_key || !$key || !$value || !$expiration_date) {
            return '{"id":-1,"t_key":"null","t_value":"null","expiration_date":"2099-99-99 00:00:00","private_key":"null","once":0}';
        }

        if ($once != 0 && $once != 1) {
            return '{"id":-1,"t_key":"null","t_value":"null","expiration_date":"2099-99-99 00:00:00","private_key":"null","once":0}';
        }

        if ($public_key != VALUE_PUBLIC_KEY) {
            return '{"id":-1,"t_key":"null","t_value":"null","expiration_date":"2099-99-99 00:00:00","private_key":"null","once":0}';
        }

        // 根据毫秒计算过期时间
        if ($expiration_date != 0) {
            $expiration_date = date('Y-m-d H:i:s', time() + $expiration_date / 1000);
        }

        try {
            // 开启事务
            Db::startTrans();

            // 试着找到私钥和key都一样的
            $data = Db::table('temp_value')->where('t_key', $key)->where('private_key', $private_key)->find();

            if ($data) {
                if ($expiration_date != 0) {
                    $data = Db::table('temp_value')->where('t_key', $key)->update([
                        't_value' => $value,
                        'expiration_date' => $expiration_date,
                        'once' => $once
                    ]);
                } else {
                    // 若过期时间为0 则不更新过期时间
                    $data = Db::table('temp_value')->where('t_key', $key)->update([
                        't_value' => $value,
                        'once' => $once
                    ]);
                }
            } else {
                $data = Db::table('temp_value')->insert([
                    't_key' => $key,
                    't_value' => $value,
                    'expiration_date' => $expiration_date,
                    'private_key' => $private_key,
                    'once' => $once
                ]);
            }

            // 提交事务
            Db::commit();
        } catch (DbException $e) {
            // 回滚事务
            Db::rollback();
            // 处理异常，如果操作失败，返回-2
            return '{"id":-2,"t_key":"null","t_value":"null","expiration_date":"2099-99-99 00:00:00","private_key":"null","once":0}';
        }

        // 上述代码若执行成功 则检查并清除
        $this->checkAndClean();

        return json_encode($data);
    }

    public function getTempVariable()
    {
        // 获得请求参数
        $public_key = input('get.public_key');
        $private_key = input('get.private_key');
        $key = input('get.key');
        $destory = input('get.destory');

        if ($public_key != VALUE_PUBLIC_KEY) {
            return '{"id":-1,"t_key":"null","t_value":"null","expiration_date":"2099-99-99 00:00:00","private_key":"null","once":0}';
        }

        try {
            // 开启事务
            Db::startTrans();

            // 查询数据是否存在
            $data = Db::table('temp_value')->where('t_key', $key)->where('private_key', $private_key)->find();

            // 检查是否为空
            if (!$data) {
                return '{"id":-1,"t_key":"null","t_value":"null","expiration_date":"2099-99-99 00:00:00","private_key":"null","once":0}';
            }

            // 检查是否为一次性
            if ($data['once'] == 1 || $destory == 1) {
                // 删除数据
                Db::table('temp_value')->where('id', $data['id'])->delete();
            }

            // 提交事务
            Db::commit();
        } catch (DbException $e) {
            // 回滚事务
            Db::rollback();
            // 处理异常，如果操作失败，返回-2
            return '{"id":-2,"t_key":"null","t_value":"null","expiration_date":"2099-99-99 00:00:00","private_key":"null","once":0}';
        }

        // 上述代码若执行成功 则检查并清除
        $this->checkAndClean();

        return json_encode($data);
    }

    // 主动触发清除过期变量
    public function clean()
    {
        return $this->checkAndClean() ? "1" : "0";
    }

    // 检查并清除过期的变量
    public function checkAndClean()
    {
        try {
            // 开启事务
            Db::startTrans();

            // 清除过期变量
            Db::table('temp_value')->where('expiration_date', '<', date('Y-m-d H:i:s'))->delete();

            // 提交事务
            Db::commit();
        } catch (DbException $e) {
            // 回滚事务
            Db::rollback();
            // 处理异常，如果操作失败，返回-2
            return false;
        }

        return true;
    }
}