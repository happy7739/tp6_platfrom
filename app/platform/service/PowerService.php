<?php
declare (strict_types = 1);

namespace app\platform\service;

use app\common\model\Admins;
use app\common\model\Menu;
use app\common\model\MicroRule;
use app\common\model\Powers;
use app\common\model\Roles;
use app\common\model\RolesPower;
use app\platform\controller\Power;
use org\Rsa;
use think\helper\Str;

/**
 * 权限
 * Class PowerService
 * @package app\platform\service
 * Date: 2020/9/1 11:02
 */
class PowerService
{
    private $rule;//角色信息
    private $power;//可操作权限

    /**
     * 设置登录用户角色信息
     * @return $this
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * Date: 2020/8/21 17:29
     */
    public function setRule(){
        if(is_numeric(request()->adminId)){
            $Info = Admins::where('id',request()->adminId)->field('is_default,role_id')->find();
            //获取角色
            //系统默认账号自动分配默认角色
            $this->rule = $Info->is_default === 1 ? Roles::where('is_default',1)->find() : Roles::where('id',$Info->role_id)->find();
            if(!$this->rule){
                $this->power = null;
                return $this;
            }
            //获取权限
            //系统默认角色拥有系统所有权限
            $this->power = $this->rule->is_default === 1 ? Powers::column('id') : RolesPower::where('role_id',$this->rule->id)->column('power_id');
        }
        return $this;
    }

    /**
     * 权限验证
     * @param string || integer $controller 控制器或者权限ID
     * @param string $action 方法
     * @param string $method 请求方式
     * @return int
     * Date: 2020/8/22 15:50
     */
    public function verify($controller,$action = '',$method = ''):int {
        if(!$this->rule) return 403;

        $power_id = is_numeric($controller) ? Powers::where('id',$controller)->value('id') : Powers::where('controller',Str::camel($controller))->where('action',Str::camel($action))->where('method',strtolower($method))->value('id');
        if(!is_numeric($power_id)) return 404;

        return intval(in_array($power_id,$this->power));
    }

    /**
     * 获取菜单
     * @return \think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * Date: 2020/9/1 12:01
     */
    public function getMenu(){
        $menuSnd = Powers::whereIn('id',$this->power)->group('menu_id')->column('menu_id');
        request()->second = $menuSnd;
        $menuFst = Menu::whereIn('id',$menuSnd)->group('pid')->column('pid');
        $menu = Menu::whereIn('id',$menuFst)->with('child')->field('title,id,icon')->select();
        return $menu;
    }

    /**
     * 权限树
     * @return \think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * Date: 2020/9/1 15:55
     */
    public function menuTree(){
        return Menu::where('level',1)->with('children')->where('is_show',1)->field('id,title')->select();
    }
}
