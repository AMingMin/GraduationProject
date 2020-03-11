<?php


namespace App\HttpController;

use App\Config\JwtConfig;
use App\Service\LoginService;
use EasySwoole\Http\AbstractInterface\Controller;
use EasySwoole\Jwt\Jwt;

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
            $jwtObject = Jwt::getInstance()
                ->setSecretKey(JwtConfig::SECRET_KEY) // 秘钥
                ->publish();
            $jwtObject->setAlg(JwtConfig::ALG); // 加密方式
            $jwtObject->setExp(time() + JwtConfig::EXP); // 过期时间
            $jwtObject->setData($result);
            $token = $jwtObject->__toString();
            $this->writeJson(200, $token, '登录成功，正在跳转...');
        }
    }

}
