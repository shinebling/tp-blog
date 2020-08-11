<?php
namespace app\admin\model;

use think\Model;
use think\facade\Db;
use think\model\concern\SoftDelete;

class User extends Model
{
    use SoftDelete;
    protected $deleteTime = 'deleteTime';
    // 设置当前模型对应的完整数据表名称
    protected $table = 'users';
    // 定义时间戳字段名
    protected $createTime = 'createdAt';
    protected $updateTime = 'updatedAt';

    public function getUserInfoById($userId)
    {
        $data = Db::table('users')
            ->field('id,nickname,avatar,account')
            ->where('id',$userId)
            ->find();
        return $data;
    }

    public function getUserInfoByAccount($account)
    {
        $data = Db::table('users')
            ->where('account',$account)
            ->find();
        return $data;
    }
}