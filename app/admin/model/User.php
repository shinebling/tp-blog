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
        try { 
            $data = Db::table('users')
                ->field('id,account,nickname,avatar,password')
                ->where('id',$userId)
                ->find();
            return [true, $data];
        } catch (\DataNotFoundException $e) {
            return [false, $e->getMessage];
        }
    }

    public function getUserInfoByAccount($account)
    {
        $data = Db::table('users')
            ->where('account',$account)
            ->find();
        return $data;
    }

    public function editUserInfo($userId, $param)
    {
        try { 
            $updateData = [];
            switch ($param) {
                case 'avatar':
                    if (!empty($param['avatar'])) {
                        $updateData['avatar'] = $param['avatar'];
                    }
                    break;
                case 'nickname':
                    if (!empty($param['nickname'])) {
                        $updateData['nickname'] = $param['nickname'];
                    }
                    break;
                case 'password':
                    if (!empty($param['password'])) {
                        $updateData['password'] = Auth::getMd5($param['password']);
                    }
                    break;
            }
            if (!empty($updateData)) {
                Db::name('users')
                    ->where('id', $userId)
                    ->update($updateData);
            }
        } catch (\DataNotFoundException $e) {
            return [false, $e->getMessage];
        }
        return [true];
    }
}