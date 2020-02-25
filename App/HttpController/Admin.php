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
}
