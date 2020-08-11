<?php
namespace app\admin\controller;

use think\facade\Db;
use app\util\ESearch;

class Test
{
    public function test()
    {
        $articles = DB::table('articles')->select();

//        $ret = ESearch::getInstance()->addDocument('articles', 'articles_type');
        foreach ($articles as $v){
            ESearch::getInstance()->addDocument('articles', 'articles_type', $v, $v['id']);
        }
        return ajaxReturn(SUCCESS,'',$articles);
//        ESearch::addIndex();
    }
}