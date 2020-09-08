<?php
declare (strict_types = 1);

namespace app\common\model;


/**
 * @mixin \think\Model
 */
class AdminLogs extends BaseModel
{

    protected $table = 'admin_logs'; //表名
    protected $pk = 'id'; //主键
    protected $autoWriteTimestamp = true; //开启自动写入时间
    protected $updateTime = false;//关闭修改时间写入

    //字段类型转换
    protected $type = [
        'id'          => 'int',
        'log'        => 'string',
        'create_time' => 'int',
        'before_data' => 'json',
        'after_data' => 'json',
        'admin_id' => 'int',
    ];

    /**
     * 写入日志
     * @param string $log 描述
     * @param array $before 操作前数据（针对编辑）
     * @param array $after 操作后数据（针对编辑）
     * Date: 2020/8/18 14:49
     */
    public static function write(string $log,$before = [],$after = []){
        request()->logs and $log = request()->logs;//已手动设置的日记描述为优先记录内容
        self::create(['log'=>$log,'after_data'=>$after,'before_data'=>$before,'admin_id'=>request()->adminId]);
    }

    public function admins(){
        return $this->hasOne('admins','id','admin_id')->bind(['username','nickname']);
    }
}
