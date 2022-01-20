<?php

use Illuminate\Support\Facades\Artisan;

//清除缓存
Route::get('/clear-cache', function() {
    Artisan::call('config:clear');  //清除配置文件缓存
    Artisan::call('cache:clear');   //清除缓存
    Artisan::call('view:clear');    //清理视图缓存
    return mysql_timestamp()." 缓存已清除! ";
});

//首页
Route::get('/','Home\IndexController@index')->name('home');


// 安装程序
Route::get('install', '\App\Http\Controllers\Install\IndexController@index')->name('install');
Route::post('install/save', '\App\Http\Controllers\Install\IndexController@save')->name('install.save');
Route::post('install/execute', '\App\Http\Controllers\Install\IndexController@execute')->name('install.execute');

// gateway Test
Route::get('/send/page','Home\GatewayController@send')->name('gateway.send.page');
Route::post('/send','Home\GatewayController@send')->name('gateway.send');
Route::post('/bind','Home\GatewayController@bind')->name('gateway.bind');
Route::get('/receive','Home\GatewayController@receive')->name('gateway.receive');

// 情绪垃圾桶
Route::get('/trash', 'Home\TrashController@login')->name('trash');
Route::get('/trash/page','Home\TrashController@page')->name('trash.page');
Route::get('/trash/list','Home\TrashController@list')->name('trash.list');
Route::post('/trash/post','Home\TrashController@store')->name('trash.post');
