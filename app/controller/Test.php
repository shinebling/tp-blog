<?php
namespace app\controller;

class Test
{
    public function test()
    {
        $avatorPath = public_path().'public\static\avator.png';
        return ajaxReturn(SUCCESS,$avatorPath);
    }
}