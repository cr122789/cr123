<?php
namespace app\admin\controller;
use think\Controller;
use think\Db;
use think\facade\Request;
use think\validate;
use think\facade\Session;
use app\admin\model\IntegralRedis;

Class Integral extends Common{
	//积分设置列表
    public function setList(){

        if(request()->isPost()){
            $key=input('post.key');
            $page =input('page')?input('page'):1;
            $pageSize =input('limit')?input('limit'):config('pageSize');
            $list=Db::name('video_integral_type')
                ->where('type','like',"%".$key."%")
                ->order('id asc')
                ->paginate(array('list_rows'=>$pageSize,'page'=>$page))
                ->toArray();
            return $result = ['code'=>0,'msg'=>'获取成功!','data'=>$list['data'],'rel'=>1];
        }
        return $this->fetch('setList');
    }
    //积分开关
    public function isOpen(){
        $id=input('post.id');
        $is_open=input('post.is_open');
        if (empty($id)){
            $result['status'] = 0;
            $result['info'] = '该积分配置不存在!';
            $result['url'] = url('setList');
            return $result;
        }
        db('video_integral_type')->where('id='.$id)->update(['is_open'=>$is_open]);
        $result['status'] = 1;
        $result['info'] = '设置成功!';
        $result['url'] = url('setList');
        return $result;
    }
    //积分编辑
    public function setEdit($id=''){
        if(request()->isPost()){
            $post = Request::except('file');
            $list = [
                'id' => $post['id'],
                'node' => $post['node'],
                'is_open' =>$post['is_open']
            ];
            $res = db('video_integral_type')->update($list);
            if($res!=false){
                return ['code'=>1,'msg'=>'修改成功!','url'=>url('setList')];
            }else{
                return ['code'=>0,'msg'=>'修改失败!','url'=>url('setList')];
            }
        }else{
            $info=db('video_integral_type')->where('id',$id)->find();
            
            $this->assign('info',json_encode($info,true));
            $this->assign('title',lang('edit').'积分');
            return $this->fetch('setEdit');
        }
    }
    //个人积分列表
    public function myList(){
        $ogroup_id=session('ogroup_id');
        $is_independence=db('organization_group')->where('id='.$ogroup_id)->value('is_independence');
        if(request()->isPost()){
            $key=input('post.key');
            $page =input('page')?input('page'):1;
            $pageSize =input('limit')?input('limit'):config('pageSize');
            if($ogroup_id=='1'){
                $where=1;
                $where1=1;
            }else{
                
                if($is_independence=='1'){
                    //独立
                    $where="users.ogroup_id='".$ogroup_id."'";
                    $where1="users.ogroup_id='".$ogroup_id."'";
                }else{
                    $where="users.ogroup_id='".$ogroup_id."'";
                    $where1="users.ogroup_id='".$ogroup_id."' or ogroup.is_independence=2";
                }
            }
           
            // print_r($list['data']);die();
            $count=$list=db('users')
                ->alias('users')
                ->join('mk_organization_group ogroup','users.ogroup_id=ogroup.id','left')
                ->where($where1)
                ->where('users.integral','gt','0')
                ->count();
            
            $userCount=$list=db('users')
                ->alias('users')
                ->join('mk_organization_group ogroup','users.ogroup_id=ogroup.id','left')
                ->where($where1)
                ->count();
            $list=db('users')
                ->alias('users')
                ->field('users.*,ogroup.group_name')
                ->join('mk_organization_group ogroup','users.ogroup_id=ogroup.id','left')
                ->where($where)
                ->where('users.username|users.nickname|users.truename','like',"%".$key."%")
                ->order('users.id desc')
                ->paginate(array('list_rows'=>$pageSize,'page'=>$page))
                ->toArray();
            foreach ($list['data'] as $k=>$v){
                
                $time=db('video_integral')->field('play_time')->where('user_id='.$list['data'][$k]['id'])->order('play_time desc')->find();
                if($time['play_time']){
                    $list['data'][$k]['play_time'] = date('Y-m-d H:s',$time['play_time']);
                }else{
                    $list['data'][$k]['play_time'] = '暂无数据';
                }
                $redis = new IntegralRedis('','rankList');
                //总排名

                if($list['data'][$k]['integral']=='0'){
                    $list['data'][$k]['rank_all']=$count+1;//总排名
                }else{
                    $gaoCount=db('users')
                        ->alias('users')
                        ->join('mk_organization_group ogroup','users.ogroup_id=ogroup.id','left')
                        ->where($where1)
                        ->where('integral','gt',$list['data'][$k]['integral'])
                        ->count();
                    $list['data'][$k]['rank_all']=$gaoCount+1;  
                }
               
                //本季度积分
                $jidu=$redis->jidu();
                $jidufen=db('video_integral_jidu')
                        ->field('integral')
                        ->where('user_id='.$list['data'][$k]['id'])
                        ->where(['jidu'=>$jidu])
                        ->find();
               
                //季度分
                $list['data'][$k]['integral_jidu']=$jidufen['integral'];
                //季度排名
                if($jidufen){
                    $list['data'][$k]['integral_jidu']=$jidufen['integral'];
                    $count_jidu=db('video_integral_jidu')
                        ->alias('jidu')
                        ->join('mk_users users','jidu.user_id=users.id','left')
                        ->join('mk_organization_group ogroup','users.ogroup_id=ogroup.id','left')
                        ->where($where1)
                        ->where(['jidu.jidu'=>$jidu])
                        ->where('jidu.integral','gt',$jidufen['integral'])
                        ->count();
                    $list['data'][$k]['rank_jidu']=$count_jidu+1;
                }else{
                    $list['data'][$k]['integral_jidu']='0';
                    $jiducount=db('video_integral_jidu')
                        ->alias('jidu')
                        ->join('mk_users users','jidu.user_id=users.id','left')
                        ->join('mk_organization_group ogroup','users.ogroup_id=ogroup.id','left')
                        ->where($where1)
                        ->where(['jidu.jidu'=>$jidu])
                        ->count();
                    $list['data'][$k]['rank_jidu']=$jiducount+1;
                }
                //等级
                //学习等级
                //百分比
                if($userCount){
                    $rate=round(($list['data'][$k]['rank_all']/$userCount)*100);
                }else{
                    $rate='0';
                }
                $filter=Db::name('video_grade_set')
                    ->field('grade,setion_start,setion_end,level')
                    ->select();
                $result = self::search($rate, $filter);
                $grade=current($result)['grade'];
                $leval=current($result)['level'];
                $list['data'][$k]['grade']=$grade;
             
                
            }
            return $result = ['code'=>0,'msg'=>'获取成功!','data'=>$list['data'],'count'=>$list['total'],'rel'=>1];
        }
        $this->assign('ogroup_id',$ogroup_id);
        return $this->fetch('myList');
    }

     //个人答题积分记录
    public function myAnswerHistory($id=''){

        if(request()->isPost()){
            $where=1;
            $key=input('post.key');
            if(input('post.type')=='2'){
                $where.=" and integral.type_id=2";
            }else if(input('post.type')=='4'){
                $where.=" and integral.type_id=4";
            }
            if(input('post.date1')&&input('post.date2')){  
                $date3=strtotime(input('post.date1'));
                $date4=strtotime(input('post.date2'));
                if($date3<$date4){
                    $where.=" and integral.play_time >= '". $date3."' and integral.play_time <= '". $date4."'";     
                }
                else{
                    $where.=" and integral.play_time >= '". $date3."' and integral.play_time <= '". $date4."'";        
                }
            } 
            elseif (input('post.date1')) {

                $date3=strtotime(input('post.date1'));
                $where.=" and integral.play_time >= '". $date3."'";
            }
            elseif (input('post.date2')) {
                $date4=strtotime(input('post.date2'));
                $where.=" and integral.play_time <= '". $date4."'";
            }
            $page =input('page')?input('page'):1;
            $pageSize =input('limit')?input('limit'):config('pageSize');
            $user_id=input('post.user_id');
            $data=db('video_integral')
                    ->alias('integral')
                    ->field('integral.id,questions.question_title,video.title,integral.play_time,integral.integral,users.username,users.nickname,users.truename,type.type')
                    ->join('mk_video_integral_type type','integral.type_id=type.id')
                    ->join('mk_video_video video','integral.video_id=video.id')
                    ->join('mk_video_questions questions','integral.question_id=questions.id')
                    ->join('mk_users users','integral.user_id=users.id')
                    ->where('integral.user_id='.$user_id)
                    ->where('video.title|users.nickname|users.username|users.truename|questions.question_title','like',"%".$key."%")
                    ->where('integral.type_id=2 or integral.type_id=4')
                    ->where($where)
                    ->order('integral.play_time desc')
                    ->paginate(array('list_rows'=>$pageSize,'page'=>$page))
                    ->toArray();
            foreach ($data['data'] as $k=>$v){
                if($v['play_time']){
                    $data['data'][$k]['play_time'] = date('Y-m-d H:i',$v['play_time']);
                }else{
                    $data['data'][$k]['play_time'] ='暂无数据';
                }
                
            }
           
            return $result = ['code'=>0,'msg'=>'获取成功!','data'=>$data['data'],'count'=>$data['total'],'rel'=>1];
        }
        $this->assign('user_id', $id);
        return $this->fetch('myAnswerHistory');
    }

     //个人课程积分记录
    public function myCourseHistory($id=''){

        if(request()->isPost()){
            $user_id=input('post.user_id');
            $where=1;
            $key=input('post.key');
            if(input('post.date1')&&input('post.date2')){  
                $date3=strtotime(input('post.date1'));
                $date4=strtotime(input('post.date2'));
                if($date3<$date4){
                    $where.=" and integral.play_time >= '". $date3."' and integral.play_time <= '". $date4."'";     
                }
                else{
                    $where.=" and integral.play_time >= '". $date3."' and integral.play_time <= '". $date4."'";        
                }
            } 
            elseif (input('post.date1')) {

                $date3=strtotime(input('post.date1'));
                $where.=" and integral.play_time >= '". $date3."'";
            }
            elseif (input('post.date2')) {
                $date4=strtotime(input('post.date2'));
                $where.=" and integral.play_time <= '". $date4."'";
            }
            $page =input('page')?input('page'):1;
            $pageSize =input('limit')?input('limit'):config('pageSize');
            $data=db('video_integral')
                    ->alias('integral')
                    ->field('integral.id,video.title,integral.play_time,integral.integral,users.username,users.nickname,users.truename')
                    ->join('mk_video_video video','integral.video_id=video.id')
                    ->join('mk_users users','integral.user_id=users.id')
                    ->where('integral.user_id='.$user_id)
                    ->where('video.title|users.nickname|users.username|users.truename','like',"%".$key."%")
                    ->where($where)
                    ->where('integral.type_id=3 or integral.type_id=10 or integral.type_id=11 or integral.type_id=7')
                    ->order('integral.play_time desc')
                    ->paginate(array('list_rows'=>$pageSize,'page'=>$page))
                    ->toArray();
            foreach ($data['data'] as $k=>$v){
                if($v['play_time']){
                    $data['data'][$k]['play_time'] = date('Y-m-d H:i',$v['play_time']);
                }else{
                    $data['data'][$k]['play_time'] ='暂无数据';
                }
                
            }
           
            return $result = ['code'=>0,'msg'=>'获取成功!','data'=>$data['data'],'count'=>$data['total'],'rel'=>1];
        }
        $this->assign('user_id', $id);
        return $this->fetch('myCourseHistory');
    }
     //个人登录积分记录
    public function myLoginHistory($id=''){

        if(request()->isPost()){
            $user_id=input('post.user_id');
            $where=1;
            $key=input('post.key');
            if(input('post.date1')&&input('post.date2')){  
                $date3=strtotime(input('post.date1'));
                $date4=strtotime(input('post.date2'));
                if($date3<$date4){
                    $where.=" and integral.play_time >= '". $date3."' and integral.play_time <= '". $date4."'";     
                }
                else{
                    $where.=" and integral.play_time >= '". $date3."' and integral.play_time <= '". $date4."'";        
                }
            } 
            elseif (input('post.date1')) {

                $date3=strtotime(input('post.date1'));
                $where.=" and integral.play_time >= '". $date3."'";
            }
            elseif (input('post.date2')) {
                $date4=strtotime(input('post.date2'));
                $where.=" and integral.play_time <= '". $date4."'";
            }
            $page =input('page')?input('page'):1;
            $pageSize =input('limit')?input('limit'):config('pageSize');
            $data=db('video_integral')
                    ->alias('integral')
                    ->field('integral.id,integral.play_time,integral.integral,users.username,users.nickname,users.truename')
                    ->join('mk_users users','integral.user_id=users.id')
                    ->where('integral.user_id='.$user_id)
                    ->where('users.nickname|users.username|users.truename','like',"%".$key."%")
                    ->where($where)
                    ->where('integral.type_id=1')
                    ->order('integral.play_time desc')
                    ->paginate(array('list_rows'=>$pageSize,'page'=>$page))
                    ->toArray();
            foreach ($data['data'] as $k=>$v){
                if($v['play_time']){
                    $data['data'][$k]['play_time'] = date('Y-m-d H:i',$v['play_time']);
                }else{
                    $data['data'][$k]['play_time'] ='暂无数据';
                }
                
            }
           
            return $result = ['code'=>0,'msg'=>'获取成功!','data'=>$data['data'],'count'=>$data['total'],'rel'=>1];
        }
        $this->assign('user_id', $id);
        return $this->fetch('myLoginHistory');
    }
     //个人考试积分记录
     public function myTestHistory($id=''){

        if(request()->isPost()){
            $user_id=input('post.user_id');
            $where=1;
            $key=input('post.key');
            if(input('post.date1')&&input('post.date2')){  
                $date3=strtotime(input('post.date1'));
                $date4=strtotime(input('post.date2'));
                if($date3<$date4){
                    $where.=" and integral.play_time >= '". $date3."' and integral.play_time <= '". $date4."'";     
                }
                else{
                    $where.=" and integral.play_time >= '". $date3."' and integral.play_time <= '". $date4."'";        
                }
            } 
            elseif (input('post.date1')) {

                $date3=strtotime(input('post.date1'));
                $where.=" and integral.play_time >= '". $date3."'";
            }
            elseif (input('post.date2')) {
                $date4=strtotime(input('post.date2'));
                $where.=" and integral.play_time <= '". $date4."'";
            }
            $page =input('page')?input('page'):1;
            $pageSize =input('limit')?input('limit'):config('pageSize');
            $data=db('video_integral')
                    ->alias('integral')
                    ->field('integral.id,integral.play_time,integral.integral,users.username,users.nickname,users.truename,test.title')
                    ->join('mk_video_test test','integral.test_id=test.id','left')
                    ->join('mk_users users','integral.user_id=users.id','left')
                    ->where('integral.user_id='.$user_id)
                    ->where('test.title|users.nickname|users.username|users.truename','like',"%".$key."%")
                    ->where($where)
                    ->where('integral.type_id=5')
                    ->order('integral.play_time desc')
                    ->paginate(array('list_rows'=>$pageSize,'page'=>$page))
                    ->toArray();
            foreach ($data['data'] as $k=>$v){
                if($v['play_time']){
                    $data['data'][$k]['play_time'] = date('Y-m-d H:i',$v['play_time']);
                }else{
                    $data['data'][$k]['play_time'] ='暂无数据';
                }
                
            }
           
            return $result = ['code'=>0,'msg'=>'获取成功!','data'=>$data['data'],'count'=>$data['total'],'rel'=>1];
        }
        $this->assign('user_id', $id);
        return $this->fetch('myTestHistory');
    }
    //个人系统积分记录
    public function mySystemHistory($id=''){

        if(request()->isPost()){
            $user_id=input('post.user_id');
            $where=1;
            $key=input('post.key');
            if(input('post.date1')&&input('post.date2')){  
                $date3=strtotime(input('post.date1'));
                $date4=strtotime(input('post.date2'));
                if($date3<$date4){
                    $where.=" and integral.play_time >= '". $date3."' and integral.play_time <= '". $date4."'";     
                }
                else{
                    $where.=" and integral.play_time >= '". $date3."' and integral.play_time <= '". $date4."'";        
                }
            } 
            elseif (input('post.date1')) {

                $date3=strtotime(input('post.date1'));
                $where.=" and integral.play_time >= '". $date3."'";
            }
            elseif (input('post.date2')) {
                $date4=strtotime(input('post.date2'));
                $where.=" and integral.play_time <= '". $date4."'";
            }
            $page =input('page')?input('page'):1;
            $pageSize =input('limit')?input('limit'):config('pageSize');
            $data=db('video_integral')
                    ->alias('integral')
                    ->field('integral.id,integral.play_time,integral.integral,users.username,users.nickname,users.truename,integral.reason')
                    ->join('mk_users users','integral.user_id=users.id','left')
                    ->where('integral.user_id='.$user_id)
                    ->where('integral.reason|users.nickname|users.username|users.truename','like',"%".$key."%")
                    ->where($where)
                    ->where('integral.type_id=6')
                    ->order('integral.play_time desc')
                    ->paginate(array('list_rows'=>$pageSize,'page'=>$page))
                    ->toArray();
            foreach ($data['data'] as $k=>$v){
                if($v['play_time']){
                    $data['data'][$k]['play_time'] = date('Y-m-d H:i',$v['play_time']);
                }else{
                    $data['data'][$k]['play_time'] ='暂无数据';
                }
                
            }
           
            return $result = ['code'=>0,'msg'=>'获取成功!','data'=>$data['data'],'count'=>$data['total'],'rel'=>1];
        }
        $this->assign('user_id', $id);
        return $this->fetch('mySystemHistory');
    }
    //全部积分记录
    public function allHistory($id=''){
        $ogroup_id=session('ogroup_id');
        if(request()->isPost()){
            $where=1;
            if($ogroup_id!='1'){
                $where.=" and users.ogroup_id= '".$ogroup_id."' ";
            }
            if(input('post.type')){
                $where.=" and integral.type_id='".input('post.type')."'";
            }
            
            $key=input('post.key');
            if(input('post.date1')&&input('post.date2')){  
                $date3=strtotime(input('post.date1'));
                $date4=strtotime(input('post.date2'));
                if($date3<$date4){
                    $where.=" and integral.play_time >= '". $date3."' and integral.play_time <= '". $date4."'";     
                }
                else{
                    $where.=" and integral.play_time >= '". $date3."' and integral.play_time <= '". $date4."'";        
                }
            } 
            elseif (input('post.date1')) {

                $date3=strtotime(input('post.date1'));
                $where.=" and integral.play_time >= '". $date3."'";
            }
            elseif (input('post.date2')) {
                $date4=strtotime(input('post.date2'));
                $where.=" and integral.play_time <= '". $date4."'";
            }
            $page =input('page')?input('page'):1;
            $pageSize =input('limit')?input('limit'):config('pageSize');
            $data=db('video_integral')
                    ->alias('integral')
                    ->field('integral.id,integral.video_id,video.title video_title,integral.play_time,integral.integral,users.username,users.nickname,users.truename,questions.question_title,type.type,test.title test_title,integral.reason,og.group_name')
                    ->join('mk_video_integral_type type','integral.type_id=type.id','left')
                    ->join('mk_video_video video','integral.video_id=video.id','left')
                    ->join('mk_video_test test','integral.test_id=test.id','left')
                    ->join('mk_users users','integral.user_id=users.id','left')
                    ->join(config('database.prefix').'organization_group og','users.ogroup_id = og.id','left')
                    ->join('mk_video_questions questions','integral.question_id=questions.id','left')
                    ->where('video.title|users.nickname|users.username|users.truename|questions.question_title','like',"%".$key."%")
                    ->where($where)
                    ->order('integral.play_time desc')
                    ->paginate(array('list_rows'=>$pageSize,'page'=>$page))
                    ->toArray();
           
            foreach ($data['data'] as $k=>$v){
                if($v['play_time']){
                    $data['data'][$k]['play_time'] = date('Y-m-d H:i',$v['play_time']);
                }else{
                    $data['data'][$k]['play_time'] ='暂无数据';
                }
                
            }
            return $result = ['code'=>0,'msg'=>'获取成功!','data'=>$data['data'],'count'=>$data['total'],'rel'=>1];
        }
        $this->assign('ogroup_id',$ogroup_id);
        return $this->fetch('allHistory');
    }

    //等级设置列表
    public function gradeList(){
        if(request()->isPost()){
            $key=input('post.key');
            $page =input('page')?input('page'):1;
            $pageSize =input('limit')?input('limit'):config('pageSize');
            $list=Db::name('video_grade_set')
                ->field('id,grade,setion_start,setion_end,level')
                ->where('grade','like',"%".$key."%")
                ->order('id asc')
                ->paginate(array('list_rows'=>$pageSize,'page'=>$page))
                ->toArray();
            // print_r($list);die();
            return $result = ['code'=>0,'msg'=>'获取成功!','data'=>$list['data'],'count'=>$data['total'],'rel'=>1];
        }
        return $this->fetch('gradeList');
    }
    //等级添加
    public function gradeAdd(){
        if(request()->isPost()){
            $post = Request::except('file');
            $list = [
                'grade' => $post['grade'],
                'setion_start' => $post['setion_start'],
                'setion_end' => $post['setion_end'],
                'time' => time(),
                'level' => $post['level']
            ];
            $res = db('video_grade_set')->insert($list);
            if($res){
                return ['code'=>1,'msg'=>'添加成功!','url'=>url('gradeList')];
            }else{
                return ['code'=>0,'msg'=>'添加失败!','url'=>url('gradeList')];
            }
        }else{
            $this->assign('info','null');
            $this->assign('title','添加学习等级');
            return $this->fetch('gradeForm');
        }
    }
    //等级设置
    public function gradeEdit($id){
        if(request()->isPost()){
            $post = Request::except('file');
            $list = [
                'id' => $post['id'],
                'grade' => $post['grade'],
                'setion_start' => $post['setion_start'],
                'setion_end' => $post['setion_end'],
                'level' => $post['level']
            ];
            $res = db('video_grade_set')->update($list);
            if($res!=false){
                return ['code'=>1,'msg'=>'修改成功!','url'=>url('gradeList')];
            }else{
                return ['code'=>0,'msg'=>'修改失败!','url'=>url('gradeList')];
            }
        }else{
            $info=db('video_grade_set')->where('id',$id)->find();
          
            $this->assign('info',json_encode($info,true));
            $this->assign('title',lang('edit').'学习等级');
            return $this->fetch('gradeForm');
        }
    }
    
   //总排行榜
    public function rankAll(){
        $ogroup_id=session('ogroup_id');
        $is_independence=db('organization_group')->where('id='.$ogroup_id)->value('is_independence');
        if(request()->isPost()){
            $redis = new IntegralRedis('','rankList');
            if($ogroup_id=='1'){
                $where=1;
                $where1=1;
            }else{
                
                if($is_independence=='1'){
                    //独立
                    $where="users.ogroup_id='".$ogroup_id."'";
                    $where1="users.ogroup_id='".$ogroup_id."'";
                }else{
                    $where="users.ogroup_id='".$ogroup_id."'";
                    $where1="users.ogroup_id='".$ogroup_id."' or ogroup.is_independence=2";
                }
            }
            $key=input('post.key');
            $page =input('page')?input('page'):1;
            $pageSize =input('limit')?input('limit'):config('pageSize');
            $get=db('users')
                ->alias('users')
                ->field('id,username,nickname,truename,integral')
                ->where($where)
                ->where('users.username|users.nickname|users.truename','like',"%".$key."%")
                ->order('integral desc')
                ->paginate(array('list_rows'=>$pageSize,'page'=>$page))
                ->toArray();
            $zongCount=db('users')
                ->alias('users')
                ->field('id')
                ->where($where)
                ->count();
            $get=$get['data'];
            // $get=$redis->getLeadboard('10',true,true);
            if(!$get){
               
                return $result = ['code'=>0,'msg'=>'获取成功!','data'=>[],'count'=>'0','rel'=>1];
            }else{
                
                $count=db('users')
                ->alias('users')
                ->field('users.id')
                ->join('mk_organization_group ogroup','users.ogroup_id=ogroup.id','left')
                ->where($where1)
                ->where('users.integral','gt','0')
                ->count();
                
                //所有人个数
                $userCount=db('users')
                ->alias('users')
                ->field('users.id')
                ->join('mk_organization_group ogroup','users.ogroup_id=ogroup.id','left')
                ->where($where1)
                ->count();
                $filter=Db::name('video_grade_set')
                        ->field('grade,setion_start,setion_end')
                        ->select();
                foreach($get as &$val){
                    //名次
                    if($val['integral']=='0'){
                       
                        $rank=$count+1;
                    }else{
                        $gaoCount=db('users')
                        ->alias('users')
                        ->field('users.id')
                        ->join('mk_organization_group ogroup','users.ogroup_id=ogroup.id','left')
                        ->where($where1)
                        ->where('integral','gt',$val['integral'])
                        ->count();
                        $rank=$gaoCount+1;  
                    } 
                    $val['rank']=$rank;
                    //学习等级
                    //百分比
                    if($userCount){
                        $rate=round(($rank/$userCount)*100);
                    }else{
                        $rate='0';
                    }
                    
                    $result = self::search($rate, $filter);
                    $grade=current($result)['grade'];
                    $val['grade']=$grade;
                    // $bang_all[]=$val;

                }
              
                return $result = ['code'=>0,'msg'=>'获取成功!','data'=>$get,'count'=>$zongCount,'rel'=>1];
            }
        }
        return $this->fetch('rankAll');
        
    }
   //季度排行榜
    public function rankJidu(){
        $ogroup_id=session('ogroup_id');
        $is_independence=db('organization_group')->where('id='.$ogroup_id)->value('is_independence');
        if(request()->isPost()){
            if($ogroup_id=='1'){
                $where=1;
                $where1=1;
            }else{
                
                if($is_independence=='1'){
                    //独立
                    $where="users.ogroup_id='".$ogroup_id."'";
                    $where1="users.ogroup_id='".$ogroup_id."'";
                }else{
                    $where="users.ogroup_id='".$ogroup_id."'";
                    $where1="users.ogroup_id='".$ogroup_id."' or ogroup.is_independence=2";
                }
            }
            $key=input('post.key');
            $redis = new IntegralRedis('','rankList');
            $jidu=$redis->jidu();
            $page =input('page')?input('page'):1;
            $pageSize =input('limit')?input('limit'):config('pageSize');
            $jidufen=Db::name('video_integral_jidu')
                ->alias('jidu')
                ->field('users.id,users.nickname,users.username,users.truename,jidu.integral integral_jidu,users.integral integral_all')
                ->join('mk_users users','jidu.user_id=users.id')  
                ->where($where)         
                ->where('users.username|users.nickname|users.truename','like',"%".$key."%")    
                ->where(['jidu'=>$jidu])
                ->order('jidu.integral desc')
                ->paginate(array('list_rows'=>$pageSize,'page'=>$page))
                ->toArray();
            $zongCount=Db::name('video_integral_jidu')
                ->alias('jidu')
                ->field('users.id')
                ->join('mk_users users','jidu.user_id=users.id')  
                ->where($where)             
                ->where(['jidu'=>$jidu])
                ->count();
            $jidufen=$jidufen['data'];
            if(!$jidufen){
                return $result = ['code'=>0,'msg'=>'获取成功!','data'=>[],'count'=>'0','rel'=>1];
            }
            $userCount=db('users')
                ->alias('users')
                ->field('users.id')
                ->join('mk_organization_group ogroup','users.ogroup_id=ogroup.id','left')
                ->where($where1)
                ->count();
            $filter=Db::name('video_grade_set')
                ->field('grade,setion_start,setion_end')
                ->select();
            foreach($jidufen as &$val){
                if(!$val['avatar']){
                    $val['avatar']='/muke/public/static/admin/images/0.jpg';
                }
                //等级
                //当前用户的总积分
                $gaoCount=db('users')
                        ->alias('users')
                        ->field('users.id')
                        ->join('mk_organization_group ogroup','users.ogroup_id=ogroup.id','left')
                        ->where($where1)
                        ->where('users.integral','gt',$val['integral_all'])
                        ->count();
                $rank=$gaoCount+1;  //名次
                $jdGaoCount=Db::name('video_integral_jidu')
                        ->alias('jidu')
                        ->field('users.id')
                        ->join('mk_users users','jidu.user_id=users.id')  
                        ->join('mk_organization_group ogroup','users.ogroup_id=ogroup.id','left')
                        ->where($where1)             
                        ->where(['jidu.jidu'=>$jidu])
                        ->where('jidu.integral','gt',$val['integral_jidu'])
                        ->count();
                $val['rank']=$jdGaoCount+1;
                $userCount=$userCount;//所有人个数
                //百分比
                if($userCount){
                    $rate=round(($rank/$userCount)*100);
                }else{
                    $rate='0';
                }
                // $val['rate']=$rate.'%';
                
                $result = self::search($rate, $filter);
                $grade=current($result)['grade'];
                $val['grade']=$grade;
                
            }
            
            return $result = ['code'=>0,'msg'=>'获取成功!','data'=>$jidufen,'count'=>$zongCount,'rel'=>1];
        }
        return $this->fetch('rankJidu');
    }
    /**
     * 二分法查找
     *
     * @param int $score 积分
     * @param array $filter 积分规则
     *
     * @return array $filter
     */
    public function search($score, $filter)
    {   
        $half = floor(count($filter) / 2); // 取出中間数

        // 判断积分在哪个区间
        if ($score <= $filter[$half - 1]['setion_end']) {
            $filter = array_slice($filter, 0 , $half);
        } else {
            $filter = array_slice($filter, $half , count($filter));
        }

        // 继续递归直到只剩一个元素
        if (count($filter) != 1) {
            $filter = self::search($score, $filter);
        }

        return $filter;
    }

    public function isOpenJidu(){
        $is_open=input('post.is_open');
        db('video_integral_jiduset')->where('id=1')->update(['is_open'=>$is_open]);
        $result['status'] = 1;
        $result['info'] = '设置成功!';
        $result['url'] = url('jiduSet');
        return $result;
    }
    //季度积分设置
    public function jiduSet(){
        $info=db('video_integral_jiduset')->field('is_open')->where('id=1')->find();
        $this->assign('info',json_encode($info,true));
        $this->assign('is_open',json_encode($info['is_open'],true));
        return $this->fetch('jiduSet');
    }

    
    //系统积分设置
    public function systemSet(){
        if(request()->isPost()){
            $post = input('post.');
            $user=$post['user'];
            if(!is_numeric($user)){
                //不是数字
                $users=db('users')->field('id,integral')->where(['username'=>$user])->find();
                $user=$users['id'];
            }else{
                $users=db('users')->field('id,integral')->where(['id'=>$user])->find();
            }
            if(!$users){
                return ['code'=>0,'msg'=>'无此用户!'];
            }
            $is_open=db('video_integral_type')->field('is_open')->where('id=6')->find();
            if($is_open=='0'){
                return ['code'=>0,'msg'=>'请联系管理员开通系统积分设置的开关!'];
            }
            //添加总积分
            $integral=$users['integral']+$post['integral'];
            db('users')->where('id='.$users['id'])->update(['integral'=>$integral]);
            //redis总积分
            $redis = new IntegralRedis('','rankList');
            // $redis->addNode($users['id'],$post['integral']);
            //添加季度积分
            $isJidu=$redis->isJidu();
                if($isJidu){
                    //季度积分已开启
                    $time=time();
                    $jidu=$redis->jidu($time);
                    $is_user=$redis->isUser($users['id'],$jidu);
                if($is_user){
                    //如果有这条数据  添加积分
                    $jidujf=$is_user['integral']+$post['integral'];
                    db('video_integral_jidu')->where('id='.$is_user['id'])->update(['integral'=>$jidujf,'update_time'=>time()]);
                }else{
                    //插入数据
                    db('video_integral_jidu')->insert(['user_id'=>$users['id'],'integral'=>$post['integral'],'create_time'=>time(),'jidu'=>$jidu]);
                }
            }
            //添加积分记录
            $list = [
                'type_id' => '6',
                'user_id' => $user,
                'play_time' => time(),
                'time' => time(),
                'integral'=>$post['integral'],
                'reason'=>$post['reason']
            ];
            $res = db('video_integral')->insert($list);
            if($res){
                return ['code'=>1,'msg'=>'设置成功!','url'=>url('systemSet')];
            }else{
                return ['code'=>0,'msg'=>'设置失败!','url'=>url('systemSet')];
            }
        }else{
            $this->assign('info','null');
            $this->assign('title','系统积分设置');
            return $this->fetch('systemSet');
        }
    }

    public function delIntegral($id=0){
        if($id == 111){
            $data=db('video_integral')->alias('integral')
                ->join('mk_video_video vv','integral.video_id=vv.id','left')
                ->join('mk_video_type vt','vv.type_id=vt.id','left')
                ->field('integral.*,vv.type_id vedio_type_id,vt.fid,vv.upload_time')
                ->where(['integral.type_id'=>3])
                ->select();
            $datas = [];
            foreach ($data as $k=>$v){
                if($v['vedio_type_id'] == 23 || $v['fid'] == 23){

                }else{
                    $datas[$v['user_id']][] = $v;
                }
            }
            foreach($datas as $kk => &$vv){
                $userIntegral = 0;
                if($vv){
                    foreach($vv as $k3 => &$v3){
                        $jidu= ceil((date('n',$v3['upload_time']))/3);
                        $year=date('Y',$v3['upload_time']);
                        $v3['jidu_date']=$year.'_'.$jidu;
    //                    $userIntegral += $v3['integral'];
                        db('users')->where(['id'=>$kk])->setDec('integral',$v3['integral']);
                        db('video_integral_jidu')->where(['user_id'=>$kk,'jidu'=>$v3['jidu_date']])->setDec('integral',$v3['integral']);
                        db('video_integral')->where(['id'=>$v3['id']])->delete();
                        // $redis = new IntegralRedis('','rankList');
                        // $integral=-$v3['integral'];
                        // $redis->addNode($kk,$integral);
                    }
                }
                // dump($kk);
                // dump($vv);
            }
            // die;
            
            echo "<script>alert('操作成功')</script>";
            return $this->fetch('delIntegral');
        }
        return $this->fetch('delIntegral');
    }

    //清理所有人的登录积分
    public function clearLogin(){
        $data=db('video_integral')
            ->alias('integral')
            ->join('mk_users users','integral.user_id=users.id','left')
            ->where('integral.type_id=1')
            ->field('integral.*')
            ->select();
        foreach($data as $key=>$val){
            $jidu= ceil((date('n',$val['time']))/3);
            $year=date('Y',$val['time']);
            $jidu_date=$year.'_'.$jidu;
            db('users')->where(['id'=>$val['user_id']])->setDec('integral',$val['integral']);
            db('video_integral_jidu')->where(['user_id'=>$val['user_id'],'jidu'=>$jidu_date])->setDec('integral',$val['integral']);
            db('video_integral')->where(['id'=>$val['id']])->delete();
        }
        
    }   
}
 