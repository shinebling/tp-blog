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

// admin
// Route::get('statistics', 'app\admin\controller\Admin@getStatisticData')->allowCrossDomain();
// Route::post('register', 'app\admin\controller\Login@register')->allowCrossDomain();
// Route::post('login', 'app\admin\controller\Login@login')->allowCrossDomain();


// // 文章
// Route::get('init_article', 'app\admin\controller\Article@initArticle')->allowCrossDomain();
// Route::get('create_article', 'app\admin\controller\Article@initArticle')->allowCrossDomain();
// Route::get('article_list', 'app\admin\controller\Article@getArticleList')->allowCrossDomain();
// Route::get('article_detail', 'app\admin\controller\Article@getArticleDetail')->allowCrossDomain();
// Route::get('delete_article', 'app\admin\controller\Article@delArticle')->allowCrossDomain();
// Route::post('edit_article', 'app\admin\controller\Article@editArticle')->allowCrossDomain();

// // 分类
// Route::get('category_list', 'app\admin\controller\Category@getCategoryList')->allowCrossDomain();
// Route::get('delete_category', 'app\admin\controller\Category@delCategory')->allowCrossDomain();
// Route::post('create_category', 'app\admin\controller\Category@createCategory')->allowCrossDomain();
// Route::get('category_detail', 'app\admin\controller\Category@getCategoryInfoById')->allowCrossDomain();
// Route::post('edit_category', 'app\admin\controller\Category@editCategory')->allowCrossDomain();




// Route::get('userinfo', 'app\admin\controller\User@getUserInfo')->allowCrossDomain()->middleware('auth');
// Route::post('edit_userinfo', 'app\admin\controller\User@editUserInfo')->allowCrossDomain();
// Route::post('upload', 'app\admin\controller\User@uploadAvatar')->allowCrossDomain();
// Route::group('blog', function () {
//     Route::rule(':id', 'blog/read');
//     Route::rule(':name', 'blog/read');
// })->ext('html')->pattern(['id' => '\d+', 'name' => '\w+']);


// Route::group('blog', function () {
//     Route::get(':id', 'read');
//     Route::post(':id', 'update');
//     Route::delete(':id', 'delete');
// })->prefix('blog/')->ext('html')->pattern(['id' => '\d+']);



// Route::post('register', 'Login/register')->allowCrossDomain();
// Route::post('login', 'Login/login')->allowCrossDomain();
// //Route::post('user', 'Login/login')->middleware('auth')->allowCrossDomain();
// Route::post('sendcpatcha', 'Login/sendEmailCpatcha')->allowCrossDomain();
// Route::post('retrievepassword', 'Login/retrievePassword')->allowCrossDomain();
// //Route::post('sendcpatcha', 'Login/sendEmailCpatcha')->middleware('auth')->allowCrossDomain();


// Route::get('test', 'Test/test')->allowCrossDomain();
