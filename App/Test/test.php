<?php

// 查询条件：所有pid=0 and status=1
$one = [
    1 => [
        'id' => 1,
        'func_name' => '会员管理',
        'func_url' => 'xxx',
        'pid' => 0,
    ]

];

// 查询条件: pid=one 下的所有id
$two = [
    2 => [
        'id' => 2,
        'func_name' => '会员列表',
        'func_url' => 'xxx',
        'pid' => 1
    ]
];

foreach ($two as $key => $item)
{
    if (in_array($item['pid'], array_keys($one))) {
        $one[$item['pid']]['two'][] = $item;
    }
}

var_dump($one);
