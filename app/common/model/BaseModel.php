<?php
declare (strict_types = 1);

namespace app\common\model;

use think\facade\Db;
use think\helper\Str;
use think\Model;

/**
 * 模型基类
 * 提供全表分页查询封装，操作日志记录
 * @mixin \think\Model
 */
abstract class BaseModel extends Model
{
    static protected $table_name;//注释表名
    protected $pk;
    protected $autoWriteTimestamp = true; //开启自动写入时间

    /**
     * 列表查询
     * @param true | array $where 查询条件 [['id','=','1'],...] 或者 ['this'=>[['id','=','1'],...],'外联表'=>[['id','=','1'],...]]
     * @param array $order 排序规则 ['id'=>'asc'], 默认 ['id'=>'desc']
     * @param array $with 模型关联 ['关联1','关联2',...]
     * @param string $hidden 隐藏字段 'id,coin_id,...'
     * @return \think\Paginator
     * @throws \think\db\exception\DbException
     * Date: 2020/8/18 13:41
     */
    public static function lists($where = true, $order = [], $with = [],string $hidden = ''){
        is_string($where) and $hidden = $where and $where = true;
        is_string($order) and $hidden = $order and $order = [];
        is_string($with) and $hidden = $with and $with = [];
        $order or $order = ['id'=>'desc'];
        $hidden = explode(',',$hidden);
        $hasWhere = null;
        if(is_array($where)){
            $condition = $where;
            $where = [];
            foreach ($condition as $relation=>$w){
                if(!is_numeric($relation)){
                    $table = $relation === 'this' ? Str::studly(self::getTable()) : Str::studly($relation);
                    foreach ($w as $key=>$item){
                        $item[0] = $table.'.'.$item[0];
                        $where[] =$item;

                    }
                    if($relation !== 'this'){
                        $hasWhere = $relation;
                    }
                }else{
                    $where = $condition;
                }
            }
        }
        if(is_null($hasWhere)){
            return self::where($where)->order($order)->with($with)->hidden($hidden)->paginate(input('limit','10'));
        }else{
            return self::hasWhere($hasWhere)->where($where)->order($order)->with($with)->hidden($hidden)->paginate(input('limit','10'));
        }

    }

    /**
     * 编辑数据
     * @param integer $id 数据主键
     * @param array $info 编辑内容
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * Date: 2020/8/18 14:21
     */
    public static function modify($id,array $info):bool {
        $data = self::where('id',$id)->find();
        return $data->data($info)->save();
    }

    /**
     * @param $id
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * Date: 2020/8/24 11:02
     */
    public static function del($id){
        $data = self::where('id',$id)->find();
        return $data->delete();
    }

    /**
     * @param Model $model
     * @return string
     * Date: 2020/8/24 12:02
     */
    private static function getComment(Model $model): string
    {
        $database = config('database.connections.mysql.database');
        $tableComment = Db::table('information_schema.TABLES')->where('TABLE_SCHEMA', $database)->where('TABLE_NAME', $model->getTable())->value('TABLE_COMMENT');
        return $tableComment;
    }

    /**
     * 转义创建时间
     * @param $name
     * @return false|string
     * Date: 2020/8/21 17:40
     */
    public function getCreateTimeAttr($name)
    {
        return is_numeric($name) ? date('Y-m-d H:i',$name) : $name;
    }
    /**
     * 编辑操作日志
     * 无需调用，执行编辑数据的方法，成功后自动执行
     * @param Model $model
     * Date: 2020/8/18 14:37
     */
    public static function onAfterUpdate(Model $model){
        if(request()->adminInfo){
            $tableComment = self::getComment($model);
            AdminLogs::write('编辑'.$tableComment,$model->getOrigin(),$model->toArray());
        }
    }

    /**
     * 新增操作日志
     * 无需调用，执行insert()，成功后自动执行
     * @param Model $model
     * @return bool|void
     * Date: 2020/8/22 15:03
     */
    public static function onAfterInsert(Model $model)
    {
        if(request()->adminInfo){
            if($model->getTable() !== 'admin_logs'){
                $tableComment = self::getComment($model);
                AdminLogs::write('新增'.$tableComment,[],$model->toArray());
            }//跳过管理端操作日志表，避免死循环
        };
    }

    /**
     * 删除操作日志
     * 无需调用，执行delete()，成功后自动执行
     * @param Model $model
     * Date: 2020/8/18 16:37
     */
    public static function onAfterDelete(Model $model)
    {
        if(request()->adminInfo){
            $tableComment = self::getComment($model);
            AdminLogs::write('删除'.$tableComment,$model->getOrigin(),[]);
        };
    }
}
