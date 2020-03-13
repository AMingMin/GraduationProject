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
        // var_dump($data);
        $limit=$data['limit'];
        $model = Admin::create()->limit($limit * ($page - 1), $limit)->withTotalCount()->order('id', 'DESC');
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
        $update_staff=$data['update_staff'];
        $update_time=$data['update_time'];
        $user = Admin::create()->get($id);  //通过id更新记录状态
        return $user->update([
            'name' => $name,
            'username' => $username,
            'sex' => $sex,
            'employee_type' => $employee_type,
            'phone' => $phone,
            'salary' => $salary,
            'update_staff' => $update_staff, //更新人
            'update_time' =>$update_time, //更新时间，当前时间
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
        $create_staff=$data['create_staff'];
        $model = Admin::create([
            'name' => $name,
            'username' => $username,
            'sex' => $sex,
            'employee_type' => $employee_type,
            'phone' => $phone,
            'salary' => $salary,
            'create_staff' => $create_staff,
            'update_staff' => $create_staff,  //更新人初始为新建人
            ]);
        return $res = $model->save();


    }

}
