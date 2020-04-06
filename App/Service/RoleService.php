<?php
namespace App\Service;

use App\Model\Role;
use EasySwoole\Component\Singleton;

class RoleService
{
    use Singleton;

    /***
     * 获取角色列表
     *
     * @param $data
     * @return array
     * @throws \EasySwoole\ORM\Exception\Exception
     * @throws \Throwable
     * CreateTime: 2020/3/22 下午9:51
     */
    public function roleList($data)
    {
        $page = $data['page']; // 页码
        $limit=$data['limit'];
        $model = Role::create()->limit($limit * ($page - 1), $limit)->withTotalCount()->order('id', 'DESC');
        // 列表数据
        $list = $model->all([
            'status'=>[0,'<>']
        ], true);
        $result = $model->lastQueryResult();
        // 总条数
        $total = $result->getTotalCount();
        return [$list, $total];
    }

    /***
     * 添加角色
     *
     * @param $data
     * @return bool|int
     * @throws \EasySwoole\ORM\Exception\Exception
     * @throws \Throwable
     * CreateTime: 2020/3/22 下午10:45
     */
    public function roleInsert($data)
    {
        $permissionId = [];
        foreach ($data['permission']??[] as $twoId => $oneId)
        {
            $permissionId[$oneId][] = $twoId;
        }

        $model = Role::create([
            'role_name' => $data['roleName'],
            'role_introduction' => $data['roleIntroduction'],
            'permission_id' => json_encode($permissionId, JSON_UNESCAPED_UNICODE),
            'status' => 1
        ]);

        return $res = $model->save();
    }

    public function roleSelect()
    {
        $userInfo = Role::create()
            ->where([
                'status' => 1
            ])
            ->all(null, true);
        return $userInfo;
    }

    public function getRolesByIds(array $ids)
    {
        $roles = Role::create()
            ->where(['id'=>[$ids, 'in']])
            ->all(null, true);
        return $roles;
    }
}
