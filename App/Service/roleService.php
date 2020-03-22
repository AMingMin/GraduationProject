<?php
namespace App\Service;

use App\Model\Role;
use EasySwoole\Component\Singleton;

class roleService
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
        $model = Role::create([
            'role_name' => $data['role_name'],
            'role_introduction' => $data['role_introduction'],
            'permission_id' => $data['permission_id'],
        ]);
        return $res = $model->save();
    }

    public function roleSelect()
    {
        $userInfo = Role::create()
            ->where([
                'permission_id' => 0,
                'status' => 1
            ])
            ->findOne();
        var_dump($userInfo);
        return $userInfo;
    }
}
