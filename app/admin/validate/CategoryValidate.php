<?php
namespace app\admin\validate;

use think\Validate;

class CategoryValidate extends Validate
{
    protected $rule =   [
        'name'  => 'require|max:25|unique:category,userId^name',
    ];
    
    protected $message  =   [
        'name.require' => '分类名称不能为空',
        'name.unique'  => '分类名称已存在',
        'id.unique'  => '分类id不能为空',
    ];
    
    protected $scene = [
        'create'  =>  ['name'],
    ];    

    // edit 验证场景定义
    public function sceneEdit()
    {
        return $this->only(['name'])
            ->remove('name', 'unique')
            ->append('id', 'require');
    }    
}