<?php
namespace EasySwoole\EasySwoole;


use App\Config\JwtConfig;
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

    public static function onRequest(Request $request, Response $response): bool
    {
        // TODO: Implement onRequest() method.
        $response->withHeader('Access-Control-Allow-Origin', '*');
        $response->withHeader('Access-Control-Allow-Methods', 'GET, POST, OPTIONS');
        $response->withHeader('Access-Control-Allow-Credentials', 'true');
        $response->withHeader('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With');

//        var_dump($request);
//        $jwtObject = Jwt::getInstance()->setSecretKey(JwtConfig::SECRET_KEY)->decode();
//        $status = $jwtObject->getStatus();
//
//        var_dump($jwtObject);
//        switch ($status) {
//            case  1:
//                echo '验证通过';
//                $jwtObject->getAlg();
//                $jwtObject->getAud();
//                $jwtObject->getData();
//                $jwtObject->getExp();
//                $jwtObject->getIat();
//                $jwtObject->getIss();
//                $jwtObject->getNbf();
//                $jwtObject->getJti();
//                $jwtObject->getSub();
//                $jwtObject->getSignature();
//                $jwtObject->getProperty('alg');
//                break;
//            case  -1:
//                echo '无效';
//                break;
//            case  -2:
//                echo 'token过期';
//                break;
//        }
        return true;
    }

    public static function afterRequest(Request $request, Response $response): void
    {
        // TODO: Implement afterAction() method.
    }
}
