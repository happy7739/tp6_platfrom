<?php
declare (strict_types = 1);

namespace app\common\model;

use think\Model;

/**
 * @mixin \think\Model
 */
class Powers extends BaseModel
{
    protected $table = 'powers';
    protected $pk = 'id'; //主键
    protected $autoWriteTimestamp = false; //开启自动写入时间
}
