<?php
/**
 * Created by PhpStorm.
 * User: Lin
 * Date: 2019/10/17
 * Time: 13:40
 */

namespace app\api\model;

use think\Model;

class Position extends Model
{
    protected $table = 'position';

    // 获取西城区街道信息
    public function getXichengStreets(){
        $streets=db($this->table)
            ->where('parent_code_id','110102000000')
            ->field('id,code_id,position_name')
            ->order('id asc')
            ->select();
        return $streets;
    }

    // 根据上级地区编码获取下级地区信息
    public function getChildPositions($parentCodeId){
        $childPositions=db($this->table)
            ->where('parent_code_id',$parentCodeId)
            ->field('id,code_id,position_name')
            ->order('id asc')
            ->select();
        return $childPositions;
    }
}