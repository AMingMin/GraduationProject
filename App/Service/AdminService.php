<?php
namespace App\Service;

use App\Model\Admin;
use EasySwoole\Component\Singleton;

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
        $limit=$data['limit'];
        $model = Admin::create()->limit($limit * ($page - 1), $limit)->withTotalCount();
        // 列表数据
        $list = $model->all(null, true);
        $result = $model->lastQueryResult();
        // 总条数
        $total = $result->getTotalCount();

        return [$list, $total];
    }
}
