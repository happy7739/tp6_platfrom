<?php
declare (strict_types = 1);

namespace app\platform\service;

class DemoTwoService  extends \think\Service
{

    /**
     * 注册服务
     *
     * @return mixed
     */
    public function register()
    {
        dump('registerServiceTwo');
    }

    
    /**
     * 执行服务
     *
     * @return mixed
     */
    public function boot()
    {
        //
    }
}
