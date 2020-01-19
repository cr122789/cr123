<?php
namespace app\api\controller;
use think\Controller;
use think\Db;
use think\validate;
use think\facade\Session;
use app\api\model\IntegralRedis;
use app\api\model\CourseModel;
class Details extends Common{
    // public function initialize(){
    //     parent::initialize();
    // }
    /**
     * @api {post} Details/details 视频详情及相应问题
     * @apiVersion 0.1.0
     * @apiGroup Details
     * @apiDescription 视频详情及相应问题
     *
     * @apiParam {String} token 登录令牌  
     * @apiParam {String} course_id 课程id  
     *
     * @apiSuccess {Number} code 状态码
     * @apiSuccess {String} data 返回结果模型
     *
     * @apiSuccessExample 成功返回示例1
	*
	*{
    *    "code": 200, 
    *    "data": {
    *        "id": 2, //课程id
    *        "title": "课程2", //课程标题
    *        "video": "", //视频地址
    *        "type_id": "2", //课程类型二级类型id
    *        "type_fid": "1", //课程类型一级类型id
    *        "type": "社区实务", //课程类型
    *        "teacher_name": "讲师1", //讲师姓名
    *        "head_img": null, //讲师头像
    *        "view_number": 102, //学习人数
    *        "introduction": "课程2课程2课程2", //课程介绍
    *        "teacher_introduce": "高级讲师", //讲师介绍
    *        "seo_title": "php从初级到高级", //seo标题
    *        "seo_key": "php从初级到高级", //seo关键词
    *        "seo_des": "php从初级到高级", //seo描述    
    *        "playtime": "00:00", //开始播放的节点
    *        "collect": "0", //0未收藏 1已收藏 
    *        "is_integral":"1",//0本课程没有课程积分 观看10分钟可添加  1 今天已有课程积分或关闭添加课程积分功能
    *        "is_integral_day":"1",//0今天没有添加每日课程积分 观看10分钟可添加  1 今天已有每日课程积分或关闭添加每日课程积分功能
    *        "questions": [ //问题
    *            {
    *                "id": 2, //问题id
    *                "question_title": "问题2", //问题标题
    *                "time_node": "02:00", //问题出现的时间节点
    *                "answer_type": "1", //1单选 2多选
    *                "option": [ //选项
    *                    {
    *                        "id": 25, //选项id
    *                        "choice_text": "选项1" //选项内容
    *                    }, 
    *                    {
    *                        "id": 26, 
    *                        "choice_text": "选项2"
    *                    }, 
    *                    {
    *                        "id": 27, 
    *                        "choice_text": "选项3"
    *                    }, 
    *                    {
    *                        "id": 28, 
    *                        "choice_text": "选项4"
    *                    }
    *                ]
    *            }, 
    *            {
    *                "id": 1, 
    *                "question_title": "问题1", 
    *                "time_node": "05:00", 
    *                "answer_type": "1", //1单选 2多选
    *                "option": [
    *                    {
    *                        "id": 29, 
    *                        "choice_text": "选项1"
    *                    }, 
    *                    {
    *                        "id": 30, 
    *                        "choice_text": "选项2"
    *                    }, 
    *                    {
    *                        "id": 31, 
    *                        "choice_text": "选项3"
    *                    }, 
    *                    {
    *                        "id": 32, 
    *                        "choice_text": "选项4"
    *                    }
    *                ]
    *            }, 
    *            {
    *                "id": 3, 
    *                "question_title": "问题3", 
    *                "time_node": "05:00", 
    *                "answer_type": "1", //1单选 2多选
    *                "option": [
    *                    {
    *                        "id": 21, 
    *                        "choice_text": "选项1"
    *                    }, 
    *                    {
    *                        "id": 22, 
    *                        "choice_text": "选项2"
    *                    }, 
    *                    {
    *                        "id": 23, 
    *                        "choice_text": "选项3"
    *                    }, 
    *                    {
    *                        "id": 24, 
    *                        "choice_text": "选项4"
    *                    }
    *                ]
    *            }
    *        ]
    *    }
    *}
    *
    *
     *@apiErrorExample 失败返回示例1:
     *
     *{
     *
     *    "code": -1,
     *
     *    "msg": "请登录"
     *
     *}
     *@apiErrorExample 失败返回示例2:
     *
     *{
     *
     *    "code": 0,
     *
     *    "msg": "缺少course_id"
     *
     *}
     *@apiErrorExample 失败返回示例3:
     *
     *{
     *
     *    "code": -3,
     *
     *    "msg": "暂无该课程或课程已下架"
     *
     *}
     *@apiErrorExample 失败返回示例4:
     *
     *{
    *    "code": -4, 
    *    "data": {
    *        "id": 2, //课程id
    *        "title": "课程2", //课程标题
    *        "video": "", //视频地址
    *        "type_id": "2", //课程类型二级类型id
    *        "type_fid": "1", //课程类型一级类型id
    *        "type": "社区实务", //课程类型
    *        "teacher_name": "讲师1", //讲师姓名
    *        "head_img": null, //讲师头像
    *        "view_number": 102, //学习人数
    *        "introduction": "课程2课程2课程2", //课程介绍
    *        "teacher_introduce": "高级讲师", //讲师介绍
    *        "seo_title": "php从初级到高级", //seo标题
    *        "seo_key": "php从初级到高级", //seo关键词
    *        "seo_des": "php从初级到高级", //seo描述    
    *        "collect": "0", //0未收藏 1已收藏 
    *        "is_integral":"1",//不可添加课程积分
    *        "is_integral_day":'1',//不可添加每日积分
    *    }
    *}
     *
     */
  
    public function details($course_id=''){
        
        $yanzheng = $this->users;
        $user_id=$yanzheng['id'];
      
        if(!$course_id){
            echo json_encode(array('code'=>0,'msg'=>'缺少course_id'),JSON_UNESCAPED_UNICODE);die();
        }
        $is_course=db('video_video')->where('id='.$course_id)->where('course_type=1')->where('is_show=1')->find();
        if(!$is_course){
            echo json_encode(array('code'=>-3,'msg'=>'暂无该课程或课程已下架'),JSON_UNESCAPED_UNICODE);die();
        }
        
        //添加观看次数
        $num=db('video_video')->field('view_number')->where('id='.$course_id)->find();
        $number=$num['view_number']+1;
        db('video_video')->where('id='.$course_id)->update(['view_number'=>$number]);
    	//判断用户有没有看过该视频
    	$history=Db::name('video_history')
    			->where('user_id='.$user_id)
    			->where('video_id='.$course_id)
    			->find();
        $course=Db::name('video_video')
                ->where('id='.$course_id)
                ->find();
        $collection=Db::name('video_collection')
                    ->where('user_id='.$user_id)
                    ->where('video_id='.$course_id)
                    ->find();
        if($collection){
            $collect='1';//已收藏
        }else{
            $collect='0';//未收藏
        }
        //课程详细信息
        $data=db('video_video')
                        ->alias('video')
                        ->field('video.id,video.title,video.video,video.type_id,type.type,teacher.teacher_name,teacher.head_img,video.view_number,video.introduction,teacher.teacher_introduce,video.seo_title,video.seo_key,video.seo_des')
                        ->join('mk_video_teacher teacher','video.teacher_id=teacher.id')
                        ->join('mk_video_type type','video.type_id=type.id')
                        ->where('video.course_type=1')
                        ->where('video.id='.$course_id)
                        ->find();
        // $data['video']=config('default_video').$data['video'];
        $type_fid=db('video_type')->field('fid')->where('id='.$data['type_id'])->find();
        if($type_fid['fid']=='0'){
            $data['type_fid']=$data['type_id'];
        }else{
            $data['type_fid']=$type_fid['fid'];
        }
        $data['collect']=$collect;
        $ogroup_id=$yanzheng['ogroup_id'];
        $course_power=db('organization_group')->where('id='.$ogroup_id)->value('course_power');
        $course_power_arr=explode(',',$course_power);
        if(!in_array($is_course['type_id'],$course_power_arr)){
            $data['video']='';
            $data['is_integral']='1';
            echo stripslashes(json_encode(array('code'=>-4,'data'=>$data),JSON_UNESCAPED_UNICODE));die();
        }else{
            $data['video']=config('default_video').$data['video'];
        }
       
        $beginToday=mktime(0,0,0,date('m'),date('d'),date('Y'));
        $endToday=mktime(0,0,0,date('m'),date('d')+1,date('Y'))-1;
        //判断该视频有没有添加课程积分
        $Integral_all=db('video_integral')
                        ->where('user_id='.$user_id)
                        ->where('video_id='.$course_id)
                        ->where('type_id=3')
                        ->count();
        //判断课程积分功能是否开启
        $integral_isopen=db('video_integral_type')
                        ->where('id=3')
                        ->value('is_open');
        //判断视频课程每日积分是否开启
        $integral_day=db('video_integral_type')
                        ->where('id=10')
                        ->value('is_open');
        //判断今天有没有添加每日积分
        $Integral_doday_day=db('video_integral')
                        ->where('user_id='.$user_id)
                        ->where('type_id=10')
                        ->where('play_time','gt',$beginToday)
                        ->where('play_time','lt',$endToday)
                        ->count();
        if($Integral_all=='0'&&$integral_isopen=='1'){
            $data['is_integral']='0';//该视频已添加过积分或关闭添加课程积分功能
        }else{
            $data['is_integral']='1';
        }                  
        if($integral_day=='1' && $Integral_doday_day=='0'){
            $data['is_integral_day']='0';
        }else{
            $data['is_integral_day']='1';
        }

        $is_questions=db('video_connect_course')
                            ->alias('cc')
                            ->field('cc.question_id id,cc.time_node,questions.question_title,questions.answer_type')
                            ->join('mk_video_questions questions','cc.question_id=questions.id')
                            ->where('cc.video_id='.$course_id)
                            ->order('cc.time_node asc')
                            ->select();
        
        if($history){
            $data['playtime']='00:00';
            //看过
            if($is_questions){
                //有问题
                $questions=array_column($is_questions, 'id');//所有的问题
                //所有回答过的问题
                $right=db('video_answer_history')
                    ->field('question_id')
                    ->where('video_id='.$course_id)
                    ->where('user_id='.$user_id)
                    ->select();
                $right=array_column($right, 'question_id');//所有回答正确的问题
                $noquestion = array_merge(array_diff($questions, $right));//所有未回答的问题的id
                foreach($noquestion as $val){
                    // $noquestions=db('video_questions')
                    //             ->field('id,question_title,time_node,answer_type')
                    //             ->where('id='.$val)
                    //             ->where('is_show=1')
                    //             ->find();
                    $noquestions=db('video_connect_course')
                                    ->alias('cc')
                                    ->field('cc.question_id id,cc.time_node,questions.question_title,questions.answer_type')
                                    ->join('mk_video_questions questions','cc.question_id=questions.id')
                                    ->where('cc.question_id='.$val)
                                    ->where('cc.video_id='.$course_id)
                                    ->find();
                    $option=Db::name('video_answers')
                        ->field('id,choice_text')
                        ->where(['question_id'=>$val])
                        ->where('is_show=1')
                        ->select();
                    $noquestions['option']=$option;//问题选项
                    $option=array();
                    $questions_no[]=$noquestions;
                    $noquestions=array();
                }
                $data['questions']=$questions_no;
                $return = stripslashes(json_encode(array('code'=>200,'data'=>$data),JSON_UNESCAPED_UNICODE));
            }else{
                //没有问题
                $return = stripslashes(json_encode(array('code'=>200,'data'=>$data),JSON_UNESCAPED_UNICODE));
            }
        }else{
            //没看过
            if($is_questions){
                $data['questions']=$is_questions;
                foreach($is_questions as $key=>$value){
                    $option=Db::name('video_answers')
                        ->field('id,choice_text')
                        ->where(['question_id'=>$value['id']])
                        ->where('is_show=1')
                        ->select();

                    $data['questions'][$key]['option']=$option;//问题选项
                    $option=array();
                }
                //没看过，有问题的
                 $return = stripslashes(json_encode(array('code'=>200,'data'=>$data),JSON_UNESCAPED_UNICODE));
            }else{
                //没看过，没有问题的
                 $return = stripslashes(json_encode(array('code'=>200,'data'=>$data),JSON_UNESCAPED_UNICODE));
            }
           
        }                
      
        echo $return;
    	
    }
    //提交问题
    /**
     * @api {post} Details/totals  提交问题
     * @apiVersion 0.1.0
     * @apiGroup Details
     * @apiDescription 提交问题
     *
     * @apiParam {String} token 登录令牌  
     * @apiParam {Number} question_id 问题id  
     * @apiParam {Number} answer_id 答案id   字符串 答案id用,隔开
     * @apiParam {Number} course_id 课程id  
     *
     * @apiSuccess {Number} code 状态码
     * @apiSuccess {String} data 返回结果模型
     *
     * @apiSuccessExample 成功返回示例1
    *
    *{
    *    "code":200,
    *    "msg":"回答正确",
    *    "data":{
    *        "question_title":"问题1",
    *        "right_answer":["选项4","选项4","选项4"]
    *    }
    *}
     * @apiSuccessExample 成功返回示例2
    *
    *{
    *    "code":201,
    *    "msg":"回答错误",
    *    "data":{
    *        "question_title":"问题1",
    *        "right_answer":["选项4","选项4","选项4"]
    *    }
    *}
    *
     *@apiErrorExample 失败返回示例1:
     *
     *{
     *
     *    "code": -1,
     *
     *    "msg": "请登录"
     *
     *}
     *
     *@apiErrorExample 失败返回示例2:
     *
     *{
     *
     *    "code": 0,
     *
     *    "msg": "缺少question_id"
     *
     *}
     *@apiErrorExample 失败返回示例3:
     *
     *{
     *
     *    "code": -2,
     *
     *    "msg": "缺少answer_id"
     *
     *}
     *@apiErrorExample 失败返回示例3:
     *
     *{
     *
     *    "code": -3,
     *
     *    "msg": "数据错误，请重试！"
     *
     *}
     *@apiErrorExample 失败返回示例4:
     *
     *{
     *
     *    "code": -4,
     *
     *    "msg": "请勿重复答题！"
     *
     *}
     */
    public function totals($question_id='',$answer_id='',$course_id=''){
        $yanzheng = $this->users;
        $user_id=$yanzheng['id'];
        if(!$question_id){
            echo json_encode(array('code'=>0,'msg'=>'缺少question_id'),JSON_UNESCAPED_UNICODE);die();
        }
        if(!$answer_id){
            echo json_encode(array('code'=>-2,'msg'=>'缺少answer_id'),JSON_UNESCAPED_UNICODE);die();
        }
        $is_question=db('video_questions')->field('question_title')->where('id='.$question_id)->where('is_show=1')->find();
        if(!$is_question){
            echo json_encode(array('code'=>-3,'msg'=>'数据错误，请重试！'),JSON_UNESCAPED_UNICODE);die();
        }
        // $user_id=session('uid');
        // $user_id='1';
        $answer=db('video_answers')
                ->alias('answers')
                ->field('answers.id,answers.choice_text,questions.question_title')
                ->join('mk_video_questions questions','answers.question_id=questions.id')
                ->where('answers.question_id='.$question_id)
                ->where('answers.is_show=1')
                ->where('answers.is_right=1')
                ->select();
        
        if(!$answer){
            echo json_encode(array('code'=>-3,'msg'=>'数据错误，请重试！'),JSON_UNESCAPED_UNICODE);die();
        }
        $answer_id_arr=explode(',',$answer_id);
        $db_answer_arr = array_column($answer, 'id');
        //查看是否是第一次回答此问题
        $is_one=db('video_answer_history')
                ->where('user_id='.$user_id)
                ->where('question_id='.$question_id)
                ->where('video_id='.$course_id)
                ->count();
        if($is_one){
            echo json_encode(array('code'=>-4,'msg'=>'请勿重复答题！'),JSON_UNESCAPED_UNICODE);die();
        }
        
        $db_answer_str=implode(',',$db_answer_arr);
       
        $db_answer_text_arr = array_column($answer, 'choice_text');
        //返回值
        $data=[
            'question_title'=>$is_question['question_title'],
            'right_answer'=>$db_answer_text_arr
        ];
        
        $db_count=count($db_answer_arr);
        $answer_count=count($answer_id_arr);
        if((!array_diff($db_answer_arr,$answer_id_arr))&&($db_count==$answer_count)){
           
            //正确
            $score=db('video_integral_type')->field('node,is_open')->where('id=2')->find();
            
            if($score['is_open']=='1'){
                //加分
                $list_one=[
                    'type_id'=>'2',
                    'video_id'=>$course_id,
                    'play_time'=>time(),
                    'integral'=>$score['node'],
                    'question_id'=>$question_id,
                    'user_id'=>$user_id,
                    'time'=>time(),
                    'is_right'=>'1'

                ];
                $user=db('users')->field('integral')->where('id='.$user_id)->find();
                $result=db('video_integral')->insert($list_one);
                $integral=$user['integral']+$score['node'];
                db('users')->where('id='.$user_id)->update(['integral'=>$integral]);
                //redis积分
                $redis = new IntegralRedis('','rankList');
                // $redis->addNode($user_id,$score['node']);
                if(!$result){
                    echo json_encode(array('code'=>-3,'msg'=>'数据错误，请重试！'),JSON_UNESCAPED_UNICODE);die();
                }
                //季度
                $isJidu=$redis->isJidu();
                if($isJidu){
                    //季度积分已开启
                    $jidu=$redis->courseJidu($course_id);
                    $user=$redis->isUser($user_id,$jidu);
                    if($user){
                        //如果有这条数据  添加积分
                        $jidujf=$user['integral']+$score['node'];
                        db('video_integral_jidu')->where('id='.$user['id'])->update(['integral'=>$jidujf,'update_time'=>time()]);
                    }else{
                        //插入数据
                        db('video_integral_jidu')->insert(['user_id'=>$user_id,'integral'=>$score['node'],'create_time'=>time(),'jidu'=>$jidu]);
                    }
                }
            }
            $data1=[
                'video_id'=>$course_id,
                'question_id'=>$question_id,
                'answer_id'=>$answer_id,
                'user_id'=>$user_id,
                'is_right'=>'1',
                'right_answer'=>$db_answer_str,
                'answer_time'=>time()
            ];
            db('video_answer_history')->insert($data1);
            echo json_encode(array('code'=>200,'msg'=>'回答正确','data'=>$data),JSON_UNESCAPED_UNICODE);die();
        }else{
            
             $data1=[
                'video_id'=>$course_id,
                'question_id'=>$question_id,
                'answer_id'=>$answer_id,
                'user_id'=>$user_id,
                'is_right'=>'0',
                'right_answer'=>$db_answer_str,
                'answer_time'=>time()
            ];
            db('video_answer_history')->insert($data1);
            $score_cuo=db('video_integral_type')->field('node,is_open')->where('id=4')->find();
            if($score_cuo['is_open']=='1'){
                //回答错误加分
                $list_one=[
                    'type_id'=>'4',
                    'video_id'=>$course_id,
                    'play_time'=>time(),
                    'integral'=>$score_cuo['node'],
                    'question_id'=>$question_id,
                    'user_id'=>$user_id,
                    'time'=>time(),
                    'is_right'=>'0'
                ];
                $user=db('users')->field('integral')->where('id='.$user_id)->find();
                $result=db('video_integral')->insert($list_one);
                $integral=$user['integral']+$score_cuo['node'];
                db('users')->where('id='.$user_id)->update(['integral'=>$integral]);
                //redis积分
                $redis = new IntegralRedis('','rankList');
                // $redis->addNode($user_id,$score_cuo['node']);
                //季度
                $isJidu=$redis->isJidu();
                if($isJidu){
                    //季度积分已开启
                    $jidu=$redis->courseJidu($course_id);
                    $user=$redis->isUser($user_id,$jidu);
                    if($user){
                        //如果有这条数据  添加积分
                        $jidujf=$user['integral']+$score_cuo['node'];
                        db('video_integral_jidu')->where('id='.$user['id'])->update(['integral'=>$jidujf,'update_time'=>time()]);
                    }else{
                        //插入数据
                        db('video_integral_jidu')->insert(['user_id'=>$user_id,'integral'=>$score_cuo['node'],'create_time'=>time(),'jidu'=>$jidu]);
                    }
                }
                if(!$result){
                    echo json_encode(array('code'=>-3,'msg'=>'数据错误，请重试！'),JSON_UNESCAPED_UNICODE);die();
                }
            }
            echo json_encode(array('code'=>201,'msg'=>'回答错误','data'=>$data),JSON_UNESCAPED_UNICODE);die();
        }
    }


    //分享
    /**
     * @api {post} Details/share 分享
     * @apiVersion 0.1.0
     * @apiGroup Details
     * @apiDescription 分享
     *
     * @apiParam {String} token 登录令牌  
     * @apiParam {Number} course_id 课程id  
     *
      * @apiSuccess {Number} code 状态码
     * @apiSuccess {String} data 返回结果模型
     *
     * @apiSuccessExample 成功返回示例
    *
    *{
    *    "code":200,
    *    "msg":"分享成功"
    *}
    *
    *
     *@apiErrorExample 失败返回示例1:
     *
     *{
     *
     *    "code": -1,
     *
     *    "msg": "请登录"
     *
     *}
     *
     *@apiErrorExample 失败返回示例2:
     *
     *{
     *
     *    "code": 0,
     *
     *    "msg": "缺少course_id"
     *
     *}
     *@apiErrorExample 失败返回示例3:
     *
     *{
     *
     *    "code": -3,
     *
     *    "msg": "暂无该课程或课程已下架"
     *
     *}
     *@apiErrorExample 失败返回示例4:
     *
     *{
     *
     *    "code": -2,
     *
     *    "msg": "数据错误，请重试！"
     *
     *}
     *@apiErrorExample 失败返回示例5:
     *
     *{
     *
     *    "code": -4,
     *
     *    "msg": "无权限"
     *
     *}
     */
    public function share($course_id=''){
        if(!$course_id){
            echo json_encode(array('code'=>0,'msg'=>'缺少course_id'),JSON_UNESCAPED_UNICODE);die();
        }
        $is_course=db('video_video')->where('id='.$course_id)->where('is_show=1')->find();
        if(!$is_course){
            echo json_encode(array('code'=>-3,'msg'=>'暂无该课程或课程已下架'),JSON_UNESCAPED_UNICODE);die();
        }
    
        $yanzheng = $this->users;
        $user_id=$yanzheng['id'];
        $ogroup_id=$yanzheng['ogroup_id'];
        $course_power=db('organization_group')->where('id='.$ogroup_id)->value('course_power');
        $course_power_arr=explode(',',$course_power);
        if(!in_array($is_course['type_id'],$course_power_arr)){
            echo json_encode(array('code'=>-4,'msg'=>'无权限'),JSON_UNESCAPED_UNICODE);die();
        }
        $data=[
            'video_id'=>$course_id,
            'user_id'=>$user_id,
            'share_time'=>time()
        ];
        $result=db('video_share')->insert($data);
        if($result){
            echo json_encode(array('code'=>200,'msg'=>'分享成功'),JSON_UNESCAPED_UNICODE);die();
        }else{
            echo json_encode(array('code'=>-2,'msg'=>'数据错误，请重试！'),JSON_UNESCAPED_UNICODE);die();
        }
    }


    //收藏
      /**
     * @api {post} Details/collection  收藏
     * @apiVersion 0.1.0
     * @apiGroup Details
     * @apiDescription 收藏
     *
     * @apiParam {String} token 登录令牌  
     * @apiParam {Number} course_id 课程id  
     *
      * @apiSuccess {Number} code 状态码
     * @apiSuccess {String} data 返回结果模型
     *
     * @apiSuccessExample 成功返回示例1
    *
    *{
    *    "code":200,
    *    "msg":"收藏成功",
    *    "status":"1",
    *    "number":"1",//收藏数
    *}
    *
    * @apiSuccessExample 成功返回示例2
    *
    *{
    *    "code":201,
    *    "msg":"取消收藏成功",
    *    "status":"0",
    *    "number":"1",//收藏数
    *}
    *
     *@apiErrorExample 失败返回示例1:
     *
     *{
     *
     *    "code": -1,
     *
     *    "msg": "请登录"
     *
     *}
     *
     *@apiErrorExample 失败返回示例2:
     *
     *{
     *
     *    "code": 0,
     *
     *    "msg": "缺少course_id"
     *
     *}
     *@apiErrorExample 失败返回示例3:
     *
     *{
     *
     *    "code": -3,
     *
     *    "msg": "暂无该课程或课程已下架"
     *
     *}
     *@apiErrorExample 失败返回示例4:
     *
     *{
     *
     *    "code": -2,
     *
     *    "msg": "数据错误，请重试！"
     *
     *}
     *@apiErrorExample 失败返回示例5:
     *
     *{
     *
     *    "code": -4,
     *
     *    "msg": "无权限"
     *
     *}
     */
    public function collection($course_id=''){
        if(!$course_id){
            echo json_encode(array('code'=>0,'msg'=>'缺少course_id'),JSON_UNESCAPED_UNICODE);die();
        }
        $is_course=db('video_video')->where('id='.$course_id)->where('is_show=1')->find();
        if(!$is_course){
            echo json_encode(array('code'=>-3,'msg'=>'暂无该课程或课程已下架'),JSON_UNESCAPED_UNICODE);die();
        }
        $yanzheng = $this->users;
        $user_id=$yanzheng['id'];
        $ogroup_id=$yanzheng['ogroup_id'];
        $course_power=db('organization_group')->where('id='.$ogroup_id)->value('course_power');
        $course_power_arr=explode(',',$course_power);
        if(!in_array($is_course['type_id'],$course_power_arr)){
            echo json_encode(array('code'=>-4,'msg'=>'无权限'),JSON_UNESCAPED_UNICODE);die();
        }
        $collection_db=db('video_collection')
                        ->where('video_id='.$course_id)
                        ->where('user_id='.$user_id)
                        ->find();
        if($collection_db){
            $result=db('video_collection')->delete($collection_db['id']);
            if($result){
                if($is_course['collection_number']>'0'){
                    $number=$is_course['collection_number']-1;
                }else{
                    $number='0';
                }
                db('video_video')->where('id='.$course_id)->where('is_show=1')->update(['collection_number'=>$number]);
                echo json_encode(array('code'=>201,'msg'=>'取消收藏成功','status'=>'0','number'=>$number),JSON_UNESCAPED_UNICODE);die();
            }else{
                 echo json_encode(array('code'=>-2,'msg'=>'数据错误，请重试！'),JSON_UNESCAPED_UNICODE);die();
            }
            
        }else{
            $data=[
            'video_id'=>$course_id,
            'user_id'=>$user_id,
            'collection_time'=>time()
            ];
            $result=db('video_collection')->insert($data);
            if($result){
                $number=$is_course['collection_number']+1;
                db('video_video')->where('id='.$course_id)->where('is_show=1')->update(['collection_number'=>$number]);
                echo json_encode(array('code'=>200,'msg'=>'收藏成功','status'=>'1','number'=>$number),JSON_UNESCAPED_UNICODE);die();
            }else{
                echo json_encode(array('code'=>-2,'msg'=>'数据错误，请重试！'),JSON_UNESCAPED_UNICODE);die();
            }
        }
        
    }

    //播放视频时记录历史
      /**
     * @api {post} Details/history  播放视频时记录历史
     * @apiVersion 0.1.0
     * @apiGroup Details
     * @apiDescription 播放视频时记录历史
     *
     * @apiParam {String} token 登录令牌  
     * @apiParam {Number} course_id 课程id  
     *
     * @apiSuccess {Number} code 状态码
     * @apiSuccess {String} data 返回结果模型
     *
     * @apiSuccessExample 成功返回示例1
    *
    *{
    *    "code":200
    *}
    *
    *
     *@apiErrorExample 失败返回示例1:
     *
     *{
     *
     *    "code": -1,
     *
     *    "msg": "请登录"
     *
     *}
     *
     *@apiErrorExample 失败返回示例2:
     *
     *{
     *
     *    "code": 0,
     *
     *    "msg": "缺少course_id"
     *
     *}
     *@apiErrorExample 失败返回示例3:
     *
     *{
     *
     *    "code": -5,
     *
     *    "msg": "暂无该课程或课程已下架"
     *
     *}
     *@apiErrorExample 失败返回示例4:
     *
     *{
     *
     *    "code": -3,
     *
     *    "msg": "数据错误，请重试！"
     *
     *}
     *@apiErrorExample 失败返回示例5:
     *
     *{
     *
     *    "code": -4,
     *
     *    "msg": "无权限"
     *
     *}
     */
    public function history($course_id=''){
        if(!$course_id){
            echo json_encode(array('code'=>0,'msg'=>'缺少course_id'),JSON_UNESCAPED_UNICODE);die();
        }
        $is_course=db('video_video')->where('id='.$course_id)->where('is_show=1')->find();
        if(!$is_course){
            echo json_encode(array('code'=>-5,'msg'=>'暂无该课程或课程已下架'),JSON_UNESCAPED_UNICODE);die();
        }
        $yanzheng = $this->users;
        $user_id=$yanzheng['id'];
        $ogroup_id=$yanzheng['ogroup_id'];
        $course_power=db('organization_group')->where('id='.$ogroup_id)->value('course_power');
        $course_power_arr=explode(',',$course_power);
        if(!in_array($is_course['type_id'],$course_power_arr)){
            echo json_encode(array('code'=>-4,'msg'=>'无权限'),JSON_UNESCAPED_UNICODE);die();
        }
        $history=db('video_history')
                ->field('id')
                ->where('video_id='.$course_id)
                ->where('user_id='.$user_id)
                ->find();
        if($history){
            $data=[
                'view_time'=>time()
            ];   
            $result=db('video_history')->where('id='.$history['id'])->update($data);
            if($result){
                echo json_encode(array('code'=>200),JSON_UNESCAPED_UNICODE);die();
            }else{
                echo json_encode(array('code'=>-3,'msg'=>'数据错误，请重试！'),JSON_UNESCAPED_UNICODE);die();
            }
        }else{
            $data=[
                'video_id'=>$course_id,
                'user_id'=>$user_id,
                'view_time'=>time()
            ]; 
            $result=db('video_history')->insert($data);
            if($result){
                echo json_encode(array('code'=>200),JSON_UNESCAPED_UNICODE);die();
            }else{
                echo json_encode(array('code'=>-3,'msg'=>'数据错误，请重试！'),JSON_UNESCAPED_UNICODE);die();
            }
        }
        
        

    }

    //推荐课程
     /**
     * @api {post} Details/recommend 推荐课程
     * @apiVersion 0.1.0
     * @apiGroup Details
     * @apiDescription 推荐课程
     *
     * @apiParam {String} token 登录令牌  
     * @apiSuccess {Number} code 状态码
     * @apiSuccess {String} data 返回结果模型
     *
     * @apiSuccessExample 成功返回示例
    *{
    *    "code":200,
    *    "data":[
    *        {
    *            "id":7,//课程id
    *            "title":"测试tag课程", //课程标题
    *            "duration":"",//课程时长
    *            "video_img":"",//缩略图
    *            "type":"基础知识",//课程类型
    *            "teacher_name":"讲师1"//讲师姓名
	*            "course_type": "1" //1 视频课程 2 图文课程
    *        },
    *        {
    *            "id":6,
    *            "title":"课程6",
    *            "duration":"00:10",
    *            "video_img":"/muke/public/uploads/20191121/f49147ef4112f3430afe0f7cd6647fba.jpeg",
    *            "type":"社区实务",
    *            "teacher_name":"讲师1"
	*            "course_type": "1" 
    *        },
    *        {
    *            "id":5,
    *            "title":"课程5",
    *            "duration":"00:10",
    *            "video_img":"/muke/public/uploads/20191121/aafb91729f8f1622384028da6c328eef.jpeg",
    *            "type":"政策解读",
    *            "teacher_name":"讲师1"
	*            "course_type": "1" 
    *        },
    *        {
    *            "id":4,
    *            "title":"课程3",
    *            "duration":"",
    *            "video_img":"/muke/public/uploads/20191121/de6f951f399540b8b1031d3a6b7da6f0.jpg",
    *            "type":"政策解读",
    *            "teacher_name":"讲师1"
	*            "course_type": "1" 
    *        }
    *    ]
    *}
    *
     *@apiErrorExample 失败返回示例1:
     *
     *{
     *
     *    "code": -1,
     *
     *    "msg": "请登录"
     *
     *}
     */
    public function recommend(){
        $yanzheng = $this->users;
        $user_id=$yanzheng['id'];
        $data=Db::name('video_video')
            ->alias('video')
            ->field('video.id,video.title,video.duration,video.video_img,type.type,teacher.teacher_name,video.course_type')
            ->join('mk_video_type type','video.type_id=type.id')
            ->join('mk_video_teacher teacher','video.teacher_id=teacher.id')
            ->where('video.is_show=1')
            ->order('upload_time desc')
            ->limit(4)
            ->select();
        foreach($data as &$val){
            $val['video_img']=config('default_video').$val['video_img'];
        }
        $return = stripslashes(json_encode(array('code'=>200,'data'=>$data),JSON_UNESCAPED_UNICODE));
        echo $return;
    }
   
    //相关课程
     /**
     * @api {post} Details/relevant 相关课程
     * @apiVersion 0.1.0
     * @apiGroup Details
     * @apiDescription 相关课程
     *
     * @apiParam {String} token 登录令牌  
     * @apiParam {Number} course_id 课程id 
     *
     * @apiSuccess {Number} code 状态码
     * @apiSuccess {String} data 返回结果模型
     *
     * @apiSuccessExample 成功返回示例
     *{
     *    "code":200,
     *    "data":[
     *        {
     *            "id":6, //课程id
     *            "title":"课程6", //课程标题
     *            "duration":"00:10", //课程时长
     *            "video_img":"/muke/public/uploads/20191121/f49147ef4112f3430afe0f7cd6647fba.jpeg", //缩略图
     *            "type":"社区实务", //课程类型
     *            "teacher_name":"讲师1" //讲师姓名
	 *            "course_type": "1" 
     * 
     *        }
     *    ]
     *}
     *
     *@apiErrorExample 失败返回示例1:
     *
     *{
     *
     *    "code": -1,
     *
     *    "msg": "请登录"
     *
     *}
     *@apiErrorExample 失败返回示例2:
     *
     *{
     *
     *    "code": 0,
     *
     *    "msg": "缺少course_id"
     *
     *}
     *@apiErrorExample 失败返回示例3:
     *
     *{
     *
     *    "code": -3,
     *
     *    "msg": "暂无该课程或课程已下架"
     *
     *}
     */
    public function relevant($course_id=''){
        if(!$course_id){
            echo json_encode(array('code'=>0,'msg'=>'缺少course_id'),JSON_UNESCAPED_UNICODE);die();
        }
        $is_course=db('video_video')->where('id='.$course_id)->where('is_show=1')->find();
        if(!$is_course){
            echo json_encode(array('code'=>-3,'msg'=>'暂无该课程或课程已下架'),JSON_UNESCAPED_UNICODE);die();
        }
        $yanzheng = $this->users;
        $user_id=$yanzheng['id'];
        $course=db('video_video')->where('id='.$course_id)->where('is_show=1')->find();
        $type=$course['type_id'];
        $data=Db::name('video_video')
            ->alias('video')
            ->field('video.id,video.tag_id')
            ->join('mk_video_type type','video.type_id=type.id')
            ->join('mk_video_teacher teacher','video.teacher_id=teacher.id')
            ->where('video.id','neq',$course_id)
            ->where('video.is_show=1')
            ->where('video.type_id='.$type)
            ->order('upload_time desc')
            ->limit(20)
            ->select();
        
        //相关tag
        $tags=$course['tag_id'];
        $tags_arr=explode('#',$tags);
        foreach($tags_arr as $val){
            $count=db('video_tag')->field('count')->where('id='.$val)->find();
            $count_tag[$val]=$count['count'];
        }
        $count_tag=self::sort_with_keyName2($count_tag);
        $max=key($count_tag);
        foreach($data as $val){
            $course_tags=explode('#',$val['tag_id']);
            if(in_array($max,$course_tags)){
                $course_ids[]=$val['id'];
            }
        }
        $course_count=count($course_ids);
        $where=1;
        

        if($course_count>'0'){
            $course_ids_str=implode(',',$course_ids);
           $where.=" and video.id in (".$course_ids_str.")";
        }
        $data1=Db::name('video_video')
            ->alias('video')
            ->field('video.id,video.title,video.duration,video.video_img,type.type,teacher.teacher_name,video.course_type')
            ->join('mk_video_type type','video.type_id=type.id','left')
            ->join('mk_video_teacher teacher','video.teacher_id=teacher.id','left')
            ->where('video.id','neq',$course_id)
            ->where($where)
            ->where('video.is_show=1')
            ->where('video.type_id='.$type)
            ->order('upload_time desc')
            ->limit(4)
            ->select();
        foreach($data1 as $val){
            $data1_ids[]=$val['id'];
        }
        if($course_count>'0'&&$course_count<'4'){
            $shao_count=4-$course_count;
            $data2=Db::name('video_video')
                ->alias('video')
                ->field('video.id,video.title,video.duration,video.video_img,type.type,teacher.teacher_name,video.course_type')
                ->join('mk_video_type type','video.type_id=type.id','left')
                ->join('mk_video_teacher teacher','video.teacher_id=teacher.id','left')
                ->where('video.id','neq',$course_id)
                ->where('video.id','not in',$data1_ids)
                ->where('video.is_show=1')
                ->where('video.type_id='.$type)
                ->order('upload_time desc')
                ->limit($shao_count)
                ->select();
            $data1=array_merge($data1,$data2);
        }
        foreach($data1 as &$val){
            $val['video_img']=config('default_video').$val['video_img'];
        }
        $return = stripslashes(json_encode(array('code'=>200,'data'=>$data1),JSON_UNESCAPED_UNICODE));
        echo $return;
    }

    //添加视频课程积分
    /**
     * @api {post} Details/courseIntegralAdd 添加视频课程积分
     * @apiVersion 0.1.0
     * @apiGroup Details
     * @apiDescription 添加课程积分
     *
     * @apiParam {String} token 登录令牌  
     * @apiParam {Number} course_id 课程id 
     *
     * @apiSuccess {Number} code 状态码
     * @apiSuccess {String} data 返回结果模型
     *
     * @apiSuccessExample 成功返回示例
     *{
     *    "code":200,
     *    "msg":"成功获取课程积分！"
     *}
     *
     *@apiErrorExample 失败返回示例1:
     *
     *{
     *
     *    "code": -1,
     *
     *    "msg": "请登录"
     *
     *}
     *@apiErrorExample 失败返回示例2:
     *
     *{
     *
     *    "code": -2,
     *
     *    "msg": "已获取过本课程积分！"
     *
     *}
     *@apiErrorExample 失败返回示例3:
     *
     *{
     *
     *    "code": -3,
     *
     *    "msg": "数据错误，请重试！"
     *
     *}
     *@apiErrorExample 失败返回示例4:
     *
     *{
     *
     *    "code": 0,
     *
     *    "msg": "缺少course_id"
     *
     *}
     *@apiErrorExample 失败返回示例5:
     *
     *{
     *
     *    "code": -4,
     *
     *    "msg": "无权限"
     *
     *}
     *@apiErrorExample 失败返回示例6:
     *
     *{
     *
     *    "code": -5,
     *
     *    "msg": "课程积分功能已关闭！/暂无该课程或课程已下架"
     *
     *}
     */
    public function courseIntegralAdd($course_id=''){
        $yanzheng = $this->users;
        $user_id=$yanzheng['id'];
        if(!$course_id){
            echo json_encode(array('code'=>0,'msg'=>'缺少course_id'),JSON_UNESCAPED_UNICODE);die();
        }
        $is_course=db('video_video')->where('id='.$course_id)->where('course_type=1')->where('is_show=1')->find();
        if(!$is_course){
            echo json_encode(array('code'=>-5,'msg'=>'暂无该课程或课程已下架'),JSON_UNESCAPED_UNICODE);die();
        }
        $ogroup_id=$yanzheng['ogroup_id'];
        $course_power=db('organization_group')->where('id='.$ogroup_id)->value('course_power');
        $course_power_arr=explode(',',$course_power);
        if(!in_array($is_course['type_id'],$course_power_arr)){
            echo json_encode(array('code'=>-4,'msg'=>'无权限'),JSON_UNESCAPED_UNICODE);die();
        }
        //判断课程积分是否开启
        $score=db('video_integral_type')->field('node,is_open')->where('id=3')->find();
        if($score['is_open']=='1')
        {
            //开启
            //判断是否添加过该添加课程积分
         
            $courseIntegral=db('video_integral')
                        ->where('user_id='.$user_id)
                        ->where('type_id=3')
                        ->where('video_id='.$course_id)
                        ->count();
            if($courseIntegral){
                echo json_encode(array('code'=>-2,'msg'=>'已获取过该视频课程积分！'),JSON_UNESCAPED_UNICODE);die();
            }else{
                $list=[
                    'type_id'=>'3',
                    'video_id'=>$course_id,
                    'play_time'=>time(),
                    'user_id'=>$user_id,
                    'time'=>time(),
                    'integral'=>$score['node'],
                ];
                $user=db('users')->field('integral')->where('id='.$user_id)->find();
                $result=db('video_integral')->insert($list);
                $integral=$user['integral']+$score['node'];
                $updateUsers=db('users')->where(['id'=>$user_id])->update(['integral'=>$integral]);
                if($updateUsers==false){
                    echo json_encode(array('code'=>-3,'msg'=>'数据错误，请重试！'),JSON_UNESCAPED_UNICODE);die();
                }
                $redis = new IntegralRedis('','rankList');
                if(!$result){
                    echo json_encode(array('code'=>-3,'msg'=>'数据错误，请重试！'),JSON_UNESCAPED_UNICODE);die();
                }else{
                    //季度
                    $isJidu=$redis->isJidu();
                    if($isJidu){
                        //季度积分已开启
                        $jidu=$redis->courseJidu($course_id);
                        $user=$redis->isUser($user_id,$jidu);
                        if($user){
                            //如果有这条数据  添加积分
                            $jidujf=$user['integral']+$score['node'];
                            db('video_integral_jidu')->where('id='.$user['id'])->update(['integral'=>$jidujf,'update_time'=>time()]);
                        }else{
                            //插入数据
                            db('video_integral_jidu')->insert(['user_id'=>$user_id,'integral'=>$score['node'],'create_time'=>time(),'jidu'=>$jidu]);
                        }
                    }
                    echo json_encode(array('code'=>200,'msg'=>'成功获取课程积分！'),JSON_UNESCAPED_UNICODE);die();
                }
            }
        }else{
            echo json_encode(array('code'=>-5,'msg'=>'课程积分功能已关闭！'),JSON_UNESCAPED_UNICODE);die();
        }
    }
    //添加视频每日积分
    /**
     * @api {post} Details/dayIntegralAdd 添加视频课程每日积分
     * @apiVersion 0.1.0
     * @apiGroup Details
     * @apiDescription 添加课程每日积分
     *
     * @apiParam {String} token 登录令牌  
     * @apiParam {Number} course_id 课程id 
     *
     * @apiSuccess {Number} code 状态码
     * @apiSuccess {String} data 返回结果模型
     *
     * @apiSuccessExample 成功返回示例
     *{
     *    "code":200,
     *    "msg":"成功获取每日课程积分！"
     *}
     *
     *@apiErrorExample 失败返回示例1:
     *
     *{
     *
     *    "code": -1,
     *
     *    "msg": "请登录"
     *
     *}
     *@apiErrorExample 失败返回示例2:
     *
     *{
     *
     *    "code": -2,
     *
     *    "msg": "已获取过每日课程积分！"
     *
     *}
     *@apiErrorExample 失败返回示例3:
     *
     *{
     *
     *    "code": -3,
     *
     *    "msg": "数据错误，请重试！"
     *
     *}
     *@apiErrorExample 失败返回示例4:
     *
     *{
     *
     *    "code": 0,
     *
     *    "msg": "缺少course_id"
     *
     *}
     *@apiErrorExample 失败返回示例5:
     *
     *{
     *
     *    "code": -4,
     *
     *    "msg": "无权限"
     *
     *}
     *@apiErrorExample 失败返回示例6:
     *
     *{
     *
     *    "code": -5,
     *
     *    "msg": "每日积分功能已关闭！/暂无该课程或课程已下架"
     *
     *}
     */
    public function dayIntegralAdd($course_id=''){
        $yanzheng = $this->users;
        $user_id=$yanzheng['id'];
        if(!$course_id){
            echo json_encode(array('code'=>0,'msg'=>'缺少course_id'),JSON_UNESCAPED_UNICODE);die();
        }
        $is_course=db('video_video')->where('id='.$course_id)->where('course_type=1')->where('is_show=1')->find();
        if(!$is_course){
            echo json_encode(array('code'=>-5,'msg'=>'暂无该课程或课程已下架'),JSON_UNESCAPED_UNICODE);die();
        }
        $ogroup_id=$yanzheng['ogroup_id'];
        $course_power=db('organization_group')->where('id='.$ogroup_id)->value('course_power');
        $course_power_arr=explode(',',$course_power);
        if(!in_array($is_course['type_id'],$course_power_arr)){
            echo json_encode(array('code'=>-4,'msg'=>'无权限'),JSON_UNESCAPED_UNICODE);die();
        }
        //判断课程积分是否开启
        $score=db('video_integral_type')->field('node,is_open')->where('id=10')->find();
        if($score['is_open']=='1')
        {
            //开启
          
            $beginToday=mktime(0,0,0,date('m'),date('d'),date('Y'));
            $endToday=mktime(0,0,0,date('m'),date('d')+1,date('Y'))-1;
            //判断今天有没有添加每日积分
            $Integral_doday_day=db('video_integral')
                ->where('user_id='.$user_id)
                ->where('type_id=10')
                ->where('play_time','gt',$beginToday)
                ->where('play_time','lt',$endToday)
                ->count();
            if($Integral_doday_day){
                echo json_encode(array('code'=>-2,'msg'=>'已获取过每日视频课程积分！'),JSON_UNESCAPED_UNICODE);die();
            }else{
                $list=[
                    'type_id'=>'10',
                    'video_id'=>$course_id,
                    'play_time'=>time(),
                    'user_id'=>$user_id,
                    'time'=>time(),
                    'integral'=>$score['node'],
                ];
                $user=db('users')->field('integral')->where('id='.$user_id)->find();
                $result=db('video_integral')->insert($list);
                $integral=$user['integral']+$score['node'];
                $updateUsers=db('users')->where(['id'=>$user_id])->update(['integral'=>$integral]);
                if($updateUsers==false){
                    echo json_encode(array('code'=>-3,'msg'=>'数据错误，请重试！'),JSON_UNESCAPED_UNICODE);die();
                }
                $redis = new IntegralRedis('','rankList');
                if(!$result){
                    echo json_encode(array('code'=>-3,'msg'=>'数据错误，请重试！'),JSON_UNESCAPED_UNICODE);die();
                }else{
                    //季度
                    $isJidu=$redis->isJidu();
                    if($isJidu){
                        //季度积分已开启
                        $jidu=$redis->courseJidu($course_id);
                        $user=$redis->isUser($user_id,$jidu);
                        if($user){
                            //如果有这条数据  添加积分
                            $jidujf=$user['integral']+$score['node'];
                            db('video_integral_jidu')->where('id='.$user['id'])->update(['integral'=>$jidujf,'update_time'=>time()]);
                        }else{
                            //插入数据
                            db('video_integral_jidu')->insert(['user_id'=>$user_id,'integral'=>$score['node'],'create_time'=>time(),'jidu'=>$jidu]);
                        }
                    }
                    echo json_encode(array('code'=>200,'msg'=>'成功获取每日课程积分！'),JSON_UNESCAPED_UNICODE);die();
                }
            }
        }else{
            echo json_encode(array('code'=>-5,'msg'=>'每日课程积分功能已关闭！'),JSON_UNESCAPED_UNICODE);die();
        }
        
    }
    //记录学习时长
    /**
     * @api {post} Details/learnTimeAdd 记录学习时长
     * @apiVersion 0.1.0
     * @apiGroup Details
     * @apiDescription 记录学习时长
     *
     * @apiParam {String} token 登录令牌  
     *
     * @apiSuccess {Number} code 状态码
     * @apiSuccess {String} data 返回结果模型
     *
     * @apiSuccessExample 成功返回示例
     *{
     *    "code":200,
     *    "msg":"记录成功！"
     *}
     *
     *@apiErrorExample 失败返回示例1:
     *
     *{
     *
     *    "code": -1,
     *
     *    "msg": "请登录"
     *
     *}
     *@apiErrorExample 失败返回示例3:
     *
     *{
     *
     *    "code": -2,
     *
     *    "msg": "数据错误，请重试！"
     *
     *}
     */
    public function learnTimeAdd(){
        $yanzheng = $this->users;
        $user_id=$yanzheng['id'];
        $user=db('users')->field('learntime')->where('id='.$user_id)->find();
        $learn_time=$user['learntime']+0.17;
        $result=db('users')->where('id='.$user_id)->update(['learntime'=>$learn_time]);
        if(!$result){
            echo json_encode(array('code'=>-2,'msg'=>'数据错误，请重试！'),JSON_UNESCAPED_UNICODE);die();
        }else{
            echo json_encode(array('code'=>200,'msg'=>'记录成功！'),JSON_UNESCAPED_UNICODE);die();
        }
    }

    public function sort_with_keyName2($arr,$orderby='desc'){//desc 从大到小倒序排列， asc递增排序
        $new_array = array();
        $new_sort = array();
        foreach($arr as $key =>$value){
            $new_array[] = $value;
        }
        if($orderby=='asc'){
            asort($new_array);
        }else{
            arsort($new_array);
        }
        foreach($new_array as $k => $v){
            foreach($arr as $key => $value){
                if($v==$value){
                    $new_sort[$key] = $value;
                    unset($arr[$key]);
                    break;
                }
            }
        }
        return $new_sort;
    }

    //图文课程详情
    /**
     * @api {post} Details/textDetails 图文课程详情
     * @apiVersion 0.1.0
     * @apiGroup Details
     * @apiDescription 图文课程详情
     *
     * @apiParam {String} token 登录令牌  
     * @apiParam {Number} course_id 课程id  
     *
     * @apiSuccess {Number} code 状态码
     * @apiSuccess {String} data 返回结果模型
     *
     * @apiSuccessExample 成功返回示例
     *{
    *    "code":200,
    *    "data":{
    *        "id":11, //课程id
    *        "title":"4", //课程标题
    *        "content":"", //课程内容
    *        "teacher_name":"讲师1", //讲师姓名
    *        "head_img":"/muke/public/uploads/20191207/5a587e758f14774763260b477bab35fa.jpeg", //讲师头像
    *        "view_number":5, //学习人数
    *        "teacher_introduce":"高级讲师", //讲师介绍
    *        "seo_title":"课程", //seo标题
    *        "seo_key":"课程", //seo关键词
    *        "seo_des":"11", //seo描述
    *        "is_work":1, //是否有作业 1是0否
    *        "upload_time":"2019-11-22 11:27", //发布时间
    *        "tag_id":[ //tag标签
    *            "政策解读"
    *        ],
    *        "status":10, //审核中  未提交作业  数字为分数
    *        "is_subwork":"1", //是否提交  1是 0否
    *        "is_collect":"0",//是否收藏  1是 0否
    *        "is_like":"0", //是否点赞  1是 2否
    *        "collection_number":"1", 收藏数
    *        "like_number":"1",点赞数
    *    }
    *}
     *
     *@apiErrorExample 失败返回示例1:
     *
     *{
     *
     *    "code": -1,
     *
     *    "msg": "请登录"
     *
     *}
     *@apiErrorExample 失败返回示例2:
     *
     *{
     *
     *    "code": 0,
     *
     *    "msg": "缺少course_id"
     *
     *}
     *@apiErrorExample 失败返回示例3:
     *
     *{
     *
     *    "code": -3,
     *
     *    "msg": "暂无该课程或课程已下架/数据错误，请重试！"
     *
     *}
     *@apiErrorExample 失败返回示例4:
     *
     *{
     *
     *    "code": -4,
     *
     *    "msg": "无权限"
     *
     *}
     *
     */
    public function textDetails($course_id=''){
        $users = $this->users;
        $user_id=$users['id'];
        if(!$course_id){
            echo json_encode(array('code'=>0,'msg'=>'缺少course_id'),JSON_UNESCAPED_UNICODE);die();
        }
        $is_course=db('video_video')->field('id,view_number,type_id')->where('id='.$course_id)->where('course_type=2')->where('is_show=1')->find();
        if(!$is_course){
            echo json_encode(array('code'=>-3,'msg'=>'暂无该课程或课程已下架'),JSON_UNESCAPED_UNICODE);die();
        }
        $ogroup_id=$users['ogroup_id'];
        $course_power=db('organization_group')->where('id='.$ogroup_id)->value('course_power');
        $course_power_arr=explode(',',$course_power);
        if(!in_array($is_course['type_id'],$course_power_arr)){
            echo json_encode(array('code'=>-4,'msg'=>'无权限'),JSON_UNESCAPED_UNICODE);die();
        }
        //添加观看次数
        $number=$is_course['view_number']+1;
        db('video_video')->where('id='.$course_id)->update(['view_number'=>$number]);
        //添加历史
        $history=db('video_history')
                ->field('id')
                ->where('video_id='.$course_id)
                ->where('user_id='.$user_id)
                ->find();
        if($history){
            $data=[
                'view_time'=>time()
            ];   
            $result=db('video_history')->where('id='.$history['id'])->update($data);
            if(!$result){
                echo json_encode(array('code'=>-3,'msg'=>'数据错误，请重试！'),JSON_UNESCAPED_UNICODE);die();
            }
        }else{
            $data=[
                'video_id'=>$course_id,
                'user_id'=>$user_id,
                'view_time'=>time()
            ]; 
            $result=db('video_history')->insert($data);
            if(!$result){
                echo json_encode(array('code'=>-3,'msg'=>'数据错误，请重试！'),JSON_UNESCAPED_UNICODE);die();
            }
        }
        $data=db('video_video')
            ->alias('video')
            ->field('video.id,video.title,video.content,teacher.teacher_name,teacher.head_img,video.view_number,teacher.teacher_introduce,video.seo_title,video.seo_key,video.seo_des,video.is_work,video.upload_time,tag_id,video.like_number,video.collection_number')
            ->join('mk_video_teacher teacher','video.teacher_id=teacher.id')
            ->where('video.id='.$course_id)
            ->where('video.course_type=2')
            ->find();
        $data['content']=str_replace("\"","'",$data['content']);
        $data['upload_time'] = date('Y-m-d H:i',$data['upload_time']);
        //得分情况
        if($data['is_work']=='1'){
            //有作业
            $work=db('video_text_work')
                    ->field('work_status,score')
                    ->where('user_id='.$user_id)
                    ->where('video_id='.$course_id)
                    ->find();
            if($work&&$work['work_status']=='1'){
                //已提交作业 审核中
                $data['status']='审核中';
                $data['is_subwork']='1';//已提交
            }else if($work&&$work['work_status']=='2'){
                //已判分
                $data['status']=$work['score'];
                $data['is_subwork']='1';//已提交
            }else{
                //未提交作业
                $data['status']='未提交作业';
                $data['is_subwork']='0';//未提交
            }
        }else{
            $data['status']='';
            $data['is_subwork']='';//已提交
        }
        //tag
        
        if($data['tag_id']){
            $tags=explode('#', $data['tag_id']);//该课程原tag
            foreach($tags as $val){
                $tag=db('video_tag')->where('id='.$val)->find();
                $tag_id[]=$tag['tag'];
            }
            $data['tag_id']=$tag_id;
        }else{
            $data['tag_id']='';
        }
        //收藏判断
        $collection=Db::name('video_collection')
                    ->where('user_id='.$user_id)
                    ->where('video_id='.$course_id)
                    ->find();
        if($collection){
            $data['is_collect']='1';//已收藏
        }else{
            $data['is_collect']='0';//未收藏
        }
       

        //点赞判断
        $collection=Db::name('video_text_like')
                    ->where('user_id='.$user_id)
                    ->where('video_id='.$course_id)
                    ->find();
        if($collection){
            $data['is_like']='1';//已点赞
        }else{
            $data['is_like']='0';//未点赞
        }
        //课程积分
        //判断该视频有没有添加课程积分
        $beginToday=mktime(0,0,0,date('m'),date('d'),date('Y'));
        $endToday=mktime(0,0,0,date('m'),date('d')+1,date('Y'))-1;
        $Integral_all=db('video_integral')
                        ->where('user_id='.$user_id)
                        ->where('video_id='.$course_id)
                        ->where('type_id=7')
                        ->count();
        //判断课程积分功能是否开启
        $integral_course=db('video_integral_type')
                        ->field('is_open,node')
                        ->where('id=7')
                        ->find();
        //判断视频课程每日积分是否开启
        $integral_day=db('video_integral_type')
                        ->field('is_open,node')
                        ->where('id=11')
                        ->find();
        //判断今天有没有添加每日积分
        $Integral_doday_day=db('video_integral')
                        ->where('user_id='.$user_id)
                        ->where('type_id=11')
                        ->where('play_time','gt',$beginToday)
                        ->where('play_time','lt',$endToday)
                        ->count();
        if($Integral_all=='0'&&$integral_course['is_open']=='1'){
            $is_integral='0';
        }              
        if($integral_day['is_open']=='1' && $Integral_doday_day=='0'){
            $is_integral_day='0';
        }
        if($is_integral_day=='0'){
            //加分
            $list=[
                'type_id'=>'11',
                'video_id'=>$course_id,
                'play_time'=>time(),
                'user_id'=>$user_id,
                'time'=>time(),
                'integral'=>$integral_day['node'],
            ];
            $user=db('users')->field('integral')->where('id='.$user_id)->find();
            $result=db('video_integral')->insert($list);
            $integral=$user['integral']+$integral_day['node'];
            $updateUsers=db('users')->where(['id'=>$user_id])->update(['integral'=>$integral]);
           
            if($updateUsers==false){
                echo '1';die();
                echo json_encode(array('code'=>-3,'msg'=>'数据错误，请重试！'),JSON_UNESCAPED_UNICODE);die();
            }
            $redis = new IntegralRedis('','rankList');
            if(!$result){
                echo '2';die();
                echo json_encode(array('code'=>-3,'msg'=>'数据错误，请重试！'),JSON_UNESCAPED_UNICODE);die();
            }else{
                //季度
                $isJidu=$redis->isJidu();
                if($isJidu){
                    //季度积分已开启
                    $jidu=$redis->courseJidu($course_id);
                    $user=$redis->isUser($user_id,$jidu);
                    if($user){
                        //如果有这条数据  添加积分
                        $jidujf=$user['integral']+$integral_day['node'];
                        db('video_integral_jidu')->where('id='.$user['id'])->update(['integral'=>$jidujf,'update_time'=>time()]);
                    }else{
                        //插入数据
                        db('video_integral_jidu')->insert(['user_id'=>$user_id,'integral'=>$integral_day['node'],'create_time'=>time(),'jidu'=>$jidu]);
                    }
                }
               
            }
        }     
        if($is_integral=='0'){
            //加分
            $list=[
                'type_id'=>'7',
                'video_id'=>$course_id,
                'play_time'=>time(),
                'user_id'=>$user_id,
                'time'=>time(),
                'integral'=>$integral_course['node'],
            ];
            $user=db('users')->field('integral')->where('id='.$user_id)->find();
            $result=db('video_integral')->insert($list);
            $integral=$user['integral']+$integral_course['node'];
            $updateUsers=db('users')->where(['id'=>$user_id])->update(['integral'=>$integral]);
            if($updateUsers==false){
                echo '3';die();
                echo json_encode(array('code'=>-3,'msg'=>'数据错误，请重试！'),JSON_UNESCAPED_UNICODE);die();
            }
            $redis = new IntegralRedis('','rankList');
            if(!$result){
                echo '4';die();
                echo json_encode(array('code'=>-3,'msg'=>'数据错误，请重试！'),JSON_UNESCAPED_UNICODE);die();
            }else{
                //季度
                $isJidu=$redis->isJidu();
                if($isJidu){
                    //季度积分已开启
                    $jidu=$redis->courseJidu($course_id);
                    $user=$redis->isUser($user_id,$jidu);
                    if($user){
                        //如果有这条数据  添加积分
                        $jidujf=$user['integral']+$integral_course['node'];
                        db('video_integral_jidu')->where('id='.$user['id'])->update(['integral'=>$jidujf,'update_time'=>time()]);
                    }else{
                        //插入数据
                        db('video_integral_jidu')->insert(['user_id'=>$user_id,'integral'=>$integral_course['node'],'create_time'=>time(),'jidu'=>$jidu]);
                    }
                }
               
            }
        }                
        $return = stripslashes(json_encode(array('code'=>200,'data'=>$data),JSON_UNESCAPED_UNICODE));
        echo $return;
    }
    
    //图文课程点赞
     /**
     * @api {post} Details/textlike  图文课程点赞
     * @apiVersion 0.1.0
     * @apiGroup Details
     * @apiDescription 图文课程点赞
     *
     * @apiParam {String} token 登录令牌  
     * @apiParam {Number} course_id 课程id  
     *
      * @apiSuccess {Number} code 状态码
     * @apiSuccess {String} data 返回结果模型
     *
     * @apiSuccessExample 成功返回示例1
    *
    *{
    *    "code":200,
    *    "msg"=>"点赞成功" //提示信息  点赞成功 取消点赞成功
    *    "number":"1",点赞数量
    *    "status":"1" //状态 1点赞  0取消点赞
    *}
    *
    *
     *@apiErrorExample 失败返回示例1:
     *
     *{
     *
     *    "code": -1,
     *
     *    "msg": "请登录"
     *
     *}
     *
     *@apiErrorExample 失败返回示例2:
     *
     *{
     *
     *    "code": 0,
     *
     *    "msg": "缺少course_id"
     *
     *}
     *@apiErrorExample 失败返回示例3:
     *
     *{
     *
     *    "code": -3,
     *
     *    "msg": "暂无该课程或课程已下架"
     *
     *}
     *@apiErrorExample 失败返回示例4:
     *
     *{
     *
     *    "code": -2,
     *
     *    "msg": "数据错误，请重试！"
     *
     *}
     *@apiErrorExample 失败返回示例5:
     *
     *{
     *
     *    "code": -4,
     *
     *    "msg": "无权限"
     *
     *}
     */
    public function textlike($course_id=''){
        $users = $this->users;
        $user_id=$users['id'];
        if(!$course_id){
            echo json_encode(array('code'=>0,'msg'=>'缺少course_id'),JSON_UNESCAPED_UNICODE);die();
        }
        $is_course=db('video_video')->where('id='.$course_id)->where('course_type=2')->where('is_show=1')->find();
        if(!$is_course){
            echo json_encode(array('code'=>-3,'msg'=>'暂无该课程或课程已下架'),JSON_UNESCAPED_UNICODE);die();
        }
        $ogroup_id=$users['ogroup_id'];
        $course_power=db('organization_group')->where('id='.$ogroup_id)->value('course_power');
        $course_power_arr=explode(',',$course_power);
        if(!in_array($is_course['type_id'],$course_power_arr)){
            echo json_encode(array('code'=>-4,'msg'=>'无权限'),JSON_UNESCAPED_UNICODE);die();
        }
        $like_db=db('video_text_like')
                        ->where('video_id='.$course_id)
                        ->where('user_id='.$user_id)
                        ->find();
        if($like_db){
            $result=db('video_text_like')->delete($like_db['id']);
            if($result){
                if($is_course['like_number']>'0'){
                    $number=$is_course['like_number']-1;
                }else{
                    $number='0';
                }
                
                db('video_video')->where('id='.$course_id)->where('course_type=2')->where('is_show=1')->update(['like_number'=>$number]);
                echo json_encode(array('code'=>200,'msg'=>'取消点赞成功','number'=>$number,'status'=>'0'),JSON_UNESCAPED_UNICODE);die();
            }else{
                 echo json_encode(array('code'=>-2,'msg'=>'数据错误，请重试！'),JSON_UNESCAPED_UNICODE);die();
            }
        }else{
            $data=[
            'video_id'=>$course_id,
            'user_id'=>$user_id,
            'time'=>time()
            ];
            $result=db('video_text_like')->insert($data);
            if($result){
                $number=$is_course['like_number']+1;
                db('video_video')->where('id='.$course_id)->where('course_type=2')->where('is_show=1')->update(['like_number'=>$number]);
                echo json_encode(array('code'=>200,'msg'=>'点赞成功','number'=>$number,'status'=>'1'),JSON_UNESCAPED_UNICODE);die();
            }else{
                echo json_encode(array('code'=>-2,'msg'=>'数据错误，请重试！'),JSON_UNESCAPED_UNICODE);die();
            }
        }
    }
    //提交作业
    /**
     * @api {post} Details/subwork  提交作业
     * @apiVersion 0.1.0
     * @apiGroup Details
     * @apiDescription 提交作业
     *
     * @apiParam {String} token 登录令牌  
     * @apiParam {Number} course_id 课程id  
     * @apiParam {Number} title 作业标题  
     * @apiParam {Number} content 作业内容
     * @apiParam {Number} status 1 提交  2暂存  
     *
      * @apiSuccess {Number} code 状态码
     * @apiSuccess {String} data 返回结果模型
     *
     * @apiSuccessExample 成功返回示例1
    *
    *{
    *    "code":200,
    *    "msg"=>"提交成功" //提示信息  
    *}
    *
    *
     *@apiErrorExample 失败返回示例1:
     *
     *{
     *
     *    "code": -1,
     *
     *    "msg": "请登录"
     *
     *}
     *
     *@apiErrorExample 失败返回示例2:
     *
     *{
     *
     *    "code": 0,
     *
     *    "msg": "缺少course_id/缺少title/缺少content"
     *
     *}
     *@apiErrorExample 失败返回示例3:
     *
     *{
     *
     *    "code": -2,
     *
     *    "msg": "数据错误，请重试！/暂无该课程或课程已下架/该课程暂无作业"
     *
     *}
     *@apiErrorExample 失败返回示例4:
     *
     *{
     *
     *    "code": -3,
     *
     *    "msg": "不可以重复提交作业"
     *
     *}
      *@apiErrorExample 失败返回示例5:
     *
     *{
     *
     *    "code": -4,
     *
     *    "msg": "无权限"
     *
     *}
     *@apiErrorExample 失败返回示例6:
     *
     *{
     *
     *    "code": -5,
     *
     *    "msg": "内容含敏感词语"
     *
     *}
     */
    public function subwork($course_id='',$title='',$content='',$status=""){
        $users = $this->users;
        $user_id=$users['id'];
        $data=input('post.');
        if(!$course_id){
            echo json_encode(array('code'=>0,'msg'=>'缺少course_id'),JSON_UNESCAPED_UNICODE);die();
        }
        $is_course=db('video_video')->where('id='.$course_id)->where('course_type=2')->where('is_show=1')->find();
        if(!$is_course){
            echo json_encode(array('code'=>-3,'msg'=>'暂无该课程或课程已下架'),JSON_UNESCAPED_UNICODE);die();
        }
        if($is_course['is_work']=='0'){
            echo json_encode(array('code'=>-3,'msg'=>'该课程暂无作业'),JSON_UNESCAPED_UNICODE);die();
        }
        if(!$title){
            echo json_encode(array('code'=>0,'msg'=>'缺少title'),JSON_UNESCAPED_UNICODE);die();
        }
        if(!$content){
            echo json_encode(array('code'=>0,'msg'=>'缺少content'),JSON_UNESCAPED_UNICODE);die();
        }
        if(!$status){
            echo json_encode(array('code'=>0,'msg'=>'status'),JSON_UNESCAPED_UNICODE);die();
        }
        $ogroup_id=$users['ogroup_id'];
        $course_power=db('organization_group')->where('id='.$ogroup_id)->value('course_power');
        $course_power_arr=explode(',',$course_power);
        if(!in_array($is_course['type_id'],$course_power_arr)){
            echo json_encode(array('code'=>-4,'msg'=>'无权限'),JSON_UNESCAPED_UNICODE);die();
        }
        $course=new CourseModel();
        $validate=$course->validate($data);
        if($validate){
            echo json_encode($validate,JSON_UNESCAPED_UNICODE);die();
        }
        
        //反垃圾未设置
        $string = $data['content']; //要过滤的内容
        $course=new CourseModel();
        $dict=$course->txt();
        $list2 = explode(",", $dict);
        $result = self::sensitive($list2, $string);
        if($result){
            echo json_encode(array('code'=>-5,'msg'=>$result),JSON_UNESCAPED_UNICODE);die();
        }
        $list=[];
        if($status=='1'){
            //提交
            $list=[
                'user_id'=>$user_id,
                'video_id'=>$course_id,
                'worktitle'=>$data['title'],
                'upload_time'=>time(),
                'work_status'=>'1'
            ];
        }else if($status=='2'){
            //暂存
            $list=[
                'user_id'=>$user_id,
                'video_id'=>$course_id,
                'worktitle'=>$data['title'],
                'upload_time'=>time(),
                'work_status'=>'3'
            ];
        }
        $list['workcontent']=str_replace("\"","'",$data['content']);
        $list['workcontent']=str_replace("\n","</br>",$data['content']);
        //判断是否有暂存的作业了
        $is_work=db('video_text_work')->field('id,work_status')->where('user_id='.$user_id)->where('video_id='.$course_id)->find();
        
        if($is_work){
            if($is_work['work_status']=='2'){
                echo json_encode(array('code'=>-3,'msg'=>'不可以重复提交作业'),JSON_UNESCAPED_UNICODE);die();
            }
            $result=db('video_text_work')->where('id='.$is_work['id'])->update($list);
        }else{
            $result=db('video_text_work')->insert($list);
        }
        
        if($result!=false){
            echo json_encode(array('code'=>200,'msg'=>'操作成功'),JSON_UNESCAPED_UNICODE);die();
        }else{
            echo json_encode(array('code'=>-2,'msg'=>'数据错误，请重试！'),JSON_UNESCAPED_UNICODE);die();
        }
    }
    
    public function sensitive($list, $string){
        $count = 0; //违s规词的个数
        $sensitiveWord = '';  //违规词
        $stringAfter = $string;  //替换后的内容
        $pattern = "/".implode("|",$list)."/u"; //定义正则表达式
        if(preg_match_all($pattern, $string, $matches)){ //匹配到了结果
            $patternList = $matches[0];  //匹配到的数组
            $count = count($patternList);
            $sensitiveWord = implode(',', $patternList); //敏感词数组转字符串
            $replaceArray = array_combine($patternList,array_fill(0,count($patternList),'*')); //把匹配到的数组进行合并，替换使用
            $stringAfter = strtr($string, $replaceArray); //结果替换
        }
        
        if($count==0){
            $log = "";
        }else{
            $log = "匹配到 [ {$count} ]个敏感词：[ {$sensitiveWord} ]";
            // $log = "内容含敏感词语";
        }
        return $log;
    }

    //删除暂存的作业
    /**
     * @api {post} Details/stagingDel  删除暂存的作业
     * @apiVersion 0.1.0
     * @apiGroup Details
     * @apiDescription 删除暂存的作业
     *
     * @apiParam {String} token 登录令牌  
     * @apiParam {Number} course_id 课程id  
     *
     * @apiSuccess {Number} code 状态码
     * @apiSuccess {String} data 返回结果模型
     *
     * @apiSuccessExample 成功返回示例1
     *
     *{
     *    "code":200,
     *    "msg"=>"操作成功" //提示信息  
     *}
     *
     *
     *@apiErrorExample 失败返回示例1:
     *
     *{
     *
     *    "code": -1,
     *
     *    "msg": "请登录"
     *
     *}
     *
     *@apiErrorExample 失败返回示例2:
     *
     *{
     *
     *    "code": 0,
     *
     *    "msg": "缺少course_id"
     *
     *}
     *@apiErrorExample 失败返回示例3:
     *
     *{
     *
     *    "code": -2,
     *
     *    "msg": "数据错误，请重试！"
     *
     *}
     *@apiErrorExample 失败返回示例4:
     *
     *{
     *
     *    "code": -3,
     *
     *    "msg": "暂无该课程或课程已下架/您没有暂存的作业！/该课程暂无作业"
     *
     *}
     *@apiErrorExample 失败返回示例5:
     *
     *{
     *
     *    "code": -4,
     *
     *    "msg": "无权限"
     *
     *}
     */
    public function stagingDel($course_id=''){
        $users = $this->users;
        $user_id=$users['id'];
        $data=input('post.');
        if(!$course_id){
            echo json_encode(array('code'=>0,'msg'=>'缺少course_id'),JSON_UNESCAPED_UNICODE);die();
        }
        $is_course=db('video_video')->where('id='.$course_id)->where('course_type=2')->where('is_show=1')->find();
        if(!$is_course){
            echo json_encode(array('code'=>-3,'msg'=>'暂无该课程或课程已下架'),JSON_UNESCAPED_UNICODE);die();
        }
        if($is_course['is_work']=='0'){
            echo json_encode(array('code'=>-3,'msg'=>'该课程暂无作业'),JSON_UNESCAPED_UNICODE);die();
        }
        $ogroup_id=$users['ogroup_id'];
        $course_power=db('organization_group')->where('id='.$ogroup_id)->value('course_power');
        $course_power_arr=explode(',',$course_power);
        if(!in_array($is_course['type_id'],$course_power_arr)){
            echo json_encode(array('code'=>-4,'msg'=>'无权限'),JSON_UNESCAPED_UNICODE);die();
        }
        //判断是否有暂存的作业了
        $is_work=db('video_text_work')->field('id,work_status')->where('user_id='.$user_id)->where('video_id='.$course_id)->where('work_status=3')->find();
        if($is_work){
            $result=db('video_text_work')->where('id='.$is_work['id'])->delete();
            if($result){
                echo json_encode(array('code'=>200,'msg'=>'操作成功'),JSON_UNESCAPED_UNICODE);die();
            }else{
                echo json_encode(array('code'=>-2,'msg'=>'数据错误，请重试！'),JSON_UNESCAPED_UNICODE);die();
            }
        }else{
            echo json_encode(array('code'=>-3,'msg'=>'您没有暂存的作业！'),JSON_UNESCAPED_UNICODE);die();
        }
        
    }
    //读取暂存作业
    /**
    * @api {post} Details/readStaging 读取暂存作业 
    * @apiVersion 0.1.0
    * @apiGroup Details
    * @apiDescription 读取暂存作业
    *
    * @apiParam {String} token 登录令牌 
    * @apiParam {Number} course_id 课程id
    *
    * @apiSuccess {Number} code 状态码
    * @apiSuccess {String} data 返回搜索结果模型
    *
    * @apiSuccessExample 成功返回示例
    *
    *{
    *    "code":200,
    *    "data":{
    *        "worktitle":"标题",//作业标题
    *        "workcontent":"内容",//作业内容
    *    }
    *}
    *
    *
    *
    * @apiErrorExample 失败返回示例1:
    *
    *{
    *
    *    "code": -1,
    *
    *    "msg": "请登录"
    *
    *}
    * @apiErrorExample 失败返回示例2:
    *{
    *
    *    "code": 0,
    *
    *    "msg": "缺少course_id/暂无该课程或课程已下架"
    *
    *}
     * @apiErrorExample 失败返回示例3:
    *{
    *
    *    "code": -4,
    *
    *    "msg": "无权限"
    *
    *}
    */
    public function readStaging($course_id=''){
        $users = $this->users;
        $user_id=$users['id'];
        if(!$course_id){
            echo json_encode(array('code'=>0,'msg'=>'缺少course_id'),JSON_UNESCAPED_UNICODE);die();
        }
        $is_course=db('video_video')->field('id,type_id')->where('id='.$course_id)->where('course_type=2')->where('is_show=1')->find();
        if(!$is_course){
            echo json_encode(array('code'=>0,'msg'=>'暂无该课程或课程已下架'),JSON_UNESCAPED_UNICODE);die();
        }
        $ogroup_id=$users['ogroup_id'];
        $course_power=db('organization_group')->where('id='.$ogroup_id)->value('course_power');
        $course_power_arr=explode(',',$course_power);
        if(!in_array($is_course['type_id'],$course_power_arr)){
            echo json_encode(array('code'=>-4,'msg'=>'无权限'),JSON_UNESCAPED_UNICODE);die();
        }
        $data=db('video_text_work')
            ->field('worktitle,workcontent')
            ->where('user_id='.$user_id)
            ->where('video_id='.$course_id)
            ->where('work_status=3')
            ->find();
        if($data){
            $data['workcontent']=str_replace("\"","'",$data['workcontent']);
            $data['workcontent']=str_replace("\n","</br>",$data['workcontent']);
        }else{
            $data=[];
        }
        $return = stripslashes(json_encode(array('code'=>200,'data'=>$data),JSON_UNESCAPED_UNICODE));
        echo $return;
    }
}
