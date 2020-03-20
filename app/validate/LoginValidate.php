<?php
namespace app\validate;

use think\Validate;

class LoginValidate extends Validate
{
    protected $rule = [
        'account' => 'require|unique:users|length:3,19',
        'password' => 'require|length:4,18',
        'confirmPassword' => 'require|length:4,18|confirm:password',
    ];
    //定义提示信息
    protected $message = [
        'account.require' => '账号不能为空',
        'account.length' => '账号长度应为4~18为字符',
        'account.unique' => '账号已存在',
        'password.require' => '密码不能为空',
        'password.length' => '密码长度应为4~18为字符',
        'confirmPassword.require' => '确认密码不能为空',
        'confirmPassword.length' => '确认密码应为4~18为字符',
        'confirmPassword.confirm' => '两次输入的密码不匹配',
    ];

}