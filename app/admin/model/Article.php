<?php
namespace app\admin\model;

use think\Model;
use think\facade\Db;
use think\facade\Log;
use think\model\concern\SoftDelete;

class Article extends Model
{
    use SoftDelete;
    protected $deleteTime = 'deleteTime';
    // 设置当前模型对应的完整数据表名称
    protected $table = 'article';
    // 定义时间戳字段名
    protected $createTime = 'createdAt';
    protected $updateTime = 'updatedAt';

    // 初始化文章
    public function initArticle($userId){
        try {
            $insertData['id'] = $this->getArticleId();
            $insertData['title'] = '未命名文章|'.$insertData['id'];
            $insertData['userId'] = $userId;
            $insertData['createdAt'] = date('Y-m-d H:i:s',time());
            $insertData['updatedAt'] = date('Y-m-d H:i:s',time());
            $articleId = Db::name('article')->insert($insertData);
            return ['code'=> 0, 'data'=>$insertData['id']];
        } catch (\DataNotFoundException $e) {
            return ['code'=>-1, 'msg'=>$e->getMessage];
        }
    }

    public function getArticleId() {
        return date('YmdH') . mt_rand(1000, 9999);
    }

    // 获取文章列表
    public function getArticleList($userId, $param)
    {
        try {
            $where = [];
            $where['a.userId'] = $userId;
            $where['a.isDel'] = 0;
            // if (!empty($param['searchField'])) {
            //     $where['a.title'] = ['like','%' . $param['searchField'] . '%'];
            // }
            $pageSize = empty($param['pageSize']) ? 10 : $param['pageSize'];
            $currentPage = empty($param['currentPage']) ? 1 : $param['currentPage'];

            $count = Db::table('article')
                ->alias('a')
                ->field('a.id,a.title,a.isPrivate,a.isOrigin,c.name as category,
                         a.isDraft,a.description,a.createdAt,a.updatedAt,a.tags')
                ->where($where)
                ->where('a.title', 'like', '%' . $param['searchField'] . '%')
                ->join('category c','a.categoryId = c.id')
                ->count();

            $data = Db::table('article')
                ->alias('a')
                ->field('a.id,a.title,a.isPrivate,a.isOrigin,c.name as category,
                         a.isDraft,a.description,a.createdAt,a.updatedAt,a.tags')
                ->where($where)
                ->where('a.title', 'like', '%' . $param['searchField'] . '%')
                ->join('category c','a.categoryId = c.id')
                ->order('a.updatedAt', 'desc')
                ->order('a.createdAt', 'desc')
                ->page($currentPage,$pageSize)
                ->select()
                ->toArray();

            foreach ($data as &$item) {
                $item['tags'] = empty($item['tags']) ? [] : array_values(json_decode($item['tags'], true));
            }
            return [true, ['list'=>$data,'total'=>$count]];
        } catch (\DataNotFoundException $e) {
            return [fasle, $e->getMessage];
        }
    }

    public function delArticle($articleId) {
        try {
            Db::name('article')
                ->where('id', $articleId)
                ->update(['isDel' => 1]);
            return [true, ''];
        } catch (\DataNotFoundException $e) {
            return [fasle, $e->getMessage];
        }
    }

    public function getArticleDetail($articleId) {
        try {
            $articleDetail = Db::name('article')
                ->alias('a')
                ->field('a.id,a.content draft, a.title, a.categoryId, c.name as category, a.createdAt, a.updatedAt, a.tags,
                         a.description, a.draftContent as content, a.isOrigin, a.isPrivate, a.isDraft')
                ->where('a.id', $articleId)
                ->join('category c','a.categoryId = c.id')
                ->find();
            if (empty($articleDetail)) {
                return [fasle, '该文章ID不存在'];
            }
            $articleDetail['tags'] = empty($articleDetail['tags']) ? '' : array_values(json_decode($articleDetail['tags'], true));
            return [true, $articleDetail];
        } catch (\DataNotFoundException $e) {
            return [fasle, $e->getMessage];
        }
    }

    public function editArticle($articleId, $param) {
        try {
            Log::info('editArticle---'.json_encode($param));
            $updateParam = [];
            foreach ($param as $key => $item) {
                switch ($key) {
                    case 'categoryId':
                        $updateParam['categoryId'] = $item;
                        break;
                    case 'title':
                        $updateParam['title'] = $item;
                        break;
                    case 'tags':
                        $updateParam['tags'] = json_encode($item);
                        break;
                    case 'description':
                        $updateParam['description'] = $item;
                        break;
                    case 'isDraft':
                        $updateParam['isDraft'] = $item ? 1 : 0;
                        break;
                    case 'isOrigin':
                        $updateParam['isOrigin'] = $item ? 1 : 0;
                        break;
                    case 'isPrivate':
                        $updateParam['isPrivate'] = $item ? 1 : 0;
                        break;
                }
            }

            if ($param['isDraft']) {
                // 存草稿
                $updateParam['draftContent'] = $param['draft'];
                Db::name('article')
                    ->where('id', $articleId)
                    ->update($updateParam);
            } else {
                // 编辑文章（更新）
                $updateParam['content'] = $param['content'];
                $updateParam['draftContent'] = $param['content'];
                Db::name('article')
                    ->where('id', $articleId)
                    ->update($updateParam);
            }
            return [true, ''];
        } catch (\DataNotFoundException $e) {
            return [fasle, $e->getMessage];
        }
    }
}