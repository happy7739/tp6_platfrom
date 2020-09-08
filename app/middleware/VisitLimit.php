<?php
declare (strict_types = 1);

namespace app\middleware;
use app\common\controller\Redis;
use think\facade\Config;

class VisitLimit
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
        return $next($request);
        $redis = new Redis();
        $redis = $redis->getConnect();
        if($token = request()->param('token')){
            $second_visit_times = 3;//每秒请求接口次数限制
            $redis_name = 'API_VISIT_LIMIT';
            $time = time();
            $key = $redis_name.'_'.$token.'_'.$time;//标识
            if($redis->exists($key)){//如果该标识存在的话判断下标识对应的值（也就是访问次数）是不是大于限制次数
                if(intval($redis->get($key)) > $second_visit_times){
                    return result(lang('The request is too frequent. Please try again later'),StatusCode::$FAIL);
                }
                //增加访问次数
                $redis->incr($key);
            }else{//如果标识不存在的话，新增标识，并设置过期时间，防止redis中存储字段过多
                $redis->setex($key,10,0);
            }
        }

        $route = request()->pathinfo();
        $second_visit_times = 1;//每秒请求接口次数限制
        $redis_name = 'IP_API_VISIT_LIMIT';
        $time = time();
        $ip=request()->ip(true);
        $key = $redis_name.'_'.$ip.'_'.$route.'_'.$time;//标识
        //限制次数为5
        $check = $redis->exists($key);
        if($check){
            $redis->incr($key);
            $count = $redis->get($key);
            if($count > $second_visit_times){
                return result(lang('The request is too frequent. Please try again later'),StatusCode::$FAIL);
            }
        }else{
            $redis->incr($key);
            //限制时间为60秒
            $redis->expire($key,60);
        }
        return $next($request);
    }
}
