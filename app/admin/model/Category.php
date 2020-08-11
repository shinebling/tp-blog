<?php
namespace app\admin\model;

use think\Model;
use think\facade\Db;
use think\model\concern\SoftDelete;

class Category extends Model
{
    use SoftDelete;
    protected $deleteTime = 'deleteTime';
    // 设置当前模型对应的完整数据表名称
    protected $table = 'category';
    // 定义时间戳字段名
    protected $createTime = 'createdAt';
    protected $updateTime = 'updatedAt';

    public function createCategory($userId, $param) 
    {
        try { 
            $insertData['userId'] = $userId;
            $insertData['name'] = $param['name'];
            $insertData['description'] = !empty($param['description']) ? $param['description'] : '';
            $id = Db::name('category')->insertGetId($insertData);
            return [true, $id];
        } catch (\DataNotFoundException $e) {
            return [false, $e->getMessage];
        }
    }

    public function getCategoryInfoById($categoryId) 
    {
        try { 
            $ret = Db::table('category')->where('id', $categoryId)->find();
            return [true, $ret];
        } catch (\DataNotFoundException $e) {
            return [false, $e->getMessage];
        }
    }

    // 获取文章列表
    public function getCategoryList($userId, $param)
    {
        try {
            $count = Db::table('category')
                ->where('userId', $userId)
                ->where('id','<>',1)
                ->where('isDel',0)
                ->count();

            $data = Db::table('category')
                ->where('userId', $userId)
                ->field('id,name,description,createdAt,updatedAt')
                ->where('id','<>',1)
                ->where('isDel',0)
                ->order('updatedAt', 'desc')
                ->select()
                ->toArray();
            foreach ($data as $k => &$item) {
                $articleCount = Db::name('article')
                                    ->where('userId', $userId)
                                    ->where('categoryId', $item['id'])
                                    ->count();
                $item['count'] = $articleCount;
            }
            return [true, ['list'=>$data,'total'=>$count]];
        } catch (\DataNotFoundException $e) {
            return [fasle, $e->getMessage];
        }
    }

    public function delCategory($categoryId) {
        try {
            Db::name('category')
                ->where('id', $categoryId)
                ->update(['isDel' => 1]);
            return [true, ''];
        } catch (\DataNotFoundException $e) {
            return [fasle, $e->getMessage];
        }
    }

    public function editCategory($param) {
        try {
            $updateData['name'] = $param['name'];
            $updateData['description'] = !empty($param['description']) ? $param['description'] : '';
            Db::name('category')
                ->where('id', $param['id'])
                ->update($updateData);
            return [true, ''];
        } catch (\DataNotFoundException $e) {
            return [fasle, $e->getMessage];
        }
    }
}