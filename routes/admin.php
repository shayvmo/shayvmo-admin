<?php

/*
|--------------------------------------------------------------------------
| 后台路由
|--------------------------------------------------------------------------
|
| 统一命名空间 Admin
| 统一前缀 admin
| 用户认证统一使用 auth 中间件
| 权限认证统一使用 permission:权限名称 (暂弃用, 已改用中间件方式)
| 如果需要记录请求日志，使用 request.log 中间件即可
*/

/*
|--------------------------------------------------------------------------
| 用户登录、退出、更改密码
|--------------------------------------------------------------------------
*/

Route::group(['middleware' => ['check.install']], function() {

    Route::group(['namespace' => 'Admin', 'prefix' => 'admin/user'], function () {
        Route::get('login','AuthController@showLoginForm')->name('admin.auth.page');//登录
        Route::post('login','AuthController@login')->name('admin.auth.login')->middleware('request.log');//登录api
    });

    /*
    |--------------------------------------------------------------------------
    | 后台公共页面
    |--------------------------------------------------------------------------
    */
    Route::group(['namespace' => 'Admin', 'prefix' => 'admin', 'middleware' => ['auth']], function () {
        Route::get('/menu','BasicController@menu')->name('admin.menu');//后台菜单
        Route::get('/','BasicController@layout')->name('admin.layout');// 后台布局
        Route::get('/index','BasicController@dateCenter')->name('admin.index');// 后台首页
        Route::get('/profile','BasicController@profile')->name('admin.profile');// 基础资料
        Route::get('/pwdPage','BasicController@pwdPage')->name('admin.password');// 更改密码页
        Route::post('/updatePwd','BasicController@updatePwd')->name('admin.password.update')->middleware('request.log');// 更新密码api
        Route::get('/logout','AuthController@logout')->name('admin.logout');// 退出

        Route::get('demo', 'BasicController@demo')->name('admin.demo');
    });

    /*
    |--------------------------------------------------------------------------
    | 控制中心模块
    |--------------------------------------------------------------------------
    */
    Route::group(['namespace' => 'Admin', 'prefix' => 'admin', 'middleware' => ['auth']], function () {
        //控制中心
        Route::get('datacenter', 'BasicController@dateCenter')->name('admin.datacenter');// 未完成
    });

    /*
    |--------------------------------------------------------------------------
    | 系统管理模块
    |--------------------------------------------------------------------------
    */
    Route::group(['namespace' => 'Admin', 'prefix' => 'admin', 'middleware' => ['auth', 'check.user.auth', 'request.log']], function () {

        // 管理员管理
        Route::group(['middleware'=>[], 'prefix' => 'admin'],function (){
            Route::get('','AdminController@showPage')->name('admin.admin.showPage');// 列表页
            Route::get('index','AdminController@index')->name('admin.admin.index');// 列表api
            Route::post('store','AdminController@store')->name('admin.admin.store');// 新增api
            Route::put('{admin}/update','AdminController@update')->name('admin.admin.update');// 更新api
            Route::delete('destroy/{admin}','AdminController@destroy')->name('admin.admin.destroy');// 删除api
            Route::put('resetPwd/{admin}','AdminController@resetPwd')->name('admin.admin.resetPwd');// 重置密码api
            Route::put('quickForbidden/{admin}','AdminController@quickForbidden')->name('admin.admin.quickForbidden');// 快速禁用api
        });

        //角色管理
        Route::group(['middleware'=>[], 'prefix' => 'role'],function (){
            Route::get('','RoleController@showPage')->name('admin.role');// 列表
            Route::get('index','RoleController@index')->name('admin.role.index');// 列表api
            Route::post('store','RoleController@store')->name('admin.role.store');// 添加
            Route::put('{role}/update','RoleController@update')->name('admin.role.update');// 更新
            Route::delete('destroy/{role}','RoleController@destroy')->name('admin.role.destroy');// 删除
            Route::get('{role}/permission','RoleController@getRolePermissions')->name('admin.role.permission');// 获取角色权限
            Route::put('{role}/assignPermission','RoleController@setRolePermissions')->name('admin.role.assignPermission');// 设置角色权限
        });


        //权限管理
        Route::group(['middleware'=>[], 'prefix' => 'permission'],function (){
            Route::get('','PermissionController@showPage')->name('admin.permission');// 权限页
            Route::get('index','PermissionController@index')->name('admin.permission.index');// 获取权限
            Route::post('store','PermissionController@store')->name('admin.permission.store');// 新增
            Route::put('{permission}/update','PermissionController@update')->name('admin.permission.update');// 更新
            Route::delete('destroy/{permission}','PermissionController@destroy')->name('admin.permission.destroy');// 删除
        });

        //配置项
        Route::group(['middleware' => [], 'prefix' => 'config'], function () {
            Route::get('', 'ConfigController@index')->name('admin.config');// 配置项页
            Route::get('data', 'ConfigController@data')->name('admin.config.data');// 获取配置项api
            Route::post('store', 'ConfigController@store')->name('admin.config.store');
            Route::put('update', 'ConfigController@update')->name('admin.config.update');
        });

    });

    /*
    |--------------------------------------------------------------------------
    | 文章管理模块
    |--------------------------------------------------------------------------
    */
    Route::group(['namespace' => 'Admin', 'prefix' => 'article', 'middleware' => ['auth']], function () {
        Route::get('detail','ArticleController@detail')->name('article.detail');
        Route::get('show/{id}','ArticleController@show')->name('article.show');
        Route::post('store','ArticleController@store')->name('article.store');

    });
});
