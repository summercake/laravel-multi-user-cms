<?php
/**
 * 前台路由
 */
Route::get('/', '\App\Http\Controllers\LoginController@welcome');
// 注册页面
Route::get('/register', '\App\Http\Controllers\RegisterController@index');
// 注册行为
Route::post('/register', '\App\Http\Controllers\RegisterController@register');
// 登陆页面
Route::get('/login', '\App\Http\Controllers\LoginController@index')->name('login');
// 登陆行为
Route::post('/login', '\App\Http\Controllers\LoginController@login');
Route::group(['middleware' => 'auth'], function (){
    // 登出行为
    Route::get('/logout', '\App\Http\Controllers\LoginController@logout');
    /**
     * 用户模块
     */
    // 用户设置页面
    Route::get('/user/me/setting', '\App\Http\Controllers\UserController@setting');
    // 用户设置行为
    Route::post('/user/me/setting', '\App\Http\Controllers\UserController@settingStore');
    // 用户个人中心
    Route::get('/user/{user}', '\App\Http\Controllers\UserController@show');
    // 关注用户
    Route::post('/user/{user}/fan', '\App\Http\Controllers\UserController@fan');
    // 取消关注
    Route::post('/user/{user}/unfan', '\App\Http\Controllers\UserController@unfan');
    /**
     * 搜索模块
     */
    Route::get('/posts/search', '\App\Http\Controllers\PostController@search');
    /**
     * 文章模块
     */
    // 文章列表
    Route::get('/posts', '\App\Http\Controllers\PostController@index');
    // 创建文章
    Route::get('/posts/create', '\App\Http\Controllers\PostController@create');
    Route::post('/posts', '\App\Http\Controllers\PostController@store');
    // 文章详情
    Route::get('/posts/{post}', '\App\Http\Controllers\PostController@show');
    // 编辑文章
    Route::get('/posts/{post}/edit', '\App\Http\Controllers\PostController@edit');
    Route::put('/posts/{post}', '\App\Http\Controllers\PostController@update');
    // 删除文章
    Route::get('/posts/{post}/delete', '\App\Http\Controllers\PostController@delete');
    // 图片上传
    Route::post('/posts/image/upload', '\App\Http\Controllers\PostController@imageUpload');
    /**
     * 评论模块
     */
    // 提交评论
    Route::post('/posts/{post}/comment', '\App\Http\Controllers\PostController@comment');
    /**
     * 点赞模块
     */
    Route::get('/posts/{post}/like', '\App\Http\Controllers\PostController@like');
    Route::get('/posts/{post}/unlike', '\App\Http\Controllers\PostController@unlike');
    /**
     * 专题模块
     */
    Route::get('/topic/{topic}', '\App\Http\Controllers\TopicController@show');
    Route::get('/topic/{topic}/submit', '\App\Http\Controllers\TopicController@submit');
    /**
     * 通知
     */
    Route::get('/notices', '\App\Http\Controllers\NoticeController@index');
});
/**
 * 后台路由
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



