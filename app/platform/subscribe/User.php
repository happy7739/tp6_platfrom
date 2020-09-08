<?php
declare (strict_types = 1);

namespace app\platform\subscribe;

class User
{
    public function onUserLogin($user)
    {
        // UserLogin事件响应处理
        dump('登录事件打印',$user);
        return ['code'=>200];
    }

    public function onUserLogout($user)
    {
        // UserLogout事件响应处理
        dump('登出订阅打印',$user);
    }
}
