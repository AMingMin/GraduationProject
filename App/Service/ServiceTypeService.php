<?php
namespace App\Service;

use App\Model\ServiceType;
use EasySwoole\Component\Singleton;

class ServiceTypeService
{
    use Singleton;

    /***
     * 插入服务类型
     *
     * @param $data
     * @return bool|int
     * @throws \EasySwoole\ORM\Exception\Exception
     * @throws \Throwable
     * CreateTime: 2020/3/4 下午7:31
     */
    public function index($data)
    {
        var_dump($data);
        if($data['sex']==='女'){
            $sex=0;
        }else{
            $sex=1;
        }

        $model = ServiceType::create([
            'name' =>$data['name'],
            'sex' => $sex,
            'price' => $data['price'],
            'service_type' => $data['service_type'],
            'hair_height' => $data['hair_height'],
            'haircut_type' => $data['haircut_type'],
            'perm_type' => $data['perm_type'],
            'picture' => $data['picture'],
            'dye_hair_type' => $data['dye_hair_type'],
            'introduction' => $data['introduction'],
            'create_staff' => $data['create_staff'],  //更新人初始为新建人
            'update_staff' => $data['create_staff'],  //更新人初始为新建人
            'create_time' =>$data['create_time'], //更新时间，当前时间
            'update_time' =>$data['create_time'], //更新时间，当前时间
        ]);
        return $res = $model->save();


    }

    /***
     * 查询服务类型数据
     *
     * @param $data
     * CreateTime: 2020/3/11 下午3:59
     */
    public function serviceTypeList($data)
    {
        $page = $data['page']; // 页码
        $limit=$data['limit'];
        $model = ServiceType::create()->limit($limit * ($page - 1), $limit)->withTotalCount()->order('id', 'DESC');
        // 列表数据
        $list = $model->all([  //已删除的不展示在页面
            'status'=>[0,'<>']
        ], true);

        $result = $model->lastQueryResult();
//        var_dump($data);
        $total = $result->getTotalCount();          // 总条数
        return [$list, $total];
    }

    /***
     * 删除服务类型
     *
     * CreateTime: 2020/3/13 下午7:24
     */
    public function serviceTypeDelete($id){
        $user = ServiceType::create()->get($id);  //通过id更新记录状态
        return $user->update([
            'status' => 0  //状态0代表删除，
        ]);
    }

    /***
     * 修改服务类型
     *
     * CreateTime: 2020/3/14 上午12:48
     */
    public function serviceTypeUpdate($data){
//        var_dump($data);
        $id = $data['id'];
        if($data['sex']==='女'){
            $sex=0;
        }else{
            $sex=1;
        }
        $service_type = $data['service_type'];
        $hair_length = $data['hair_length'];
        $haircut_type = $data['haircut_type'];
        $perm_type = $data['perm_type'];
        $dye_hair_type = $data['dye_hair_type'];
        $picture = $data['picture'];
        $price = $data['price'];
        $introduction = $data['introduction'];
        $name = $data['name'];
//        $update_staff=$data['update_staff'];
//        $update_time=$data['update_time'];

        $user = ServiceType::create()->get($id);  //通过id更新记录状态
        return $user->update([
            'name' => $name,
            'sex' => $sex,
            'service_type' => $service_type,
            'hair_length' => $hair_length,
            'haircut_type' => $haircut_type,
            'perm_type' => $perm_type,
            'dye_hair_type' => $dye_hair_type,
            'picture' => $picture,
            'price' => $price,
            'introduction' => $introduction,
//            'update_staff' => $update_staff, //更新人
//            'update_time' =>$update_time, //更新时间，当前时间
        ]);
    }
}
