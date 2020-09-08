<?php
declare (strict_types = 1);

namespace app\common\model;

use think\Model;

/**
 * 权限模型
 * Class MicroRule
 * @package app\common\model
 * Date: 2020/8/18 16:40
 */
class MicroRule extends BaseModel
{
    protected $table = 'micro_rule';
    static protected $table_name = '权限';
    protected $readonly = ['module', 'controller','action','method'];//只读字段
    /**
     * 关联下级权限
     * @return \think\model\relation\HasMany
     * Date: 2020/8/18 14:50
     */
    public function child(){
        $model = $this->hasMany(self::class,'pid','id');
        if(request()->rules) $model = $model->where('id','in',explode(',',request()->rules));
        return $model->field('id,pid,name as title,concat(controller,"/",action,".html") as href,icon')->hidden(['id','pid']);
    }

    public function children(){
        return $this->hasMany(self::class,'pid','id')->field('id,pid,name as title')->hidden(['pid']);
    }
}
