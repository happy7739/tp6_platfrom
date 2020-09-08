<?php
/**
 * Created by PhpStorm.
 * User: xiaoziyan
 * Date: 2020/8/20
 * Time: 10:45
 */
use think\facade\Route;
use \app\middleware\Power;
use \app\middleware\VisitLimit;
use \app\middleware\CheckAdminToken;
use \app\middleware\Sign;
use \think\middleware\LoadLangPack;

define('STILL_TIME',1800);//登录静置最大时间

Route::domain(env('route.platform','auth.platform.com'), function () {
    // 动态注册域名的路由规则
    //无需验证权限
    Route::group('common/',function (){
        Route::get('/test','test/index');

        //获取图形验证码
        Route::get('/imgCode','auth/imgCode');
        //权限树
        Route::get('/authorization','auth/authorization');
        //获取菜单，验证登录状态
        Route::get('/menuLists','index/menuLists')->middleware(CheckAdminToken::class);
        Route::get('/roleOption','Power/roleOption')->middleware(CheckAdminToken::class);
        //登录
        Route::post('/login','auth/login');
        Route::post('/logout','auth/logout')->middleware(CheckAdminToken::class);
        //验证权限
        Route::post('/checkPower','auth/checkPower')->middleware(CheckAdminToken::class);
    })->middleware(VisitLimit::class);
    //
    Route::group(function (){
        Route::get('/adminLists','Admins/lists');//管理员列表
        Route::get('/rolesLists','Roles/lists');//角色列表
        Route::get('/adminLogs','Logs/admins');//管理员操作日志

        Route::post('/addAdmin','Admins/add');//添加管理员
        Route::post('/modifyAdmin','Admins/modify');//编辑管理员
        Route::post('/delAdmin','Admins/del');//删除管理员

        Route::post('/addRole','Roles/add');//添加角色
        Route::post('/modifyRole','Roles/modify');//编辑角色
        Route::post('/delRole','Roles/del');//删除角色
        Route::post('/authorization','Roles/authorization');//角色授权
    })->middleware([CheckAdminToken::class,Power::class,Sign::class,VisitLimit::class]);
    Route::miss(function() {
        return '404 Not Found!';
    });
})->middleware([LoadLangPack::class])->allowCrossDomain();
