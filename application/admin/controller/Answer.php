<?php
namespace app\admin\controller;
use think\Controller;
use think\Db;
use think\facade\Request;
use think\validate;
use think\facade\Session;

Class Answer extends Common{
    //个人答题列表
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
                
                $time=db('video_answer_history')->field('answer_time')->where('user_id='.$list['data'][$k]['id'])->order('answer_time desc')->find();
                if($time['answer_time']){
                    $list['data'][$k]['answer_time'] = date('Y-m-d H:s',$time['answer_time']);
                }else{
                    $list['data'][$k]['answer_time'] = '暂无数据';
                }
                $count_all=db('video_answer_history')
                ->where('user_id='.$list['data'][$k]['id'])
                ->count();
                $count_right=db('video_answer_history')
                        ->where('user_id='.$list['data'][$k]['id'])
                        ->where('is_right=1')
                        ->count();
                if($count_all){
                    $rate=round(($count_right/$count_all)*100).'%';
                }else{
                    $rate='0%';
                }
                $list['data'][$k]['count']=$count_all;
                $list['data'][$k]['rate']=$rate;
            }
            return $result = ['code'=>0,'msg'=>'获取成功!','data'=>$list['data'],'count'=>$list['total'],'rel'=>1];
        }
        $this->assign('ogroup_id',$ogroup_id);
        return $this->fetch('myList');
    }

     //个人答题记录
    public function myHistory($id=''){

        if(request()->isPost()){
            $user_id=input('post.user_id');
            $key=input('post.key');
            $where=1;
            if(input('post.date1')&&input('post.date2')){  
                $date3=strtotime(input('post.date1'));
                $date4=strtotime(input('post.date2'));
                if($date3<$date4){
                    $where.=" and history.answer_time >= '". $date3."' and history.answer_time <= '". $date4."'";     
                }
                else{
                    $where.=" and history.answer_time >= '". $date3."' and history.answer_time <= '". $date4."'";        
                }
            } 
            elseif (input('post.date1')) {

                $date3=strtotime(input('post.date1'));
                $where.=" and history.answer_time >= '". $date3."'";
            }
            elseif (input('post.date2')) {
                $date4=strtotime(input('post.date2'));
                $where.=" and history.answer_time <= '". $date4."'";
            }
            $page =input('page')?input('page'):1;
            $pageSize =input('limit')?input('limit'):config('pageSize');
            $list=Db::name('video_answer_history')
                ->alias('history')
                ->field('history.id,history.is_right,history.answer_time,video.title,questions.question_title')
                ->join('mk_video_video video','history.video_id=video.id')
                ->join('mk_video_questions questions','history.question_id=questions.id')
                // ->join('mk_video_answers answers','history.answer_id=answers.id')
                ->join('mk_users users','history.user_id=users.id')
                ->where(['history.user_id'=>$user_id])
                ->where('video.title|questions.question_title','like',"%".$key."%")
                ->where($where)
                ->order('history.answer_time desc')
                ->paginate(array('list_rows'=>$pageSize,'page'=>$page))
                ->toArray();

            foreach ($list['data'] as $k=>$v){
                $list['data'][$k]['answer_time'] = date('Y年m月d日',$v['answer_time']);
            }
            // print_r($list);
            return $result = ['code'=>0,'msg'=>'获取成功!','data'=>$list['data'],'count'=>$list['total'],'rel'=>1];
        }
        $this->assign('user_id', $id);
        return $this->fetch('myHistory');
    }

     //全部答题记录
    public function allHistory($id=''){
        $ogroup_id=session('ogroup_id');
        if(request()->isPost()){
            $key=input('post.key');
            $where=1;
            if($ogroup_id!='1'){
                $where.=" and users.ogroup_id= '".$ogroup_id."' ";
            }
            if(input('post.date1')&&input('post.date2')){  
                $date3=strtotime(input('post.date1'));
                $date4=strtotime(input('post.date2'));
                if($date3<$date4){
                    $where.=" and history.answer_time >= '". $date3."' and history.answer_time <= '". $date4."'";     
                }
                else{
                    $where.=" and history.answer_time >= '". $date3."' and ihistory.answer_time <= '". $date4."'";        
                }
            } 
            elseif (input('post.date1')) {

                $date3=strtotime(input('post.date1'));
                $where.=" and history.answer_time >= '". $date3."'";
            }
            elseif (input('post.date2')) {
                $date4=strtotime(input('post.date2'));
                $where.=" and history.answer_time <= '". $date4."'";
            }
            $page =input('page')?input('page'):1;
            $pageSize =input('limit')?input('limit'):config('pageSize');
            $list=Db::name('video_answer_history')
                ->alias('history')
                ->field('history.id,history.is_right,history.answer_time,video.title,questions.question_title,users.username,users.nickname,users.truename,og.group_name')
                ->join('mk_video_video video','history.video_id=video.id')
                ->join('mk_video_questions questions','history.question_id=questions.id')
                ->join('mk_users users','history.user_id=users.id')
                ->join(config('database.prefix').'organization_group og','users.ogroup_id = og.id','left')
                ->where('video.title|questions.question_title|users.username|users.nickname|users.truename','like',"%".$key."%")
                ->where($where)
                ->order('history.answer_time desc')
                ->paginate(array('list_rows'=>$pageSize,'page'=>$page))
                ->toArray();

            foreach ($list['data'] as $k=>$v){
                $list['data'][$k]['answer_time'] = date('Y年m月d日',$v['answer_time']);
            }
            return $result = ['code'=>0,'msg'=>'获取成功!','data'=>$list['data'],'count'=>$list['total'],'rel'=>1];
        }
        $this->assign('ogroup_id',$ogroup_id);
        return $this->fetch('allHistory');
    }
}
 