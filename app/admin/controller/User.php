<?php
namespace app\admin\controller;

use think\Request;
use app\util\Token;
use app\admin\service\Auth;
use think\facade\Filesystem;
use app\admin\model\User as UserModel;
use app\admin\validate\UserValidate;

class User
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

    public function getUserInfo()
    {
        try {
            list($dealRet, $userInfo) =  (new UserModel)->getUserInfoById($this->request->userId);
            if (!$dealRet) {
                return ajaxReturn(ERR_CODE_GET_USER_INFO, $userInfo);
            }
            unset($userInfo['password']);
        } catch (\Exception $e) {
            return ajaxReturn(ERR_CODE_GET_USER_INFO,$e->getMessage());
        }
        return ajaxReturn(SUCCESS,'',$userInfo);
    }

    public function editUserInfo()
    {
        try {
            $userId = $this->request->userId;
            validate(UserValidate::class)->scene('edit')->check($this->param);
            $param = $this->param;
            if (!empty($param['oldPassword']) || !empty($param['password']) || !empty($param['confirmPassword'])) {
            	validate(UserValidate::class)->scene('editPassword')->check($this->param);
	            list($dealRet, $userInfo) =  (new UserModel)->getUserInfoById($userId);
	            if (!$dealRet) {
	                return ajaxReturn(ERR_CODE_GET_USER_INFO, $userInfo);
	            }
	            if (Auth::getMd5($this->param['password']) != $userInfo['password']) {
	                return ajaxReturn(ERR_CODE_EDIT_USER_INFO, '旧密码错误');
	            }
            }
            list($dealRet, $editRet) =  (new UserModel)->editUserInfo($userId, $this->param);
            if (!$dealRet) {
                return ajaxReturn(ERR_CODE_EDIT_USER_INFO, $editRet);
            }
        } catch (\ValidateException $e) {
            return ajaxReturn(ERR_CODE_EDIT_USER_INFO, $e->getMessage());
        } catch (\Exception $e) {
            return ajaxReturn(ERR_CODE_EDIT_USER_INFO,$e->getMessage());
        }
        return ajaxReturn(SUCCESS);
    }

    public function uploadAvatar()
    {
        try {
        	// 获取表单上传文件 例如上传了001.jpg
		    $file = $this->request->file('file');
		    // 上传到本地服务器
		    $savename = Filesystem::putFile('topic', $file);
		    $path = str_replace('\\/', '\\\\', 'runtime\\avatorfile\\'.$savename);
        } catch (\Exception $e) {
            return ajaxReturn(FAIL,$e->getMessage());
        }
        return ajaxReturn(SUCCESS,'',['url'=>$path]);
    }

}
