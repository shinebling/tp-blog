<?php
namespace app\admin\controller;

use think\Request;
use app\util\Token;
use app\admin\model\Article as ArticleModel;

class Article
{
   protected $request;
    /**
     * 构造方法
     * @param Request $request Request对象
     * @access public
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->header = $request->header();
    }

    public function initArticle()
    {
        try {
            if (!isset($this->header['token'])) {
                return ajaxReturn('ERR_CODE_LOGIN_OVERDUE');
            }
            $token = $this->header['token'];
            $userId = Token::getTokenInfo($token);
            $initArticleRet = (new ArticleModel)->initArticle($userId);
            if ($initArticleRet['code'] != 0){
                return ajaxReturn(ERR_CODE_INIT_ARTICLE,$initArticleRet['msg']);
            }
        } catch (\Exception $e) {
            return ajaxReturn(ERR_CODE_INIT_ARTICLE,$e->getMessage());
        }
        return ajaxReturn(SUCCESS,'',['id'=>$initArticleRet['data']]);
    }

    public function createArticle()
    {
        try {
            $articleId = Token::getTokenInfo($token);
            $ret = (new ArticleModel)->createArticle(Request::param());
            return ajaxReturn(SUCCESS,'',[$articleList]);
        } catch (\Exception $e) {
            return ajaxReturn(ERR_CODE_GET_ARTICLE_INFO,$e->getMessage());
        }
        return ajaxReturn(SUCCESS,'',[$articleList]);
    }

    public function getArticleList()
    {
        try {
            if (!isset($this->header['token'])) {
                return ajaxReturn('ERR_CODE_LOGIN_OVERDUE');
            }
            $token = $this->header['token'];
            $userId = Token::getTokenInfo($token);
            list($dealRet, $articleList) = (new ArticleModel)->getArticleList($userId, $this->request->param());
            if (!$dealRet) {
                return ajaxReturn(ERR_CODE_GET_ARTICLE, $articleList);
            }
        } catch (\Exception $e) {
            return ajaxReturn(ERR_CODE_GET_ARTICLE,$e->getMessage());
        }
        return ajaxReturn(SUCCESS,'',$articleList);
    }

    public function delArticle()
    {
        try {
            if (!isset($this->header['token'])) {
                return ajaxReturn('ERR_CODE_LOGIN_OVERDUE');
            }
            $token = $this->header['token'];
            $userId = Token::getTokenInfo($token);
            list($dealRet, $articleList) = (new ArticleModel)->delArticle($this->request->param('id'));
            if (!$dealRet) {
                return ajaxReturn(ERR_CODE_DEL_ARTICLE, $articleList);
            }
        } catch (\Exception $e) {
            return ajaxReturn(ERR_CODE_DEL_ARTICLE, $e->getMessage());
        }
        return ajaxReturn(SUCCESS,'',$articleList);
    }

    public function getArticleInfo()
    {
        try {
            if (!isset($this->header['token'])) {
                return ajaxReturn('ERR_CODE_LOGIN_OVERDUE');
            }
            $token = $this->header['token'];
            $articleId = Token::getTokenInfo($token);
            $articleInfo = (new ArticleModel)->getArticleInfo($articleId);
            $articleInfo['avatar'] = public_path()."public\static". '\\'.$articleInfo['avatar'];
            return ajaxReturn(SUCCESS,'',[$articleInfo]);
        } catch (\Exception $e) {
            return ajaxReturn(ERR_CODE_GET_ARTICLE_INFO,$e->getMessage());
        }
        return ajaxReturn(SUCCESS,'',[$articleInfo]);
    }
}
