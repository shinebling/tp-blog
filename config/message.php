<?php
// 回复信息配置
return [
    'code' => [
        'SUCCESS'                                   => 0,
        'ERR_CODE_INTERFACE_ERROR'                  => -10000,
        'ERR_CODE_PARAM_ERROR'                      => -10001,
        'ERR_CODE_REGISTER'                         => -10002,
        'ERR_CODE_LOGIN'                            => -10003,
        'ERR_CODE_LOGIN_OVERDUE'                    => -10004,
    ],
    'info' => [
        0           => 'success',
        -10000       => '接口请求错误',
        -10001       => '参数错误',
        -10002       => '注册错误',
        -10003       => '登录错误',
        -10004       => '登录过期，请重新登录',
    ]
];
