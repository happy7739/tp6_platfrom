<?php
declare (strict_types = 1);

namespace app\common\model;

use think\model\concern\SoftDelete;
use think\Model;
/**
 * @mixin \think\Model
 */
class Admins extends BaseModel
{
    use SoftDelete;
    protected $deleteTime = 'delete_time';
    protected $table = 'admins'; //表名
    protected $pk = 'id'; //主键
    protected $updateTime = false;//关闭修改时间写入
    protected $readonly = ['is_default'];//只读字段
    static protected $table_name = '管理员';

    public function adminRules(){
        return $this->hasOne('roles','id','role_id')->bind(['role_name']);
    }
}
