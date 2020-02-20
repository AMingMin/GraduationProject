<?php
namespace App\HttpController;

use App\Service\AdminService;
use EasySwoole\Http\AbstractInterface\Controller;

class Admin extends Controller
{

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

}
