<?php

use Illuminate\Http\Request;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['middleware' => []], function () {
    //文件上传接口
    Route::group(['prefix' => 'attachment'], function () {
        Route::get('groups', 'FileStoragesController@groups')->name('api.attachment.groups');
        Route::get('list', 'FileStoragesController@list')->name('api.attachment.list');
        Route::post('group', 'FileStoragesController@saveGroup')->name('api.attachment.saveGroup');
        Route::delete('group/{attachmentGroup}', 'FileStoragesController@deleteGroup')->name('api.attachment.deleteGroup');
        Route::post('move', 'FileStoragesController@moveToGroup')->name('api.attachment.moveToGroup');
        Route::post('destroy', 'FileStoragesController@destroy')->name('api.attachment.destroy');
        Route::post('upload_file', 'FileStoragesController@upload')->name('api.attachment.upload_file');
    });

    Route::group(['namespace' => 'Api'], function () {

        Route::group(['prefix' => 'wechat'], function () {
            Route::post('miniAppLogin', 'BasicController@miniAppLogin')->name('api.wechat.miniAppLogin');
        });

        Route::group(['prefix' => 'user', 'middleware' => ['auth:api']], function () {
            Route::get('index', 'BasicController@index')->name('api.user.index');
            Route::post('updateInfo', 'BasicController@updateInfo')->name('api.user.updateInfo');
        });

    });
});





