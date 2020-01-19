<?php
namespace app\admin\controller; 
use think\Controller;
use think\Db;
use think\facade\Request;
use think\validate;
use think\facade\Session;
use app\admin\model\IntegralRedis;
Class Work extends Common{
    public function workReview(){
        $ogroup_id=session('ogroup_id');
        if(request()->isPost()){
            $where=1;
            if($ogroup_id!='1'){
                $where.=" and users.ogroup_id= '".$ogroup_id."' ";
            }
            if(input('post.date1')&&input('post.date2')){  
                $date3=strtotime(input('post.date1'));
                $date4=strtotime(input('post.date2'));
                if($date3<$date4){
                    $where.=" and work.upload_time >= '". $date3."' and work.upload_time <= '". $date4."'";     
                }
                else{
                    $where.=" and work.upload_time >= '". $date3."' and work.upload_time <= '". $date4."'";        
                }
            } 
            elseif (input('post.date1')) {
                $date3=strtotime(input('post.date1'));
                $where.=" and work.upload_time >= '". $date3."'";
            }
            elseif (input('post.date2')) {
                $date4=strtotime(input('post.date2'));
                $where.=" and work.upload_time <= '". $date4."'";
            }
            $key=input('post.key');
            $page =input('page')?input('page'):1;
            $pageSize =input('limit')?input('limit'):config('pageSize');
			$list=db('video_text_work')
				->alias('work')
				->field('work.id,users.username,users.nickname,users.truename,video.title,work.worktitle,work.upload_time,og.group_name')
				->join('mk_video_video video','work.video_id=video.id')
                ->join('mk_users users','work.user_id=users.id')
                ->join(config('database.prefix').'organization_group og','users.ogroup_id = og.id','left')
                ->where('work.work_status=1')
                ->where('users.username|users.nickname|users.truename|video.title|work.worktitle','like',"%".$key."%")
                ->where($where)
                ->order('work.upload_time desc')
                ->paginate(array('list_rows'=>$pageSize,'page'=>$page))
                ->toArray();
                foreach ($list['data'] as $k=>$v){
	            	$list['data'][$k]['upload_time'] = date('Y年m月d日',$v['upload_time']);
	            }
            // print_r($list);
            return $result = ['code'=>0,'msg'=>'获取成功!','data'=>$list['data'],'count'=>$list['total'],'rel'=>1];
        }
        $this->assign('ogroup_id',$ogroup_id);
        return $this->fetch('workReview');
    }
    public function review(){
        if(request()->isPost()){
            $post = Request::except('file');
            $list = [
            	'id' => $post['id'],
                'score' => $post['score'],
                'admin_id' => session('aid'),
                'work_status' => '2',
                'review_time' => time()
            ];
            $res = db('video_text_work')->update($list);
            $course=db('video_text_work')->field('video_id,user_id')->where('id='.$post['id'])->find();
            if($res!=false){
                //判断课程积分是否开启
                $score=db('video_integral_type')->field('is_open')->where('id=9')->find();
                if($score['is_open']=='1')
                {
                    $list1=[
                        'type_id'=>'9',
                        'video_id'=>$course['video_id'],
                        'play_time'=>time(),
                        'user_id'=>$course['user_id'],
                        'time'=>time(),
                        'integral'=>$post['score'],
                        'work_id'=>$post['id']
                    ];
                    $user=db('users')->field('integral')->where('id='.$course['user_id'])->find();
                    $result=db('video_integral')->insert($list1);
                    $integral=$user['integral']+$post['score'];
                    $updateUsers=db('users')->where(['id'=>$course['user_id']])->update(['integral'=>$integral]);
                    if($updateUsers==false){
                        return ['code'=>0,'msg'=>'操作失败!','url'=>url('workReview')];
                    }
                    $redis = new IntegralRedis('','rankList');
                    // $redis->addNode($course['user_id'],$post['score']);
                    if(!$result){
                        return ['code'=>0,'msg'=>'操作失败!','url'=>url('workReview')];
                    }else{
                        //季度
                        $isJidu=$redis->isJidu();
                        if($isJidu){
                            //季度积分已开启
                            $jidu=$redis->courseJidu($course['video_id']);
                            $user=$redis->isUser($course['user_id'],$jidu);
                            if($user){
                                //如果有这条数据  添加积分
                                $jidujf=$user['integral']+$post['score'];
                                db('video_integral_jidu')->where('id='.$user['id'])->update(['integral'=>$jidujf,'update_time'=>time()]);
                            }else{
                                //插入数据
                                db('video_integral_jidu')->insert(['user_id'=>$course['user_id'],'integral'=>$post['score'],'create_time'=>time(),'jidu'=>$jidu]);
                            }
                        }
                    }
                    
                }
                return ['code'=>1,'msg'=>'操作成功!','url'=>url('workReview')];
            }else{
                return ['code'=>0,'msg'=>'操作失败!','url'=>url('workReview')];
            }
        }else{
        	$id=input('get.id');
            $info=db('video_text_work')
                ->alias('work')
                ->field('video.title,work.worktitle,work.workcontent')
                ->join('mk_video_video video','work.video_id=video.id')
                ->join('mk_users users','work.user_id=users.id')
                ->where('work.id='.$id)
                ->find();
            if($info){
            	$content=$info['workcontent'];
            }else{
            	$content='';
            }
            $this->assign('content',$content);
            $this->assign('info',json_encode($info,true));
            $this->assign('title','作业审核');
            $this->assign('id',$id);
            return $this->fetch('review');
        }
    }
    public function workList(){
        $ogroup_id=session('ogroup_id');
        if(request()->isPost()){
            $where=1;
            if($ogroup_id!='1'){
                $where.=" and users.ogroup_id= '".$ogroup_id."' ";
            }
            if(input('post.date1')&&input('post.date2')){  
                $date3=strtotime(input('post.date1'));
                $date4=strtotime(input('post.date2'));
                if($date3<$date4){
                    $where.=" and work.review_time >= '". $date3."' and work.review_time <= '". $date4."'";     
                }
                else{
                    $where.=" and work.review_time >= '". $date3."' and work.review_time <= '". $date4."'";        
                }
            } 
            elseif (input('post.date1')) {
                $date3=strtotime(input('post.date1'));
                $where.=" and work.review_time >= '". $date3."'";
            }
            elseif (input('post.date2')) {
                $date4=strtotime(input('post.date2'));
                $where.=" and work.review_time <= '". $date4."'";
            }
            $key=input('post.key');
            $page =input('page')?input('page'):1;
            $pageSize =input('limit')?input('limit'):config('pageSize');
			$list=db('video_text_work')
				->alias('work')
                ->field('work.id,users.username,users.nickname,users.truename,video.title,work.worktitle,work.score,work.review_time,work.upload_time,admin.username adminname,og.group_name')
                ->join('mk_admin admin','work.admin_id=admin.admin_id')
				->join('mk_video_video video','work.video_id=video.id')
                ->join('mk_users users','work.user_id=users.id')
                ->join(config('database.prefix').'organization_group og','users.ogroup_id = og.id','left')
                ->where('work.work_status=2')
                ->where('users.username|video.title|work.worktitle|work.score|admin.username','like',"%".$key."%")
                ->where($where)
                ->order('work.review_time desc')
                ->paginate(array('list_rows'=>$pageSize,'page'=>$page))
                ->toArray();
                foreach ($list['data'] as $k=>$v){
                    $list['data'][$k]['upload_time'] = date('Y年m月d日',$v['upload_time']);
                    $list['data'][$k]['review_time'] = date('Y年m月d日',$v['review_time']);
	            }
            // print_r($list);
            return $result = ['code'=>0,'msg'=>'获取成功!','data'=>$list['data'],'count'=>$list['total'],'rel'=>1];
        }
        $this->assign('ogroup_id',$ogroup_id);
        return $this->fetch('workList');
    }
    public function workDetails(){
        $id=input('get.id');
        $info=db('video_text_work')
            ->alias('work')
            ->field('users.username,video.title,work.worktitle,work.score,work.review_time,work.upload_time,admin.username adminname,work.workcontent')
            ->join('mk_admin admin','work.admin_id=admin.admin_id')
            ->join('mk_video_video video','work.video_id=video.id')
            ->join('mk_users users','work.user_id=users.id')
            ->where('work.id='.$id)
            ->find();
        if($info){
            $content=$info['workcontent'];
        }else{
            $content='';
        }
        
        $info['upload_time'] = date('Y年m月d日',$info['upload_time']);
        $info['review_time'] = date('Y年m月d日',$info['review_time']);
        $this->assign('content',$content);
        $this->assign('info',json_encode($info,true));
        $this->assign('title','作业详情');
        return $this->fetch('workDetails');
    }
}