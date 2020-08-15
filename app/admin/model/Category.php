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

    const CATEGORY_DEL              = 1;                // 已删除
    const CATEGORY_NOT_DEL          = 0;                // 未删除

    const CATEGORY_LIMIT_NUM        = 30;               // 最多可添加分类个数

    public function createCategory($userId, $param) 
    {
        try { 
            $count = Db::table('category')
                ->alias('c')
                ->where([
                    'userId' => $userId,
                    'isDel'  => Category::CATEGORY_NOT_DEL,
                ])
                ->where('id','<>', 1)
                ->count();
            if ($count >= Category::CATEGORY_LIMIT_NUM) {
                return [false, '最多可添加'.Category::CATEGORY_LIMIT_NUM.'个分类'];
            }
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
            $where = [
                'c.userId' => $userId,
                'c.isDel'  => Category::CATEGORY_NOT_DEL,
            ];

            $count = Db::table('category')
                ->alias('c')
                ->field('c.id,c.name,c.description,c.createdAt,c.updatedAt')
                ->where($where)
                ->where('id','<>', 1)
                ->order('updatedAt', 'desc')
                ->select()
                ->toArray();

            $data = Db::table('category')
                    ->alias('c')
                    ->field('c.id,c.name,c.description,c.createdAt,c.updatedAt,count(c.id) as num')
                    ->join('article a','c.id = a.categoryId')
                    ->where($where)
                    ->group('a.categoryId')
                    ->order('num', 'desc')
                    ->order('c.updatedAt', 'desc')
                    ->select()
                    ->toArray();

            $categoryIds = array_column($data, 'id');

            foreach ($count as $k => &$item) {
                if (!in_array($item['id'], $categoryIds)) {
                    $item['num'] = 0;
                    $data[] = $item;
                }
            }

            return [true, ['list'=>$data,'total'=>count($count)]];
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