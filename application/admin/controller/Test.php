<?php
namespace app\admin\controller; 
use think\Controller;
use think\Db;
use think\facade\Request;
use think\validate;
use think\facade\Session;
use app\admin\model\Ogroup;
Class Test extends Common{
	//试卷列表
	public function testList(){
        $ogroup_id=session('ogroup_id');
		if(request()->isPost()){
            $where=1;
            if(input('post.date1')&&input('post.date2')){  
                $date3=strtotime(input('post.date1'));
                $date4=strtotime(input('post.date2'));
                if($date3<$date4){
                    $where.=" and createtime >= '". $date3."' and createtime <= '". $date4."'";     
                }
                else{
                    $where.=" and createtime >= '". $date3."' and createtime <= '". $date4."'";        
                }
            } 
            elseif (input('post.date1')) {

                $date3=strtotime(input('post.date1'));
                $where.=" and createtime >= '". $date3."'";
            }
            elseif (input('post.date2')) {
                $date4=strtotime(input('post.date2'));
                $where.=" and createtime <= '". $date4."'";
            }
			$key=input('post.key');
            $page =input('page')?input('page'):1;
            $pageSize =input('limit')?input('limit'):config('pageSize');
            if($ogroup_id!='1'){
                $list=Db::name('video_test')
                    ->field('id,title,time_start,time_end,admin_id,createtime,is_open,ogroup')
                    ->where('is_show=1')
                    ->where('title','like',"%".$key."%")
                    ->where($where)
                    ->order('createtime desc')
                    ->select();
                $list['data']=$list;
            }else{
                $list=Db::name('video_test')
                    ->field('id,title,time_start,time_end,admin_id,createtime,is_open,ogroup')
                    ->where('is_show=1')
                    ->where('title','like',"%".$key."%")
                    ->where($where)
                    ->order('createtime desc')
                    ->paginate(array('list_rows'=>$pageSize,'page'=>$page))
                	->toArray();
            }
			
	        foreach($list['data'] as $key=>&$val){
	        	// echo $val['time_end'];die();
	            if($val['time_end']<=time()){
	                
	                $val['is_end']='已结束';//已结束
	            }else{
	                $val['is_end']='未结束';//未结束
	            } 
	            $val['time_start'] = date('Y-m-d H:i',$val['time_start']);
	            $val['time_end'] = date('Y-m-d H:i',$val['time_end']);
                $val['createtime'] = date('Y-m-d H:i',$val['createtime']);
                if($ogroup_id!='1'){
                    $ogroup=explode(',',$val['ogroup']);
                    if(in_array($ogroup_id,$ogroup)){
                        $data[]=$list['data'][$key];
                    }
                    
                }
                
            }
            
            if($ogroup_id!='1'){
                $count=count($data);
                if($data){
                    $data=array_slice($data,($page-1)*10,10) ;
                }else{
                    $data=[];
                }
                
                return $result = ['code'=>0,'msg'=>'获取成功!','data'=>$data,'count'=>$count,'rel'=>1];
            }else{
                return $result = ['code'=>0,'msg'=>'获取成功!','data'=>$list['data'],'count'=>$list['total'],'rel'=>1];
            }
        }
       
		return $this->fetch('testList');
		
	}
	//试题开关
	public function is_open(){
		$id=input('post.id');
        $is_open=input('post.is_open');
        if (empty($id)){
            $result['status'] = 0;
            $result['info'] = '试卷不存在!';
            $result['url'] = url('testList');
            return $result;
        }
        db('video_test')->where('id='.$id)->update(['is_open'=>$is_open]);
        $result['status'] = 1;
        $result['info'] = '设置成功!';
        $result['url'] = url('testList');
        return $result;
	}
	//试卷添加
	public function testAdd(){
		if(request()->isPost()){
            $post = input('post.');
            $list = [
                'title' => $post['title'],
                'time_start' => strtotime($post['time_start']),
                'time_end' => strtotime($post['time_end']),
                'createtime' => time(),
                'is_show'=>'1'
            ];
            if($post['ogroup_ids']){
                $ogroup=implode(',',$post['ogroup_ids']);
                $list['ogroup']=$ogroup;
            }else{
                $list['ogroup']=session('ogroup_id');
            }
            
            $res = db('video_test')->insert($list);
            if($res){
                return ['code'=>1,'msg'=>'添加成功!','url'=>url('testList')];
            }else{
                return ['code'=>0,'msg'=>'添加失败!','url'=>url('testList')];
            }
        }else{
            $ogroup = Ogroup::where('is_use=1')->where('id','neq','1')->all();
            $ogroup_id=session('ogroup_id');
            $ogroup_test=[];
            $this->assign('ogroup',$ogroup);
            $this->assign('info','null');
            $this->assign('ogroup_test',$ogroup_test);
            $this->assign('title','添加试卷');
            $this->assign('ogroup_id',$ogroup_id);
            return $this->fetch('testForm');
        }
	}
	//试卷编辑
	public function testEdit(){
		if(request()->isPost()){
            $post = Request::except('file');
            // print_r( $post['ogroup_ids'] );die();
            // echo $ogroup_ids;die();
            $list = [
            	'id' => $post['id'],
                'title' => $post['title'],
                'time_start' => strtotime($post['time_start']),
                'time_end' => strtotime($post['time_end']),
                'updatetime' => time()
            ];
            if($post['ogroup_ids']){
                $ogroup=implode(',',$post['ogroup_ids']);
                $list['ogroup']=$ogroup;
            }
            $res = db('video_test')->update($list);
            if($res!=false){
                return ['code'=>1,'msg'=>'修改成功!','url'=>url('testList')];
            }else{
                return ['code'=>0,'msg'=>'修改失败!','url'=>url('testList')];
            }
        }else{
        	$id=input('get.id');
            $info=db('video_test')->where('id',$id)->find();
            $info['time_start'] = date('Y-m-d h:i:s',$info['time_start']);
            $info['time_end'] = date('Y-m-d h:i:s',$info['time_end']);
            $ogroup = Ogroup::where('is_use=1')->where('id','neq','1')->all();
            $ogroup_id=session('ogroup_id');
            $ogroup_test=explode(',',$info['ogroup']);
            $this->assign('ogroup',$ogroup);
            $this->assign('ogroup_test',$ogroup_test);
            $this->assign('ogroup_id',$ogroup_id);
            $this->assign('info',json_encode($info,true));
            $this->assign('title',lang('edit').'试卷');
            $this->assign('id',$id);
            return $this->fetch('testForm');
        }
	}
	//试卷删除
	public function testDel(){
            db('video_test')->where(['id'=>input('id')])->update(['is_show'=>'0']);
            return $result = ['code'=>1,'msg'=>'删除成功!'];
        
	}
	//试卷多删
	public function testDels(){
		$map[] =array('id','IN',input('param.ids/a'));
        db('video_test')->where($map)->update(['is_show'=>'0']);
        $result['msg'] = '删除成功！';
        $result['code'] = 1;
        $result['url'] = url('testList');
        return $result;
	}
	//试题列表
	public function questionList($id=''){
        if(request()->isPost()){
            $test_id = input('post.test_id');
            $list=db('video_connect_test')
                    ->alias('ct')
                    ->field('ct.question_id id,ct.id ctid,ct.score,questions.question_title')
                    ->join('mk_video_questions questions','ct.question_id=questions.id')
                    ->where('ct.test_id='.$test_id)
                    ->select();
            foreach($list as &$v){
                $v['answer_num'] = db('video_answers')->where('question_id',$v['id'])->where('is_show=1')->count();
                $v['answer_right_num'] = db('video_answers')->where(['question_id'=>$v['id'],'is_right'=>1])->where('is_show=1')->count();
            }
            return $result = ['code'=>0,'msg'=>'获取成功!','data'=>$list,'rel'=>1];
        }
        $cause = db('video_test')->where('id',$id)->where('is_show=1')->find();

        $this->assign('title', $cause['title']);
        $this->assign('test_id', $id);

        return $this->fetch('questionList');
    }
	//试题单项添加
	public function questionAdd($test_id = ''){
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
                'test_id'=>$post['test_id'],
                'question_id'=>$questionres,
                'score'=>$post['score']
            ];
            $result = db('video_connect_test')->insert($connectList);
            foreach($post['answer'] as $k => $v){
                $answerslist = [
                    'question_id' => $questionres,
                    'choice_text' => $v,
                    'is_right' => $post['is_right'][$k],
                ];
                $answersres = db('video_answers')->insert($answerslist);
            }

            if($questionres && $answersres){
                return ['code'=>1,'msg'=>'添加成功!','url'=>url('questionList',['id'=>$post['test_id']])];
            }else{
                return ['code'=>0,'msg'=>'添加失败!','url'=>url('questionList',['id'=>$post['test_id']])];
            }
        }else{
            $info = array();
            $this->assign('info',json_encode($info,true));
            $this->assign('test_id',$video_id);
            $this->assign('title','创建问题');
            return $this->fetch('questionForm');
        }
    }
	//试题单项修改
	public function questionEdit($question_id = '',$test_id=''){
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
            db('video_connect_test')->where('question_id='.$question_id)->where('test_id='.$test_id)->update(['score'=>$post['score']]);
           
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
                return ['code'=>1,'msg'=>'编辑成功!','url'=>url('questionList',['id'=>$post['test_id']])];
            }else{
                return ['code'=>0,'msg'=>'编辑失败!','url'=>url('questionList',['id'=>$post['test_id']])];
            }
        }else{
            $info = db('video_questions')->where(['id'=>$question_id])->where('is_show=1')->find();
            $answers = db('video_answers')->where(['question_id'=>$question_id])->where('is_show=1')->order('id asc')->select();
            $connect=db('video_connect_test')->field('score')->where('test_id='.$test_id)->where('question_id='.$question_id)->find();
            $info['score']=$connect['score'];
            // print_r($info);die();
            $this->assign('info',json_encode($info,true));
            $this->assign('answers',$answers);
            $this->assign('test_id',$video_id);
            $this->assign('title','编辑问题');
            return $this->fetch('questionForm');
        }
    }
	//试题单项删除
	public function questionDel(){
		$question_id=input('post.question_id');
		$test_id=input('post.test_id');
		db('video_connect_test')->where('test_id='.test_id)->where('question_id='.$question_id)->delete();
        return ['code'=>1,'msg'=>'删除成功！'];
	}
	//多项添加试题列表
	public function questionAddList($test_id=''){
		if(request()->isPost()){
            $no=db('video_connect_test')->where('test_id='.$test_id)->select();
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
        $this->assign('test_id', $test_id);

        return $this->fetch('questionAddList');
	}
	//多项添加中的添加单项
	public function questionsAdd(){
		$question_id=input('post.question_id');
        $test_id=input('post.test_id');
        $list=[
            'question_id'=>$question_id,
            'test_id'=>$test_id,
            'score'=>'',
            // 'sort'=>'50'
        ];
        $result=db('video_connect_test')->insert($list);
        if($result!==false){
            return $result = ['code'=>1,'msg'=>'添加成功!'];
        }else{
            return $result = ['code'=>0,'msg'=>'添加失败，请重试!'];
        }
	}
	//多项添加中的添加多项
	public function questionsAdds(){
		$question_ids =input('param.question_id/a');
        $test_id=input('post.test_id');
        foreach($question_ids as $val){
            $list[]=[
                'question_id'=>$val,
                'test_id'=>$test_id,
                'score'=>'',
                // 'sort'=>'50'
            ];
        }
        $result=db('video_connect_test')->insertAll($list);
        if($result!==false){
            return $result = ['code'=>1,'msg'=>'添加成功!'];
        }else{
            return $result = ['code'=>0,'msg'=>'添加失败，请重试!'];
        }
	}
	//得分管理
	public function score(){
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
                    $where.=" and score.time >= '". $date3."' and score.time <= '". $date4."'";     
                }
                else{
                    $where.=" and score.time >= '". $date3."' and score.time <= '". $date4."'";        
                }
            } 
            elseif (input('post.date1')) {

                $date3=strtotime(input('post.date1'));
                $where.=" and score.time >= '". $date3."'";
            }
            elseif (input('post.date2')) {
                $date4=strtotime(input('post.date2'));
                $where.=" and score.time <= '". $date4."'";
            }
            $key=input('post.key');
            $page =input('page')?input('page'):1;
            $pageSize =input('limit')?input('limit'):config('pageSize');
			$list=db('video_score')
				->alias('score')
				->field('score.id,users.username,users.nickname,users.truename,test.title,score.score,score.time,og.group_name')
				->join('mk_video_test test','score.test_id=test.id')
				->join('mk_users users','score.user_id=users.id')
                ->join(config('database.prefix').'organization_group og','users.ogroup_id = og.id','left')
                ->where('users.username|users.nickname|users.truename|test.title','like',"%".$key."%")
                ->where($where)
                ->order('score.time desc')
                ->paginate(array('list_rows'=>$pageSize,'page'=>$page))
                ->toArray();
                foreach ($list['data'] as $k=>$v){
	            	$list['data'][$k]['time'] = date('Y年m月d日',$v['time']);
	            }
            // print_r($list);
            return $result = ['code'=>0,'msg'=>'获取成功!','data'=>$list['data'],'count'=>$list['total'],'rel'=>1];
        }
        $this->assign('ogroup_id',$ogroup_id);
        return $this->fetch('score');
        
	}
	//得分详情
	public function testDetails($id=''){
		if(request()->isPost()){
            $key=input('post.key');
            $page =input('page')?input('page'):1;
            $pageSize =input('limit')?input('limit'):config('pageSize');
			$result=db('video_score')->field('user_id,test_id')->where('id='.$id)->find();
			$list=db('video_answer_history')
					->alias('history')
					->field('history.id,questions.question_title,history.is_right,history.answer_time,questions.id question_id')
					->join('mk_video_questions questions','history.question_id=questions.id','left')
					->where('test_id='.$result['test_id'])
					->where('user_id='.$result['user_id'])
					->order('answer_time desc')
					->paginate(array('list_rows'=>$pageSize,'page'=>$page))
                	->toArray();
            // print_r($list['data']);die();
				// print_r($list);
            foreach ($list['data'] as $k=>$v){
            	$list['data'][$k]['answer_time'] = date('Y年m月d日',$v['answer_time']);
            	$score=db('video_connect_test')
            		->field('score')
            		->where('test_id='.$result['test_id'])
            		->where('question_id='.$v['question_id'])
            		->find();
            	$list['data'][$k]['score'] =$score['score'];
            }
            return $result = ['code'=>0,'msg'=>'获取成功!','data'=>$list['data'],'count'=>$list['total'],'rel'=>1];
        }
        $this->assign('id',$id);
        return $this->fetch('testDetails');
	}
}