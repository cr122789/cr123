<?php
namespace app\api\model;

use think\Model;
use think\Db;

class Ad extends Model
{
    protected $table = 'ad';

    /**
     * 轮播图
     */
    public function ads($typeId){
        $ads=Db::name($this->table)->where(['type_id'=>$typeId,'open'=>1])->order('sort')->field('ad_id,name,type_id,pic,url')->select();
        foreach ($ads as &$v){
            $v['pic'] = config('prefix_url').$v['pic'];
        }
        if($ads) {
            return $ads; //信息正确
        }else{
            return ['code' => 0, 'msg' => '轮播图为空！'];
        }
    }

}

