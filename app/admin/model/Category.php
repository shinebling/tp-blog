<?php
namespace app\admin\model;

use think\Model;
use think\facade\Db;
use think\facade\Log;
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

    const CATEGORY_DEFAULT_STATUS   = 1;                // 分类默认状态

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
            return [false, $e->getMessage()];
        }
    }

    public function getCategoryInfoById($categoryId) 
    {
        try { 
            $ret = Db::table('category')->where('id', $categoryId)->find();
            return [true, $ret];
        } catch (\DataNotFoundException $e) {
            return [false, $e->getMessage()];
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
                    ->field('c.id,c.name,c.description,c.isDefault,c.createdAt,c.updatedAt,count(c.id) as count')
                    ->join('article a','c.id = a.categoryId')
                    ->where($where)
                    ->group('a.categoryId')
                    ->order('count', 'desc')
                    ->order('c.updatedAt', 'desc')
                    ->select()
                    ->toArray();

            $categoryIds = array_column($data, 'id');

            foreach ($count as $k => &$item) {
                if (!in_array($item['id'], $categoryIds)) {
                    $item['count'] = 0;
                    $data[] = $item;
                }
            }

            return [true, ['list'=>$data,'total'=>count($count)]];
        } catch (\DataNotFoundException $e) {
            return [fasle, $e->getMessage()];
        }
    }

    public function delCategory($categoryId) {
        try {
            $categoryInfo = $this->getCategoryInfoById($categoryId);
            if ($categoryInfo['isDefault'] == 1) {
                return [fasle, '默认分类不可删除'];
            }
            Db::name('category')
                ->where('id', $categoryId)
                ->update(['isDel' => 1]);
            return [true, ''];
        } catch (\DataNotFoundException $e) {
            return [fasle, $e->getMessage()];
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
            return [fasle, $e->getMessage()];
        }
    }

    public function createDefaultCategory($userId) 
    {
        try { 
            $insertData['userId'] = $userId;
            $insertData['name'] = '默认分类';
            $insertData['description'] = '这是一个默认分类';
            $insertData['isDefault'] = 1;
            $data = ['foo' => 'bar', 'bar' => 'foo'];
            $id = Db::name('category')->insertGetId($insertData);
            return [true, $id];
        } catch (\DataNotFoundException $e) {
            return [false, $e->getMessage()];
        }
    }

    public function getDefaultCategory($userId) 
    {
        try { 
            $categoryInfo = Db::table('category')
                            ->where([
                                'isDefault' => Category::CATEGORY_DEFAULT_STATUS,
                                'userId'    => $userId
                            ])
                            ->find();
            if (!empty($categoryInfo)) {
                Log::info('已存在默认分类');
                return [true ,$categoryInfo['id']];
            } else {
                list($dealRet, $response) = $this->createDefaultCategory($userId);
                Log::info('不存在默认分类'.json_decode($response));
                if (!$dealRet) {
                    return [false, $response];
                }
                return [true ,$response];
            }
        } catch (\DataNotFoundException $e) {
            return [false, $e->getMessage()];
        }
    }
}