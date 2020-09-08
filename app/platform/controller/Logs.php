<?php
declare (strict_types = 1);

namespace app\platform\controller;


use app\common\controller\StatusCode;
use app\platform\service\LogService;

class Logs extends BaseController
{
    /**
     * @param LogService $logService
     * @return \think\Paginator
     * @throws \think\db\exception\DbException
     * Date: 2020/8/24 11:19
     */
    public function admins(LogService $logService){
        $where = [];
        array_key_exists('name',$this->param) && $this->param['name'] and $where['admins'][] = ['username','like',"%".$this->param['name']."%"];
        array_key_exists('start',$this->param) && $this->param['start'] and $where['this'][] = ['create_time','>=',strtotime($this->param['start'])];
        if(array_key_exists('end',$this->param) && $this->param['end']){
            if(array_key_exists('start',$this->param) && $this->param['start']){
                array_pop($where['this']);
                $where['this'][] = ['create_time','between',[strtotime($this->param['start']),strtotime($this->param['end'].' 23:59:59')]];
            }else{
                $where['this'][] = ['create_time','<',strtotime($this->param['end'])];
            }

        }
        return result('ok',$logService->logs($where),StatusCode::$SUCCESS);
    }
}
