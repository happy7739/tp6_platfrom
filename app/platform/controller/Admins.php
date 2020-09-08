<?php
declare (strict_types = 1);

namespace app\platform\controller;

use app\common\controller\StatusCode;
use app\common\service\Validate;
use app\platform\service\AdminService;
use app\platform\service\PowerService;
use org\Rsa;
use think\exception\ValidateException;

class Admins extends BaseController
{
    /**
     * 管理员列表
     * @param AdminService $adminService
     * @return \think\response\Json
     * Date: 2020/8/22 15:09
     */
    public function lists(AdminService $adminService){
        try{
            $where = [];
            array_key_exists('name',$this->param) && $this->param['name'] and $where[] = ['username','like',"%".$this->param['name']."%"];
            $lists = $adminService->adminLists($where);
            return result('ok',$lists,StatusCode::$SUCCESS);
        }catch (\Throwable $throwable){
            return result($throwable->getMessage(),StatusCode::$FAIL);
        }

    }
    /**
     * 添加管理员
     * @param AdminService $adminService
     * @param Validate $validateService
     * @return \think\response\Json
     * Date: 2020/8/22 15:09
     */
    public function add(AdminService $adminService,Validate $validateService){
        try{
            startTrans();
            $this->validate($this->param,'Admin.create');
            $validate = $validateService->exist($this->param['username'],'admins','username',false);
            if(!$validate === true){
                rollback();
                return result('账号已存在');
            }
            $res = $adminService->newAdmin($this->param['username'],$this->param['password'],$this->param['role_id'],$this->param['nickname'],$this->param['telephone'],$this->param['email']);
            if($res){
                commit();
                return result('添加成功',StatusCode::$SUCCESS);
            } else {
                rollback();
                return result('添加失败');
            }
        }catch (ValidateException $validate){
            rollback();
            return result($validate->getMessage());
        }catch (\Throwable $t){
            rollback();
            trace('添加管理员异常：'.$t->getMessage().' - '.$t->getLine(),'error');
            return result('添加失败');
        }
    }

    /**
     * 编辑管理员
     * @param AdminService $adminService
     * @param Validate $validateService
     * @return \think\response\Json
     */
    public function modify(AdminService $adminService,Validate $validateService){
        try{
            startTrans();
            $this->validate($this->param,'Admin.modify');
            $validate = $validateService->exist($this->param['username'],'admins','username',$this->param['id'],false);
            if(!$validate === true){
                rollback();
                return result('账号已存在');
            }
            if($this->param['password']){//有新密码，加密
                $validate = $validateService->rule($this->param['password']);
                if(!$validate === true){
                    rollback();
                    return result($validate);
                }
                $this->param['password'] = Rsa::encode($this->param['password']);
            }else{//无密码，所以删除密码字段，不修改密码
                unset($this->param['password']);
            }
            $res = $adminService->modifyAdmin(intval($this->param['id']),$this->param);
            if($res){
                commit();
                return result('修改成功',StatusCode::$SUCCESS);
            }else{
                rollback();
                return result('修改失败1');
            }
        }catch (ValidateException $validate){
            rollback();
            return result($validate);
        }catch (\Throwable $t){
            rollback();
            trace('修改管理员异常：'.$t->getMessage().' - '.$t->getLine(),'error');
            return result('修改失败2');
        }
    }
    /**
     * 删除管理员
     * @param AdminService $adminService
     * @return \think\response\Json
     * Date: 2020/8/24 13:34
     */
    public function del(AdminService $adminService){

        try{
            startTrans();
            $res = $adminService->delAdmin(intval($this->param['id']));
            if($res){
                commit();
                return result('删除成功',StatusCode::$SUCCESS);
            }else{
                rollback();
                return result('删除失败');
            }
        }catch (\Throwable $t){
            rollback();
            trace('管理员删除异常：'.$t->getMessage(),'error');
            return result('删除失败');
        }
    }
}
