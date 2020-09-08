<?php
declare (strict_types = 1);

namespace app\middleware;

use app\common\controller\StatusCode;
use org\Rsa;

class Sign
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
        try{
            $param = $request->param();
            if(strtolower($request->controller()) === 'auth' && strtolower($request->action()) === 'imgcode'){
                //图形验证码不做签名验证
                return $next($request);
            }
            //请求参数中不存在签名
            if(!array_key_exists('sign',$param)){
                return result('非法请求',StatusCode::$FAIL);
            }
            //解密签名
            $sign = Rsa::decode(rawurldecode(urlencode($param['sign'])));

            $ss = $this->setSign();
            if($ss === ''){
                $sa = ''; $time = $sign;
            }else{
                //参数签名
                $sa = substr($sign,0,32);
                //发起请求时间戳（毫秒级）
                $time = substr($sign,32);
            }
            //参数签名验证
            if($sa !== $ss) return result('非法请求，签名丢失',StatusCode::$FAIL);
            //获取服务器时间戳（毫秒级）
            list($msec, $sec) = explode(' ', microtime());
            $msectime = (float)sprintf('%.0f', (floatval($msec) + floatval($sec)) * 1000);
            //时间误差验证
            if(abs($msectime - $time) > 300){
                return result('请求异常，请确认网络情况',StatusCode::$FAIL);
            }
            return $next($request);
        }catch (\Throwable $t){
            return $next($request);
        }

    }

    /**
     * 生成参数签名
     * @return string
     * Date: 2020/8/27 11:59
     */
    private function setSign(){
        $param = request()->param();
        ksort($param);
        $str = '';
        foreach ($param as $key=>$value){
            if(in_array($key,['token','sign'])) continue;
            $str .= $key.'='.$value.'&';
        }
        return md5($str ? substr($str,0,-1) : $str);
    }
}
