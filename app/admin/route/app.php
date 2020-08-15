<?php
use think\facade\Route;

// admin
Route::group(function () {
	Route::get('statistics', 'getStatisticData');
})->prefix('app\admin\controller\Admin@')->allowCrossDomain()->middleware('auth');


// 用户
Route::group(function () {
	Route::post('register', 'register');
	Route::post('login', 'login');
})->prefix('app\admin\controller\Login@')->allowCrossDomain();

// 文章
Route::group(function () {
	Route::get('init_article', 'initArticle');
	Route::get('article_list', 'getArticleList');
	Route::get('article_detail', 'getArticleDetail');
	Route::get('delete_article', 'delArticle');
	Route::post('edit_article', 'editArticle');
})->prefix('app\admin\controller\Article@')->allowCrossDomain()->middleware('auth');

// 分类
Route::group(function () {
	Route::get('category_list', 'getCategoryList');
	Route::get('delete_category', 'delCategory');
	Route::post('create_category', 'createCategory');
	Route::get('category_detail', 'getCategoryInfoById');
	Route::post('edit_category', 'editCategory');
})->prefix('app\admin\controller\Category@')->allowCrossDomain()->middleware('auth');

// 用户
Route::group(function () {
    Route::get('userinfo', 'getUserInfo');
    Route::post('edit_userinfo', 'editUserInfo');
	Route::post('upload', 'uploadAvatar');
})->prefix('app\admin\controller\User@')->allowCrossDomain()->middleware('auth');