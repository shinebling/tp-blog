<?php
// 回复信息配置
// 统一的管理项目操作过程中需要返回用户信息的 内容
// 数据库
return [
    'code' => [
        'SUCCESS'                     => 0,
        'ERROR_PARAM'                 => -1,
        // register错误信息
        'ERROR_REGISTER'              => -1001,
    ],
    'info' => [
        1     => '操作成功',
        -1    => '参数错误',
        -1001 => '注册错误',
    ]
];
