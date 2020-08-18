<?php
namespace app\admin\controller;

use think\Request;
use app\util\Token;
use app\BaseController;
use app\admin\model\Admin as AdminModel;

class Admin extends BaseController
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
        $this->param = $this->request->param();
    }

    public function getStatisticData(){
    	try {
            $userId = $this->request->userId;
    		$ret = (new AdminModel)->getStatisticData($userId);
    		if ($ret['code'] != 0){
                return ajaxReturn(ERR_CODE_GET_INDEX_DATA,$ret['msg']);
            }
    	} catch (\Exception $e) {
    		return ajaxReturn(ERR_CODE_GET_INDEX_DATA,$e->getMessage());
    	}
    	return ajaxReturn(SUCCESS,'',$ret['data']);
    }
}
