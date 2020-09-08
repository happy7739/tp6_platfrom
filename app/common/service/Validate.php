<?php
declare (strict_types = 1);

namespace app\common\service;

use think\facade\Db;

class Validate
{
    /**
     * 验证值是否存在
     * @param string $value 验证值
     * @param string $table 表
     * @param string $field 字段
     * @param string $notId 不参与验证的数据主键
     * @param bool $exist 验证模式: true - 存在 | false - 不存在
     * @return bool
     * Date: 2020/8/22 14:16
     */
    public function exist(string $value,string $table,string $field,$notId = '',bool $exist = true){
        is_bool($notId) and $exist = $notId and $notId = '';
        $pk = Db::table($table)->getPk();
        $result = Db::table($table)->where($field,$value);
        if(is_numeric($notId)) $result = $result->where($pk,'<>',$notId);
        $result = boolval($result->count('*'));
        return $result === $exist;
    }

    public function rule($pwd){
        $len = mb_strlen($pwd,'UTF8');
        //6 - 20位
        if($len > 20 || $len < 6) return lang('PASSWORD_LENGTH_ERROR',['min'=>6,'max'=>20]);
        //不能是纯数字
        if(is_numeric($pwd)) return lang('The password cannot be a pure number');
        //不能包含中文
        if(preg_match('/[\x{4e00}-\x{9fa5}]/u', $pwd)>0) return lang('The password cannot contain Chinese');
        return true;
    }
}
