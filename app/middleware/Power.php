<?php
declare (strict_types = 1);

namespace app\middleware;

use app\common\controller\StatusCode;
use app\platform\service\PowerService;

/**
 * 权限验证
 * Class Power
 * @package app\middleware
 * Date: 2020/8/18 11:52
 */
class Power
{
    /**
     * 处理请求
     *
     * @param \think\Request $request
     * @param \Closure       $next
     * @return Response
     */
    public function handle($request, \Closure $next)
    {
        $powerService = new PowerService();
        if(strtolower($request->controller()) !== 'index'){
            $validate = $powerService->setRule()->verify($request->controller(),$request->action(),$request->method());
            if($validate !== 1){
                switch ($validate){
                    case 0:
                        $msg = '权限不足';
                        break;
                    case 403:
                        return result('账号角色已删除，请联系管理员',StatusCode::$LOGIN);
                    case 404:
                    default:
                        $msg = '非法访问';
                }
                return result($msg,StatusCode::$FAIL);
            }
        }
        return $next($request);
    }
}
