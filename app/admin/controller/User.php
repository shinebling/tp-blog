<?php
namespace app\admin\controller;

use think\Request;
use app\util\Token;
use app\admin\model\User as UserModel;

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
            $userInfo = (new UserModel)->getUserInfoById($userId);
            $userInfo['avatar'] = $userInfo['avatar'];
            return ajaxReturn(SUCCESS,'',$userInfo);
        } catch (\Exception $e) {
            return ajaxReturn(ERR_CODE_GET_USER_INFO,$e->getMessage());
        }
        return ajaxReturn(SUCCESS,'',[$userInfo]);
    }
}
