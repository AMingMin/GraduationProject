<?php
namespace EasySwoole\EasySwoole;


use App\Config\JwtConfig;
use App\Config\RequestCodeConfig;
use EasySwoole\EasySwoole\Swoole\EventRegister;
use EasySwoole\EasySwoole\AbstractInterface\Event;
use EasySwoole\Http\Request;
use EasySwoole\Http\Response;
use EasySwoole\Jwt\Jwt;
use EasySwoole\ORM\Db\Config;
use EasySwoole\ORM\Db\Connection;
use EasySwoole\ORM\DbManager;

class EasySwooleEvent implements Event
{

    public static function initialize()
    {
        // TODO: Implement initialize() method.
        date_default_timezone_set('Asia/Shanghai');
    }

    /***
     *
     *
     * @param EventRegister $register
     * CreateTime: 2020/3/12 下午4:11
     */
    public static function mainServerCreate(EventRegister $register)
    {
        // TODO: Implement mainServerCreate() method.
        //设置数据库连接参数
        $config = new Config();
        $config->setDatabase('haircut');
        $config->setUser('root');
        $config->setPassword('123456');
        $config->setHost('127.0.0.1');

        DbManager::getInstance()->addConnection(new Connection($config));

        // 配置同上别忘了添加要检视的目录
        $hotReloadOptions = new \EasySwoole\HotReload\HotReloadOptions;
        $hotReload = new \EasySwoole\HotReload\HotReload($hotReloadOptions);
        $hotReloadOptions->setMonitorFolder([EASYSWOOLE_ROOT . '/App']);

        $server = ServerManager::getInstance()->getSwooleServer();
        $hotReload->attachToServer($server);

    }

    /***
     * 每次easyswoole的请求都会执行该方法
     *
     * @param Request $request
     * @param Response $response
     * @return bool
     * @throws \EasySwoole\Jwt\Exception
     * CreateTime: 2020/3/12 下午4:04
     */
    public static function onRequest(Request $request, Response $response): bool
    {
        // TODO: Implement onRequest() method.
        $response->withHeader('Access-Control-Allow-Origin', '*');
        $response->withHeader('Access-Control-Allow-Methods', 'GET, POST, OPTIONS');
        $response->withHeader('Access-Control-Allow-Credentials', 'true');
        $response->withHeader('Access-Control-Allow-Headers', 'token, Content-Type, Authorization, X-Requested-With');

        if ($request->getMethod() === 'OPTIONS') { //默认两次请求，设置该请求时无操作
            return false;
        }
//
//        $token = $request->getHeader('token')[0];
//        if ($token === 'login') {
//            return true;
//        } else if ($token === 'null') {
//            $response->write(json_encode(['code'=>RequestCodeConfig::CODE_LOGIN]));
//            return false;
//        } else {
//            $jwtObject = Jwt::getInstance()->setSecretKey(JwtConfig::SECRET_KEY)->decode($token);
//            $status = $jwtObject->getStatus();
//            if ($status !== 1) {
//                $response->write(json_encode(['code'=>RequestCodeConfig::CODE_LOGIN]));
//                return false;
//            }
//        }

        return true;
    }

    public static function afterRequest(Request $request, Response $response): void
    {
        // TODO: Implement afterAction() method.
    }
}
