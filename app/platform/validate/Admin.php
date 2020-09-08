<?php
declare (strict_types = 1);

namespace app\platform\validate;

use app\common\validate\BaseValidate;
use think\Validate;

class Admin extends BaseValidate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */
	protected $rule = [
	    'username'=>'require',
	    'nickname'=>'require',
	    'password'=>'require|pwdRule',
	    'telephone'=>'mobile',
	    'email'=>'email',
	    'role_id'=>'require|exist:roles,id',
     ];

    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */
    protected $message = [];

    protected $scene = [
        'create' => ['username','name','user_password','telephone','email','role_id'],
        'modify' => ['username','name','telephone','email','role_id'],
    ];
}
