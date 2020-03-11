<?php
namespace App\HttpController;

use App\Service\ServiceTypeService;
use EasySwoole\Http\AbstractInterface\Controller;
use EasySwoole\Http\Message\UploadFile;

class ServiceType extends Controller
{
    /***
     * '添加服务类型'到数据库
     *
     * CreateTime: 2020/3/4 下午7:18
     */
    function index()
    {
        $data = $this->request()->getRequestParam();
//        var_dump($data);
        $data['create_staff']="创建人";  //创建人
        $data['create_time']=date('y-m-d h:i:s',time());//创建时间，当前时间
        $data['update_time']=date('y-m-d h:i:s',time());//更新时间，当前时间
        //var_dump($_SESSION['admin']);
        //var_dump($data);
        $result = ServiceTypeService::getInstance()->index($data);
        if ($result) {
            $this->writeJson(200, [], '服务类型创建成功');
        } else {
            $this->writeJson(400, [], '服务类型创建失败');
        }
    }

    /***
     * 上传图片文件
     *
     * CreateTime: 2020/3/10 下午7:21
     */
    function uploadPicture()
    {
        $request=  $this->request();
        /** @var $fileUploadObj UploadFile*/
        $fileUploadObj = $request->getUploadedFile('file');  //获取一个上传文件,返回的是一个\EasySwoole\Http\Message\UploadFile的对象
        $clientName = $fileUploadObj->getClientFilename();  //获取上传文件客户端名称
        $fileName=time().$clientName;  //时间戳（十位）+文件名
        $filePath = EASYSWOOLE_ROOT.'/Static/img/service/'.$fileName;
        $res = $fileUploadObj->moveTo($filePath);  // 图片文件上传到指定目录
        if ($res) {  //图片上传成功
            $this->writeJson(200, [
                "pictureUrl"=>$fileName  //返回图片地址
            ], '图片上传成功');
        }else{ //图片上传失败
            $this->writeJson(400, [], '图片上传失败');
        }
    }

    /***
     * 获取服务类型列表
     *
     * CreateTime: 2020/3/11 下午3:20
     */
    function select(){
        $data = $this->request()->getRequestParam();
        //var_dump($data);
        [$list, $total] = serviceTypeService::getInstance()->serviceTypeList($data);

        $this->writeJson(200, [
            'count' => $total,
            'data'  => $list
        ], '获取成功！');


    }
}
