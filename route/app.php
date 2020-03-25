<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
use think\facade\Route;

Route::get('think', function () {
    return 'hello,ThinkPHP6!';
});

Route::get('hello/:name', 'index/hello');
Route::post('register', 'Login/register')->allowCrossDomain();;
Route::post('login', 'Login/login')->allowCrossDomain();;
//Route::post('user', 'Login/login')->middleware('auth')->allowCrossDomain();;
Route::post('sendcpatcha', 'Login/sendEmailCpatcha')->allowCrossDomain();;
//Route::post('sendcpatcha', 'Login/sendEmailCpatcha')->middleware('auth')->allowCrossDomain();;
