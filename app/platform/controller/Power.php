<?php
declare (strict_types = 1);

namespace app\platform\controller;

use app\common\controller\StatusCode;
use app\common\service\Validate;
use app\platform\service\PowerService;
use app\platform\service\RolesService;
use think\exception\ValidateException;
use think\facade\Db;
use think\Request;

class Power extends BaseController
{

    /**
     * 角色选项
     * @param PowerService $powerService
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * Date: 2020/8/22 13:46
     */
    public function roleOption(RolesService $rolesService){
        $roles = $rolesService->getRoleOption();
        return result('ok',$roles);
    }
    /**
     * 角色列表
     * @param PowerService $powerService
     * @return \think\response\Json
     * @throws \think\db\exception\DbException
     * Date: 2020/8/25 11:38
     */
    public function roles(PowerService $powerService){
        $where = [];
        array_key_exists('name',$this->param) && $this->param['name'] and $where[] = ['name','like',"%".$this->param['name']."%"];
        $lists = $powerService->roleLists($where);
        $button = $powerService->getRules(['mode'=>"button"]);
        foreach ($lists as $list){
            $list->rules = $list->rules ? implode(',',array_intersect(explode(',',$list->rules),$button)) : implode(',',$button);
        }
        return result('ok',$lists,StatusCode::$SUCCESS);
    }
    /**
     * 新增角色
     * @param PowerService $powerService
     * @param Validate $validateService
     * @return \think\response\Json
     * Date: 2020/8/25 14:41
     */
    public function addRole(PowerService $powerService,Validate $validateService){
        try{
            startTrans();
            $this->validate($this->param,'Role.create');
            $validate = $validateService->exist($this->param['name'],'admin_rules','name',false);
            if(!$validate === true){
                rollback();
                return result('角色已存在');
            }
            $res = $powerService->newRole($this->param['name'],$this->param['remark']);
            if($res){
                commit();
                return result('添加成功',StatusCode::$SUCCESS);
            }else{
                rollback();
                return result('添加失败',StatusCode::$FAIL);
            }
        }catch (ValidateException $validate){
            rollback();
            return result($validate->getMessage(),StatusCode::$FAIL);
        }catch (\Throwable $t){
            rollback();
            trace('角色添加异常：'.$t->getMessage().' - '.$t->getLine(),'error');
            return result('添加失败',StatusCode::$FAIL);
        }
    }
    /**
     * 编辑角色
     * @param PowerService $powerService
     * @param Validate $validateService
     * @return \think\response\Json
     * Date: 2020/8/25 17:33
     */
    public function modifyRole(PowerService $powerService,Validate $validateService){
        try{
            startTrans();
            $this->validate($this->param,'Role.modify');
            $validate = $validateService->exist($this->param['name'],'admin_rules','name',$this->param['id'],false);
            if(!$validate === true){
                rollback();
                return result('角色已存在');
            }
            $res = $powerService->modifyRole(intval($this->param['id']),$this->param);
            if($res){
                commit();
                return result('修改成功',StatusCode::$SUCCESS);
            }else{
                rollback();
                return result('修改失败');
            }
        }catch (ValidateException $validate){
            rollback();
            return result($validate->getMessage());
        }catch (\Throwable $t){
            rollback();
            trace('编辑角色异常：'.$t->getMessage().' - '.$t->getLine(),'error');
            return result('修改失败');
        }

    }
    /**
     * 角色授权
     * @param PowerService $powerService
     * @return \think\response\Json
     * Date: 2020/8/25 17:46
     */
    public function authorization(PowerService $powerService){
        try{
            startTrans();
            $this->validate($this->param,'Role.authorization');
            request()->logs = '角色授权';
            $res = $powerService->modifyRole(intval($this->param['id']),$this->param);
            if($res === true) {
                commit();
                return result('授权成功',StatusCode::$SUCCESS);
            }else{
                rollback();
                return result('授权失败');
            }
        }catch (ValidateException $validate){
            rollback();
            return result($validate);
        }catch (\Throwable $t){
            rollback();
            return result('授权失败');
        }
    }
    /**
     * 删除角色
     * @param PowerService $powerService
     * @return \think\response\Json
     * Date: 2020/8/26 10:32
     */
    public function delRole(PowerService $powerService){
        try{
            $this->validate($this->param,'Role.del');
            $res = $powerService->delRole(intval($this->param['id']));
            if($res === true) {
                return result('删除成功',StatusCode::$SUCCESS);
            }else{
                return result('删除失败',StatusCode::$FAIL);
            }
        }catch (ValidateException $validate){
            return result($validate,StatusCode::$FAIL);
        }catch (\Throwable $t){
            trace('角色删除异常：'.$t->getMessage().' - '.$t->getLine().'error');
            return result('删除失败',StatusCode::$FAIL);
        }
    }
}
