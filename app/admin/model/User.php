<?php
namespace app\admin\model;

use think\Model;
use think\facade\Db;
use think\facade\Log;
use app\admin\service\Auth;
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
            return [false, $e->getMessage()];
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
            foreach ($param as $key => $item) {
                switch ($key) {
                    case 'avatar':
                        if (!empty($item)) {
                            $updateData['avatar'] = $item;
                        }
                        break;
                    case 'nickname':
                        if (!empty($item)) {
                            $updateData['nickname'] = $item;
                        }
                        break;
                    case 'password':
                        if (!empty($item)) {
                            $updateData['password'] = Auth::getMd5($item);
                        }
                        break;
                }
            }
            if (!empty($updateData)) {
                Db::name('users')
                    ->where('id', $userId)
                    ->update($updateData);
            }
        } catch (\DataNotFoundException $e) {
            return [false, '111'.$e->getMessage()];
        }
        return [true, ''];
    }
}