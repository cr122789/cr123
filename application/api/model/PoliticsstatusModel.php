<?php
namespace app\api\model;
use think\Model;
use think\Db;
use ali\SendMessage;

class PoliticsstatusModel extends Model
{
    protected $table = 'politics_status';

    /**
     * 获取政治面貌列表
     * @return array|\PDOStatement|string|\think\Collection
     */
    public function index(){
        $data = Db::name($this->table)->select();
        return $data;
    }
}

