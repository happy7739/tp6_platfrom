<?php
declare (strict_types = 1);

namespace app\common\model;

use think\model\concern\SoftDelete;

/**
 * @mixin \think\Model
 */
class Roles extends BaseModel
{
    use SoftDelete;
    protected $deleteTime = 'delete_time';
    protected $table = 'roles'; //表名
    protected $pk = 'id'; //主键
    protected $autoWriteTimestamp = true; //开启自动写入时间
    protected $updateTime = false;//关闭修改时间写入
    protected $readonly = ['is_default'];//只读字段

    /**
     * 读取权限码
     * @param string $name
     * @param $data
     * @return string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * Date: 2020/9/1 16:59
     */
    public function getRulesAttr(string $name,$data)
    {
        $info = RolesPower::where('role_id',$data['id'])->with('code')->field('power_id')->select();
        $powers = [];
        if($info){
            foreach ($info as $value){
                array_push($powers,$value->code);
            }
        }
        return $powers ? implode(',',$powers) : '';
    }
}
