<?php
namespace App\HttpController;

use App\Service\ServiceTypeService;
use EasySwoole\Http\AbstractInterface\Controller;

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
        $data['create_staff']=$_SESSION['admin']['name'];  //创建人
        $data['create_time']=date('y-m-d h:i:s',time());//创建时间，当前时间
        $data['update_time']=date('y-m-d h:i:s',time());//更新时间，当前时间

        $request=  $this->request();
        $img_file = $request->getUploadedFile('img');//获取一个上传文件,返回的是一个\EasySwoole\Http\Message\UploadFile的对象
        $data['picture'] = $request->getUploadedFiles();
        var_dump($data['picture']);

        //var_dump($_SESSION['admin']);
        //var_dump($data);
        $result = ServiceTypeService::getInstance()->index($data);
        if ($result) {
            $this->writeJson(200, [], '服务类型创建成功');
        } else {
            $this->writeJson(400, [], '服务类型创建失败');
        }
    }


}
