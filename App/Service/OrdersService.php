<?php
namespace App\Service;

use App\Model\Orders;
use EasySwoole\Component\Singleton;

class OrdersService
{
    use Singleton;

    public function ordersInsert($data)
    {
//        var_dump('向数据库插入');
//        var_dump($data);
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


}
