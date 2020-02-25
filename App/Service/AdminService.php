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
        $list = $model->all([
            'status'=>[0,'<>']
        ], true);
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

            $list[$key]['sex'] = $sexTextMap[$item['sex']];
            $list[$key]['employee_type'] = $employeeTypeTextMap[$item['employee_type']];
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
}
