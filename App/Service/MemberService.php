<?php
namespace App\Service;

use App\Model\Member;
use EasySwoole\Component\Singleton;

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
        //var_dump($data);
        $id = $data['id'];
        $name=$data['name'];
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
        $balance=$data['balance'];
        $member_level=$data['member_level'];
        $know_space=$data['know_space'];
        $update_staff=$data['update_staff'];
        $introduction=$data['introduction'];
        $update_time=$data['update_time'];
        $user = Member::create()->get($id);  //通过id更新记录状态
        return $user->update([
            'name' => $name,
            'sex' => $sex,
            'phone' => $phone,
            'status' => $status,
            'balance' => $balance,
            'member_level' => $member_level,
            'know_space' => $know_space,
            'introduction' => $introduction,
            'update_staff' => $update_staff, //更新人
            'update_time' =>$update_time, //更新时间，当前时间
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
        //var_dump('向数据库插入');
        $name=$data['name'];
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
        $balance=$data['balance'];
        $member_level=$data['member_level'];
        $know_space=$data['know_space'];
        $update_staff=$data['update_staff'];
        $introduction=$data['introduction'];
        $create_time=$data['create_time'];
        $update_time=$data['update_time'];
        $create_staff=$data['create_staff'];
        $model = Member::create([
            'name' => $name,
            'sex' => $sex,
            'phone' => $phone,
            'status' => $status,
            'balance' => $balance,
            'member_level' => $member_level,
            'know_space' => $know_space,
            'introduction' => $introduction,
            'create_staff' => $create_staff,  //更新人初始为新建人
            'update_staff' => $create_staff,  //更新人初始为新建人
            'create_time' =>$create_time, //更新时间，当前时间
            'update_time' =>$create_time, //更新时间，当前时间
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
//        var_dump($phone);
        $memberInfo = Member::create()
            ->where([
                'phone' => $phone,
                'status' => 1
            ])
            ->findOne();
        return $memberInfo;
    }
}
