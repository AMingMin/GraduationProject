<?php
namespace App\Service;

use App\Model\Admin;
use EasySwoole\Component\Singleton;

class LoginService
{
    use Singleton;

    /**
     * 验证登录
     *
     * @param $data
     * @return array|\EasySwoole\ORM\AbstractModel|null
     * @throws \EasySwoole\Mysqli\Exception\Exception
     * @throws \EasySwoole\ORM\Exception\Exception
     * @throws \Throwable
     * CreateTime: 2020/2/19 上午12:04
     */
    public function checkLogin($data)
    {
        $userInfo = Admin::create()
            ->where([
                'username' => $data['username'],
                'password' => $data['password'],
                'status' => 1
            ])
            ->findOne();
        return $userInfo;
    }
}
