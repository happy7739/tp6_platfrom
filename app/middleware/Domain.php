<?php
declare (strict_types = 1);

namespace app\middleware;
/**
 * 跨域处理
 * Class Domain
 * @package app\middleware
 * Date: 2020/8/18 13:34
 */
class Domain
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
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, AuthKey, sessionid,Lang,Token,Referer,User-Agent,token");
        if(request()->isOptions()){
            return 1;
        }
        return $next($request);
    }
}
