<?php
namespace App\HttpController;

use App\Service\OrdersService;
use EasySwoole\Component\Context\ContextManager;
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

        $adminInfo = ContextManager::getInstance()->get('admin');  //拿到admin中的用户信息
        $data['create_staff']=$adminInfo['name'];
        $data['status']=1;  //支付状态,暂定默认：支付
        $data['member_discount']=1;  //折扣率
        $data['create_time']=date('Y-m-d H:i:s',time());
        $data['finish_time']=date('Y-m-d H:i:s',time());
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

    /***
     * 近7日，订单成交量
     *
     * CreateTime: 2020/3/20 下午5:33
     */
    function sevenDaysEcharts(){
        $result = OrdersService::getInstance()->sevenDaysEcharts();
        if ($result) {
            $this->writeJson(200, $result, '查询成功！');
        } else {
            $this->writeJson(400, [
                'result' => $result
            ], '查询失败！');
        }
    }

    /***
     * 本月，订单成交量
     *
     * CreateTime: 2020/3/20 下午5:34
     */
    function currentMonthEcharts(){
        $result = OrdersService::getInstance()->currentMonthEcharts();
        if ($result) {
            $this->writeJson(200, $result, '查询成功！');
        } else {
            $this->writeJson(400, [
                'result' => $result
            ], '查询失败！');
        }
    }

    /***
     * 查询今日订单成交
     *
     * CreateTime: 2020/3/20 下午8:53
     */
    function selectTodayOrdersTotal(){
        $result = OrdersService::getInstance()->selectTodayOrdersTotal();
        if ($result) {
            $this->writeJson(200, $result, '查询成功！');
        } else {
            $this->writeJson(400, [
                'result' => $result
            ], '查询失败！');
        }
    }
}
