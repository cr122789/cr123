<?php
namespace app\admin\model;

use think\Model;

class Ogroup extends Model
{
	protected $name = 'organization_group';
    protected $type       = [
        // 设置addtime为时间戳类型（整型）
        'addtime' => 'timestamp:Y-m-d H:i:s',
    ];


}