<?php
namespace App\HttpController;

use App\Service\MemberService;
use EasySwoole\Http\AbstractInterface\Controller;

class Member extends Controller
{
    /***
     * 获取会员列表数据
     *
     * CreateTime: 2020/3/3 下午2:22
     */
    function index()
    {
        $request=$this->request();
        $data = $request->getRequestParam();
        [$list, $total] = MemberService::getInstance()->memberList($data);
        $this->writeJson(200, [
            'count' => $total,
            'data'  => $list
        ], '获取成功！');
    }

    /***
     * 获取会员列表中的 '操作'
     *
     * CreateTime: 2020/3/3 下午4:09
     */
    function delete()
    {
        $data = $this->request()->getRequestParam();
        $result = MemberService::getInstance()->memberDelete($data['id']);
        if ($result) {
            $this->writeJson(200, [], '删除该会员成功');
        } else {
            $this->writeJson(400, [], '删除该会员失败');
        }
    }

    /***
     * 将编辑后的员工表单的信息更新到数据库
     *
     * CreateTime: 2020/3/3 下午6:55
     */
    function update()
    {
        $data = $this->request()->getRequestParam();
        $result = MemberService::getInstance()->memberUpdate($data);
        $data['update_staff']=$_SESSION['admin']['name'];  //更新人
        $data['update_time']=date('y-m-d h:i:s',time());//更新时间，当前时间
        if ($result) {
            $this->writeJson(200, [], '该会员信息更新成功');
        } else {
            $this->writeJson(400, [], '该会员信息更新失败');
        }
    }

    /***
     *'添加员工'到数据库
     *
     * CreateTime: 2020/3/3 下午6:57
     */
    function insert()
    {
        $data = $this->request()->getRequestParam();
        $data['create_staff']=$_SESSION['admin']['name'];
        $data['create_time']=date('y-m-d h:i:s',time());//创建时间，当前时间
        $data['update_time']=date('y-m-d h:i:s',time());//更新时间，当前时间
        $result = MemberService::getInstance()->memberInsert($data);
        if ($result) {
            $this->writeJson(200, [], '会员创建成功');
        } else {
            $this->writeJson(400, [], '会员创建失败');
        }
    }

    /***
     * 根据手机号，查询会员表
     *
     * CreateTime: 2020/3/16 下午4:50
     */
    function select(){
        $data = $this->request()->getRequestParam();
        $result = MemberService::getInstance()->memberSelect($data['phone']);
        if (empty($result)) {
            $this->writeJson(400, [], '该手机号不存在！');
        } else {
            $this->writeJson(200, [
                'result' => $result
            ], '查询会员成功');
        }
    }

    /***
     * 根据手机号，更新会员表余额，完成会员扣款、充值
     *
     * @throws \EasySwoole\Mysqli\Exception\Exception
     * @throws \EasySwoole\ORM\Exception\Exception
     * @throws \Throwable
     * CreateTime: 2020/3/18 上午7:51
     */
    function updateBalance(){
        $data = $this->request()->getRequestParam();
        $data['update_time']=date('y-m-d h:i:s',time());//更新时间，当前时间
        $result = MemberService::getInstance()->updateBalance($data);
        if ($result) {
            $this->writeJson(200, [], '操作成功！');
        } else {
            $this->writeJson(400, [
                'result' => $result
            ], '操作失败！');
        }
    }

    /***
     * 近7日会员办理情况统计
     *
     * CreateTime: 2020/3/19 下午8:11
     */
    function sevenDaysEcharts(){
        $result = MemberService::getInstance()->sevenDaysEcharts();
        if ($result) {
            $this->writeJson(200, $result, '查询成功！');
        } else {
            $this->writeJson(400, [
                'result' => $result
            ], '查询失败！');
        }
    }

    /***
     * 本月会员办理情况
     *
     * CreateTime: 2020/3/20 下午3:33
     */
    function currentMonthEcharts(){
        $result = MemberService::getInstance()->currentMonthEcharts();
        if ($result) {
            $this->writeJson(200, $result, '查询成功！');
        } else {
            $this->writeJson(400, [
                'result' => $result
            ], '查询失败！');
        }
    }

    /***
     * 今日新增会员统计
     *
     * CreateTime: 2020/3/20 下午11:21
     */
    function selectTodayAddMemberTotal(){
        $result = MemberService::getInstance()->selectTodayAddMemberTotal();
        if ($result) {
            $this->writeJson(200, $result, '查询成功！');
        } else {
            $this->writeJson(400, [
                'result' => $result
            ], '查询失败！');
        }
    }


}
