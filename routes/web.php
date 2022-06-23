<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
//前端操作
Route::get('search', 'UserController@search');
Route::get('class', 'UserController@sortList');
Route::get('byclass', 'UserController@sortSearch');
Route::get('detail', 'UserController@foodDetail');
Route::get('like','UserController@Like');
Route::get('banner','UserController@getBanner');
Route::get('recommend','UserController@recommend');
//管理员 控制台
Route::post('login', 'AdminController@login');//登录
Route::get("user/info",'AdminController@userInfo');
Route::post("logout",'AdminController@out');
Route::post("addBanner",'AdminController@addBanner');
Route::post("modifyBanner",'AdminController@modifyBanner');//修改
Route::delete("removeBanner",'AdminController@removeBanner');
Route::post("addRecommend",'AdminController@addRecommend');
Route::delete("removeRecommend",'AdminController@removeRecommend');
Route::post("modifyRecommend",'AdminController@modifyRecommend');//修改
Route::post("addMenu",'AdminController@addMenu');
Route::post("modifyMenu",'AdminController@modifyMenu');//修改
