<?php
namespace app\model;

use think\Model;
use think\facade\Db;

class Login extends Model
{
    // 设置当前模型对应的完整数据表名称
    protected $table = 'users';
    // 设置字段信息
    protected $schema = [
        'createdAt' => 'timestamp',
        'updatedAt' => 'timestamp',
    ];

    public static function register($param)
    {
        try {
            $param['createdAt'] = time();
            Db::name('users')->insert($param);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
        return;
    }

    public static function saveEmailCaptcha($account, $captcha)
    {
         try {
            $param['createdAt'] = time();
            Db::name('users')
                ->where('account', $account)
                ->update(['emailCaptcha' => $captcha]);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
        return;
    }

}