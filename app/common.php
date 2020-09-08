<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------
use \app\common\controller\StatusCode;
use think\captcha\facade\Captcha;
// 应用公共文件
/**
 * 去掉小数后面的0，整数的话要去掉.
 * @param $arg
 * @return string
 * @author:LS GROUP
 * @date:2019/6/22 9:21
 */
if(!function_exists('delZero')){
    function delZero($arg,$round = 4){
        if(strpos($arg,'E')) $arg = number_format($arg, 8, '.', '');
        if(strpos($arg,'.')) return (string)rtrim(rtrim($arg, '0'), '.');
        return (string)floatval($arg);
    }
}
/**
 * 手动创建分页数据
 * @param $list
 * @param $listRows
 * @param $page
 * @return mixed
 * @date:xxx
 */
function setPage($list,$listRows,$page,$time = 'time'){
    $start=(int)($page - 1) * $listRows;//偏移量，当前页-1乘以每页显示条数
    $date = array_column($list, $time);
    array_multisort($date,SORT_DESC,$list);
    $data['total']=(int)count($list);
    $data['per_page']=(int)$listRows;
    $data['current_page']=(int)$page;
    $num = $data['total'] / $listRows;
    if($num < '1'){
        $data['last_page']=1;
    }else{
        if($str = strpos($num,'.')){
            $exp = explode('.',$num);
            $data['last_page']= (int)$exp[0] + 1;
        }else{
            $data['last_page']= (int)$num;
        }
    }
    $data['data']=array_slice($list,$start,$listRows);
    return $data;
}

if(!function_exists('result')){
    function result($msg,$data = null,$code = null){
        if(is_numeric($data)){
            $code = $data;
            $data = null;
        }
        is_null($code) and $code = StatusCode::$FAIL;
        return json(['code'=>$code,'msg'=>$msg,'data'=>$data]);
    }
}

if(!function_exists('imgCode')){
    /**
     * 生成图形验证码
     * @param string|null $config
     * @param string $id
     * @return \think\Response
     * Date: 2020/8/21 13:54
     */
    function imgCode(string $config = null,$id = ''){
        return Captcha::create($config,false,$id);
    }
}
if(!function_exists('startTrans')){
    /**
     * 开启事务
     * Date: 2020/8/22 14:47
     */
    function startTrans(){
        \think\facade\Db::startTrans();
    }
}
if(!function_exists('commit')){
    /**
     * 提交事务
     * Date: 2020/8/22 14:47
     */
    function commit(){
        \think\facade\Db::commit();
    }
}
if(!function_exists('rollback')){
    /**
     * 回滚事务
     * Date: 2020/8/22 14:47
     */
    function rollback(){
        \think\facade\Db::rollback();
    }
}

/**
 * 判断协议
 * @return string
 */
function is_https() {
    $theme = "http://";
    if ( !empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off') {
        $theme = "https://";
    } elseif ( isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https' ) {
        $theme = "https://";
    } elseif ( !empty($_SERVER['HTTP_FRONT_END_HTTPS']) && strtolower($_SERVER['HTTP_FRONT_END_HTTPS']) !== 'off') {
        $theme = "https://";
    }
    return $theme;
}

/**
 * 舍去数值末位的0
 * @param $arg
 * @return string
 */
function cutZero($arg){
    if(strpos($arg,'E')) $arg = number_format($arg, 8, '.', '');
    if(strpos($arg,'.')) return (string)rtrim(rtrim($arg, '0'), '.');
    return (string)floatval($arg);
}


/**
 * 检查数据成功
 * @param $rs
 * @return bool
 */
function check_arr($rs){
    foreach ($rs as $v) {
        if (!$v) return false;
    }
    return true;
}


/**
 * 随机字符串
 * @param int $len
 * @param int $type
 * @param string $addChars
 * @return false|string
 */
function RandString($len = 6, $type = 0, $addChars = '') {
    switch ($type) {
        case 0:
            $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890' . $addChars;
            break;
        case 1:
            $chars = str_repeat('0123456789', 3);
            break;
        case 2:
            $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ' . $addChars;
            break;
        case 3:
            $chars = 'abcdefghijklmnopqrstuvwxyz' . $addChars;
            break;
        case 4:
            $chars = 'abcdefghijklmnopqrstuvwxyz1234567890' . $addChars;
            break;
        default :
            $chars = 'ABCDEFGHIJKMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz23456789' . $addChars;
            break;
    }
    $chars = str_shuffle($chars);
    $str = substr($chars, 0, $len);
    return $str;
}
