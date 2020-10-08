<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/','Index\IndexController@index');//首页
Route::get('/login','Index\LoginController@login');//登录
Route::any('/logindo','Index\LoginController@logindo');//执行登录
Route::any('/logout','Index\LoginController@logout');//退出
Route::get('/reg','Index\LoginController@reg');//注册
Route::any('/reg/sendSMS','Index\LoginController@sendSMS');//发送短信验证码
Route::any('/regdo','Index\LoginController@regdo');//执行注册
Route::get('/item/{id}','Index\IndexController@item');

Route::get('/serch/{id}','Index\IndexController@serch');
