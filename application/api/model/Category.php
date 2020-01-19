<?php
namespace app\api\model;

use think\Model;
use think\Db;
use clt\Leftnav;


class Category extends Model
{
    protected $table = 'category';

    /**
     * 导航
     */
    public function index($catId){
        // 获取缓存数据
        $cate = cache('cate');

        if(!$cate){
            $column_one = Db::name($this->name)->where([['parentid','=',0],['ismenu','=',1]])->order('sort')->field('id,catname,parentid,arrparentid,arrchildid,ismenu,catdir')->select();
            $column_two = Db::name($this->name)->where('ismenu',1)->order('sort')->field('id,catname,parentid,arrparentid,arrchildid,ismenu,catdir')->select();
            $tree = new Leftnav ();
            $cate = $tree->index_top($column_one,$column_two);
            cache('cate', $cate, 3600);
        }
        return $cate;
    }

    /**
     * 获取单个导航信息
     */
    public function catInfo($catId){
        $thisCat = Db::name($this->name)->where('id',$catId)->field('id,catname,parentid,arrparentid,arrchildid,ismenu,pagesize')->find();
        return $thisCat;
    }

}

