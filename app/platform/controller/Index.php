<?php
declare (strict_types = 1);

namespace app\platform\controller;

use app\common\controller\StatusCode;
use app\platform\service\PowerService;
use org\Rsa;

class Index extends BaseController
{
    /**
     * mini 框架配置
     * @param PowerService $powerService
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * Date: 2020/8/21 17:04
     */
    public function menuLists(PowerService $powerService){
        $power = $powerService->setRule()->getMenu();
        if($power === 403) return result('账号异常，请联系管理员',StatusCode::$LOGIN);
        $info = [
            'logoInfo'=>['title'=>'云荒纪年'],
            'menuInfo'=>$power,
            'operator'=>request()->adminInfo->username,
        ];
       return result('ok',$info,StatusCode::$SUCCESS);
    }
}
