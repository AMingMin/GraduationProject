<?php
namespace App\HttpController;

use App\Service\PermissionsService;
use EasySwoole\Http\AbstractInterface\Controller;

class Permissions extends Controller
{
    /***
     * 获取权限列表
     *
     * @throws \EasySwoole\ORM\Exception\Exception
     * @throws \Throwable
     * CreateTime: 2020/3/22 下午9:43
     */
    function index()
    {
        $request=$this->request();
        $data = $request->getRequestParam();

        [$list, $total] = PermissionsService::getInstance()->permissionsList($data);

        $this->writeJson(200, [
            'count' => $total,
            'data'  => $list
        ], '获取成功！');

    }
}
