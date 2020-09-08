<?php
declare (strict_types = 1);

namespace app\platform\controller;

use app\common\controller\StatusCode;
use app\common\model\MicroRule;
use app\common\model\AdminLogs;
use app\platform\service\LoginService;
use app\platform\service\PowerService;
use think\exception\ValidateException;

class Auth extends BaseController
{
    /**
     * 获取图形验证码
     * @return \think\Response
     * Date: 2020/8/21 13:55
     */
    public function imgCode(){
        return imgCode('number',input('id',''));
    }

    /**
     * 登录
     * @param LoginService $loginService
     * @return \think\response\Json
     * Date: 2020/8/21 16:21
     */
    public function login(LoginService $loginService){
        try{
            //表单验证
            $this->validate($this->param,'Login');
            //登录密码验证
            $res = $loginService->verifyLogin($this->param['username'],$this->param['password']);
            if($res){
                //获取token
                $token = $loginService->getToken($this->param['username']);
                //写入日志
                AdminLogs::write('登录系统',[],[]);
                return result(lang('Login successful'),['token'=>$token],StatusCode::$SUCCESS) ;
            }else{
                return result(lang('Password error')) ;
            }
        }catch (ValidateException $validate){
            //验证反馈
            return result(lang($validate->getError()),StatusCode::$FAIL);
        }catch (\Throwable $t){
            //程序异常
            trace('登录异常:'.$t->getMessage().'|'.$t->getLine(),'error');
            return result(lang('The server is busy. Please try again later'));
        }
    }

    /**
     * 注销登录
     * @return \think\response\Json
     * Date: 2020/8/25 11:18
     */
    public function logout(){
        cache($this->param['token'],null);
        \app\common\model\AdminLogs::write('退出系统',[],[]);
        return result(lang('Log out successfully'),StatusCode::$SUCCESS) ;
    }

    /**
     * 权限验证
     * @param PowerService $powerService
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * Date: 2020/8/22 12:00
     */
    public function checkPower(PowerService $powerService){
        $validate = $powerService->setRule()->verify($this->param['route']);
        if($validate !== 1){
            switch ($validate){
                case 0:
                    $msg = '权限不足';
                    break;
                case 404:
                default:
                    $msg = '非法访问';
            }
            return result($msg,StatusCode::$FAIL);
        }
        return result('ok',StatusCode::$SUCCESS);
    }

    /**
     * 权限树
     * @param PowerService $powerService
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * Date: 2020/8/25 17:33
     */
    public function authorization(PowerService $powerService){
        $lists = $powerService->menuTree();
        return result('ok',$lists,StatusCode::$SUCCESS);
    }
}
