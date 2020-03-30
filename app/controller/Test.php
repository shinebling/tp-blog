<?php
namespace app\controller;

use ESearch;

class Test
{
    public function test()
    {
        $ret = ESearch::addIndex();
        return ajaxReturn(SUCCESS,$ret);
//        ESearch::addIndex();
    }
}