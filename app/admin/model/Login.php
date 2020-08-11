<?php
namespace app\admin\model;

use think\Model;
use think\facade\Db;
use think\model\concern\SoftDelete;

class Login extends Model
{
    use SoftDelete;
    protected $deleteTime = 'deleteTime';
    // 设置当前模型对应的完整数据表名称
    protected $table = 'users';
    // 定义时间戳字段名
    protected $createTime = 'createdAt';
    protected $updateTime = 'updatedAt';

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