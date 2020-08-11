<?php
namespace app\admin\model;

use think\Model;
use think\facade\Db;

class Admin extends Model
{
    // 设置当前模型对应的完整数据表名称
    protected $table = 'article';
    public function getStatisticData($userId){
        try {
            $where['userId'] = $userId;
            $where['isDel'] = 0;
            $data['articleCount'] = Db::table('article')->where($where)->count();
            $data['categoryCount'] = Db::table('category')->where($where)->count();
            return ['code'=> 0, 'data'=>$data];
        } catch (\DataNotFoundException $e) {
            return ['code'=>-1, 'msg'=>$e->getMessage];
        }
    }
}
