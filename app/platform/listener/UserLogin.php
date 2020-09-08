<?php
declare (strict_types = 1);

namespace app\platform\listener;

use app\platform\service\DemoService;

class UserLogin
{
    /**
     * @param $event
     * @return array
     * Date: 2020/9/3 14:20
     */
    public function handle($event,DemoService $demoService)
    {
        dump('监听打印',$event);
        event('UserLogout',$event);
        $demoService->register();
       return ['success'=>1,'code'=>23];
    }    
}
