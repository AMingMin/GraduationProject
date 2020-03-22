<?php
namespace App\Service;

use App\Model\Admin;
use App\Model\Permissions;
use EasySwoole\Component\Singleton;

class PermissionsService
{
    use Singleton;

    /***
     * 获取权限列表
     *
     * @param $data
     * @return array
     * @throws \EasySwoole\ORM\Exception\Exception
     * @throws \Throwable
     * CreateTime: 2020/3/22 下午9:34
     */
    public function permissionsList($data)
    {
        $page = $data['page']; // 页码
        $limit=$data['limit'];
        $model = Permissions::create()->limit($limit * ($page - 1), $limit)->withTotalCount()->order('id', 'DESC');
        // 列表数据
        $list = $model->all([
            'status'=>[0,'<>']
        ], true);
        $result = $model->lastQueryResult();
        // 总条数
        $total = $result->getTotalCount();
        return [$list, $total];
    }
}
