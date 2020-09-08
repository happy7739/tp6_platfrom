<?php
declare (strict_types = 1);

namespace app\common\model;


/**
 * @mixin \think\Model
 */
class Config extends BaseModel
{
    protected $table = 'config'; //表名
    protected $pk = 'id'; //主键
    protected $autoWriteTimestamp = false; //开启自动写入时间
}
