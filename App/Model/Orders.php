<?php
namespace App\Model;

use EasySwoole\Mysqli\QueryBuilder;
use EasySwoole\ORM\AbstractModel;
use EasySwoole\ORM\DbManager;

class Orders extends AbstractModel
{
    protected $tableName='orders';   //数据库中的会员表，表名'orders'

    //自定义SQL执行，查询指定时间段的新增会员数据
    public function dayCount($data)
    {
        $queryBuild = new QueryBuilder();
        // 支持参数绑定 第二个参数非必传
        $queryBuild
            ->raw(
                "select date_format(create_time, '%Y-%m-%d') as day, count(*) as total from orders where create_time >= ? and create_time <= ? group by date_format(create_time, '%Y-%m-%d'); ",
                $data
            );
        $result =  DbManager::getInstance()->query($queryBuild, true, 'default')->getResult();
        return array_column($result, null, 'day');
    }

    //自定义SQL执行，查询指定时间段的新增会员数据
    public function hourCount($data)
    {
        var_dump($data);
        $queryBuild = new QueryBuilder();
        // 支持参数绑定 第二个参数非必传
        $queryBuild
            ->raw(
                "select date_format(create_time, '%Y-%m-%d %H') as day, count(*) as total from orders where create_time >= ? and create_time <= ? group by date_format(create_time, '%Y-%m-%d %H'); ",
                $data
            );
        $result =  DbManager::getInstance()->query($queryBuild, true, 'default')->getResult();
        return array_column($result, null, 'day');
    }
}
