<?php
namespace app\controller;

use think\Request;
use app\util\Token;
use app\model\User as UserModel;

class User
{
    /**
     * 构造方法
     * @param Request $request Request对象
     * @access public
     */
    public function __construct(Request $request)
    {
        $this->header = $request->header();
    }

    public function getUserInfo()
    {
        try {
            if (!isset($this->header['token'])) {
                return ajaxReturn('ERR_CODE_LOGIN_OVERDUE');
            }
            $token = $this->header['token'];
            $userId = Token::getTokenInfo($token);
            $userInfo = (new UserModel)->getUserInfo($userId);
            $userInfo['avatar'] = public_path()."public\static". '\\'.$userInfo['avatar'];
            return ajaxReturn(SUCCESS,'',[$userInfo]);
        } catch (\Exception $e) {
            return ajaxReturn(ERR_CODE_GET_USER_INFO,$e->getMessage());
        }
        return ajaxReturn(SUCCESS,'',[$userInfo]);
    }
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        //
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        //
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        //
    }

    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read($id)
    {
        //
    }

    /**
     * 显示编辑资源表单页.
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * 保存更新的资源
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        //
    }
}
