<?php
namespace app\admin\validate;

use think\Validate;
use app\admin\service\Auth;

class UserValidate extends Validate
{
    protected $rule = [
        'account' => 'require|unique:users|length:4,18',
        'oldPassword' => 'require|length:4,18',
        'password' => 'require|length:4,18',
        'confirmPassword' => 'require|length:4,18|confirm:password',
    ];
    //定义提示信息
    protected $message = [
        'account.require' => '账号不能为空',
        'account.length' => '账号长度应为4~18字符',
        'account.unique' => '账号已存在',
        'password.require' => '新密码不能为空',
        'password.length' => '新密码长度应为4~18字符',
        'oldPassword.require' => '旧密码不能为空',
        'oldPassword.length' => '旧密码长度应为4~18字符',
        'confirmPassword.require' => '确认新密码不能为空',
        'confirmPassword.length' => '确认新密码应为4~18字符',
        'confirmPassword.confirm' => '两次输入的密码不匹配',
    ];

    protected $scene = [
        'edit'  =>  ['account'],
        'editPassword'  =>  ['oldPassword','password','confirmPassword'],
    ];

}