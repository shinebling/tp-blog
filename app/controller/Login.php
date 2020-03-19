<?php
namespace app\controller;

use app\BaseController;
use think\Request;
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

    public fucntion login()
    {
        $ret['code'] = 0;
        $ret['data'] = [];
        $ret['message'] = 'success';
        $user = LoginModel::where('account', $this->param['account'])->find();
        if (empty($user)) {
            $ret['code'] = -1;
            $ret['message'] = '用户不存在';
        } else {
            if(md5(md5($this->param['password'] != $user->password) {
                $ret['code'] = -1;
                $ret['message'] = '密码错误';
            }
        }
        return json_encode($ret, true);
    }

    public function register()
    {
        $ret['code'] = 0;
        $ret['data'] = [];
        $ret['message'] = 'success';
        try {
            validate(LoginValidate::class)->check($this->param);
            $this->param['password'] = md5(md5($this->param['password']));
            unset($this->param['confirmPassword']);
            try{
                $ret = (new LoginModel)->save($this->param);
                Session::set('account',$this->param['account']);
            } catch (\Exception $e) {
                $ret['code'] = -1;
                $ret['message'] = $e->getError();
            }
        } catch (ValidateException $e) {
            // 验证失败 输出错误信息
            $ret['code'] = -1;
            $ret['message'] = $e->getError();
        }
        return json_encode($ret, true);
    }
}
