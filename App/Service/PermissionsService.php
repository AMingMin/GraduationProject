<?php
namespace App\Service;

use App\Model\Permissions;
use EasySwoole\Component\Singleton;

class PermissionsService
{
    use Singleton;

    /***
     * 获取权限列表
     *
     * @param $data
     * @return array
     * @throws \EasySwoole\ORM\Exception\Exception
     * @throws \Throwable
     * CreateTime: 2020/3/22 下午9:34
     */
    public function permissionsList()
    {
        $model = Permissions::create()->withTotalCount()->order('id');

        // 一级菜单
        $oneList = $model->all(['pid' => 0], true);
        $oneList = array_column($oneList, null, 'id');

        // 二级菜单
        $twoList = $model->all(['pid' => [0, '<>']], true);

        $pidTwoListMap = [];
        foreach ($twoList as $item) {
            $pidTwoListMap[$item['pid']][] = $item;
        }

        $result = [];
        foreach ($oneList as $pid => $item) {
            $result[] = $item;
            $result = array_merge($result, $pidTwoListMap[$pid]);
        }

        return [$result, 0];
    }

    /**
     * 角色页面所需options
     *
     * @throws \EasySwoole\ORM\Exception\Exception
     * @throws \Throwable
     * CreateTime: 2020/4/6 下午4:36
     */
    public function permissionsOptions()
    {
        $model = Permissions::create()->withTotalCount()->order('id');

        // 一级菜单
        $oneList = $model->all(['pid' => 0], true);
        $oneList = array_column($oneList, null, 'id');

        // 二级菜单
        $twoList = $model->all(['pid' => [0, '<>']], true);

        foreach ($twoList as $item) {
            $oneList[$item['pid']]['two'][] = $item;
        }
        return array_values($oneList);
    }
}
