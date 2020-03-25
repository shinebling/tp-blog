<?php
// +----------------------------------------------------------------------
// | 邮件配置
// +----------------------------------------------------------------------

return [
    // 编码格式
    'charset'           => 'utf8',
    // 发送方的SMTP服务器地址
    'host'              => 'smtp.163.com',
    // 是否使用身份验证
    'smtp_auth'         => true,
    // 发送方的邮箱
    'user_name'         => 'shinexuanzhu@163.com',
    // 客户端授权密码
    'password'          => 'LBNRRQAITGOPPGAF',
    // 使用ssl协议方式
    'smtp_secure'       => 'ssl',
    // 使用ssl协议方式
    'port'              => 994,
    // 是否支持语言分组
    'allow_group'       => false,
    // 邮件标题
    'subject'           =>'xuanzhu密码找回title',
    // 邮件正文
    'body'              =>'您的验证码是 %s ，请在2分钟内填写。',
    // 验证码长度
    'captcha_length'    => 10,
    // 验证码字符范围
    'captcha_character' => 'abcdefghijkmnpqrstuvwxyABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789',
    // 验证码过期时间
    'captcha_exp'       => 7200,
];
