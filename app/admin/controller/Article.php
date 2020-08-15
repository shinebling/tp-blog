<?php
namespace app\admin\controller;

use think\Request;
use app\util\Token;
use app\admin\model\Article as ArticleModel;
use app\admin\validate\ArticleValidate;

class Article
{
   protected $request;
   protected $param;
    /**
     * 构造方法
     * @param Request $request Request对象
     * @access public
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->header = $request->header();
        $this->param = trimParms($this->request->param());
    }

    public function initArticle()
    {
        try {
            $userId = $this->request->userId;
            $initArticleRet = (new ArticleModel)->initArticle($userId);
            if ($initArticleRet['code'] != 0){
                return ajaxReturn(ERR_CODE_INIT_ARTICLE,$initArticleRet['msg']);
            }
        } catch (\Exception $e) {
            return ajaxReturn(ERR_CODE_INIT_ARTICLE,$e->getMessage());
        }
        return ajaxReturn(SUCCESS,'',['id'=>$initArticleRet['data']]);
    }


    public function getArticleList()
    {
        try {
            $userId = $this->request->userId;
            list($dealRet, $response) = (new ArticleModel)->getArticleList($userId, $this->param);
            if (!$dealRet) {
                return ajaxReturn(ERR_CODE_GET_ARTICLE, $response);
            }
        } catch (\Exception $e) {
            return ajaxReturn(ERR_CODE_GET_ARTICLE,$e->getMessage());
        }
        return ajaxReturn(SUCCESS,'',$response);
    }

    public function delArticle()
    {
        try {
            $userId = $this->request->userId;
            list($dealRet, $articleList) = (new ArticleModel)->delArticle($this->request->param('id'));
            if (!$dealRet) {
                return ajaxReturn(ERR_CODE_DEL_ARTICLE, $articleList);
            }
        } catch (\Exception $e) {
            return ajaxReturn(ERR_CODE_DEL_ARTICLE, $e->getMessage());
        }
        return ajaxReturn(SUCCESS,'',$articleList);
    }

    public function getArticleDetail()
    {
        try {
            list($dealRet, $response) = (new ArticleModel)->getArticleDetail($this->request->param('id'));
            if (!$dealRet) {
                return ajaxReturn(ERR_CODE_GET_ARTICLE_DETAIL, $response);
            }
        } catch (\Exception $e) {
            return ajaxReturn(ERR_CODE_GET_ARTICLE_DETAIL,$e->getMessage());
        }
        return ajaxReturn(SUCCESS,'',$response);
    }

    public function editArticle()
    {
        try {
            validate(ArticleValidate::class)->scene('edit')->check($this->param);
            list($dealRet, $response) = (new ArticleModel)->editArticle($this->request->param('id'), $this->param);
            if (!$dealRet) {
                return ajaxReturn(ERR_CODE_SAVE_ARTICLE, $response);
            }
        } catch (\ValidateException $e) {
            return ajaxReturn(ERR_CODE_SAVE_ARTICLE, $e->getMessage());
        } catch (\Exception $e) {
            return ajaxReturn(ERR_CODE_SAVE_ARTICLE,$e->getMessage());
        }
        return ajaxReturn(SUCCESS);
    }
}
