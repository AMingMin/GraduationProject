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
        $name=$data['name'];
        if($data['sex']==='女'){
            $sex=0;
        }else{
            $sex=1;
        }
        $price=$data['price'];
        $service_type=$data['service_type'];
        if($data['hair_height']===''){
            $data['hair_height']===null;
        }else{
            $hair_height=$data['hair_height'];
        }
        if($data['haircut_type']===''){
            $data['haircut_type']===null;
        }else{
            $haircut_type=$data['haircut_type'];
        }
        if($data['$perm_type']===''){
            $data['$perm_type']===null;
        }else{
            $perm_type=$data['perm_type'];
        }

        $dye_hair_type=$data['dye_hair_type'];
        $introduction=$data['introduction'];
        $create_time=$data['create_time'];
        $create_staff=$data['create_staff'];
        $model = ServiceType::create([
            'name' =>$name,
            'sex' => $sex,
            'price' => $price,
            'service_type' => $service_type,
            'hair_height' => $hair_height,
            'haircut_type' => $haircut_type,
            'perm_type' => $perm_type,
            'dye_hair_type' => $dye_hair_type,
            'introduction' => $introduction,
            'create_staff' => $create_staff,  //更新人初始为新建人
            'update_staff' => $create_staff,  //更新人初始为新建人
            'create_time' =>$create_time, //更新时间，当前时间
            'update_time' =>$create_time, //更新时间，当前时间
        ]);
        return $res = $model->save();


    }


}
