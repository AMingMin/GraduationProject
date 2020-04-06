<?php
namespace App\HttpController;

use App\Service\RoleService;
use EasySwoole\Http\AbstractInterface\Controller;

class Role extends Controller
{
    /***
     * 获得角色列表
     *
     * @throws \EasySwoole\ORM\Exception\Exception
     * @throws \Throwable
     * CreateTime: 2020/3/22 下午9:49
     */
    function index()
    {
        $request=$this->request();
        $data = $request->getRequestParam();

        [$list, $total] = RoleService::getInstance()->roleList($data);

        $this->writeJson(200, [
            'count' => $total,
            'data'  => $list
        ], '获取成功！');

    }

    /***
     * 添加新角色
     *
     * CreateTime: 2020/3/22 下午10:44
     */
    function insert()
    {
        $data = $this->request()->getRequestParam();
        $result = RoleService::getInstance()->roleInsert($data);
        if ($result) {
            $this->writeJson(200, [], '角色创建成功');
        } else {
            $this->writeJson(400, [], '角色创建失败');
        }
    }

    function get(){
        $result = RoleService::getInstance()->roleSelect();
        if ($result) {
            $this->writeJson(200, $result, '获取角色列表成功');
        } else {
            $this->writeJson(400, [], '获取角色列表成功');
        }
    }

}
