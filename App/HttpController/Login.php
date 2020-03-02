<?php


namespace App\HttpController;

use App\Service\LoginService;
use EasySwoole\Http\AbstractInterface\Controller;

class Login extends Controller
{


    function index()
    {
    }

    /**
     * 登录
     *
     * @throws \EasySwoole\Mysqli\Exception\Exception
     * @throws \EasySwoole\ORM\Exception\Exception
     * @throws \Throwable
     * CreateTime: 2020/2/19 上午12:04
     */
    function login()
    {
        $data = $this->request()->getRequestParam();
        $result = LoginService::getInstance()->checkLogin($data);

        if (empty($result)) {
            $this->writeJson(400, [], '登录验证失败');
        } else {
            //将数据库表查询结果的数组，设置为session值
            $_SESSION['admin']=$result;
            //var_dump($_SESSION['admin']);

            $this->writeJson(200, [], '登录成功，正在跳转...');
        }
    }

}
