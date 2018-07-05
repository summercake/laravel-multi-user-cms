<?php
/**
 * Created by PhpStorm.
 * User: jack
 * Date: 7/4/2018
 * Time: 4:56 AM
 */
Route::group(['prefix' => 'admin'], function (){
    // 登陆页面
    Route::get('/login', '\App\Admin\Controller\LoginController@index');
    // 登陆行为
    Route::post('/login', '\App\Admin\Controller\LoginController@login');
    // 登出行为
    Route::get('/logout', '\App\Admin\Controller\LoginController@logout');
    Route::group(['middleware' => 'auth:admin'], function (){
        // 首页
        Route::get('/home', '\App\Admin\Controller\HomeController@index');
        Route::group(['middleware' => 'can:system'], function (){
            /**
             * 管理人员模块
             */
            Route::get('/users', '\App\Admin\Controller\UserController@index');
            Route::get('/users/create', '\App\Admin\Controller\UserController@create');
            Route::post('/users/store', '\App\Admin\Controller\UserController@store');
            Route::get('/users/{user}/role', '\App\Admin\Controller\UserController@role');
            Route::post('/users/{user}/role', '\App\Admin\Controller\UserController@storeRole');
            /**
             * 角色模块
             */
            Route::get('/roles', '\App\Admin\Controller\RoleController@index');
            Route::get('/roles/create', '\App\Admin\Controller\RoleController@create');
            Route::post('/roles/store', '\App\Admin\Controller\RoleController@store');
            Route::get('/roles/{role}/permission', '\App\Admin\Controller\RoleController@permission');
            Route::post('/roles/{role}/permission', '\App\Admin\Controller\RoleController@storePermission');
            /**
             * 权限模块
             */
            Route::get('/permissions', '\App\Admin\Controller\PermissionController@index');
            Route::get('/permissions/create', '\App\Admin\Controller\PermissionController@create');
            Route::post('/permissions/store', '\App\Admin\Controller\PermissionController@store');
        });
        Route::group(['middleware' => 'can:post'], function (){
            /**
             * 审核模块
             */
            Route::get('/posts', '\App\Admin\Controller\PostController@index');
            Route::post('/posts/{post}/status', '\App\Admin\Controller\PostController@status');
        });
        Route::group(['middleware' => 'can:topic'], function (){
            /**
             * 专题模块
             */
            Route::resource('topics', '\App\Admin\Controller\TopicController',
                ['only' => ['index', 'create', 'store', 'destroy']]);
        });
        Route::group(['middleware' => 'can:notice'], function (){
            /**
             * 通知模块
             */
            Route::resource('notices', '\App\Admin\Controller\NoticeController',
                ['only' => ['index', 'create', 'store']]);
        });
    });
});

