<?php
declare (strict_types = 1);

namespace app\platform\service;

use app\common\model\Admins;
use org\Rsa;

/**
 * Class LoginService
 * @package app\platform\service
 * Date: 2020/8/21 16:13
 */
class LoginService
{
    /**
     * 验证登录密码
     * @param string $username
     * @param string $password
     * @return bool
     * Date: 2020/8/21 16:12
     */
    public function verifyLogin(string $username, string $password) : bool {
        $adminPassword = Admins::where('username',$username)->value('password');
        return Rsa::decode($adminPassword) === $password;
    }

    /**
     * 生成TOKEN，并绑定登录用户
     * @param string $username
     * @return string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * Date: 2020/8/21 16:22
     */
    public function getToken(string $username) : string{
        $admins = Admins::where('username',$username)->find();
        request()->adminId = $admins->id;
        $token = strtoupper(hash('md5',$admins->username.time()));
        cache($token,$admins);
        cache($token.'stamp',time()+STILL_TIME);
        return $token;
    }
}
