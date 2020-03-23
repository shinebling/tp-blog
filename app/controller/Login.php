<?php
namespace app\controller;

use app\BaseController;
use think\Request;
use app\util\Token;
use app\service\Auth;
use app\model\Login as LoginModel;
use app\validate\LoginValidate;
use think\exception\ValidateException;

class Login extends BaseController
{
    /**
     * @var \think\Request Request实例
     */
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
		$this->param = $this->request->param();
    }

    public function login()
    {
        try {
            validate(LoginValidate::class)->scene('login')->check($this->param);
            $user = LoginModel::where('account', $this->param['account'])->find();
            if (empty($user)) {
                return ajaxReturn(ERR_CODE_LOGIN,'用户不存在');
            } else {
                if(Auth::getMd5($this->param['password']) != $user->password) {
                    return ajaxReturn(ERR_CODE_LOGIN,'密码不正确');
                }
            }
            $userToken = Token::createToken($user->id);
            LoginModel::update(['name' => 'thinkphp'], ['id' => 1]);
        } catch (ValidateException $e) {
            // 验证失败 输出错误信息
            return ajaxReturn(ERR_CODE_LOGIN,$e->getError());
        }
        return ajaxReturn(SUCCESS,'',['token'=>Token::createToken($user->id)]);
    }

    public function register()
    {
        try {
            validate(LoginValidate::class)->check($this->param);
            $this->param['password'] = Auth::getMd5($this->param['password']);
            unset($this->param['confirmPassword']);
            $ret = LoginModel::register($this->param);
            if (!empty($ret)) {
                return ajaxReturn(ERR_CODE_REGISTER,$ret);
            }
        } catch (ValidateException $e) {
            // 验证失败 输出错误信息
            return ajaxReturn(ERR_CODE_REGISTER,$e->getError());
        }
        return ajaxReturn(SUCCESS);
    }

    /*
    * 找回密码
    */
    public function retrievePassword()
    {

    }
}
