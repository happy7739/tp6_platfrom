<?php
declare (strict_types = 1);

namespace app\common\model;


/**
 * @mixin \think\Model
 */
class RolesPower extends BaseModel
{
    protected $table = 'roles_power';
    protected $pk = 'id'; //主键
    protected $autoWriteTimestamp = false; //开启自动写入时间

    public function code(){
        return $this->hasOne('powers','id','power_id')->bind(['code']);
    }
}
