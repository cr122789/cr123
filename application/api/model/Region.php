<?php
namespace app\api\model;
use think\Model;
use think\Db;
class Region extends Model
{
    protected $table = 'region';
    public function index($pid){
//        if(cache('region')){
//            $region = cache('region');
//        }else{
            $region = $this->region($pid);
//        }
        if($region) {
//            cache('region',$region);
            return ['code' => 200, 'data' => $region ];
        }else{
            return ['code' => -2, 'msg' => '获取失败！'];
        }
    }

    protected function region($pid){
        $region=Db::name($this->table)->where('pid',$pid)->select();
        foreach($region as $k => $v){
            $vRetion = $this->region($v['id']);
            $region[$k]['area'] = $vRetion;
        }
        return $region;
    }

}

