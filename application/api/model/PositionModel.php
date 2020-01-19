<?php
/**
 * Created by PhpStorm.
 * User: Lin
 * Date: 2019/10/17
 * Time: 13:40
 */

namespace app\api\model;

use think\Model;
use think\Db;

class PositionModel extends Model
{
    protected $table = 'position';

    /**
     * 根据上级地区编码获取下级地区信息
     * @param $parentCodeId
     * @return array|\PDOStatement|string|\think\Collection
     */
    public function index($parentCodeId){
        $data = Db::name($this->table)->where(['parent_code_id'=>$parentCodeId])->order('id asc')->select();
        return $data;
    }

    /**
     * 根据code_id获取地区信息
     * @param $codeId
     * @return array|null|\PDOStatement|string|Model
     */
    public function position($codeId){
        $position = Db::name($this->table)->where('code_id',$codeId)->find();
        return $position;
    }
}