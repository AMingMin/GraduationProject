<?php
namespace App\Service;

use App\Model\Orders;
use EasySwoole\Component\Singleton;

class OrdersService
{
    use Singleton;

    public function ordersInsert($data)
    {
        $model = Orders::create([
            'create_time' =>$data['create_time'],
            'finish_time' =>$data['finish_time'],
            'status' =>$data['status'],
            'pay_type' =>$data['pay_type'],
            'customer_type' =>$data['customer_type'],
            'member_phone' =>$data['member_phone'],
            'should_amount' =>$data['should_amount'],
            'real_amount' =>$data['real_amount'],
            'service_type_id' =>$data['service_type_id'],
            'service_type_name' =>$data['service_type_name'],
            'admin_id' =>$data['admin_id'],
            'admin_name' =>$data['admin_name'],
        ]);
        return $res = $model->save();
    }

    /***
     * 查询订单信息
     *
     * @param $data
     * @return array
     * CreateTime: 2020/3/19 上午12:50
     */
    public function ordersList($data)
    {
        $page = $data['page']; // 页码
        // var_dump($data);
        $limit=$data['limit'];
        $model = Orders::create()->limit($limit * ($page - 1), $limit)->withTotalCount()->order('order_id', 'DESC');
        // 列表数据
        $list = $model->all([], true);
        $result = $model->lastQueryResult();
        //订单状态
        $statusTextMap =[
            0=>'预支付',
            1=>'支付',
            2=>'退款',
            3=>'退款失败',
            4=>'支付失败'
        ];
        //支付方式
        $payTypeTextMap=[
            0=>'现金',
            1=>'微信',
            2=>'支付宝',
            3=>'支付宝',
            4=>'刷卡',
            5=>'其它',
            6=>'会员卡余额'
        ];
        //顾客类型
        $customerTypeTextMap=[
            0=>'散客',
            1=>'会员'
        ];
        foreach ($list as $key => $item) {
            $list[$key]['status_txt'] = $statusTextMap[$item['status']];
            $list[$key]['pay_type_txt'] = $payTypeTextMap[$item['pay_type']];
            $list[$key]['customer_type_txt'] = $customerTypeTextMap[$item['customer_type']];
        }
        // 总条数
        $total = $result->getTotalCount();
        return [$list, $total];
    }

    /***
     * 近7天订单成交量
     *
     * @return array
     * CreateTime: 2020/3/20 下午5:35
     */
    public function sevenDaysEcharts(){
        //设置指定时间段
        $start = date('Y-m-d', strtotime('-6 days')) . " 00:00:00";
        $end = date('Y-m-d', time()) . " 23:59:59";
        //获取指定时间段的查询结果
        $result = Orders::create()->dayCount([
            $start, $end
        ]);
        //日期转换时间戳，便于循环
        $start = strtotime($start);
        $end = strtotime($end);
        //遍历查询结果，组成键值对
        $values=[];
        for ($i=$start; $i<=$end; $i= $i+60*60*24)
        {
            $day = date('Y-m-d', $i);
            $kes[] = $day;
            if (isset($result[$day]))
            {
                $values[] = $result[$day]['total'];
            } else {
                $values[] = 0;
            }
        }
        return ['keys'=>$kes, 'values'=>$values];

    }

    /***
     * 本月订单成交量
     *
     * @return array
     * CreateTime: 2020/3/20 下午5:35
     */
    public function currentMonthEcharts(){
        //设置指定时间段
        $start = date('Y-m-01', strtotime('+0 days')) . " 00:00:00";  //当前月第一天
        $end = date('Y-m-d', time()) . " 23:59:59";
        //获取指定时间段的查询结果
        $result = Orders::create()->dayCount([
            $start, $end
        ]);
        //日期转换时间戳，便于循环
        $start = strtotime($start);
        $end = strtotime($end);
        //遍历查询结果，组成键值对
        $values=[];
        for ($i=$start; $i<=$end; $i= $i+60*60*24)
        {
            $day = date('Y-m-d', $i);
            $kes[] = $day;
            if (isset($result[$day]))
            {
                $values[] = $result[$day]['total'];
            } else {
                $values[] = 0;
            }
        }
        return ['keys'=>$kes, 'values'=>$values];

    }

    /***
     * 今日订单数
     *
     * @return array
     * @throws \EasySwoole\ORM\Exception\Exception
     * CreateTime: 2020/3/20 下午8:56
     */
    public function selectTodayOrdersTotal(){
        //设置指定时间段
        $start = date('Y-m-d', time()) . " 00:00:00";
        $end = date('Y-m-d', time()) . " 23:59:59";
        //获取指定时间段的查询结果
        $result = Orders::create()->hourCount([
            $start, $end
        ]);
        //日期转换时间戳，便于循环
        $start = strtotime($start);
        $end = strtotime($end);
        //遍历查询结果，组成键值对
        $values=[];
        for ($i=$start; $i<=$end; $i= $i+60*60)
        {
            $day = date('Y-m-d H', $i);
            $key = date('H', $i)+1;
            $kes[] = "{$key}时";

            if (isset($result[$day]))
            {
                $values[] = $result[$day]['total'];
            } else {
                $values[] = 0;
            }
            $sum=0;
            foreach($values as $key=>$item){
                $sum=$sum+$item;
            }
        }
        return ['keys'=>$kes, 'values'=>$values,'sum'=>$sum];

    }

}
