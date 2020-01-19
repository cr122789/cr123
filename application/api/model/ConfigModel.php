<?php
namespace app\api\model;
use think\Model;
use think\Db;
use app\api\model\IntegralRedis;
class ConfigModel extends Model
{
    protected $table = 'config';

    /**
     * 获取指定系统配置信息
     * @param $name
     * @return array|null|\PDOStatement|string|Model
     */
    public function index($name){
        $config=Db::name($this->table)->where('name',$name)->field('value')->find();
        return $config;
    }


}

