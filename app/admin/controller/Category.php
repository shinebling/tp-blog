<?php
namespace app\admin\controller;

use think\Request;
use app\util\Token;
use app\admin\model\Category as CategoryModel;
use app\admin\validate\CategoryValidate;

class Category
{
    protected $request;
    protected $header;
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

    public function getCategoryInfoById()
    {
        try {
            list($dealRet, $CategoryInfo) =  (new CategoryModel)->getCategoryInfoById($this->request->param('id'));
            if (!$dealRet) {
                return ajaxReturn(ERR_CODE_CREATE_CATEGORY, $CategoryInfo);
            }
        } catch (\Exception $e) {
            return ajaxReturn(ERR_CODE_GET_CATEGORY_INFO,$e->getMessage());
        }
        return ajaxReturn(SUCCESS,'', $CategoryInfo);
    }

    public function createCategory()
    {
        try {
        	$userId = $this->request->userId;
            validate(CategoryValidate::class)->scene('create')->check($this->param);
            list($dealRet, $CategoryList) = (new CategoryModel)->createCategory($userId, $this->param);
            if (!$dealRet) {
                return ajaxReturn(ERR_CODE_CREATE_CATEGORY, $CategoryList);
            }
        } catch (\ValidateException $e) {
            return ajaxReturn(ERR_CODE_CREATE_CATEGORY, $e->getMessage());
        } catch (\Exception $e) {
            return ajaxReturn(ERR_CODE_CREATE_CATEGORY, $e->getMessage());
        }
        return ajaxReturn(SUCCESS);
    }

    public function getCategoryList()
    {
        try {
            $userId = $this->request->userId;
            list($dealRet, $CategoryList) = (new CategoryModel)->getCategoryList($userId, $this->request->param());
            if (!$dealRet) {
                return ajaxReturn(ERR_CODE_GET_CATEGORY, $CategoryList);
            }
        } catch (\Exception $e) {
            return ajaxReturn(ERR_CODE_GET_CATEGORY,$e->getMessage());
        }
        return ajaxReturn(SUCCESS,'',$CategoryList);
    }

    public function delCategory()
    {
        try {
            list($dealRet, $CategoryList) = (new CategoryModel)->delCategory($this->request->param('id'));
            if (!$dealRet) {
                return ajaxReturn(ERR_CODE_DEL_CATEGORY, $CategoryList);
            }
        } catch (\Exception $e) {
            return ajaxReturn(ERR_CODE_DEL_CATEGORY, $e->getMessage());
        }
        return ajaxReturn(SUCCESS,'',$CategoryList);
    }

    public function editCategory()
    {
        try {
            validate(CategoryValidate::class)->scene('edit')->check($this->param);
            list($dealRet, $CategoryRet) = (new CategoryModel)->editCategory($this->param);
            if (!$dealRet) {
                return ajaxReturn(ERR_CODE_CREATE_CATEGORY, $CategoryRet);
            }
        } catch (\ValidateException $e) {
            return ajaxReturn(ERR_CODE_CREATE_CATEGORY, $e->getMessage());
        } catch (\Exception $e) {
            return ajaxReturn(ERR_CODE_CREATE_CATEGORY, $e->getMessage());
        }
        return ajaxReturn(SUCCESS);
    }
}
