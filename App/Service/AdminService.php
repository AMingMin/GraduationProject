<?php
namespace App\Service;

use App\Model\Admin;
use App\Model\Permissions;
use EasySwoole\Component\Context\ContextManager;
use EasySwoole\Component\Singleton;
use App\Model\Role;

class AdminService
{
    use Singleton;

    /**
     * 员工列表
     *
     * @param $data
     * @return array
     * @throws \EasySwoole\ORM\Exception\Exception
     * @throws \Throwable
     * CreateTime: 2020/2/20 下午11:22
     */
    public function adminList($data)
    {
        $page = $data['page']; // 页码
        // var_dump($data);
        $limit=$data['limit'];
        $model = Admin::create()->limit($limit * ($page - 1), $limit)->withTotalCount()->order('id', 'DESC');
        // 列表数据
        $list = $model->all([
            'status'=>[0,'<>']
        ], true);

        $roleIds = array_column($list, 'role_id');

        $roles = RoleService::getInstance()->getRolesByIds(array_unique($roleIds));

        $rolesMap = array_column($roles, null, 'id');

        $result = $model->lastQueryResult();
        //员工类型
        $employeeTypeTextMap =[
          0=>'理发师',
          1=>'服务员',
          2=>'洗头师'
        ];
        //性别
        $sexTextMap=[
          0=>'女',
          1=>'男'
        ];
        //员工状态
        $statusTextMap = [
            1=>'在职'
        ];

        foreach ($list as $key => $item) {
            $list[$key]['role'] = $rolesMap[$item['role_id']]['role_name']??'无';
            $list[$key]['sex_txt'] = $sexTextMap[$item['sex']];
            $list[$key]['employee_type_txt'] = $employeeTypeTextMap[$item['employee_type']];
            $list[$key]['status'] = $statusTextMap[$item['status']];
        }

        // 总条数
        $total = $result->getTotalCount();
        return [$list, $total];
    }

    /**
     * 删除该员工信息
     *
     * CreateTime: 2020/2/25 下午6:48
     */
    public function adminDelete($id)
    {
        $user = Admin::create()->get($id);  //通过id更新记录状态
        return $user->update([
            'status' => 0
        ]);
    }

    /***
     * 更新员工信息
     *
     * CreateTime: 2020/2/29 下午7:49
     */
    public function adminUpdate($data)
    {
        $id = $data['id']; //
        $name=$data['name'];
        $username=$data['username'];
        if($data['sex']==='女'){
            $sex=0;
        }else{
            $sex=1;
        }
        $employee_type=$data['employee_type'];
        $phone=$data['phone'];
        $salary=$data['salary'];
        $user = Admin::create()->get($id);  //通过id更新记录状态
        $adminInfo = ContextManager::getInstance()->get('admin');  //拿到admin中的用户信息
        return $user->update([
            'name' => $name,
            'username' => $username,
            'sex' => $sex,
            'employee_type' => $employee_type,
            'phone' => $phone,
            'salary' => $salary,
            'update_staff' => $adminInfo['name'],
            'update_time' => date('Y-m-d H:i:s',time()),//更新时间，当前时间
            'role_id' => $data['role_id']
        ]);
    }

    /***
     * 插入员工信息
     *
     * @param $data
     * CreateTime: 2020/2/29 下午10:57
     */
    public function adminInsert($data)
    {
        //var_dump('向数据库插入');
        $name=$data['name'];
        $username=$data['username'];
        if($data['sex']==='女'){
            $sex=0;
        }else{
            $sex=1;
        }
        $employee_type=$data['employee_type'];
        $phone=$data['phone'];
        $salary=$data['salary'];
        $adminInfo = ContextManager::getInstance()->get('admin');  //拿到admin中的用户信息
        $model = Admin::create([
            'name' => $name,
            'username' => $username,
            'sex' => $sex,
            'employee_type' => $employee_type,
            'phone' => $phone,
            'salary' => $salary,
            'role_id' => $data['role_id'],
            'password' => 123456,
            'create_staff' => $adminInfo['name'],
            'update_staff' => $adminInfo['name'],
            'create_time' =>date('Y-m-d H:i:s',time()),//时间，当前时间
            'update_time' => date('Y-m-d H:i:s',time())//更新时间，当前时间
            ]);
        return $res = $model->save();
    }

    //获取用户权限
    public function getPermission($adminInfo)
    {
        if ($adminInfo['username'] === 'admin') {
            return $this->allPermissions();
        }

        $roleId = $adminInfo['role_id'];
        if (empty($roleId)) {
            return [];
        }

        //查询角色信息
        $roleInfo = Role::create()->get($roleId)->toArray();
        if (empty($roleInfo['permission_id'])) {
            return [];
        }

        $permission = json_decode($roleInfo['permission_id']);  //存放某个角色所有的权限id
        if (empty($permission)) {
            return [];
        }

        $oneIds = [];
        $twoIds = [];
        //遍历一二级id
        foreach ($permission as $oneId => $item)
        {
            $oneIds[] = $oneId;
            $twoIds = array_merge($twoIds, $item);
        }

        // 查询一级菜单信息
        $onePermissions = Permissions::create()->where([
            'id'=>[$oneIds, 'in'],
            'status'=>1,
        ])->all(null, true);
        $onePermissions = array_column($onePermissions, null, 'id');
        // 查询二级菜单信息
        $twoPermissions = Permissions::create()->where([
            'id' => [$twoIds, 'in'],
            'status' => 1,
        ])->all(null, true);

        // 拼装数据
        foreach ($twoPermissions as $item)
        {
            $onePermissions[$item['pid']]['two'][] = $item;
        }

        return array_values($onePermissions);
    }

    /**
     * 账户admin，拥有最高权限
     *
     * CreateTime: 2020/5/8 下午10:50
     */
    private function allPermissions()
    {
        // 查询一级菜单信息
        $onePermissions = Permissions::create()->where([
            'pid'=>0,
            'status'=>1,
        ])->all(null, true);
        $onePermissions = array_column($onePermissions, null, 'id');
        // 查询二级菜单信息
        $twoPermissions = Permissions::create()->where([
            'pid' => [0,'<>'],
            'status' => 1,
        ])->all(null, true);

        // 拼装数据
        foreach ($twoPermissions as $item)
        {
            $onePermissions[$item['pid']]['two'][] = $item;
        }

        return array_values($onePermissions);
    }

}
