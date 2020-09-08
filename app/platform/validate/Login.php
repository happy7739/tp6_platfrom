<?php
declare (strict_types = 1);

namespace app\platform\validate;

use app\common\validate\BaseValidate;

class Login extends BaseValidate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */	
	protected $rule = [
	    'username'=>'require|exist:admins,username',
	    'password'=>'require',
	    'captcha'=>'require|checkImg:id',
	    'id'=>'require',
     ];
    
    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */	
    protected $message = [
        'username.require'=>'请输入用户名',
        'username.exist'=>'用户不存在',
        'password.require'=>'请输入登录密码',
        'captcha.require'=>'请输入图形验证码',
        'captcha.checkImg'=>'图形验证码错误',
        'id.require'=>'参数异常',
    ];


}
