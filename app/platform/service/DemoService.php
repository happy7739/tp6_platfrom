<?php
declare (strict_types = 1);

namespace app\platform\service;

use app\platform\controller\Test;
use think\Service;

class DemoService  extends Service
{

    /**
     * 注册服务
     *
     * @return mixed
     */
    public function register()
    {
//        $this->app->bind('test', Test::class);
    	   dump('registerService');
    }

    
    /**
     * 执行服务
     *
     * @return mixed
     */
    public function boot()
    {
        dump('doService');
    }
}
