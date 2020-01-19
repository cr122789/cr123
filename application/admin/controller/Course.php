<?php
namespace app\admin\controller;
// require_once VENDOR_PATH . '\autoload.php';  
use Qiniu\Auth;
use Qiniu\Storage\UploadManager;
use think\Controller;
use think\Db;
use think\facade\Request;
use think\validate;
use think\facade\Session;
use Qiniu\Storage\BucketManager;

Class Course extends Common{
	//视频列表
    public function index(){
        $ogroup_id=session('ogroup_id');
        if(request()->isPost()){
            $where=1;
            
            if(input('post.date1')&&input('post.date2')){  
                $date3=strtotime(input('post.date1'));
                $date4=strtotime(input('post.date2'));
                if($date3<$date4){
                    $where.=" and video.upload_time >= '". $date3."' and video.upload_time <= '". $date4."'";     
                }
                else{
                    $where.=" and video.upload_time >= '". $date3."' and video.upload_time <= '". $date4."'";        
                }
            } 
            elseif (input('post.date1')) {

                $date3=strtotime(input('post.date1'));
                $where.=" and video.upload_time >= '". $date3."'";
            }
            elseif (input('post.date2')) {
                $date4=strtotime(input('post.date2'));
                $where.=" and video.upload_time <= '". $date4."'";
            }
            if(input('post.type')){
                $type=input('post.type');
                $type_count=db('video_type')
                ->where('fid='.$type)
                ->where('is_show=1')
                ->count();
                //大分类
                if($type_count){
                    $where.=" and type.fid='".$type."'";
                }else{
                    //小分类
                    $where.=" and video.type_id='".$type."' ";
                } 
            }else{
                if($ogroup_id!='1'){
                    $course_power=db('organization_group')->where('id='.$ogroup_id)->value('course_power');
                    $course_power=rtrim($course_power, ",");
                    $where.=" and video.type_id in (".$course_power.")";
                }
            }
            $key=input('post.key');
            $page =input('page')?input('page'):1;
            $pageSize =input('limit')?input('limit'):config('pageSize');
            $list=Db::name('video_video')
            	->alias('video')
                ->field('video.id,video.title,type.type,teacher.teacher_name,video.video_img,admin.username,video.is_recommend,video.upload_time,video.view_number')
                ->join('mk_admin admin','video.admin_id=admin.admin_id')
                ->join('mk_video_teacher teacher','video.teacher_id=teacher.id')
                ->join('mk_video_type type','video.type_id=type.id')
                ->where('video.title','like',"%".$key."%")
                ->where($where)
                ->where('type.is_show=1')
                ->where('video.course_type=1')
                ->where('video.is_show=1')
                ->order('video.id desc')
                ->paginate(array('list_rows'=>$pageSize,'page'=>$page))
                ->toArray();

            foreach ($list['data'] as $k=>$v){
                $list['data'][$k]['upload_time'] = date('Y年m月d日',$v['upload_time']);
         
            }
            // print_r($list);die();
            return $result = ['code'=>0,'msg'=>'获取成功!','data'=>$list['data'],'count'=>$list['total'],'rel'=>1];
        }
        $where_type=1;
        if($ogroup_id!='1'){
            $course_power=db('organization_group')->where('id='.$ogroup_id)->value('course_power');
            $course_power=rtrim($course_power, ",");
            $where_type="id in (".$course_power.")";
        }
        $type=db('video_type')->where($where_type)->where('is_show=1')->select();
        $type=self::tree($type);
        $this->assign('type', $type);
        return $this->fetch();
    }

    //修改课程是否为精品
    public function recommend(){
        $id=input('post.id');
        $is_recommend=input('post.is_open');
        if (empty($id)){
            $result['status'] = 0;
            $result['info'] = '课程ID不存在!';
            $result['url'] = url('index');
            return $result;
        }
        db('video_video')->where('id='.$id)->update(['is_recommend'=>$is_recommend]);
        $result['status'] = 1;
        $result['info'] = '设置成功!';
        $result['url'] = url('index');
        return $result;
    }
    //新增课程
    public function add(){
    	
    	if(request()->isPost()){
            $post = Request::except('file');
            $validate = new validate([
                'video'  => 'require',
                'title' =>'require|max:30',
            ],[
                'video.require' => '请上传视频',
                'title.require' =>'请输入课程名称',
                'title.max' => '课程名称必须在30个字符之间'
              
            ]);
            if(!$validate->check($post)){
                return $result = ['code'=>0,'msg'=>$validate->getError()];
            }
            $url=config('default_video').$post['video'];
         
            if($url){
            	$file_contents = file_get_contents($url.'?avinfo&type=json');
				$arr=json_decode($file_contents, true);
				$s=$arr['format']['duration'];
				$h    =    floor($s/60);
			    //计算秒
			    //算法：取得秒%60的余数，既得到秒数
			    $s    =    $s%60;
			    //如果只有一位数，前面增加一个0
			    $h    =    (strlen($h)==1)?'0'.$h:$h;
			    $s    =    (strlen($s)==1)?'0'.$s:$s;
			   $duration= $h.':'.$s;
            }else{
            	$duration= '';
            }
			
		   //tag
		    $tag=$post['tag_id'];
		    $tag=substr($tag, 1);
		    $tag=explode('#', $tag);
		    foreach($tag as $val){
		   		$tag_db=db('video_tag')->where(['tag'=>$val])->find();
		   		// print_r($tag_db);die();
		   		if($tag_db){
		   			$tag_db_count=$tag_db['count']+1;
		   			db('video_tag')->where(['tag'=>$val])->update(['count'=>$tag_db_count]);
		   			$tag_idss[]=$tag_db['id'];
		   		}else{
		   			$list_tag=[
			   			'tag'=>$val,
			   			'count'=>'0',
			   			'time'=>time()
			   		];
			   		$tag_id=db('video_tag')->insertGetId($list_tag);
			   		$tag_idss[]=$tag_id;
		   		}
		   		
		    }
		    $tag_ids=implode('#', $tag_idss);
            
            $list = [
                'title' => $post['title'],
                'type_id' => $post['type_id'],
                'teacher_id' => $post['teacher_id'],
                'admin_id' => session('aid'),
                'video' => $post['video'],
                'introduction' => $post['introduction'],
                // 'is_recommend' => $post['is_recommend'],
                'video_img' => $post['video_img'],
                'duration' => $duration,
                'tag_id' => $tag_ids,
                'seo_title' => $post['seo_title'],
                'seo_key' => $post['seo_key'],
                'seo_des' => $post['seo_des'],
                'course_type'=>'1',
                'upload_time' => time()
            ];
            $res = db('video_video')->insertGetId($list);
            if($res){
                //添加tag至关联表
                foreach($tag_idss as $val){
                    db('video_connect_tag')->insert(['video_id'=>$res,'tag_id'=>$val]);
                }
                return ['code'=>1,'msg'=>'添加成功!','url'=>url('index')];
            }else{
                return ['code'=>0,'msg'=>'添加失败!','url'=>url('index')];
            }
        }else{
        	$type=db('video_type')->where('is_show=1')->select();
        	$type = self::tree($type);
            $teacher=db('video_teacher')->where('is_show=1')->select();
            $this->assign('info','null');
            $this->assign('title','创建课程');
            $this->assign('type',$type);
            $this->assign('teacher',$teacher);
            $this->assign('id','');
            return $this->fetch('caurseForm');
        }
    
    }
    //课程编辑
    public function edit($id=''){
        if(request()->isPost()){
            $post = Request::except('file');
            $validate = new validate([
                'video'  => 'require',
                'title' =>'require|max:30',
            ],[
                'video.require' => '请上传视频',
                'title.require' =>'请输入课程名称',
                'title.max' => '课程名称必须在30个字符之间'
              
            ]);
            if(!$validate->check($post)){
                return $result = ['code'=>0,'msg'=>$validate->getError()];
            }
            $url=config('default_video').$post['video'];
            if($url){
            	$file_contents = file_get_contents($url.'?avinfo&type=json');
				$arr=json_decode($file_contents, true);
				$s=$arr['format']['duration'];
				$h    =    floor($s/60);
			    //计算秒
			    //算法：取得秒%60的余数，既得到秒数
			    $s    =    $s%60;
			    //如果只有一位数，前面增加一个0
			    $h    =    (strlen($h)==1)?'0'.$h:$h;
			    $s    =    (strlen($s)==1)?'0'.$s:$s;
			   	$duration= $h.':'.$s;
		   }else{
		   		$duration= '';
		   }
			

		   //修改前的tag处理

		   	$course_tag=db('video_video')->field('tag_id')->where('id='.$post['id'])->find();
		   	//新tag
            $tag=$post['tag_id'];
            $tag=substr($tag, 1);
            $tag=explode('#', $tag);
            if($course_tag['tag_id']){
             
                //原来有tag
                $course_tags=explode('#', $course_tag['tag_id']);//该课程原tag
                foreach($course_tags as $val){
                    $tag_db_jiu=db('video_tag')->where('id='.$val)->find();
                    $tag_db_jius[]=$tag_db_jiu['tag'];
                }
                //如果没改
                if($tag==$tag_db_jius){
                    $tag_ids=$course_tag['tag_id'];
                }else{
                    
                    foreach($tag_db_jius as $val){
                        //编辑时删除的
                        // echo $val;
                        // print_r($tag);die();
                        $result=in_array($val, $tag);
                        if(!$result){
                            $result_1=db('video_tag')->where(['tag'=>$val])->find();
                            // print_r($result_1);die();
                            if($result_1['count']>'0'){
                                $tag_count=$result_1['count']-1;
                                // echo $tag_count;die();
                                db('video_tag')->where(['tag'=>$val])->update(['count'=>$tag_count]);
                            }else{
                                db('video_tag')->where('id='.$result_1['id'])->delete();
                            }
                            db('video_connect_tag')->where('video_id='.$post['id'])->where('tag_id='.$result_1['id'])->delete();
                        }
                    }
                   
                    foreach($tag as $val){
                        
                        $tag_db=db('video_tag')->where(['tag'=>$val])->find();
                        if($tag_db){
                            if(in_array($val,$tag_db_jius)){
                                $tag_idss[]=$tag_db['id'];
                            }else{
                                $tag_db_count=$tag_db['count']+1;
                                db('video_tag')->where(['tag'=>$val])->update(['count'=>$tag_db_count]);
                                $tag_idss[]=$tag_db['id'];
                                db('video_connect_tag')->insert(['video_id'=>$post['id'],'tag_id'=>$tag_db['id']]);
                            }
                            
                        }else{
                            $list_tag=[
                                'tag'=>$val,
                                'count'=>'0',
                                'time'=>time()
                            ];
                            $tag_id=db('video_tag')->insertGetId($list_tag);
                            $tag_idss[]=$tag_id;
                            db('video_connect_tag')->insert(['video_id'=>$post['id'],'tag_id'=>$tag_id]);
                        }
                        
                    }
                    $tag_ids=implode('#', $tag_idss);
                }
		   	
		   	}else{
                foreach($tag as $val){
                    $tag_db=db('video_tag')->where(['tag'=>$val])->find();
                    // print_r($tag_db);die();
                    if($tag_db){
                        $tag_db_count=$tag_db['count']+1;
                        db('video_tag')->where(['tag'=>$val])->update(['count'=>$tag_db_count]);
                        $tag_idss[]=$tag_db['id'];
                        db('video_connect_tag')->insert(['video_id'=>$post['id'],'tag_id'=>$tag_db['id']]);
                    }else{
                        $list_tag=[
                            'tag'=>$val,
                            'count'=>'0',
                            'time'=>time()
                        ];
                        $tag_id=db('video_tag')->insertGetId($list_tag);
                        $tag_idss[]=$tag_id;
                        db('video_connect_tag')->insert(['video_id'=>$post['id'],'tag_id'=>$tag_id]);
                    }
                    
               }
               $tag_ids=implode('#', $tag_idss);
            }

		   	// print_r( $tag);die();
		   //删除原视频
		   $course_video=db('video_video')->field('video')->where('id='.$post['id'])->find();
		   if($course_video['video']!=$post['video']){
		   		$key = $course_video['video'];
				$a=substr($key,strrpos($key,"/")); 
				$b=substr($a,1);  
				$bucket = 'swpedu';
                $accessKey = 'MsUTHp5JH9_5Bjoie6_wsNS2NoqxkqrvcshuYA_g';
                $secretKey = 'Givh3XA9wjLkEY6twzTav651sZMxjOOKD4V4J0Kt';
				$expires = 6000;
				$auth = new Auth($accessKey, $secretKey);
				$policy = array(
				    'callbackBody' => 'key=$(key)&hash=$(etag)&bucket=$(bucket)&fsize=$(fsize)&name=$(x:name)',
				    'callbackBodyType' => 'application/json'
				);
				$token = $auth->uploadToken($bucket, null, $expires, $policy, true);
				// 构建 UploadManager 对象
				$bucketMgr = new BucketManager($auth);
				 
				$err = $bucketMgr->delete($bucket,$b);
		   }
            
            $list = [
                'id' => $post['id'],
                'title' => $post['title'],
                'type_id' => $post['type_id'],
                'teacher_id' => $post['teacher_id'],
                'admin_id' => session('aid'),
                'video' => $post['video'],
                'introduction' => $post['introduction'],
                // 'is_recommend' => $post['is_recommend'],
                'duration' => $duration,
                'tag_id' => $tag_ids,
                'seo_title' => $post['seo_title'],
                'seo_key' => $post['seo_key'],
                'seo_des' => $post['seo_des'],
                'video_img' => $post['video_img']
            ];
            // print_r($list);die();
            $res = db('video_video')->update($list);
            if($res!==false){
                return ['code'=>1,'msg'=>'修改成功!','url'=>url('index')];
            }else{
                return ['code'=>0,'msg'=>'修改失败!','url'=>url('index')];
            }
        }else{
            $info=db('video_video')->where('id',$id)->where('course_type=1')->find();
            if($info['tag_id']){
                $course_tags=explode('#', $info['tag_id']);
                foreach($course_tags as $val){
                    $tag_title=db('video_tag')->field('tag')->where('id='.$val)->find();
                    $tags[]=$tag_title['tag'];
                }
                $tags=implode('#', $tags);
                $tags='#'.$tags;
            }else{
                $tags='';
            }
            $info['tag_id']=$tags;
            $type=db('video_type')->where('is_show=1')->select();
            $type = self::tree($type);
            $teacher=db('video_teacher')->where('is_show=1')->select();
            $this->assign('info',json_encode($info,true));
            $this->assign('title',lang('edit').'课程');
            $this->assign('type',$type);
            $this->assign('teacher',$teacher);
            return $this->fetch('caurseForm');
        }
    }
    //单删
    public function courseDel(){
        // db('video_video')->delete(['id'=>input('id')]);
        //软删除
        db('video_video')->where(['id'=>input('id')])->update(['is_show'=>'0']);
        db('video_collection')->where(['video_id'=>input('id')])->delete();
        return $result = ['code'=>1,'msg'=>'删除成功!'];
    }
    //多删
    public function delall(){
        $map[] =array('id','IN',input('param.ids/a'));
        // db('video_video')->where($map)->delete();
        // print_r($map);die();
        db('video_video')->where($map)->update(['is_show'=>'0']);
        $map1[] =array('video_id','IN',input('param.ids/a'));
        db('video_collection')->where($map1)->delete();
        $result['msg'] = '删除成功！';
        $result['code'] = 1;
        $result['url'] = url('index');
        return $result;
    }

    /***********************************问题列表***********************************/
    public function questionList($id=''){
        if(request()->isPost()){
            $video_id = input('post.video_id');
            $list=db('video_connect_course')
                    ->alias('cc')
                    ->field('cc.question_id id,cc.id ccid,cc.time_node,questions.question_title,cc.sort')
                    ->join('mk_video_questions questions','cc.question_id=questions.id')
                    ->where('cc.video_id='.$video_id)
                    ->select();
            foreach($list as &$v){
                $v['answer_num'] = db('video_answers')->where('question_id',$v['id'])->where('is_show=1')->count();
                $v['answer_right_num'] = db('video_answers')->where(['question_id'=>$v['id'],'is_right'=>1])->where('is_show=1')->count();
            }
            return $result = ['code'=>0,'msg'=>'获取成功!','data'=>$list,'rel'=>1];
        }
        $cause = db('video_video')->where('id',$id)->where('is_show=1')->find();

        $this->assign('title', $cause['title']);
        $this->assign('video_id', $id);

        return $this->fetch('questionList');
    }
    //排序
    public function questionOrder(){
        $db=db('video_connect_course');
        $data = input('post.');
        $result=Db::name('video_connect_course')->where(['id'=>$data['id']])->update(['sort'=>$data['sort']]);
        if($result!==false){
            return $result = ['msg' => '操作成功！', 'code' =>1];
        }else{
            return $result = ['code'=>0,'msg'=>'操作失败！'];
        }
    }
    public function questionAdd($video_id = ''){
        if(request()->isPost()){
            $post = Request::except('file');
            $validate = new validate([
                
                'question_title' =>'require|max:150',
            ],[
               
                'question_title.require' =>'请填写问题名称',
                'question_title.max' => '问题最多可写入150个字符'
              
            ]);
            if(!$validate->check($post)){
                return $result = ['code'=>0,'msg'=>$validate->getError()];
            }
            $answerType=count(array_filter($post['is_right']));
            if($answerType<'1'){
                return $result = ['code'=>0,'msg'=>'至少选择一个正确答案！'];
            }else if($answerType=='1'){
                $answer_type='1';
            }else if($answerType>'1'){
                $answer_type='2';
            }
            $questionlist = [
                'question_title' => $post['question_title'],
                'answer_type' => $answer_type,
                'add_time' =>time()
            ];
            // print_r($questionlist);die();
            $questionres = db('video_questions')->insertGetId($questionlist);
            $connectList=[
                'video_id'=>$post['video_id'],
                'question_id'=>$questionres,
                'time_node'=>$post['time_node']
            ];
            $result = db('video_connect_course')->insert($connectList);
            foreach($post['answer'] as $k => $v){
                $answerslist = [
                    'question_id' => $questionres,
                    'choice_text' => $v,
                    'is_right' => $post['is_right'][$k],
                ];
                $answersres = db('video_answers')->insert($answerslist);
            }

            if($questionres && $answersres){
                return ['code'=>1,'msg'=>'添加成功!','url'=>url('questionList',['id'=>$post['video_id']])];
            }else{
                return ['code'=>0,'msg'=>'添加失败!','url'=>url('questionList',['id'=>$post['video_id']])];
            }
        }else{
            $info = array();
            $this->assign('info',json_encode($info,true));
            $this->assign('video_id',$video_id);
            $this->assign('title','创建问题');
            return $this->fetch('questionForm');
        }
    }
    public function questionEdit($question_id = '',$video_id=''){
        if(request()->isPost()){
            $post = Request::except('file');
            $validate = new validate([
                
                'question_title' =>'require|max:150',
            ],[
               
                'question_title.require' =>'请填写问题名称',
                'question_title.max' => '问题最多可写入150个字符'
              
            ]);
            if(!$validate->check($post)){
                return $result = ['code'=>0,'msg'=>$validate->getError()];
            }
            $answerType=count(array_filter($post['is_right']));
            if($answerType<'1'){
                return $result = ['code'=>0,'msg'=>'至少选择一个正确答案！'];
            }else if($answerType=='1'){
                $answer_type='1';
            }else if($answerType>'1'){
                $answer_type='2';
            }
            $questionlist = [
               'question_title' => $post['question_title'],
                'answer_type' => $answer_type,
            ];
            $questionres = db('video_questions')->where(['id'=>$post['question_id']])->update($questionlist);
            db('video_connect_course')->where('question_id='.$question_id)->where('video_id='.$video_id)->update(['time_node'=>$post['time_node']]);
            // db('video_answers')->where(['question_id'=>$post['question_id']])->delete();
            //软删除
            db('video_answers')->where(['question_id'=>$post['question_id']])->update(['is_show'=>'0']);
            foreach($post['answer'] as $k => $v){
                $answerslist = [
                    'question_id' => $post['question_id'],
                    'choice_text' => $v,
                    'is_right' => $post['is_right'][$k],
                    'is_show' => '1'
                ];
                $answersres = db('video_answers')->insert($answerslist);
            }
            if($questionres!==false && $answersres){
                return ['code'=>1,'msg'=>'编辑成功!','url'=>url('questionList',['id'=>$post['video_id']])];
            }else{
                return ['code'=>0,'msg'=>'编辑失败!','url'=>url('questionList',['id'=>$post['video_id']])];
            }
        }else{
            $info = db('video_questions')->where(['id'=>$question_id])->where('is_show=1')->find();
            $answers = db('video_answers')->where(['question_id'=>$question_id])->where('is_show=1')->order('id asc')->select();
            $connect=db('video_connect_course')->field('time_node')->where('video_id='.$video_id)->where('question_id='.$question_id)->find();
            $info['time_node']=$connect['time_node'];
            // print_r($info);die();
            $this->assign('info',json_encode($info,true));
            $this->assign('answers',$answers);
            $this->assign('video_id',$video_id);
            $this->assign('title','编辑问题');
            return $this->fetch('questionForm');
        }
    }
    public function questionDel(){
        $vidoe_id = input('post.vidoe_id');
        $question_id = input('post.question_id');
        db('video_connect_course')->where('vidoe_id='.video_id)->where('question_id='.$question_id)->delete();
        return ['code'=>1,'msg'=>'删除成功！'];
    }
    //多项添加
    public function questionAddslist($video_id=''){
        if(request()->isPost()){
            $no=db('video_connect_course')->where('video_id='.$video_id)->select();
            foreach($no as $val){
                $ids[]=$val['question_id'];
            }
           
            $where=1;
            if(input('post.date1')&&input('post.date2')){  
                $date3=strtotime(input('post.date1'));
                $date4=strtotime(input('post.date2'));
                if($date3<$date4){
                    $where.=" and add_time >= '". $date3."' and add_time <= '". $date4."'";     
                }
                else{
                    $where.=" and add_time >= '". $date3."' and add_time <= '". $date4."'";        
                }
            } 
            elseif (input('post.date1')) {

                $date3=strtotime(input('post.date1'));
                $where.=" and add_time >= '". $date3."'";
            }
            elseif (input('post.date2')) {
                $date4=strtotime(input('post.date2'));
                $where.=" and add_time <= '". $date4."'";
            }
            $key=input('post.key');
            $page =input('page')?input('page'):1;
            $pageSize =input('limit')?input('limit'):config('pageSize');
            if(!$ids){
                $ids=['0'=>'0'];
            }
            $list=db('video_questions')
                    ->field('id,question_title,add_time')
                    ->where('id','not in',$ids)
                    ->where('question_title','like',"%".$key."%")
                    ->where($where)
                    ->order('id desc')
                    ->paginate(array('list_rows'=>$pageSize,'page'=>$page))
                    ->toArray();
     
            foreach ($list['data'] as $k=>$v){
                $list['data'][$k]['add_time'] = date('Y年m月d日',$v['add_time']);
                $list['data'][$k]['answer_num'] = db('video_answers')->where('question_id',$v['id'])->where('is_show=1')->count();
                $list['data'][$k]['answer_right_num'] = db('video_answers')->where(['question_id'=>$v['id'],'is_right'=>1])->where('is_show=1')->count();
            }
            return $result = ['code'=>0,'msg'=>'获取成功!','data'=>$list['data'],'count'=>$list['total'],'rel'=>1];
        }
        $this->assign('title', '批量添加问题');
        $this->assign('video_id', $video_id);

        return $this->fetch('questionAddslist');
    }

    //多项添加中的添加单项
    public function questionAlladd(){
        $question_id=input('post.question_id');
        $video_id=input('post.video_id');
        $list=[
            'question_id'=>$question_id,
            'video_id'=>$video_id,
            'time_node'=>'',
            // 'sort'=>'50'
        ];
        $result=db('video_connect_course')->insert($list);
        if($result!==false){
            return $result = ['code'=>1,'msg'=>'添加成功!'];
        }else{
            return $result = ['code'=>0,'msg'=>'添加失败，请重试!'];
        }
    }
    //多项添加中的添加多项
    public function questionAlladds(){
        $question_ids =input('param.question_id/a');
        $video_id=input('post.video_id');
        foreach($question_ids as $val){
            $list[]=[
                'question_id'=>$val,
                'video_id'=>$video_id,
                'time_node'=>'',
                // 'sort'=>'50'
            ];
        }
        $result=db('video_connect_course')->insertAll($list);
        if($result!==false){
            return $result = ['code'=>1,'msg'=>'添加成功!'];
        }else{
            return $result = ['code'=>0,'msg'=>'添加失败，请重试!'];
        }
    }
    

 /***********************************答题记录***********************************/
 	//答题记录
 	public function answerHistory(){
    	if(request()->isPost()){
            $key=input('post.key');
            $page =input('page')?input('page'):1;
            $pageSize =input('limit')?input('limit'):config('pageSize');
            $list=Db::name('video_answer_history')
            	->alias('history')
            	->field('history.id,history.is_right,history.answer_time,video.title,questions.question_title,answers.choice_text,users.username,users.truename')
            	->join('mk_video_video video','history.video_id=video.id')
            	->join('mk_video_questions questions','history.question_id=questions.id')
            	->join('mk_video_answers answers','history.answer_id=answers.id')
            	->join('mk_users users','history.user_id=users.id')
                ->where('users.truename','like',"%".$key."%")
                ->order('history.id asc')
                ->paginate(array('list_rows'=>$pageSize,'page'=>$page))
                ->toArray();

            foreach ($list['data'] as $k=>$v){
            	$list['data'][$k]['answer_time'] = date('Y年m月d日',$v['answer_time']);
            }
            // print_r($list);
            return $result = ['code'=>0,'msg'=>'获取成功!','data'=>$list['data'],'rel'=>1];
        }
        return $this->fetch('answerHistory');
    }

 /***********************************讲师列表***********************************/
    //讲师列表
    public function teacherList(){
    	if(request()->isPost()){
            $key=input('post.key');
            $page =input('page')?input('page'):1;
            $pageSize =input('limit')?input('limit'):config('pageSize');
            $list=Db::name('video_teacher')
                ->where('teacher_name','like',"%".$key."%")
                ->where('is_show=1')
                ->order('id asc')
                ->paginate(array('list_rows'=>$pageSize,'page'=>$page))
                ->toArray();
            foreach ($list['data'] as $k=>$v){
            	$list['data'][$k]['create_time'] = date('Y年m月d日',$v['create_time']);
            }
            return $result = ['code'=>0,'msg'=>'获取成功!','data'=>$list['data'],'rel'=>1];
        }
        return $this->fetch('teacherList');
    }
     //新增讲师
    public function teacherAdd(){
    	
    	if(request()->isPost()){
            $post = Request::except('file');
            $list = [
                'teacher_name' => $post['teacher_name'],
                'sex' => $post['sex'],
                'phone' => $post['phone'],
                'teacher_introduce' => $post['teacher_introduce'],
                'head_img' => $post['head_img'],
                'create_time' => time()
            ];
            $res = db('video_teacher')->insert($list);
            if($res){
                return ['code'=>1,'msg'=>'添加成功!','url'=>url('teacherList')];
            }else{
                return ['code'=>0,'msg'=>'添加失败!','url'=>url('teacherList')];
            }
        }else{
            $this->assign('info','null');
            $this->assign('title','添加讲师');
            return $this->fetch('teacherForm');
        }
    
    }
    //讲师编辑
    public function teacherEdit($id=''){
        if(request()->isPost()){
            $post = Request::except('file');
            $list = [
            	'id' => $post['id'],
                'teacher_name' => $post['teacher_name'],
                'sex' => $post['sex'],
                'phone' => $post['phone'],
                'head_img' => $post['head_img'],
                'teacher_introduce' => $post['teacher_introduce']
            ];
            $res = db('video_teacher')->update($list);
            if($res!=false){
                return ['code'=>1,'msg'=>'修改成功!','url'=>url('teacherList')];
            }else{
                return ['code'=>0,'msg'=>'修改失败!','url'=>url('teacherList')];
            }
        }else{
            $info=db('video_teacher')->where('id',$id)->find();
            $this->assign('info',json_encode($info,true));
            $this->assign('title',lang('edit').'讲师信息');
            return $this->fetch('teacherForm');
        }
    }
    //讲师删除
    public function teacherDel(){
        $count=db('video_video')
            ->where('is_show=1')
            ->where('teacher_id='.input('id'))
            ->count();
        if($count>0){
            return $result = ['code'=>2,'msg'=>'该先删除该讲师下的教程!'];
        }else{
            // db('video_teacher')->delete(['id'=>input('id')]);
            db('video_teacher')->where(['id'=>input('id')])->update(['is_show'=>'0']);
            return $result = ['code'=>1,'msg'=>'删除成功!'];
        }
        
    }

  /***********************************课程类型列表***********************************/ 
    //课程类型列表
    public function courseType(){
        $ogroup_id=session('ogroup_id');
        if(request()->isPost()){
            $where=1;
            if($ogroup_id!='1'){
                $course_power=db('organization_group')->where('id='.$ogroup_id)->value('course_power');
                $course_power=rtrim($course_power, ",");
                $where="id in (".$course_power.")";
            }
            $key=input('post.key');
            $list=Db::name('video_type')
                ->where($where)
                ->where('type','like',"%".$key."%")
                ->where('is_show=1')
                ->order('sort asc')
                ->select();
            $list1 = self::tree($list);
            // print_r($list1);die();
            return $result = ['code'=>0,'msg'=>'获取成功!','data'=>$list1,'rel'=>1];
        }
        return $this->fetch('courseType');
    }
     //新增课程类型
    public function typeAdd(){
        
        if(request()->isPost()){
            $post = Request::except('file');
            $list = [
                'fid' => $post['fid'],
                'type' => $post['type'],
                'sort' => $post['sort']
            ];
            $res = db('video_type')->insert($list);
            if($res){
                return ['code'=>1,'msg'=>'添加成功!','url'=>url('CourseType')];
            }else{
                return ['code'=>0,'msg'=>'添加失败!','url'=>url('CourseType')];
            }
        }else{
            $this->assign('info','null');
            $this->assign('title','添加课程类型');
            $type=db('video_type')->where('fid=0')->select();
            $this->assign('type',$type);
            return $this->fetch('typeForm');
        }
    
    }
    //课程类型编辑
    public function typeEdit($id=''){
        if(request()->isPost()){
            $post = Request::except('file');
            $list = [
                'id' => $post['id'],
                'fid' => $post['fid'],
                'type' => $post['type'],
                'sort' => $post['sort']
            ];
            $res = db('video_type')->update($list);
            if($res!=false){
                return ['code'=>1,'msg'=>'修改成功!','url'=>url('CourseType')];
            }else{
                return ['code'=>0,'msg'=>'修改失败!','url'=>url('CourseType')];
            }
        }else{
            $info=db('video_type')->where('id',$id)->find();
            $this->assign('info',json_encode($info,true));
            $this->assign('title',lang('edit').'课程类型');
            $type=db('video_type')->where('fid=0')->where('id','neq',$id)->select();
            $this->assign('type',$type);
            $fid=$info['fid'];
            $this->assign('fid',$fid);
            return $this->fetch('typeForm');
        }
    }
    //排序
    public function typeOrder(){
        $ad=db('video_type');
        $data = input('post.');
        if($ad->update($data)!==false){
            return $result = ['msg' => '操作成功！','url'=>url('courseType'), 'code' =>1];
        }else{
            return $result = ['code'=>0,'msg'=>'操作失败！'];
        }
    }
    //课程类型删除
    public function typeDel(){
        $count=db('video_video')->where('is_show=1')->where('type_id='.input('id'))->count();
        $count_type=db('video_type')->where('is_show=1')->where('fid='.input('id'))->count();
        if($count>0||$count_type>0){
            return $result = ['code'=>2,'msg'=>'请先删除该类型下的教程或类型!'];
        }else{
            // db('video_type')->delete(['id'=>input('id')]);
            db('video_type')->where(['id'=>input('id')])->update(['is_show'=>'0']);
            return $result = ['code'=>1,'msg'=>'删除成功!'];
        }
        
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

    //图文课列表
    public function textCourseList(){
        $ogroup_id=session('ogroup_id');
        if(request()->isPost()){
            $where=1;
            
            if(input('post.date1')&&input('post.date2')){  
                $date3=strtotime(input('post.date1'));
                $date4=strtotime(input('post.date2'));
                if($date3<$date4){
                    $where.=" and video.upload_time >= '". $date3."' and video.upload_time <= '". $date4."'";     
                }
                else{
                    $where.=" and video.upload_time >= '". $date3."' and video.upload_time <= '". $date4."'";        
                }
            } 
            elseif (input('post.date1')) {

                $date3=strtotime(input('post.date1'));
                $where.=" and video.upload_time >= '". $date3."'";
            }
            elseif (input('post.date2')) {
                $date4=strtotime(input('post.date2'));
                $where.=" and video.upload_time <= '". $date4."'";
            }
            if(input('post.type')){
                $type=input('post.type');
                $type_count=db('video_type')
                ->where('fid='.$type)
                ->where('is_show=1')
                ->count();
                //大分类
                if($type_count){
                    $where.=" and type.fid='".$type."'";
                }else{
                    //小分类
                    $where.=" and video.type_id='".$type."'";
                } 
            }else{
                if($ogroup_id!='1'){
                    $course_power=db('organization_group')->where('id='.$ogroup_id)->value('course_power');
                    $course_power=rtrim($course_power, ",");
                    $where.=" and video.type_id in (".$course_power.")";
                }
            }
            $key=input('post.key');
            $page =input('page')?input('page'):1;
            $pageSize =input('limit')?input('limit'):config('pageSize');
            $list=Db::name('video_video')
            	->alias('video')
                ->field('video.id,video.title,type.type,teacher.teacher_name,video.video_img,video.upload_time,video.view_number,video.is_work')
                ->join('mk_video_teacher teacher','video.teacher_id=teacher.id')
                ->join('mk_video_type type','video.type_id=type.id')
                ->where('video.title','like',"%".$key."%")
                ->where($where)
                ->where('type.is_show=1')
                ->where('video.course_type=2')
                ->where('video.is_show=1')
                ->order('video.id desc')
                ->paginate(array('list_rows'=>$pageSize,'page'=>$page))
                ->toArray();

            foreach ($list['data'] as $k=>$v){
                $list['data'][$k]['upload_time'] = date('Y年m月d日',$v['upload_time']);
         
            }
            // print_r($list);die();
            return $result = ['code'=>0,'msg'=>'获取成功!','data'=>$list['data'],'count'=>$list['total'],'rel'=>1];
        }
        $where_type=1;
        if($ogroup_id!='1'){
            $course_power=db('organization_group')->where('id='.$ogroup_id)->value('course_power');
            $course_power=rtrim($course_power, ",");
            $where_type="id in (".$course_power.")";
        }
        $type=db('video_type')->where($where_type)->where('is_show=1')->select();
        $type=self::tree($type);
        $this->assign('type', $type);
        return $this->fetch('textCourseList');
    }
    //图片课添加
    public function textCourseAdd(){
    	
    	if(request()->isPost()){
            $post = Request::except('file');
            $title_is_open=db('video_length')->field('is_open,length')->where('id=1')->find();
            $content_is_open=db('video_length')->field('is_open,length')->where('id=2')->find();
            if(($title_is_open['is_open']=='0'||$title_is_open['length']=='0')&&($content_is_open['is_open']=='0'||$content_is_open['length']=='0')){
                $validate = new validate([
                    'title'  => 'require',
                    'content'  => 'require',
                ],[
                    'title.require'  => '请输入课程标题',
                    'content.require'  => '请上传课程内容',
                ]);
            }else if($title_is_open['is_open']=='0'||$title_is_open['length']=='0'){
                $validate = new validate([
                    'title'  => 'require',
                    'content'  => 'require|max:'.$content_is_open['length'],
                ],[
                    'title.require'  => '请输入课程标题',
                    'content.require'  => '请上传课程内容',
                    'content.max'  => '课程内容字符限制'.$content_is_open['length'],
                ]);
            }else if($content_is_open['is_open']=='0'||$content_is_open['length']=='0'){
                $validate = new validate([
                    'title'  => 'require',
                    'content'  => 'require|max:'.$title_is_open['length'],
                ],[
                    'title.require'  => '请输入课程标题',
                    'content.require'  => '请上传课程内容',
                    'content.max'  => '课程内容字符限制'.$title_is_open['length'],
                ]);
            }else{
                $validate = new validate([
                    'title'  => 'require|max:'.$title_is_open['length'],
                    'content'  => 'require|max:'.$content_is_open['length'],
                ],[
                    'title.require'  => '请输入课程标题',
                    'title.max'  => '课程标题字符限制'.$title_is_open['length'],
                    'content.require'  => '请上传课程内容',
                    'content.max'  => '课程内容字符限制'.$content_is_open['length'],
                ]);
            }
           
            if(!$validate->check($post)){
                return $result = ['code'=>0,'msg'=>$validate->getError()];
            }
           
		   //tag
		    $tag=$post['tag_id'];
		    $tag=substr($tag, 1);
		    $tag=explode('#', $tag);
		    foreach($tag as $val){
		   		$tag_db=db('video_tag')->where(['tag'=>$val])->find();
		   		// print_r($tag_db);die();
		   		if($tag_db){
		   			$tag_db_count=$tag_db['count']+1;
		   			db('video_tag')->where(['tag'=>$val])->update(['count'=>$tag_db_count]);
		   			$tag_idss[]=$tag_db['id'];
		   		}else{
		   			$list_tag=[
			   			'tag'=>$val,
			   			'count'=>'0',
			   			'time'=>time()
			   		];
			   		$tag_id=db('video_tag')->insertGetId($list_tag);
			   		$tag_idss[]=$tag_id;
		   		}
                

		    }
		    $tag_ids=implode('#', $tag_idss);
            
            $list = [
                'title' => $post['title'],
                'type_id' => $post['type_id'],
                'teacher_id' => $post['teacher_id'],
                'content' => $post['content'],
                'video_img' => $post['video_img'],
                'tag_id' => $tag_ids,
                'seo_title' => $post['seo_title'],
                'seo_key' => $post['seo_key'],
                'seo_des' => $post['seo_des'],
                'is_work'=>$post['is_work'],
                'course_type'=>'2',
                'upload_time' => time()
            ];
            $res = db('video_video')->insertGetId($list);
            if($res){
                //添加tag至关联表
                foreach($tag_idss as $val){
                    db('video_connect_tag')->insert(['video_id'=>$res,'tag_id'=>$val]);
                }
                return ['code'=>1,'msg'=>'添加成功!','url'=>url('textCourseList')];
            }else{
                return ['code'=>0,'msg'=>'添加失败!','url'=>url('textCourseList')];
            }
        }else{
        	$type=db('video_type')->where('is_show=1')->select();
        	$type = self::tree($type);
            $teacher=db('video_teacher')->where('is_show=1')->select();
            $this->assign('info','null');
            $this->assign('title','创建课程');
            $this->assign('type',$type);
            $this->assign('teacher',$teacher);
            return $this->fetch('textCourseForm');
        }
    
    }
    //图文课修改
    public function textCourseEdit($id=''){
        $title_is_open=db('video_length')->field('is_open,length')->where('id=1')->find();
        $content_is_open=db('video_length')->field('is_open,length')->where('id=2')->find();
        if(request()->isPost()){
            
            $post = Request::except('file');
            // print_r($post);die();
            if(($title_is_open['is_open']=='0'||$title_is_open['length']=='0')&&($content_is_open['is_open']=='0'||$content_is_open['length']=='0')){
                $validate = new validate([
                    'title'  => 'require',
                    'content'  => 'require',
                ],[
                    'title.require'  => '请输入课程标题',
                    'content.require'  => '请上传课程内容',
                ]);
            }else if($title_is_open['is_open']=='0'||$title_is_open['length']=='0'){
                $validate = new validate([
                    'title'  => 'require',
                    'content'  => 'require|max:'.$content_is_open['length'],
                ],[
                    'title.require'  => '请输入课程标题',
                    'content.require'  => '请上传课程内容',
                    'content.max'  => '课程内容字符限制'.$content_is_open['length'],
                ]);
            }else if($content_is_open['is_open']=='0'||$content_is_open['length']=='0'){
                $validate = new validate([
                    'title'  => 'require',
                    'content'  => 'require|max:'.$title_is_open['length'],
                ],[
                    'title.require'  => '请输入课程标题',
                    'content.require'  => '请上传课程内容',
                    'content.max'  => '课程内容字符限制'.$title_is_open['length'],
                ]);
            }else{
                $validate = new validate([
                    'title'  => 'require|max:'.$title_is_open['length'],
                    'content'  => 'require|max:'.$content_is_open['length'],
                ],[
                    'title.require'  => '请输入课程标题',
                    'title.max'  => '课程标题字符限制'.$title_is_open['length'],
                    'content.require'  => '请上传课程内容',
                    'content.max'  => '课程内容字符限制'.$content_is_open['length'],
                ]);
            }
            
            if(!$validate->check($post)){
                return $result = ['code'=>0,'msg'=>$validate->getError()];
            }
		   //修改前的tag处理

		   	$course_tag=db('video_video')->field('tag_id')->where('id='.$post['id'])->find();
		   	//新tag
            $tag=$post['tag_id'];
            $tag=substr($tag, 1);
            $tag=explode('#', $tag);
            if($course_tag['tag_id']){
             
                //原来有tag
                $course_tags=explode('#', $course_tag['tag_id']);//该课程原tag
                foreach($course_tags as $val){
                    $tag_db_jiu=db('video_tag')->where('id='.$val)->find();
                    $tag_db_jius[]=$tag_db_jiu['tag'];
                }
                //如果没改
                if($tag==$tag_db_jius){
                    $tag_ids=$course_tag['tag_id'];
                }else{
                    
                    foreach($tag_db_jius as $val){
                        //编辑时删除的
                        // echo $val;
                        // print_r($tag);die();
                        $result=in_array($val, $tag);
                        if(!$result){
                            $result_1=db('video_tag')->where(['tag'=>$val])->find();
                            // print_r($result_1);die();
                            if($result_1['count']>'0'){
                                $tag_count=$result_1['count']-1;
                                // echo $tag_count;die();
                                db('video_tag')->where(['tag'=>$val])->update(['count'=>$tag_count]);
                            }else{
                                db('video_tag')->where('id='.$result_1['id'])->delete();
                            }
                            db('video_connect_tag')->where('video_id='.$post['id'])->where('tag_id='.$result_1['id'])->delete();
                        }
                    }
                   
                    foreach($tag as $val){
                        
                        $tag_db=db('video_tag')->where(['tag'=>$val])->find();
                        if($tag_db){
                            if(in_array($val,$tag_db_jius)){
                                $tag_idss[]=$tag_db['id'];
                            }else{
                                $tag_db_count=$tag_db['count']+1;
                                db('video_tag')->where(['tag'=>$val])->update(['count'=>$tag_db_count]);
                                $tag_idss[]=$tag_db['id'];
                                db('video_connect_tag')->insert(['video_id'=>$post['id'],'tag_id'=>$tag_db['id']]);
                            }
                            
                        }else{
                            $list_tag=[
                                'tag'=>$val,
                                'count'=>'0',
                                'time'=>time()
                            ];
                            $tag_id=db('video_tag')->insertGetId($list_tag);
                            $tag_idss[]=$tag_id;
                            db('video_connect_tag')->insert(['video_id'=>$post['id'],'tag_id'=>$tag_id]);
                        }
                        
                    }
                    $tag_ids=implode('#', $tag_idss);
                }
		   	
		   	}else{
                foreach($tag as $val){
                    $tag_db=db('video_tag')->where(['tag'=>$val])->find();
                    // print_r($tag_db);die();
                    if($tag_db){
                        $tag_db_count=$tag_db['count']+1;
                        db('video_tag')->where(['tag'=>$val])->update(['count'=>$tag_db_count]);
                        $tag_idss[]=$tag_db['id'];
                        db('video_connect_tag')->insert(['video_id'=>$post['id'],'tag_id'=>$tag_db['id']]);
                    }else{
                        $list_tag=[
                            'tag'=>$val,
                            'count'=>'0',
                            'time'=>time()
                        ];
                        $tag_id=db('video_tag')->insertGetId($list_tag);
                        $tag_idss[]=$tag_id;
                        db('video_connect_tag')->insert(['video_id'=>$post['id'],'tag_id'=>$tag_id]);
                    }
                    
               }
               $tag_ids=implode('#', $tag_idss);
            }
           
            
            $list = [
                'id' => $post['id'],
                'title' => $post['title'],
                'type_id' => $post['type_id'],
                'teacher_id' => $post['teacher_id'],
                'content' => $post['content'],
                'tag_id' => $tag_ids,
                'seo_title' => $post['seo_title'],
                'seo_key' => $post['seo_key'],
                'seo_des' => $post['seo_des'],
                'video_img' => $post['video_img'],
                'is_work'=>$post['is_work'],
               
            ];
            // print_r($list);die();
            $res = db('video_video')->update($list);
            if($res!==false){
                return ['code'=>1,'msg'=>'修改成功!','url'=>url('textCourseList')];
            }else{
                return ['code'=>0,'msg'=>'修改失败!','url'=>url('textCourseList')];
            }
        }else{
            $info=db('video_video')->where('id',$id)->where('course_type=2')->find();
            if($info['tag_id']){
                $course_tags=explode('#', $info['tag_id']);
                foreach($course_tags as $val){
                    $tag_title=db('video_tag')->field('tag')->where('id='.$val)->find();
                    $tags[]=$tag_title['tag'];
                }
                $tags=implode('#', $tags);
                $tags='#'.$tags;
            }else{
                $tags='';
            }
            $info['tag_id']=$tags;
            $type=db('video_type')->where('is_show=1')->select();
            $type = self::tree($type);
            $teacher=db('video_teacher')->where('is_show=1')->select();
            if($title_is_open['is_open']=='0'||$title_is_open['length']=='0'){
                $title_length='';
            }else{
                $title_length=$title_is_open['length'];
            }
            if($content_is_open['is_open']=='0'||$content_is_open['length']=='0'){
                $content_length='';
          
            }else{
                $content_length=$content_is_open['length'];
            
            }

            $this->assign('info',json_encode($info,true));
            $this->assign('title',lang('edit').'图文课程');
            $this->assign('type',$type);
            $this->assign('teacher',$teacher);
            $this->assign('title_length',$title_length);
            $this->assign('content_length',$content_length);
            if($info){
            	$content=$info['content'];
            }else{
            	$content='';
            }
            $this->assign('content',$content);
            $this->assign('id',$id);
            return $this->fetch('textCourseForm');
        }
    }
    //图文课单删
    public function TextCourseDel(){
        //软删除
        db('video_video')->where(['id'=>input('id')])->update(['is_show'=>'0']);
        db('video_collection')->where(['video_id'=>input('id')])->delete();
        return $result = ['code'=>1,'msg'=>'删除成功!'];
    }
    //图文课多删
    public function TextCourseDels(){
        $map[] =array('id','IN',input('param.ids/a'));
        db('video_video')->where($map)->update(['is_show'=>'0']);
        $map1[] =array('video_id','IN',input('param.ids/a'));
        db('video_collection')->where($map1)->delete();
        $result['msg'] = '删除成功！';
        $result['code'] = 1;
        $result['url'] = url('textCourseList');
        return $result;
    }
    //导入tag
    public function addtag(){
        $course=db('video_video')->field('id,tag_id')->where('is_show=1')->select();
        foreach($course as $val){
            $tags=explode('#',$val['tag_id']);
            foreach($tags as $vall){
                db('video_connect_tag')->insert(['video_id'=>$val['id'],'tag_id'=>$vall]);
            }
           
        }
    }
    //导入收藏数
    public function addcollection(){
        $collection=db('video_collection')->field('id,video_id')->select();
        foreach($collection as $val){
            db('video_video')->where('id='.$val['video_id'])->setInc('collection_number');
        }
    }
    //导入点赞数
    public function addlike(){
        $like=db('video_text_like')->field('id,video_id')->select();
        foreach($like as $val){
            db('video_video')->where('id='.$val['video_id'])->setInc('like_number');
        }
    }

}