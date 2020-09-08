<?php
declare (strict_types = 1);

namespace app\platform\controller;

use app\common\controller\StatusCode;
use app\common\service\Validate;
use app\platform\service\RolesService;
use think\exception\ValidateException;

/**
 * 角色管理
 * Class Roles
 * @package app\platform\controller
 * Date: 2020/9/1 14:26
 */
class Roles extends BaseController
{
    /**
     * 角色列表
     * @param RolesService $rolesService
     * @return \think\response\Json
     * @throws \think\db\exception\DbException
     * Date: 2020/9/1 15:07
     */
    public function lists(RolesService $rolesService){
        //查询条件解析
        $where = [];
        array_key_exists('name',$this->param) && $this->param['name'] and $where[] = ['name','like',"%".$this->param['name']."%"];
        //获取列表数据
        $lists = $rolesService->roleLists($where);
        //读取权限码
        foreach ($lists as $list){ $list->rules = '';}
        return result('ok',$lists,StatusCode::$SUCCESS);
    }

    /**
     * 添加角色
     * @param Validate $validateService
     * @param RolesService $rolesService
     * @return \think\response\Json
     * Date: 2020/9/1 14:59
     */
    public function add(Validate $validateService,RolesService $rolesService){
        try{
            startTrans();
            //表单验证
            $this->validate($this->param,'Role.create');
            //额外参数验证
            $validate = $validateService->exist($this->param['role_name'],'roles','role_name',false);
            if(!$validate === true){
                rollback();
                return result('角色已存在');
            }
            //执行角色新增
            $res = $rolesService->newRole($this->param['role_name'],$this->param['introduce']);
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
     * @param Validate $validateService
     * @param RolesService $rolesService
     * @return \think\response\Json
     * Date: 2020/9/1 15:33
     */
    public function modify(Validate $validateService,RolesService $rolesService){
        try{
            startTrans();
            //表单验证
            $this->validate($this->param,'Role.modify');
            //额外参数验证
            $validate = $validateService->exist($this->param['role_name'],'roles','role_name',$this->param['id'],false);
            if(!$validate === true){
                rollback();
                return result('角色已存在');
            }
            //执行角色编辑
            $res = $rolesService->editRole($this->param['id'],$this->param['role_name'],$this->param['introduce']);
            if($res){
                commit();
                return result('编辑成功',StatusCode::$SUCCESS);
            }else{
                rollback();
                return result('编辑失败',StatusCode::$FAIL);
            }
        }catch (ValidateException $validate){
            rollback();
            return result($validate->getMessage(),StatusCode::$FAIL);
        }catch (\Throwable $t){
            rollback();
            trace('角色编辑异常：'.$t->getMessage().' - '.$t->getLine(),'error');
            return result('编辑失败',StatusCode::$FAIL);
        }
    }

    /**
     * 角色授权
     * @param RolesService $rolesService
     * @return \think\response\Json
     * Date: 2020/9/1 17:21
     */
    public function authorization(RolesService $rolesService){
        try{
            startTrans();
            //表单验证
            $this->validate($this->param,'Role.authorization');
            $res = $rolesService->authorization($this->param['id'],explode(',',$this->param['rules']));
            if($res){
                commit();
                return result('授权成功',StatusCode::$SUCCESS);
            }else{
                rollback();
                return result('授权失败',StatusCode::$FAIL);
            }
        }
        catch (ValidateException $validate){
            rollback();
            return result($validate->getMessage(),StatusCode::$FAIL);
        }
        catch (\Throwable $t){
            rollback();
            trace('角色授权异常：'.$t->getMessage().' - '.$t->getLine(),'error');
            return result('授权失败',StatusCode::$FAIL);
        }
    }

    /**
     * 删除角色
     * @param RolesService $rolesService
     * @return \think\response\Json
     * Date: 2020/9/1 17:43
     */
    public function del(RolesService $rolesService){
        try{
            startTrans();
            $this->validate($this->param,'Role.del');
            $res = $rolesService->del($this->param['id']);
            if($res){
                commit();
                return result('删除成功',StatusCode::$SUCCESS);
            }else{
                rollback();
                return result('删除失败');
            }
        }catch (ValidateException $validate){
            rollback();
            return result($validate->getMessage());
        }catch (\Throwable $t){
            rollback();
            trace('角色删除异常：'.$t->getMessage().' - '.$t->getLine(),'error');
            return result('删除失败');
        }
    }
}
