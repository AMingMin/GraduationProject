<?php
namespace App\HttpController;

use App\Service\AdminService;
use EasySwoole\Http\AbstractInterface\Controller;

class Admin extends Controller
{
    /**
     *获取员工列表数据
     *
     * @throws \EasySwoole\ORM\Exception\Exception
     * @throws \Throwable
     * CreateTime: 2020/2/25 下午6:02
     */
    function index()
    {
        $request=$this->request();
        $data = $request->getRequestParam();

        [$list, $total] = AdminService::getInstance()->adminList($data);

        $this->writeJson(200, [
            'count' => $total,
            'data'  => $list
        ], '获取成功！');

    }

    /**
     *获取员工列表中的 '操作'
     *
     * CreateTime: 2020/2/25 下午6:03
     */
    function delete()
    {
        $data = $this->request()->getRequestParam();
        //var_dump($data);
        $result = AdminService::getInstance()->adminDelete($data['id']);
        if ($result) {
            $this->writeJson(200, [], '删除员工成功');
        } else {
            $this->writeJson(400, [], '删除员工失败');
        }
    }

    /***
     * 将编辑后的员工表单的信息更新到数据库
     *
     * CreateTime: 2020/2/29 下午7:51
     */
    function update()
    {
        $data = $this->request()->getRequestParam();
        //var_dump($data);
        $result = AdminService::getInstance()->adminUpdate($data);
        $data['update_staff']=$_SESSION['admin']['name'];  //更新人
        $data['update_time']=date('y-m-d h:i:s',time());//更新时间，当前时间
        $data['update_staff']=$_SESSION['admin']['name'];
        if ($result) {
            $this->writeJson(200, [], '员工信息更新成功');
        } else {
            $this->writeJson(400, [], '员工信息更新失败');
        }
    }

    /***
     * '添加员工'到数据库
     *
     * CreateTime: 2020/2/29 下午10:24
     */
    function insert()
    {
        $data = $this->request()->getRequestParam();
        //var_dump($data);
        $data['create_staff']=$_SESSION['admin']['name'];
        $data['update_staff']=$_SESSION['admin']['name'];
        //var_dump($_SESSION['admin']);
        //var_dump($data);
        $result = AdminService::getInstance()->adminInsert($data);
        if ($result) {
            $this->writeJson(200, [], '员工创建成功');
        } else {
            $this->writeJson(400, [], '员工创建失败');
        }
    }

}
