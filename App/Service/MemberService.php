<?php
namespace App\Service;

use App\Model\Member;
use EasySwoole\Component\Singleton;
use EasySwoole\Mysqli\QueryBuilder;
use EasySwoole\ORM\DbManager;

class MemberService
{
    use Singleton;

    /***
     * 获取会员列表
     *
     * @param $data
     * @return array
     * CreateTime: 2020/3/3 下午2:24
     */
    public function memberList($data)
    {
        $page = $data['page']; // 页码
        $limit=$data['limit'];

        $model = Member::create()->limit($limit * ($page - 1), $limit)->withTotalCount()->order('id', 'DESC');
        // 列表数据
        $list = $model->all([
            'status'=>[0,'<>']
        ], true);
        $result = $model->lastQueryResult();
        //会员等级
        $memberLevelTextMap =[
            0=>'普通会员',
            1=>'白银会员',
            2=>'黄金会员',
            3=>'钻石会员'
        ];
        //性别
        $sexTextMap=[
            0=>'女',
            1=>'男'
        ];
        //会员状态0-删除
        $statusTextMap = [
            1=>'正常',
            2=>'过期'
        ];
        //了解渠道
        $knowSpaceTextMap =[
            0=>'线下推广',
            1=>'朋友圈推广',
            2=>'朋友推荐',
            3=>'其它'
        ];
        foreach ($list as $key => $item) {
            $list[$key]['sex_txt'] = $sexTextMap[$item['sex']];
            $list[$key]['member_level_txt'] = $memberLevelTextMap[$item['member_level']];
            $list[$key]['status_txt'] = $statusTextMap[$item['status']];
            $list[$key]['know_space_txt'] = $knowSpaceTextMap[$item['know_space']];
        }
        $total = $result->getTotalCount();          // 总条数
        return [$list, $total];
    }

    /***
     * 删除该会员信息
     *
     * @param $id
     * @return bool
     * @throws \EasySwoole\Mysqli\Exception\Exception
     * @throws \EasySwoole\ORM\Exception\Exception
     * @throws \Throwable
     * CreateTime: 2020/3/3 下午4:19
     */
    public function memberDelete($id)
    {
        $user = Member::create()->get($id);  //通过id更新记录状态
        return $user->update([
            'status' => 0
        ]);
    }

    /***
     * 编辑后的会员信息更新到数据库
     *
     * @param $data
     * @return mixed
     * CreateTime: 2020/3/3 下午5:43
     */
    public function memberUpdate($data)
    {
        if($data['sex']==='女'){
            $sex=0;
        }else{
            $sex=1;
        }
        if($data['status']==='正常'){
            $status=1;
        }else if($data['status']==='过期'){
            $status=2;
        }
        $user = Member::create()->get($data['id']);  //通过id更新记录状态
        return $user->update([
            'name' => $data['name'],
            'sex' => $sex,
            'phone' => $data['phone'],
            'status' => $status,
            'balance' => $data['balance'],
            'member_level' => $data['member_level'],
            'know_space' => $data['know_space'],
            'introduction' => $data['introduction'],
            'update_staff' => $data['update_staff'], //更新人
            'update_time' =>$data['update_time'], //更新时间，当前时间
        ]);
    }

    /***
     * 插入会员信息
     *
     * @param $data
     * @return mixed
     * CreateTime: 2020/3/3 下午6:57
     */
    public function memberInsert($data)
    {
        if($data['sex']==='女'){
            $sex=0;
        }else{
            $sex=1;
        }
        $phone=$data['phone'];
        if($data['status']==='正常'){
            $status=1;
        }else if($data['status']==='过期'){
            $status=2;
        }
        $model = Member::create([
            'name' => $data['name'],
            'sex' => $sex,
            'phone' => $phone,
            'status' => $status,
            'balance' => $data['balance'],
            'member_level' => $data['member_level'],
            'know_space' => $data['know_space'],
            'introduction' => $data['introduction'],
            'create_staff' => $data['create_staff'],  //更新人初始为新建人
            'update_staff' => $data['create_staff'],  //更新人初始为新建人
            'create_time' =>$data['create_time'], //更新时间，当前时间
            'update_time' =>$data['update_time'], //更新时间，当前时间
        ]);
        return $res = $model->save();
    }

    /***
     * 根据手机号，获取会员信息
     *
     * @param $phone
     * @return array|\EasySwoole\ORM\AbstractModel|null
     * @throws \EasySwoole\Mysqli\Exception\Exception
     * @throws \EasySwoole\ORM\Exception\Exception
     * @throws \Throwable
     * CreateTime: 2020/3/16 下午5:07
     */
    public function memberSelect($phone){
        $memberInfo = Member::create()
            ->where([
                'phone' => $phone,
                'status' => 1
            ])
            ->findOne();
        return $memberInfo;
    }

    /***
     * 会员扣款支付，更新余额
     *
     * @param $phone
     * @return array|bool|\EasySwoole\ORM\AbstractModel|null
     * @throws \EasySwoole\Mysqli\Exception\Exception
     * @throws \EasySwoole\ORM\Exception\Exception
     * @throws \Throwable
     * CreateTime: 2020/3/18 上午7:58
     */
    public function updateBalance($data){

        $phone=$data['phone'];
        $balance=$data['balance'];
        $memberResult = Member::create()->get($phone);  //通过phone更新记录状态
        return $memberResult->update([
            'balance' => $balance,
            'update_time' =>$data['update_time']
        ]);
    }

    /***
     * 查询指定时间段会员的办理情况
     *
     * CreateTime: 2020/3/20 下午12:59
     */
    public function sevenDaysEcharts(){
        //设置指定时间段
        $start = date('Y-m-d', strtotime('-6 days')) . " 00:00:00";
        $end = date('Y-m-d', time()) . " 23:59:59";
        //获取指定时间段的查询结果
        $result = Member::create()->dayCount([
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
     * 本月会员办理
     *
     * @return array
     * @throws \EasySwoole\ORM\Exception\Exception
     * CreateTime: 2020/3/20 下午3:34
     */
    public function currentMonthEcharts(){
        //设置指定时间段
        $start = date('Y-m-01', strtotime('+0 days')) . " 00:00:00";  //当前月第一天
        $end = date('Y-m-d', time()) . " 23:59:59";
        //获取指定时间段的查询结果
        $result = Member::create()->dayCount([
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
     * 查询指定时间段会员的办理情况
     *
     * CreateTime: 2020/3/20 下午12:59
     */
    public function selectTodayAddMemberTotal(){
        //设置指定时间段
        $start = date('Y-m-d', time()) . " 00:00:00";
        $end = date('Y-m-d', time()) . " 23:59:59";
        //获取指定时间段的查询结果
        return $result = Member::create()->todayCount([
            $start, $end
        ]);

    }

}
