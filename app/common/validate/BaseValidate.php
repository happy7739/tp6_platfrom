<?php
declare (strict_types = 1);

namespace app\common\validate;

use org\Rsa;
use think\Validate;
use \think\captcha\facade\Captcha;
use think\facade\Db;

abstract class BaseValidate extends Validate
{

    /**
     * 验证数据是否存在于表
     * 存在返回真，不存在或者参数错误返回假
     * @param $v
     * @param $condition
     * @return bool
     * Date: 2020/6/16 14:55
     */
    function exist($v,$condition){
        if(count(explode(',',$condition)) === 2){
            list($table,$field) = explode(',',$condition);
            if(config('database.prefix') && strpos(config('database.prefix'),$table) === false){
                $table = config('database.prefix').$table;
            }
            return Db::table($table)->where($field,$v)->count() > 0;
        }
        return false;
    }
    /**
     * 验证数据是否存在于表
     * 不存在返回真，存在或者参数错误返回假
     * @param $v
     * @param $condition
     * @return bool
     * Date: 2020/6/23 17:41
     */
    function nonExist($v,$condition){
        if(count(explode(',',$condition)) === 2){
            list($table,$field) = explode(',',$condition);
            if(strpos(config('database.prefix'),$table) === false){
                $table = config('database.prefix').$table;
            }
            return Db::table($table)->where($field,$v)->count() > 0 ? false : true;
        }
        return false;
    }
    /**
     * 图形验证码验证
     * @param $v
     * @param $imgIdKey
     * @param $form
     * @return bool
     * Date: 2020/7/27 14:17
     */
    function checkImg($v,$imgIdKey,$form) : bool{
        return Captcha::check($v,$imgIdKey ? $form[$imgIdKey] : '');
    }

    /**
     * 登录状态下密码验证
     * @param $v
     * @param $condition
     * @return bool|string
     * Date: 2020/7/31 15:29
     */
    function checkPassword($v,$condition) {

        try{
            $pwd = Tool::decodePassword($v);
        }catch (\Throwable $e){
            $pwd = $v;
        }
        if(!$pwd) return lang('PASSWORD_FORMAT_ERROR');
        if(!request()->uid) return lang('LOGIN_REQUIRE');
        $password = Users::where('id',request()->uid)->value($condition.'_password');
        if($password){
            if(Tool::decodePassword($password) == $pwd){
                return true;
            }else{
                return lang('Password error');
            }
        }else{
            return lang('Please set the pay password first');
        }
    }

    /**
     * 余额验证
     * @param $v string 被验证的值
     * @param $condition string 额外参数
     *  格式：账户 - fund | otc , 是否是普通账户 - true | false , 币种ID - 数字 | 表单参数中代表币种ID的字段
     * @param $form array 表单参数
     * @return bool|mixed|string 验证结果
     * Date: 2020/7/27 15:54
     */
    function checkBalance($v,$condition,$form){
        list($account,$is_account,$coin_id) = explode(',',$condition);
        $field = $account.'_'.($is_account === 'true' ? 'account' : 'freeze');
        if(is_string($coin_id)){
            if(array_key_exists($coin_id,$form)){
                $coin_id = $form[$coin_id];
            }else{
                return 'error';
            }
        }elseif (!is_numeric($coin_id)){
            return 'error';
        }
        $balance = UsersCoin::where('uid',request()->uid)->where('coin_id',$coin_id)->value($field);
        if(!$balance) return 'error';
        if($balance >= $v){
            return  true;
        }else{
            $coin_name_field = 'name_'.str_replace('-','_',request()->lang);
            $coin_name = Coin::where('id',$coin_id)->value($coin_name_field);
            return lang('BALANCE_LOW',['coin'=>$coin_name]);
        }
    }

    /**
     * 密码规则验证
     * @param $pwd
     * @return bool|string
     * Date: 2020/7/28 11:13
     */
    function pwdRule($pwd){
        $len = mb_strlen($pwd,'UTF8');
        //6 - 20位
        if($len > 20 || $len < 6) return lang('PASSWORD_LENGTH_ERROR',['min'=>6,'max'=>20]);
        //不能是纯数字
        if(is_numeric($pwd)) return lang('The password cannot be a pure number');
        //不能包含中文
        if(preg_match('/[\x{4e00}-\x{9fa5}]/u', $pwd)>0) return lang('The password cannot contain Chinese');
        return true;
    }

    /**
     * 验证码验证
     * @param $v
     * @param $condition
     * @param $form
     * @return bool|string
     * Date: 2020/7/30 14:35
     */
    function checkCode($v,$condition,$form){
        $send_type = $condition;
        if(in_array($send_type,['register','forgetPassword'])){
            if(array_key_exists('username',$form)) $account = $form['username'];
            else return lang('email_require');
        }else{
            if(request()->userInfo) $account = request()->userInfo['username'];
            else return lang('Login has expired');
        }
        $code = cache($send_type.$account.'2code');
        if($code) {
            if($code == $v){
                cache($send_type.$account.'2code',null);
                return true;
            }else{
                return lang('WRONG_CODE');
            }
        }else{
            return lang('CODE_NOT_EXIST');
        }
    }
}
