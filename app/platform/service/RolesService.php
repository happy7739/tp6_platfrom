<?php
declare (strict_types = 1);

namespace app\platform\service;

use app\common\model\AdminLogs;
use app\common\model\Powers;
use app\common\model\Roles;
use app\common\model\RolesPower;

/**
 * 角色服务
 * Class RolesService
 * @package app\platform\service
 * Date: 2020/9/1 14:30
 */
class RolesService
{
    /**
     * 列表
     * @param bool|array $where
     * @return \think\Paginator
     * @throws \think\db\exception\DbException
     * Date: 2020/9/1 14:30
     */
    public function roleLists($where = true){
        return Roles::lists($where);
    }

    /**
     * 新建角色
     * @param $name
     * @param string $introduce
     * @return bool
     * Date: 2020/9/1 15:21
     */
    public function newRole($name,$introduce = ''){
        $role = [
            'role_name'=>$name,
            'introduce'=>$introduce,
            'is_default'=>0
        ];
        $roles = Roles::create($role);
        $role_id = $roles->id;
        $powers = Powers::where('is_default',1)->column('id');
        $rp = [];
        foreach ($powers as $power_id){
            $rp[] = [
                'role_id'=>$role_id,
                'power_id'=>$power_id
            ];
        }
        $prs = RolesPower::insertAll($rp);
        return (is_numeric($role_id) && $prs);
    }

    /**
     * 修改角色
     * @param $pk
     * @param $name
     * @param $introduce
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * Date: 2020/9/1 15:21
     */
    public function editRole($pk,$name,$introduce){
        return Roles::modify(intval($pk),['role_name'=>$name,'introduce'=>$introduce]);
    }

    /**
     * 角色授权
     * @param $pk
     * @param $codes
     * @return bool
     * Date: 2020/9/1 17:17
     */
    public function authorization($pk,$codes){
        $old = RolesPower::where('role_id',$pk)->column('power_id');
        //清除已有的权限
        $clean = RolesPower::where('role_id',$pk)->delete();
        //创建新的权限
        $rules = Powers::whereIn('code',$codes)->column('id');
        $powers = [];
        foreach ($rules as $power_id){
            $powers[] = [
                'role_id'=>$pk,
                'power_id'=>$power_id
            ];
        }
        $new = RolesPower::insertAll($powers);
        AdminLogs::write('角色授权',$old,$rules);
        return ($clean && $new);
    }

    /**
     * 删除角色
     * @param $pk
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * Date: 2020/9/1 17:36
     */
    public function del($pk){
        return Roles::del(intval($pk));
    }

    /**
     * 所有角色
     * @return \think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getRoleOption()
    {
        $role = Roles::field('id,role_name')->select();
        return $role;
    }
}
