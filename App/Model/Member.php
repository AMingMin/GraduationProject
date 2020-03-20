<?php
namespace App\Model;

use EasySwoole\Mysqli\QueryBuilder;
use EasySwoole\ORM\AbstractModel;
use EasySwoole\ORM\DbManager;

class Member extends AbstractModel
{
    protected $tableName='member';   //数据库中的会员表，表名'member'

    //自定义SQL执行，查询指定时间段（近7日、本月）内会员增加情况
    public function dayCount($data)
    {
        $queryBuild = new QueryBuilder();
        // 支持参数绑定 第二个参数非必传
        $queryBuild
            ->raw(
                "select date_format(create_time, '%Y-%m-%d') as day, count(*) as total from member where create_time >= ? and create_time <= ? group by date_format(create_time, '%Y-%m-%d'); ",
                $data
            );
        $result =  DbManager::getInstance()->query($queryBuild, true, 'default')->getResult();
        return array_column($result, null, 'day');
    }

    //自定义SQL执行，查询今日新增会员总数
    public function todayCount($data)
    {
        $queryBuild = new QueryBuilder();
        // 支持参数绑定 第二个参数非必传
        $queryBuild
            ->raw(
                "select count(*) as total from member where create_time >= ? and create_time <= ? ; ",
                $data
            );
        $result =  DbManager::getInstance()->query($queryBuild, true, 'default')->getResult();
        return $result;
    }
}
