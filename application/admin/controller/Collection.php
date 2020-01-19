<?php
namespace app\admin\controller;
use think\Controller;
use think\Db;
use think\facade\Request;
use think\validate;
use think\facade\Session;

Class Collection extends Common{
    //个人收藏列表
    public function myList(){
        $ogroup_id=session('ogroup_id');
        if(request()->isPost()){
            if($ogroup_id=='1'){
                $where=1;
            }else{
                $where='u.ogroup_id= '.$ogroup_id;
            }
            $key=input('post.key');
            $page =input('page')?input('page'):1;
            $pageSize =input('limit')?input('limit'):config('pageSize');
            $list=db('users')->alias('u')
                ->join(config('database.prefix').'user_level ul','u.level = ul.level_id','left')
                ->join(config('database.prefix').'organization_group og','u.ogroup_id = og.id','left')
                ->field('u.*,ul.level_name,og.group_name')
                ->where($where)
                ->where('u.username|u.nickname|u.truename','like',"%".$key."%")
                ->order('u.id desc')
                ->paginate(array('list_rows'=>$pageSize,'page'=>$page))
                ->toArray();
            
            foreach ($list['data'] as $k=>$v){
                
                $time=db('video_collection')->field('collection_time')->where('user_id='.$list['data'][$k]['id'])->order('collection_time desc')->find();
                if($time['collection_time']){
                    $list['data'][$k]['collection_time'] = date('Y-m-d H:s',$time['collection_time']);
                }else{
                    $list['data'][$k]['collection_time'] = '暂无数据';
                }
                $count=db('video_collection')->where('user_id='.$list['data'][$k]['id'])->count();
                $list['data'][$k]['count'] = $count; 
            }
            return $result = ['code'=>0,'msg'=>'获取成功!','data'=>$list['data'],'count'=>$list['total'],'rel'=>1];
        }
        $this->assign('ogroup_id',$ogroup_id);
        return $this->fetch('myList');
    }

     //个人收藏记录
    public function myCollection($id=''){

        if(request()->isPost()){
            $user_id=input('post.user_id');
            $key=input('post.key');
            $where=1;
            if(input('post.date1')&&input('post.date2')){  
                $date3=strtotime(input('post.date1'));
                $date4=strtotime(input('post.date2'));
                if($date3<$date4){
                    $where.=" and collection.collection_time >= '". $date3."' and collection.collection_time <= '". $date4."'";     
                }
                else{
                    $where.=" and collection.collection_time >= '". $date3."' and collection.collection_time <= '". $date4."'";        
                }
            } 
            elseif (input('post.date1')) {

                $date3=strtotime(input('post.date1'));
                $where.=" and collection.collection_time >= '". $date3."'";
            }
            elseif (input('post.date2')) {
                $date4=strtotime(input('post.date2'));
                $where.=" and collection.collection_time <= '". $date4."'";
            }
            if(input('post.type')){
                $type=input('post.type');
                $type_count=db('video_type')
                ->where('fid='.$type)
                ->where('is_show=1')
                ->count();
                //大分类
                if($type_count){
                    $where.=" and type.fid='".$type."' and type.is_show=1";
                }else{
                    //小分类
                    $where.=" and video.type_id='".$type."' and type.is_show=1";
                } 
            }

            $page =input('page')?input('page'):1;
            $pageSize =input('limit')?input('limit'):config('pageSize');
            $list=db('video_collection')
                ->alias('collection')
                ->field('video.id,video.title,type.type,collection.user_id,collection.collection_time')
                ->join('mk_video_video video','collection.video_id=video.id')
                ->join('mk_video_type type','video.type_id=type.id')
                ->where(['collection.user_id'=>$user_id])
                ->where('video.title','like',"%".$key."%")
                ->where($where)
                ->order('collection.collection_time desc')
                ->paginate(array('list_rows'=>$pageSize,'page'=>$page))
                ->toArray();
           
            foreach ($list['data'] as $k=>$v){

                if($v['collection_time']){
                     $list['data'][$k]['collection_time'] = date('Y年m月d日',$v['collection_time']);
                 }else{
                     $list['data'][$k]['collection_time'] = '暂无数据';
                 }
               
            }
            // print_r($list);
            return $result = ['code'=>0,'msg'=>'获取成功!','data'=>$list['data'],'count'=>$list['total'],'rel'=>1];
        }
        $this->assign('user_id', $id);
        $type=db('video_type')->select();
        $type=self::tree($type);
        $this->assign('type', $type);
        return $this->fetch('myCollection');
    }

     //全部收藏记录
    public function allList($id=''){
        $ogroup_id=session('ogroup_id');
        if(request()->isPost()){
            $user_id=input('post.user_id');
            $key=input('post.key');
            $where=1;
            if($ogroup_id!='1'){
                $where.=" and users.ogroup_id= '".$ogroup_id."' ";
            }
            if(input('post.date1')&&input('post.date2')){  
                $date3=strtotime(input('post.date1'));
                $date4=strtotime(input('post.date2'));
                if($date3<$date4){
                    $where.=" and collection.collection_time >= '". $date3."' and collection.collection_time <= '". $date4."'";     
                }
                else{
                    $where.=" and collection.collection_time >= '". $date3."' and collection.collection_time <= '". $date4."'";        
                }
            } 
            elseif (input('post.date1')) {

                $date3=strtotime(input('post.date1'));
                $where.=" and collection.collection_time >= '". $date3."'";
            }
            elseif (input('post.date2')) {
                $date4=strtotime(input('post.date2'));
                $where.=" and collection.collection_time <= '". $date4."'";
            }
            if(input('post.type')){
                $type=input('post.type');
                $type_count=db('video_type')
                ->where('fid='.$type)
                ->where('is_show=1')
                ->count();
                //大分类
                if($type_count){
                    $where.=" and type.fid='".$type."' and type.is_show=1";
                }else{
                    //小分类
                    $where.=" and video.type_id='".$type."' and type.is_show=1";
                } 
            }

            $page =input('page')?input('page'):1;
            $pageSize =input('limit')?input('limit'):config('pageSize');
            $list=db('video_collection')
                ->alias('collection')
                ->field('video.id,video.title,type.type,collection.user_id,collection.collection_time,users.nickname,users.username,users.truename,og.group_name')
                ->join('mk_video_video video','collection.video_id=video.id')
                ->join('mk_video_type type','video.type_id=type.id')
                ->join('mk_users users','collection.user_id=users.id')
                ->join(config('database.prefix').'organization_group og','users.ogroup_id = og.id','left')
                ->where('video.title|users.username|users.nickname|users.truename','like',"%".$key."%")
                ->where($where)
                ->order('collection.collection_time desc')
                ->paginate(array('list_rows'=>$pageSize,'page'=>$page))
                ->toArray();
           
            foreach ($list['data'] as $k=>$v){

                if($v['collection_time']){
                     $list['data'][$k]['collection_time'] = date('Y年m月d日',$v['collection_time']);
                 }else{
                     $list['data'][$k]['collection_time'] = '暂无数据';
                 }
               
            }
            // print_r($list);
            return $result = ['code'=>0,'msg'=>'获取成功!','data'=>$list['data'],'count'=>$list['total'],'rel'=>1];
        }
        $type=db('video_type')->select();
        $type=self::tree($type);
        $this->assign('type', $type);
        $this->assign('ogroup_id',$ogroup_id);
        return $this->fetch('allList');
    }

    /**
     * 无限级分类
     * @access public 
     * @param Array $data     //数据库里获取的结果集 
     * @param Int $gtfid             
     * @param Int $count       //第几级分类
     * @return Array $treeList   
     */


    public function tree($array, $fid =0, $level = 0){

        // $f_name=__FUNCTION__; // 定义当前函数名

        //声明静态数组,避免递归调用时,多次声明导致数组覆盖
        static $list = [];

        foreach ($array as $key => $value){
            //第一次遍历,找到父节点为根节点的节点 也就是pid=0的节点
            if ($value['fid'] == $fid){
                //父节点为根节点的节点,级别为0，也就是第一级
                $flg = str_repeat('┖┄',$level);
                // 更新 名称值
                $value['type'] = $flg.$value['type'];
                // 输出 名称
                // echo $value['type']."<br/>";
                //把数组放到list中
                $list[] = $value;
                //把这个节点从数组中移除,减少后续递归消耗
                unset($array[$key]);
                //开始递归,查找父ID为该节点ID的节点,级别则为原级别+1
                self::tree($array, $value['id'], $level+1);
            }
        }
        return $list;
    }
}
 