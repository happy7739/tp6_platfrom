<?php
declare (strict_types = 1);

namespace app\platform\validate;

use app\common\validate\BaseValidate;

class Role extends BaseValidate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */	
	protected $rule = [
	    'role_name' => 'require',
	    'id' => 'require|exist:roles,id',
	    'rules' => 'require',
     ];
    
    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */	
    protected $message = [
        'role_name.require' => '请输入角色名称',
        'rules.require' => '请授予至少一个权限',
        'id.require' => '参数异常',
        'id.exist' => '数据丢失',
    ];

    protected $scene = [
        'create' => ['role_name'],
        'modify' => ['id','role_name'],
        'authorization' => ['id','rules'],
        'del' => ['id'],
    ];
}
