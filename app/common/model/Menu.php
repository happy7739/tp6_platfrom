<?php
declare (strict_types = 1);

namespace app\common\model;


/**
 * @mixin \think\Model
 */
class Menu extends BaseModel
{
    protected $table = 'menu';
    protected $pk = 'id'; //主键
    protected $autoWriteTimestamp = false; //开启自动写入时间

    public function child(){
        $second = $this->hasMany(self::class,'pid','id');
        if(is_array(request()->second)) $second = $second->whereIn('id',request()->second);
        return $second->field('id,pid,title,concat(url,".html") as href')->hidden(['pid']);
    }

    /**
     * 关联权限，获取权限树
     * @return \think\model\relation\HasMany
     * Date: 2020/9/1 15:55
     */
    public function children(){
        if(request()->level === 2){
            return $this->hasMany('powers','menu_id','id')->field('code as id,menu_id,name as title');
        }else{
            request()->level = 2;
            return $this->hasMany(self::class,'pid','id')->where('is_show',1)->field('id,title,pid')->with('children');
        }
    }

}
