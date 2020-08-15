<?php
namespace app\admin\validate;

use think\Validate;

class ArticleValidate extends Validate
{
    protected $rule = [
        'id' => 'require',
        'title' => 'require|max:150',
    ];
    
    //定义提示信息
    protected $message = [
        'id.require' => '文章ID不能为空',
        'title.require' => '文章标题不能为空',
        'title.max' => '文章标题不能超过150个字符',
    ];

    protected $scene = [
        'edit'  =>  ['id'],
    ];
}