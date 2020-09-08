<?php
declare (strict_types = 1);

namespace app\platform\service;

use app\common\model\Admins;
use org\Rsa;

class AdminService  extends \think\Service
{

    /**
     * 管理员列表
     * @param $where
     * @return \think\Paginator
     * @throws \think\db\exception\DbException
     */
    public function adminLists($where)
    {
    	return Admins::lists($where,[],['adminRules'],'');
    }


    /**
     * 添加管理员
     * @param $username
     * @param $password
     * @param $role
     * @param $nickname
     * @param $telephone
     * @param $email
     * @return Admins|\think\Model
     */
    public function newAdmin($username,$password,$role,$nickname,$telephone,$email)
    {
        $data = [
            'username'=>$username,
            'password'=>Rsa::encode($password),
            'nickname'=>$nickname,
            'telephone'=>$telephone,
            'email'=>$email,
            'role_id'=>$role,
        ];
        $res = Admins::create($data);
        return $res;
    }

    /**
     * 修改管理员信息
     * @param int $id
     * @param $data
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function modifyAdmin(int $id,$data)
    {
        return Admins::modify($id,$data);
    }

    /**
     * 删除管理员
     * @param int $id
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function delAdmin(int $id)
    {
        return Admins::del($id);
    }
}
