<?php
declare (strict_types = 1);

namespace app\platform\service;

use app\common\model\AdminLogs;

/**
 * 记录服务
 * Class LogService
 * @package app\platform\service
 * Date: 2020/8/24 11:18
 */
class LogService
{
    /**
     * @param array|bool $condition
     * @return \think\Paginator
     * @throws \think\db\exception\DbException
     * Date: 2020/8/26 14:10
     */
    public function logs($condition = true){
        return AdminLogs::lists($condition,['id'=>"desc"],['admins'],'admin_id,mode');
    }

}
