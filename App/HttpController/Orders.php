<?php
namespace App\HttpController;

use App\Service\OrdersService;
use EasySwoole\Http\AbstractInterface\Controller;

class Orders extends Controller
{
    /***
     * 添加订单
     *
     * CreateTime: 2020/3/17 下午5:52
     */
    function index()
    {
        $data = $this->request()->getRequestParam();
//        var_dump($data);
        $data['status']=1;  //支付状态,暂定默认：支付
        $data['member_discount']=1;  //折扣率
        $data['create_time']=date('y-m-d h:i:s',time());
        $data['finish_time']=date('y-m-d h:i:s',time());
        $result = OrdersService::getInstance()->ordersInsert($data);
        if ($result) {
            $this->writeJson(200, [], '订单支付成功');
        } else {
            $this->writeJson(400, [], '订单支付失败');
        }
    }

    /***
     * 查询订单信息
     *
     * CreateTime: 2020/3/19 上午12:48
     */
    function select()
    {
        $request=$this->request();
        $data = $request->getRequestParam();
        [$list, $total] = OrdersService::getInstance()->ordersList($data);

        $this->writeJson(200, [
            'count' => $total,
            'data'  => $list
        ], '获取成功！');

    }
}
