<?php
namespace app\admin\model;

use think\Model;
use think\facade\Db;
use think\facade\Log;
use app\admin\model\Category as CategoryModel;
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
            list($dealRet, $response) =  (new CategoryModel)->getDefaultCategory($userId);
            if (!$dealRet) {
                return ajaxReturn(false, $response);
            }
            $insertData['id'] = $this->getArticleId();
            $insertData['title'] = '未命名文章|'.$insertData['id'];
            $insertData['userId'] = $userId;
            $insertData['categoryId'] = $response;
            $insertData['createdAt'] = date('Y-m-d H:i:s',time());
            $insertData['updatedAt'] = date('Y-m-d H:i:s',time());
            $articleId = Db::name('article')->insert($insertData);
        } catch (\DataNotFoundException $e) {
            return [false, $e->getMessage()];
        }
        return [true, $insertData['id']];
    }

    public function getArticleId() {
        return date('YmdH') . mt_rand(1000, 9999);
    }

    // 获取文章列表
    public function getArticleList($userId, $param)
    {
        try {
            Log::info('查询开始');


            $where = [
                ['userId', '=', $userId],
                ['isDel','=', 0],
            ];
            if (!empty($param['searchField'])) {
                $where[] =  ['title', 'like', '%' . $param['searchField'] . '%'];
            }

            $pageSize = empty($param['pageSize']) ? 10 : $param['pageSize'];
            $currentPage = empty($param['currentPage']) ? 1 : $param['currentPage'];

            $count = 0;
            $data = [];
            $count = Db::table('article')
                ->where([$where])
                ->count();

            $data = Db::table('article')
                ->field('id,title,isPrivate,isOrigin,isDraft,categoryId,
                         description,createdAt,updatedAt,tags')
                ->where([$where])
                ->order('updatedAt', 'desc')
                ->order('createdAt', 'desc')
                ->page($currentPage,$pageSize)
                ->select()
                ->toArray();

            if (empty($data)) {
                return [true, ['list'=>$data,'total'=>$count]];
            }

            foreach ($data as &$item) {
                if (empty($item['categoryId'])) {
                    $item['category'] = '';
                } else {
                    $categoryName = Db::table('category')->where('id', $item['categoryId'])->value('name');
                    $item['category'] = $categoryName;
                }
                $item['tags'] = empty($item['tags']) ? [] : array_values(json_decode($item['tags'], true));

            }
            Log::info('查询结束');
            return [true, ['list'=>$data,'total'=>$count]];
        } catch (\DataNotFoundException $e) {
            return [false, $e->getMessage()];
        } catch (\Exception $e) {
            return [false, $e->getMessage()];
        }
    }

    public function delArticle($articleId) {
        try {
            Db::name('article')
                ->where('id', $articleId)
                ->update(['isDel' => 1]);
            return [true, ''];
        } catch (\DataNotFoundException $e) {
            return [false, $e->getMessage()];
        }
    }

    public function getArticleDetail($articleId) {
        try {
            $articleDetail = [];
            $articleDetail = Db::name('article')
                ->field('id,content draft, title, categoryId, createdAt, updatedAt, tags,
                         description, draftContent as draft, isOrigin, isPrivate, isDraft')
                ->where('id', $articleId)
                ->find();

            if (empty($articleDetail)) {
                return [false, '该文章ID不存在'];
            }
            $articleDetail['draft'] = html_entity_decode($articleDetail['draft']);
            if (empty($articleDetail['categoryId'])) {
                $articleDetail['category'] = '';
            } else {
                $categoryName = Db::table('category')->where('id', $articleDetail['categoryId'])->value('name');
                $articleDetail['category'] = $categoryName;
            }
            $articleDetail['tags'] = empty($articleDetail['tags']) ? [] : array_values(json_decode($articleDetail['tags'], true));
        } catch (\DataNotFoundException $e) {
            return [false, $e->getMessage];
        }
        return [true, $articleDetail];
    }

    public function editArticle($articleId, $param) {
        try {
            $updateParam = [];
            foreach ($param as $key => &$item) {
                switch ($key) {
                    case 'categoryId':
                        $updateParam['categoryId'] = empty($item) ? null : $item;
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
                    case 'draft':
                        $param['draft'] = $item;
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
                $updateParam['content'] = $param['draft'];
                $updateParam['draftContent'] = $param['draft'];
                Db::name('article')
                    ->where('id', $articleId)
                    ->update($updateParam);
            }
            return [true, ''];
        } catch (\DataNotFoundException $e) {
            return [false, $e->getMessage];
        }
    }
}