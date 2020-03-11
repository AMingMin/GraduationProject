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
     * 获取服务类型数据
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
        $list = $model->all([], true);
        $result = $model->lastQueryResult();
//        var_dump($data);
        //性别
        $sexTextMap=[
            0=>'女',
            1=>'男'
        ];
        //服务类型
        $serviceTypeMap =[
            0=>'洗发',
            1=>'剪发',
            2=>'烫发',
            3=>'染发',
            4=>'拉直'
        ];
        //头发长度
        $hairLengthMap=[
            0=>'通用',
            1=>'超短发',
            2=>'短发',
            3=>'中短发',
            4=>'中长发',
            5=>'长发'
        ];
        //剪发类型
        $hairCutTypeMap=[
            0=>'无',
            1=>'板寸',
            2=>'背头',
            3=>'平头',
            4=>'圆寸',
            5=>'通用'
        ];
        //烫发类型
        $permTypeMap=[
            0=>'无',
            1=>'编织烫',
            2=>'螺旋烫',
            3=>'离子烫',
            4=>'空气烫',
            5=>'物理烫',
            6=>'通用'
        ];
        //染发色系
        $dyeHairMap=[
            0=>'无',
            1=>'黑色',
            2=>'奶奶灰',
            3=>'紫色',
            4=>'黄色',
            5=>'其它配色'
        ];
        foreach ($list as $key => $item) {
            $list[$key]['sex'] = $sexTextMap[$item['sex']];
            $list[$key]['service_type'] = $serviceTypeMap[$item['service_type']];
            $list[$key]['hair_length'] = $hairLengthMap[$item['hair_length']];
            $list[$key]['haircut_type'] = $hairCutTypeMap[$item['haircut_type']];
            $list[$key]['perm_type'] = $permTypeMap[$item['perm_type']];
            $list[$key]['dye_hair_type'] = $dyeHairMap[$item['dye_hair_type']];
        }
        $total = $result->getTotalCount();          // 总条数
        return [$list, $total];
    }



}
