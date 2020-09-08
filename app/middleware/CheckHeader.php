<?php
declare (strict_types = 1);

namespace app\middleware;

use org\Rsa;

class CheckHeader
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
        //获取header处理后的参数
        $data = $this->getHeaderDate($request->header());
        if(!isset($data['timestamp']) || empty((int)$data['timestamp'])) return result('请求时间不能为空',500);
        if(strtotime($data['timestamp'])) return result('请求时间格式不合法',500);
        if(!isset($data['sign']) || empty($data['sign'])) return result('签名不能为空',500);
        //获取私钥
        $privateKey = 'abcdefghijklmnopqrstuvwxyz';
        //获取sign
        $data['data'] = $this->getParam($request->param());//前端数据数字传到服务器变为字符串,所以将数据拼接处理
        $header_sign = $data['sign'];
        unset($data['sign']);
        $sign = $this->getSign($data,$privateKey);
        //判断请求时间是否超时
        $time_num = 1000;//超时时间，单位：毫秒
        list($msec, $sec) = explode(' ', microtime());
        $msectime = (float)sprintf('%.0f', (floatval($msec) + floatval($sec)) * 1000);
        $timestamp = $data['timestamp'] + $time_num;
        if($timestamp < $msectime) return result('请求超时',500);
        //判断sign是否和前端的一样
        if($sign != $header_sign) return result('参数错误',500);
        //判断sign是否在缓存中
        $ip = str_replace($request->ip(),'.','_');
        $constroller = strtolower($request->controller());
        $action = strtolower($request->action());
        $signs = cache('sign_'.$constroller.'_'.$action.'_'.$ip);
        if($signs == $sign){
            //如果有就是验证过了，表示被抓包了
            return result('请求过于频繁',500);
        }
        //如果没有就通过，并且将sign保存在缓存之中
        cache('sign_'.$constroller.'_'.$action.'_'.$ip,$sign,1);
        return $next($request);
    }

    /**
     * 获取sign
     * @param $data
     * @param $privateKey
     * @return string
     */
    public function getSign($data,$privateKey)
    {
        ksort($data);
        $sign = '';
        foreach ($data as $k=>$v){
            if($k != '' && $v != ''){
                $sign .= $k . $v;
            }
        }
        $sign = strtolower($sign . $privateKey);
        $sign = Rsa::encode($sign);
        return $sign;
    }

    /**
     * 处理数据
     * @param $data
     * @return string
     */
    public function getParam($data)
    {
        $str = '';
        foreach ($data as $k=>$v){
            $str .= $k . $v;
        }
        return $str;
    }

    /**
     * 处理header参数
     * @return array
     */
    public function getHeaderDate($data)
    {
        $headers = [];
        foreach ($data as $key=>$value){
            if(substr($key,0,5) == 'hskj_'){
                $key = substr_replace($key,'',0,5);
                $headers[$key] = $value;
            }
        }
        return $headers;
    }
}
