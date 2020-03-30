<?php
namespace app\listener;

use think\Facade;
use think\Loader;

class LoaderFacade
{
    /**
     * 信息码注册
     */
    public function handle()
    {
        // 注册核心类的静态代理
        // Facade::bind(config('facade.facade'));
        // 注册类库别名
        Loader::addClassAlias(config('facade.alias'));
    }
}

