<?php
namespace app\api\controller;
use think\Controller;
use think\Db;
use think\validate;
use think\facade\Session;
use think\facade\Env;
use app\api\model\IntegralRedis;

class My extends Common{
    // public function initialize(){
    //     parent::initialize();
    // }

    //我的收藏
    /**
     * @api {post} My/myCollection 我的收藏
     * @apiVersion 0.1.0
     * @apiGroup My
     * @apiDescription 我的收藏
     *
     * @apiParam {String} token 登录令牌 
     * @apiSuccess {Number} code 状态码
     * @apiSuccess {String} data 返回结果模型
     *
     * @apiSuccessExample 成功返回示例
	*
	*{
	*    "code":200,
	*    "data":[
	*        {
	*            "id":4,//课程id
	*            "title":"课程3",//课程标题
	*            "video_img":"/mukez/public/uploads/20191121/de6f951f399540b8b1031d3a6b7da6f0.jpg",//缩略图
	*            "type":"政策解读",//课程类型
	*            "teacher_name":"讲师1",//讲师姓名
	*            "course_type": "1"  //1 视频课程 2 图文课程
	*        },
	*        {
	*            "id":2,
	*            "title":"课程2",
	*            "video_img":"/mukez/public/uploads/20191119/565109b2bfa066cb3f22d7a5dbd393e9.jpeg",
	*            "type":"社区实务",
	*            "teacher_name":"讲师1",
	*            "course_type": "1" 
	*        }
	*    ]
	*}
	*
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
     */
    public function myCollection(){
    	$yanzheng = $this->users;
        $user_id=$yanzheng['id'];
        $data=db('video_collection')
                ->alias('collection')
                ->field('video.id,video.title,video.video_img,type.type,teacher.teacher_name,video.course_type')
                ->join('mk_video_video video','collection.video_id=video.id','left')
                ->join('mk_video_type type','video.type_id=type.id','left')
                ->join('mk_video_teacher teacher','video.teacher_id=teacher.id','left')
                ->where('collection.user_id='.$user_id)
                ->where('video.is_show=1')
                ->order('collection.collection_time desc')
                ->select();
        foreach($data as &$val){
            $val['video_img']=config('default_video').$val['video_img'];
        }
		$return = stripslashes(json_encode(array('code'=>200,'data'=>$data),JSON_UNESCAPED_UNICODE));
		echo $return;

    }

    //观看历史
    /**
     * @api {post} My/myHistory 观看历史
     * @apiVersion 0.1.0
     * @apiGroup My
     * @apiDescription 观看历史
     * @apiParam {String} token 登录令牌 
     *
      * @apiSuccess {Number} code 状态码
     * @apiSuccess {String} data 返回结果模型
     *
     * @apiSuccessExample 成功返回示例
	*{
	*    "code":200,
	*    "data":[
	*        {
	*            "id":5,//课程id
	*            "title":"课程5",//课程标题
	*            "video_img":"/mukez/public/uploads/20191121/aafb91729f8f1622384028da6c328eef.jpeg",//缩略图
	*            "type":"政策解读",//课程类型
	*            "teacher_name":"讲师1",//讲师姓名
	*            "course_type": "1"  //1 视频课程 2 图文课程
	*        },
	*        {
	*            "id":4,
	*            "title":"课程3",
	*            "video_img":"/mukez/public/uploads/20191121/de6f951f399540b8b1031d3a6b7da6f0.jpg",
	*            "type":"政策解读",
	*            "teacher_name":"讲师1",
	*            "course_type": "1"  //1 视频课程 2 图文课程
	*        },
	*        {
	*            "id":2,
	*            "title":"课程2",
	*            "video_img":"/mukez/public/uploads/20191119/565109b2bfa066cb3f22d7a5dbd393e9.jpeg",
	*            "type":"社区实务",
	*            "teacher_name":"讲师1",
	*            "course_type": "1"  //1 视频课程 2 图文课程
	*        }
	*    ]
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
     */
    public function myHistory(){
    	$yanzheng = $this->users;
        $user_id=$yanzheng['id'];
    	$data=db('video_history')
    			->alias('history')
    			->field('video.id,video.title,video.video_img,type.type,teacher.teacher_name,video.course_type')
    			->join('mk_video_video video','history.video_id=video.id')
				->join('mk_video_type type','video.type_id=type.id')
				->join('mk_video_teacher teacher','video.teacher_id=teacher.id')
                ->where('history.user_id='.$user_id)
                ->where('video.is_show=1')
				->order('history.view_time desc')
				->select();
       //删除3个月前的历史记录
       foreach($data as &$val){
            $val['video_img']=config('default_video').$val['video_img'];
        }
        $time=time()-2678400;
        $result=db('video_history')->where('view_time','lt',$time)->delete();
		$return = stripslashes(json_encode(array('code'=>200,'data'=>$data),JSON_UNESCAPED_UNICODE));
		echo $return;
    }

    //我的答题

    /**
     * @api {post} My/myAnswers 我的答题
     * @apiVersion 0.1.0
     * @apiGroup My
     * @apiDescription 我的答题
     *
     * @apiParam {String} token 登录令牌 
     * @apiSuccess {Number} code 状态码
     * @apiSuccess {String} data 返回结果模型
     *
     * @apiSuccessExample 成功返回示例
    *{
    *    "code": 200, 
    *    "data": [
    *        {
    *            "id": 3, //答题日志id
    *            "video_id": 3, //课程id
    *            "is_right": 1, //是否正确  1正确 2错误
    *            "answer_time": "2019-11-24 20:56", //答题时间
    *            "title": "课程2", //课程名称
    *            "question_title": "问题1", //问题标题
    *            "choice_text": ["lallallallalllallalallalallaallalll","选项2222222","选项444"] //正确答案
    *        }, 
    *        {
    *            "id": 2, 
    *            "video_id": 3, 
    *            "is_right": 0, 
    *            "answer_time": "2019-11-24 20:53", 
    *            "title": "课程2", 
    *            "question_title": "问题3", 
    *            "choice_text": ["lallallallalllallalallalallaallalll","选项2222222","选项444"]
    *        }
    *    ]
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
     */
    public function myAnswers(){
        $yanzheng = $this->users;
        $user_id=$yanzheng['id'];
        $list=Db::name('video_answer_history')
                ->alias('history')
                ->field('history.id,history.video_id,history.is_right,history.answer_time,video.title,questions.question_title,history.question_id')
                ->join('mk_video_video video','history.video_id=video.id')
                ->join('mk_video_questions questions','history.question_id=questions.id')
                ->join('mk_users users','history.user_id=users.id')
                ->where(['history.user_id'=>$user_id])
                ->where('video.is_show=1')
                ->order('history.answer_time desc')
                ->select();
        // print_r($list);die();
        foreach ($list as $k=>$v){
            $list[$k]['answer_time'] = date('Y-m-d H:i',$v['answer_time']);
            $answer=db('video_answers')
                ->field('choice_text')
                ->where('question_id='.$v['question_id'])
                ->where('is_show=1')
                ->where('is_right=1')
                ->select();
            $list[$k]['choice_text'] = array_column($answer, 'choice_text');
        }
        $return = stripslashes(json_encode(array('code'=>200,'data'=>$list),JSON_UNESCAPED_UNICODE));
        echo $return;
    }

    //我的积分

    /**
     * @api {post} My/myIntegral 我的积分
     * @apiVersion 0.1.0
     * @apiGroup My
     * @apiDescription 我的积分
     * @apiParam {String} token 登录令牌 
     * @apiParam {Number} type 积分类型  1登录积分 2答题积分 3课程积分 4考试积分  5系统积分 6作业积分
     *
      * @apiSuccess {Number} code 状态码
     * @apiSuccess {String} data 返回结果模型
     *
     * @apiSuccessExample 成功返回示例1：登录积分
    *
    *{
    *    "code": 200, 
    *    "data": [
    *        {
    *            "play_time": "2019-11-25 18:52", //登录时间
    *            "integral": 5 //积分
    *        }
    *    ]
    *}
    *
    * @apiSuccessExample 成功返回示例2：答题积分
    *{
    *    "code": 200, 
    *    "data": [
    *        {   
    *            "video_id":"2",课程id
    *            "question_title": "问题3", //问题
    *            "title": "课程2", //课程标题
    *            "play_time": "2019-11-25 18:51", //答题时间
    *            "integral": 5 //积分
    *            "is_right":1//是否正确 1正确 2错误
    *        }, 
    *        {   
    *            "video_id":"3",课程id
    *            "question_title": "问题3",  
    *            "title": "课程2", 
    *            "play_time": "2019-11-25 18:52", 
    *            "integral": 5,
    *            "is_right":1//是否正确 1正确 2错误
    *        }
    *    ]
    *}
    * @apiSuccessExample 成功返回示例3：课程积分
    *
    *{
    *    "code": 200, 
    *    "data": [
    *        {   
    *            "video_id":"2",//课程id
    *            "type_id":"4",//二级课程类型id
    *            "type_fid":"1",//一级课程类型id
    *            "title": "课程2", //课程标题
    *            "video_img": "/mukez/public/uploads/20191119/565109b2bfa066cb3f22d7a5dbd393e9.jpeg", //缩略图
    *            "duration": "05:59", //课程时长
    *            "type": "社区实务", //课程类型
    *            "play_time": "2019-11-25 18:52", //播放时间
    *            "integral": 5 //积分,
	*            "course_type": "1" // 1 视频课程 2 图文课程
    *        }, 
    *        {   
    *            "video_id":"3",课程id
    *            "type_id":"4",//二级课程类型id
    *            "type_fid":"1",//一级课程类型id
    *            "title": "课程4", 
    *            "video_img": "/mukez/public/uploads/20191121/08c95afb98d4354d6c325757a458baa5.jpeg", 
    *            "duration": null, 
    *            "type": "政策解读", 
    *            "play_time": "2019-11-25 19:24", 
    *            "integral": 5,
	*            "course_type": "1" 
    *        }
    *    ]
    *}
    * @apiSuccessExample 成功返回示例4：考试积分
    *
    *{
    *    "code":200,
    *    "data":[
    *        {
    *            "test_id":1, //试卷id
    *            "title":"2020年度", //试卷名称
    *            "time_start":"2019-12-19 14:48", //开始时间
    *            "time_end":"2019-12-29 14:48", //结束时间
    *            "integral":4 //积分
    *        },
    *        {
    *            "test_id":2,
    *            "title":"2021年度",
    *            "time_start":"2019-12-19 14:48",
    *            "time_end":"2019-12-29 14:48",
    *            "integral":4
    *        }
    *    ]
    *}
    * @apiSuccessExample 成功返回示例5：系统积分
    *
    *{
    *    "code":200,
    *    "data":[
    *        {
    *            "id":95, //积分表id
    *            "integral":-5,  //积分
    *            "reason":"测试减分",  //原因
    *            "play_time":"2019-12-23 15:42" //操作时间
    *        },
    *        {
    *            "id":94,
    *            "integral":5,
    *            "reason":"测试减分",
    *            "play_time":"2019-12-23 15:41"
    *        },
    *        {
    *            "id":93,
    *            "integral":5,
    *            "reason":"测试",
    *            "play_time":"2019-12-23 15:40"
    *        }
    *    ]
    *}
    * @apiSuccessExample 成功返回示例6：作业积分
    *
    *{
    *    "code":200,
    *    "data":[
    *        {
    *            "work_id":6,//作业id
    *            "worktitle":"作业标题",//作业标题
    *            "video_id":11,//课程id
    *            "video_title":"课程标题",//课程标题
    *            "upload_time":"2020-01-03 14:20",//提交时间
    *            "integral":1//积分
    *        }
    *    ]
    *}
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
     *    "msg": "缺少type"
     *
     *}
     */
    public function myIntegral($type=''){
        if(!$type){
            echo json_encode(array('code'=>0,'msg'=>'缺少type'),JSON_UNESCAPED_UNICODE);die();
        }

        $yanzheng = $this->users;
        $user_id=$yanzheng['id'];
        if($type=='1'){
            //登录积分
            $data=db('video_integral')
                    ->field('play_time,integral')
                    ->where('user_id='.$user_id)
                    ->where('type_id=1')
                    ->order('play_time desc')
                    ->select();
            foreach ($data as $k=>$v){
                $data[$k]['play_time'] = date('Y-m-d H:i',$v['play_time']);
            }
        }else if($type=='2'){
            //答题积分
            $data=db('video_integral')
                    ->alias('integral')
                    ->field('integral.video_id,questions.question_title,video.title,integral.play_time,integral.integral,integral.is_right')
                    ->join('mk_video_video video','integral.video_id=video.id')
                    ->join('mk_video_questions questions','integral.question_id=questions.id')
                    ->where('integral.user_id='.$user_id)
                    ->where('video.is_show=1')
                    ->where('integral.type_id=2 or integral.type_id=4')
                    ->order('integral.play_time desc')
                    ->select();
            foreach ($data as $k=>$v){
                $data[$k]['play_time'] = date('Y-m-d H:i',$v['play_time']);
            }
        }else if($type=='3'){
            //课程积分
            $data=db('video_integral')
                    ->alias('integral')
                    ->field('integral.video_id,video.title,video.video_img,video.duration,video.type_id,type.type,integral.play_time,integral.integral,video.course_type')
                    ->join('mk_video_video video','integral.video_id=video.id')
                    ->join('mk_video_type type','video.type_id=type.id')
                    ->where('integral.user_id='.$user_id)
                    ->where('video.is_show=1')
                    ->where('integral.type_id=3 or integral.type_id=7 or integral.type_id=10 or integral.type_id=11')
                    ->order('integral.play_time desc')
                    ->select();
            // print_r($data);die();
            foreach ($data as $k=>$v){
                $data[$k]['play_time'] = date('Y-m-d H:i',$v['play_time']);
                $type_fid=db('video_type')
                        ->field('fid')
                        ->where('id='.$v['type_id'])
                        ->find();
                if($type_fid['fid']=='0'){
                    $data[$k]['type_fid']=$v['type_id'];
                }else{
                    $data[$k]['type_fid']=$type_fid['fid'];
                }
                
                $data[$k]['video_img']=config('default_video').$v['video_img'];
                
            }
        }else if($type=='4'){
            //考试积分
            $data=db('video_integral')
                    ->alias('integral')
                    ->field('test.id test_id,test.title,test.time_start,test.time_end,integral.integral')
                    ->join('mk_video_test test','integral.test_id=test.id','left')
                    ->where('integral.user_id='.$user_id)
                    ->where('type_id=5')
                    ->where('test.is_show=1')
                    ->where('test.is_open=1')
                    ->order('integral.play_time desc')
                    ->select();
            foreach ($data as $k=>$v){
                $data[$k]['time_start'] = date('Y-m-d H:i',$v['time_start']);
                $data[$k]['time_end'] = date('Y-m-d H:i',$v['time_end']);
            }
        }else if($type=='5'){
            //系统积分
            $data=db('video_integral')
                    ->field('id,integral,reason,play_time')
                    ->where('user_id='.$user_id)
                    ->where('type_id=6  or type_id=8')
                    ->order('play_time desc')
                    ->select();
            foreach ($data as $k=>$v){
                $data[$k]['play_time'] = date('Y-m-d H:i',$v['play_time']);
            }
        }else if($type=='6'){
            //作业积分
            $data=db('video_integral')
                    ->alias('integral')
                    ->field('work.id work_id,work.worktitle,video.id video_id,video.title video_title,work.upload_time,integral.integral')
                    ->join('mk_video_text_work work','integral.work_id=work.id','left')
                    ->join('mk_video_video video','integral.video_id=video.id','left')
                    ->where('integral.user_id='.$user_id)
                    ->where('video.is_show=1')
                    ->where('integral.type_id=9')
                    ->order('integral.play_time desc')
                    ->select();
            foreach ($data as $k=>$v){
                $data[$k]['upload_time'] = date('Y-m-d H:i',$v['upload_time']);
            }
        }
        $return = stripslashes(json_encode(array('code'=>200,'data'=>$data),JSON_UNESCAPED_UNICODE));
        echo $return;
    }


    //清除历史

    /**
     * @api {post} My/clearHistory 清除历史
     * @apiVersion 0.1.0
     * @apiGroup My
     * @apiDescription 清除历史
     *
     * @apiParam {String} token 登录令牌 
     * @apiSuccess {Number} code 状态码
     * @apiSuccess {String} data 返回结果模型
     *
     * @apiSuccessExample 成功返回示例
    *{
    *    "code": 200, 
    *    "msg": "清除历史成功"
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
     */
    public function clearHistory(){
        $yanzheng = $this->users;
        $user_id=$yanzheng['id'];
        $result=db('video_history')
                ->where('user_id='.$user_id)
                ->delete();
        if($result){
            $return = stripslashes(json_encode(array('code'=>200,'msg'=>'清除历史成功'),JSON_UNESCAPED_UNICODE));
            echo $return;
        }
    }

    //个人信息
     /**
     * @api {post} My/mylearninfo 个人学习信息
     * @apiVersion 0.1.0
     * @apiGroup My
     * @apiDescription 个人学习信息
     *
     * @apiParam {String} token 登录令牌 
     * @apiSuccess {Number} code 状态码
     * @apiSuccess {String} data 返回结果模型
     *
     * @apiSuccessExample 成功返回示例
    *{
    *    "code":200,
    *    "data":{
    *        "id":1, //用户id
    *        "nickname":"chichu", //用户昵称
    *        "avatar":"/mukez/public/uploads/20191126/7081b0965714d780f26695d630f5866e.jpeg", //头像
    *        "level_name":"用户身份", //用户身份
    *        "learntime":14.3, //学习时长
    *        "integral":"20", //总积分
    *        "count":3, //答题数
    *        "rate":"100%" //正确率
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
     */
    public function mylearninfo(){
        $yanzheng = $this->users;
        $user_id=$yanzheng['id'];
        $data=db('users')
                ->alias('users')
                ->field('users.id,users.nickname,users.avatar,users.learntime,users.integral,level.level_name')
                ->join('mk_user_level level','users.level=level.level_id')
                ->where('id='.$user_id)
                ->find();
        $count_all=db('video_answer_history')
                ->where('user_id='.$user_id)
                ->count();
        $count_right=db('video_answer_history')
                ->where('user_id='.$user_id)
                ->where('is_right=1')
                ->count();
        if($count_all){
            $rate=round(($count_right/$count_all)*100).'%';
        }else{
            $rate='0%';
        }
        $data['count']=$count_all;
        $data['rate']=$rate;
        $data['avatar'] = $data['avatar'] ? config('default_video').$data['avatar'] : '/mukez/public/static/admin/images/0.jpg';
        $return = stripslashes(json_encode(array('code'=>200,'data'=>$data),JSON_UNESCAPED_UNICODE));
            echo $return;
    }

    //用户头像
    /**
     * @api {post} My/head_img 用户头像
     * @apiVersion 0.1.0
     * @apiGroup My
     * @apiDescription 用户头像
     *
     * @apiParam {String} token 登录令牌 
     * @apiSuccess {Number} code 状态码
     * @apiSuccess {String} data 返回结果模型
     *
     * @apiSuccessExample 成功返回示例
     *
     *{
     *    "code":200,
     *    "avatar":"/cltphp/public/uploads/20180613/fcb729987d8e9339bd9b2e85c85f3028.jpg"
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
    */
    public function head_img(){
        $user = $this->users;
        $user['avatar'] = $user['avatar'] ? $user['avatar'] : '/mukez/public/static/admin/images/0.jpg';
        $return = stripslashes(json_encode(array('code'=>200,'avatar'=>$user['avatar']),JSON_UNESCAPED_UNICODE));
        echo $return;
    }
    //积分概览
    /**
     * @api {post} My/integralOverview 积分概览
     * @apiVersion 0.1.0
     * @apiGroup My
     * @apiDescription 积分概览
     *
     * @apiParam {String} token 登录令牌 
     * @apiSuccess {Number} code 状态码
     * @apiSuccess {String} data 返回结果模型
     *
     * @apiSuccessExample 成功返回示例
     *
     *{
     *    "code":200,
     *    "data":{
     *        "integral_all":"28",//总积分
     *        "rank_all":2,//总排名
     *        "integral_jidu":13,//季度积分
     *        "rank_jidu":1,//季度排名
     *        "grade":"专业",//等级
     *        "leval":2 //指针指向
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
     *
    */
    public function integralOverview(){
        $user = $this->users;
        $ogroup_id=$user['ogroup_id'];
        $is_independence=db('organization_group')->where('id='.$ogroup_id)->value('is_independence');
        if($is_independence=='1'){
            //独立
            
            $where1="users.ogroup_id='".$ogroup_id."'";
        }else{
           
            $where1="users.ogroup_id='".$ogroup_id."' or ogroup.is_independence=2";
        }
        $redis = new IntegralRedis('','rankList');
        //当前总积分
        $data['integral_all']=$user['integral'];
        //总排名
        if($user['integral']=='0'){
            $count=db('users')
                ->alias('users')
                ->field('users.id')
                ->join('mk_organization_group ogroup','users.ogroup_id=ogroup.id','left')
                ->where($where1)
                ->where('users.integral','gt','0')
                ->count();
            $rank_all=$count+1;//总排名
        }else{
            $gaoCount=db('users')
                ->alias('users')
                ->field('users.id')
                ->join('mk_organization_group ogroup','users.ogroup_id=ogroup.id','left')
                ->where($where1)
                ->where('integral','gt',$user['integral'])
                ->count();
            $rank_all=$gaoCount+1;  
        }
       
        $data['rank_all']=$rank_all;
        //本季度积分
        $jidu=$redis->jidu();
        $jidufen=db('video_integral_jidu')
                ->field('integral')
                ->where('user_id='.$user['id'])
                ->where(['jidu'=>$jidu])
                ->find();
        //季度分
        $integral_jidu=$jidufen['integral'];
        //季度排名
        if($jidufen){
            $data['integral_jidu']=$jidufen['integral'];
            $count=db('video_integral_jidu')
                ->alias('jidu')
                ->field('users.id')
                ->join('mk_users users','jidu.user_id=users.id')
                ->join('mk_organization_group ogroup','users.ogroup_id=ogroup.id','left')
                ->where($where1)
                ->where(['jidu.jidu'=>$jidu])
                ->where('jidu.integral','gt',$jidufen['integral'])
                ->count();
            $rank_jidu=$count+1;
        }else{
            $data['integral_jidu']='0';
            $jiducount=db('video_integral_jidu')
                ->alias('jidu')
                ->field('users.id')
                ->join('mk_users users','jidu.user_id=users.id')
                ->join('mk_organization_group ogroup','users.ogroup_id=ogroup.id','left')
                ->where($where1)
                ->where(['jidu.jidu'=>$jidu])
                ->count();
            $rank_jidu=$jiducount+1;
        }
        $data['rank_jidu']=$rank_jidu;
        //等级
        //学习等级
        //所有人个数
        $userCount=db('users')
            ->alias('users')
            ->field('users.id')
            ->join('mk_organization_group ogroup','users.ogroup_id=ogroup.id','left')
            ->where($where1)
            ->count();
     
        //百分比
        if($userCount){
            $rate=round(($rank_all/$userCount)*100);
        }else{
            $rate='0';
        }
        $filter=Db::name('video_grade_set')
            ->field('grade,setion_start,setion_end,level')
            ->select();
        $result = self::search($rate, $filter);
        $grade=current($result)['grade'];
        $leval=current($result)['level'];
        $data['grade']=$grade;
        $data['leval']=$leval;
        $return = stripslashes(json_encode(array('code'=>200,'data'=>$data),JSON_UNESCAPED_UNICODE));
        echo $return;
    }
    //榜单

    /**
     * @api {post} My/rank 榜单
     * @apiVersion 0.1.0
     * @apiGroup My
     * @apiDescription 榜单
     *
     * @apiParam {String} token 登录令牌 
     * @apiParam {String} type 榜单类型  1总榜 2季度榜
     * @apiSuccess {Number} code 状态码
     * @apiSuccess {String} data 返回结果模型
     *
     * @apiSuccessExample 成功返回示例1 总榜
     *{
    *    "code":200,
    *    "data":[
    *        {
    *            "id":19,//用户id
    *            "nickname":"11",//昵称
    *            "avatar":"/mukez/public/static/admin/images/0.jpg",//头像
    *            "integral":"28",//总积分
    *            "grade":"专家" //等级
    *        },
    *        {
    *            "id":1,
    *            "nickname":"admin",
    *            "avatar":null,
    *            "integral":"24",
    *            "grade":"专业"
    *        },
    *        {
    *            "id":9,
    *            "nickname":"啦啦啦",
    *            "avatar":"/mukez/public/static/admin/images/0.jpg",
    *            "integral":"22",
    *            "grade":"专业"
    *        },
    *        {
    *            "id":4,
    *            "nickname":"啦啦啦",
    *            "avatar":null,
    *            "integral":"14",
    *            "grade":"业余"
    *        },
    *        {
    *            "id":18,
    *            "nickname":null,
    *            "avatar":"/mukez/public/static/admin/images/0.jpg",
    *            "integral":"6",
    *            "grade":"业余"
    *        },
    *        {
    *            "id":17,
    *            "nickname":null,
    *            "avatar":"/mukez/public/static/admin/images/0.jpg",
    *            "integral":"6",
    *            "grade":"业余"
    *        },
    *        {
    *            "id":16,
    *            "nickname":null,
    *            "avatar":"/mukez/public/static/admin/images/0.jpg",
    *            "integral":"6",
    *            "grade":"业余"
    *        },
    *        {
    *            "id":15,
    *            "nickname":null,
    *            "avatar":"/mukez/public/static/admin/images/0.jpg",
    *            "integral":"6",
    *            "grade":"业余"
    *        },
    *        {
    *            "id":14,
    *            "nickname":null,
    *            "avatar":"/mukez/public/static/admin/images/0.jpg",
    *            "integral":"6",
    *            "grade":"业余"
    *        },
    *        {
    *            "id":13,
    *            "nickname":null,
    *            "avatar":"/mukez/public/static/admin/images/0.jpg",
    *            "integral":"6",
    *            "grade":"业余"
    *        }
    *    ]
    *}
     * @apiSuccessExample 成功返回示例2 季度榜
     *
     *{
     *    "code":200,
     *    "data":[
     *        {
     *            "id":19,//用户id
     *            "nickname":"11", //昵称
     *            "integral":13, //积分数
     *            "avatar":"/mukez/public/static/admin/images/0.jpg", //头像
     *            "grade":"专业", //等级 
     *        },
     *        {
     *            "id":18,
     *            "nickname":null,
     *            "integral":3,
     *            "avatar":"/mukez/public/static/admin/images/0.jpg",
     *            "grade":"业余",
     *        }
     *    ]
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
    */
    public function rank($type='1'){
        $user = $this->users;
        //前10全部积分排行榜
        $redis = new IntegralRedis('','rankList');
        $ogroup_id=$user['ogroup_id'];
        $is_independence=db('organization_group')->where('id='.$ogroup_id)->value('is_independence');
        if($is_independence=='1'){
            //独立
            $where="users.ogroup_id='".$ogroup_id."'";
            $where1="users.ogroup_id='".$ogroup_id."'";
        }else{
            $where="users.ogroup_id='".$ogroup_id."'  or ogroup.is_independence=2";
            $where1="users.ogroup_id='".$ogroup_id."' or ogroup.is_independence=2";
        }
        $filter=Db::name('video_grade_set')
                ->field('grade,setion_start,setion_end')
                ->select();
        //所有人个数
        $userCount=db('users')
                ->alias('users')
                ->field('users.id')
                ->join('mk_organization_group ogroup','users.ogroup_id=ogroup.id','left')
                ->where($where1)
                ->count();
        if($type=='1'){
            //总榜
            // $get=$redis->getLeadboard('10',true,true);
            $get=db('users')
                ->alias('users')
                ->join('mk_organization_group ogroup','users.ogroup_id=ogroup.id','left')
                ->field('users.id,users.username,users.nickname,users.truename,users.integral,users.avatar')
                ->where($where)
                ->order('users.integral desc')
                ->limit(10)
                ->select();
            if(!$get){
                $return = stripslashes(json_encode(array('code'=>200,'data'=>[]),JSON_UNESCAPED_UNICODE));
                echo $return;die();
            }
            $zongCount=db('users')
                ->alias('users')
                ->join('mk_organization_group ogroup','users.ogroup_id=ogroup.id','left')
                ->field('user.id')
                ->where($where)
                ->count();
            $count=db('users')
                ->alias('users')
                ->field('users.id')
                ->join('mk_organization_group ogroup','users.ogroup_id=ogroup.id','left')
                ->where($where1)
                ->where('users.integral','gt','0')
                ->count();
                
            
            
            foreach($get as $key=>$val){
                if(!$val['avatar']){
                    $val['avatar']='/mukez/public/static/admin/images/0.jpg';
                }else{
                    $val['avatar']=config('default_video').$val['avatar'];
                }
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
                //学习等级
                
                //百分比
                if($userCount){
                    $rate=round(($rank/$userCount)*100);
                }else{
                    $rate='0';
                }
                $val['rate']=$rate.'%';
                
                $result = self::search($rate, $filter);
                $grade=current($result)['grade'];
                $val['grade']=$grade;
                $bang_all[]=$val;
                
            }
            $return = stripslashes(json_encode(array('code'=>200,'data'=>$bang_all),JSON_UNESCAPED_UNICODE));
            echo $return;
        }else{
            //季度榜
            $jidu=$redis->jidu();
            $jidufen=Db::name('video_integral_jidu')
                ->alias('jidu')
                ->field('users.id,users.nickname,users.username,users.avatar,users.truename,jidu.integral integral,users.integral integral_all')
                ->join('mk_users users','jidu.user_id=users.id')  
                ->join('mk_organization_group ogroup','users.ogroup_id=ogroup.id','left')
                ->where($where)             
                ->where(['jidu'=>$jidu])
                ->order('jidu.integral desc')
                ->limit(10)
                ->select();
            if(!$jidufen){
                $return = stripslashes(json_encode(array('code'=>200,'data'=>[]),JSON_UNESCAPED_UNICODE));
                echo $return;die();
            }
            
            foreach($jidufen as &$val){
                if(!$val['avatar']){
                    $val['avatar']='/mukez/public/static/admin/images/0.jpg';
                }else{
                    $val['avatar']=config('default_video').$val['avatar'];
                }
                //等级
                $gaoCount=db('users')
                        ->alias('users')
                        ->field('users.id')
                        ->join('mk_organization_group ogroup','users.ogroup_id=ogroup.id','left')
                        ->where($where1)
                        ->where('users.integral','gt',$val['integral_all'])
                        ->count();
                $rank=$gaoCount+1;  //名次
              
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
            $return = stripslashes(json_encode(array('code'=>200,'data'=>$jidufen),JSON_UNESCAPED_UNICODE));
            echo $return;
        }
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

    //我的考试
    /**
     * @api {post} My/testList 我的考试
     * @apiVersion 0.1.0
     * @apiGroup My
     * @apiDescription 我的考试
     *
     * @apiParam {String} token 登录令牌 
     * @apiParam {Number} status 1未回答  2已回答
     *  
     * @apiSuccess {Number} code 状态码
     * @apiSuccess {String} data 返回结果模型
     *
     * @apiSuccessExample 成功返回示例1 未完成
     *
    *{
    *    "code":200,
    *    "data":[
    *        {
    *            "id":2,//试卷id
    *            "title":"2020年度",//试卷名称
    *            "time_start":"2019-12-19 14:48",//开始时间
    *            "time_end":"2019-12-29 14:48",//结束时间
    *            "is_end":"0"//0未结束  1已结束 
    *        }
    *    ]
    *}
    * @apiSuccessExample 成功返回示例2 已完成
    *
    *{
    *    "code":200,
    *    "data":[
    *        {
    *            "id":1,//试卷id
    *            "title":"2019年度",//试卷名称
    *            "time_start":"2019-12-19 14:48",//开始时间
    *            "time_end":"2019-12-29 14:48",//结束时间
    *            "score":"10"//分数
    *        }
    *    ]
    *}
     *
     *
     *@apiErrorExample 失败返回示例:
     *
     *{
     *
     *    "code": -1,
     *
     *    "msg": "请登录"
     *
     *}
     *
    */
    public function testList($status='1'){
        $user = $this->users;
        $time=time();
        $ogroup_id=$user['ogroup_id'];
        $data=Db::name('video_score')
                    ->alias('score')
                    ->field('test.id id,test.title,test.time_start,test.time_end,score.score,test.ogroup')
                    ->join('mk_video_test test','score.test_id=test.id')
                    ->where('score.user_id='.$user['id'])
                    ->where('test.is_show=1')
                    ->where('test.is_open=1')
                    ->order('test.createtime desc')
                    ->select();//已作答的
        if($status=='1'){
            //未作答
            $answer_yes=array_column($data, 'id');//已回答的
            if(!count($answer_yes)){
                $answer_yes=['0'=>'0'];
            }
            $data1=Db::name('video_test')
                    ->field('id,title,time_start,time_end,ogroup')
                    ->where('id','not in',$answer_yes)
                    ->where('is_show=1')
                    ->where('is_open=1')
                    ->order('createtime desc')
                    ->select();
            foreach($data1 as $key=>$val){
                if($data1[$key]['time_end']<=$time){
                    
                    $data1[$key]['is_end']='1';//已结束
                }else{
                    $data1[$key]['is_end']='0';//未结束
                } 
                $data1[$key]['time_start'] = date('Y-m-d H:i',$data1[$key]['time_start']);
                $data1[$key]['time_end'] = date('Y-m-d H:i',$data1[$key]['time_end']);
                $ogroup=explode(',',$data1[$key]['ogroup']);
                if(in_array($ogroup_id,$ogroup)){
                    $data2[]=$data1[$key];
                } 
                
            }
            $return = stripslashes(json_encode(array('code'=>200,'data'=>$data2),JSON_UNESCAPED_UNICODE));
            
        }else if($status=='2'){
            //已作答
            foreach($data as $key=>$val){
                $data[$key]['time_start'] = date('Y-m-d H:i',$data[$key]['time_start']);
                $data[$key]['time_end'] = date('Y-m-d H:i',$data[$key]['time_end']);
                $ogroup=explode(',',$data[$key]['ogroup']);
                if(in_array($ogroup_id,$ogroup)){
                    $data2[]=$data[$key];
                } 
            }
            $return = stripslashes(json_encode(array('code'=>200,'data'=>$data2),JSON_UNESCAPED_UNICODE));
        }
        echo $return;
    }
    //问题列表
    /**
     * @api {post} My/questions 问题列表
     * @apiVersion 0.1.0
     * @apiGroup My
     * @apiDescription 问题列表
     *
     * @apiParam {String} token 登录令牌 
     * @apiParam {Number} test_id 试卷id
     *  
     * @apiSuccess {Number} code 状态码
     * @apiSuccess {String} data 返回结果模型
     *
     * @apiSuccessExample 成功返回示例
     *
   *{
    *    "code":200,
    *    "data":{
    *        "title":"2019年度",//试卷名称
    *        "time_start":"2019-12-11 14:48",//开始时间
    *        "time_end":"2019-12-16 14:48",//结束时间
    *        "end_timeStamp":1576478930,//结束时间戳
    *        "questions":[//问题
    *            {
    *                "id":2,//问题id
    *                "question_title":"问题1",//题目
    *                "answer_type":2,//1单选 2 多选
    *                "option":[ //选项
    *                    {
    *                        "id":114, //选项id
    *                        "choice_text":"选项1"//选项内容
    *                    },
    *                    {
    *                        "id":115,
    *                        "choice_text":"选项2"
    *                    },
    *                    {
    *                        "id":116,
    *                        "choice_text":"选项3"
    *                    },
    *                    {
    *                        "id":117,
    *                        "choice_text":"选项4"
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
     *    "code": -2,
     *
     *    "msg": "缺少test_id"
     *
     *}
     *@apiErrorExample 失败返回示例3:
     *
     *{
     *
     *    "code": -3,
     *
     *    "msg": "该试题不存在！/该试题已过期！"
     *
     *}
     *
     * *@apiErrorExample 失败返回示例4:
     *
     *{
     *
     *    "code": -4,
     *
     *    "msg": "无权限"
     *
     *}
     * *@apiErrorExample 失败返回示例5:
     *
     *{
     *
     *    "code": -5,
     *
     *    "msg": "您已回答过该试题！"
     *
     *}
     *
    */
    public function questions($test_id=''){
        $user = $this->users;
        if(!$test_id){
            echo json_encode(array('code'=>-2,'msg'=>'缺少test_id'),JSON_UNESCAPED_UNICODE);die();
        }
        $test=db('video_test')->field('title,time_start,time_end,ogroup')->where('is_show=1')->where('is_open=1')->where('id='.$test_id)->find();
        if(!$test){
            echo json_encode(array('code'=>-3,'msg'=>'该试题不存在！'),JSON_UNESCAPED_UNICODE);die();
        }
        if($test['time_end']<=time()){
            echo json_encode(array('code'=>-3,'msg'=>'该试题已过期！'),JSON_UNESCAPED_UNICODE);die();
        }
        $score=db('video_score')->field('id')->where('user_id='.$user['id'])->where('test_id='.$test_id)->count();
        if($score){
            echo json_encode(array('code'=>-5,'msg'=>'您已回答过该试题！'),JSON_UNESCAPED_UNICODE);die();
        }
        $ogroup_id=$user['ogroup_id'];
        $test_power_arr=explode(',',$test['ogroup']);
        if(!in_array($ogroup_id,$test_power_arr)){
            echo json_encode(array('code'=>-4,'msg'=>'无权限'),JSON_UNESCAPED_UNICODE);die();
        }
        //试题
        $list=db('video_connect_test')
                ->alias('ct')
                ->field('ct.question_id id,questions.question_title,questions.answer_type')
                ->join('mk_video_questions questions','ct.question_id=questions.id')
                ->where('ct.test_id='.$test_id)
                ->select();
        foreach($list as &$val){
            $option=Db::name('video_answers')
                ->field('id,choice_text')
                ->where('is_show=1')
                ->where(['question_id'=>$val['id']])
				->order('id asc')
                ->select();
            $val['option']=$option;
        }
        
        $test['end_timeStamp']=$test['time_end'];
        $test['time_start'] = date('Y-m-d H:i',$test['time_start']);
        $test['time_end'] = date('Y-m-d H:i',$test['time_end']);
        $test['questions']=$list;
        $return = stripslashes(json_encode(array('code'=>200,'data'=>$test),JSON_UNESCAPED_UNICODE));
        echo $return;
    }
    //提交试题
     /**
    * @api {post} My/testTotals 提交试题
    * @apiVersion 0.1.0
    * @apiGroup My
    * @apiDescription 提交试题
    *
    * @apiParam {String} token 登录令牌 
    * @apiParam {Number} test_id 试卷id
    * @apiParam {Array} data 二维数组  问题为question  答案字符串为answer
    *
    * @apiSuccess {Number} code 状态码
    * @apiSuccess {String} data 返回搜索结果模型
    *
    * @apiSuccessExample 成功返回示例
    *
    *{
    *    "code":200,
    *    "data":4 //分数
    *}
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
    *@apiErrorExample 失败返回示例2:
    *
    *{
    *
    *    "code": -2,
    *
    *    "msg": "缺少test_id"
    *
    *}
    *@apiErrorExample 失败返回示例3:
    *
    *{
    *
    *    "code": -3,
    *
    *    "msg": "该试题不存在！/该试题已过期！"
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
    *@apiErrorExample 失败返回示例5:
    *
    *{
    *
    *    "code": -5,
    *
    *    "msg": "您已回答过该试题！"
    *
    *}
    *@apiErrorExample 失败返回示例6:
    *
    *{
    *
    *    "code": -6,
    *
    *    "msg": "缺少data！"
    *
    *}
    */
    public function testTotals($test_id='',$data){
      
        $user = $this->users;
        if(!$test_id){
            echo json_encode(array('code'=>-2,'msg'=>'缺少test_id'),JSON_UNESCAPED_UNICODE);die();
        }
        $test=db('video_test')->field('id,time_end,time_start,ogroup')->where('is_show=1')->where('is_open=1')->where('id='.$test_id)->find();
        if(!$test){
            echo json_encode(array('code'=>-3,'msg'=>'该试题不存在！'),JSON_UNESCAPED_UNICODE);die();
        }
        if($test['time_end']<=time()){
            echo json_encode(array('code'=>-3,'msg'=>'该试题已过期！'),JSON_UNESCAPED_UNICODE);die();
        }
        $score=db('video_score')->field('id')->where('user_id='.$user['id'])->where('test_id='.$test_id)->count();
        if($score){
            echo json_encode(array('code'=>-5,'msg'=>'您已回答过该试题！'),JSON_UNESCAPED_UNICODE);die();
        }
        if(!$data){
            echo json_encode(array('code'=>-6,'msg'=>'缺少data！'),JSON_UNESCAPED_UNICODE);die();
        }
        $ogroup_id=$user['ogroup_id'];
        $test_power_arr=explode(',',$test['ogroup']);
        if(!in_array($ogroup_id,$test_power_arr)){
            echo json_encode(array('code'=>-4,'msg'=>'无权限'),JSON_UNESCAPED_UNICODE);die();
        }
        $sum='0';
        $data=json_decode($data,true);
        // print_r($data);die();
        foreach($data as &$val){
            $question_id=$val['question'];//问题
            $answer=$val['answer'];//提交的答案 字符串
            $answer_arr=explode(',',$answer); //提交的答案 数组
            $answer_db=db('video_answers')
                        ->where('question_id='.$question_id)
                        ->where('is_show=1')
                        ->where('is_right=1')
                        ->select();
            if(!$answer_db){
                echo json_encode(array('code'=>-7,'msg'=>'数据错误，请重试！'),JSON_UNESCAPED_UNICODE);die();
            }
            $answer_db_arr=array_column($answer_db, 'id');//正确答案 数组
            $answer_db_str=implode(',',$answer_db_arr);//正确答案 字符串
            $db_count=count($answer_db_arr);
            $answer_count=count($answer_arr);
            // print_r($answer_db_arr);
            // print_r($answer_arr);die();
          
            if((!array_diff($answer_db_arr,$answer_arr))&&($db_count==$answer_count)){
                //正确
                
                $is_right='1';
                $score=db('video_connect_test')->field('score')->where('test_id='.$test_id)->where('question_id='.$val['question'])->find();
                $sum+=$score['score'];
            }else{
               
                //错误
                $is_right='0';
            }
            $data1=[
                'test_id'=>$test_id,
                'question_id'=>$question_id,
                'answer_id'=>$answer,
                'user_id'=>$user['id'],
                'is_right'=>$is_right,
                'right_answer'=>$answer_db_str,
                'answer_time'=>time()
            ];
            
            db('video_answer_history')->insert($data1);//添加答题记录
        }
        db('video_score')->insert(['user_id'=>$user['id'],'test_id'=>$test_id,'score'=>$sum,'time'=>time()]);
        //是否开启考试积分
        $score=db('video_integral_type')->field('is_open')->where('id=5')->find();
     
        if($score['is_open']=='1'&&$sum>'0'){
            
            //加分
            $list_one=[
                'type_id'=>'5',
                'test_id'=>$test_id,
                'play_time'=>time(),
                'integral'=>$sum,
                'user_id'=>$user['id'],
                'time'=>time()
            ];
            $users=db('users')->field('integral')->where('id='.$user['id'])->find();
            $result=db('video_integral')->insert($list_one);
            $integral=$users['integral']+$sum;
            db('users')->where('id='.$user['id'])->update(['integral'=>$integral]);
            //redis积分
            $redis = new IntegralRedis('','rankList');
            // $redis->addNode($user['id'],$sum);
            //季度
            $isJidu=$redis->isJidu();
            
            if($isJidu){
                //季度积分已开启
                $time=time();
                $jidu=$redis->jidu($time);
                $is_user=$redis->isUser($user['id'],$jidu);
                
                if($is_user){
                    //如果有这条数据  添加积分
                    $jidujf=$is_user['integral']+$sum;
                    db('video_integral_jidu')->where('id='.$is_user['id'])->update(['integral'=>$jidujf,'update_time'=>time()]);
                }else{
                    //插入数据
                    db('video_integral_jidu')->insert(['user_id'=>$user_id,'integral'=>$sum,'create_time'=>time(),'jidu'=>$jidu]);
                }
            }
        }
        $return = stripslashes(json_encode(array('code'=>200,'data'=>$sum),JSON_UNESCAPED_UNICODE));
        echo $return;
    }
    //答题详情
    /**
    * @api {post} My/testDetails 答题详情
    * @apiVersion 0.1.0
    * @apiGroup My
    * @apiDescription 答题详情
    *
    * @apiParam {String} token 登录令牌 
    * @apiParam {Number} test_id 试卷id
    *
    * @apiSuccess {Number} code 状态码
    * @apiSuccess {String} data 返回搜索结果模型
    *
    * @apiSuccessExample 成功返回示例
    *
    *{
    *    "code":200,
    *    "data":{
    *        "id":1,//试卷id
    *        "title":'2019年度考试',//试卷名称
    *        "time_end":"2019-12-16 14:48",//开始时间
    *        "time_start":"2019-12-11 14:48",//结束时间
    *        "questions":[ //问题
    *            {
    *                "id":1, //问题id
    *                "question_title":"问题1问题1", //问题题目
    *                "answer_type":2,  //1单选  2多选
    *                "option":[
    *                    {
    *                        "id":164,  //选项id
    *                        "choice_text":"选项1"  //选项内容
    *                        "is_right":'1'//正确 0 错误
    *                        "is_xuan":'1'//选了 0 没选
    *                    },
    *                    {
    *                        "id":165,
    *                        "choice_text":"选项2"
    *                        "is_right":'1'//正确 0 错误
    *                        "is_xuan":'1'//选了 0 没选
    *                    },
    *                    {
    *                        "id":166,
    *                        "choice_text":"选项3"
    *                        "is_right":'1'//正确 0 错误
    *                        "is_xuan":'1'//选了 0 没选
    *                    },
    *                    {
    *                        "id":167,
    *                        "choice_text":"选项4"
    *                        "is_right":'1'//正确 0 错误
    *                        "is_xuan":'1'//选了 0 没选
    *                    }
    *                ],
    *            },
    *            {
    *                "id":2,
    *                "question_title":"问题2问题2",
    *                "answer_type":2,
    *                "option":[
    *                    {
    *                        "id":114,
    *                        "choice_text":"选项1"
    *                        "is_right":'1'//正确 0 错误
    *                        "is_xuan":'1'//选了 0 没选
    *                    },
    *                    {
    *                        "id":115,
    *                        "choice_text":"选项2"
    *                        "is_right":'1'//正确 0 错误
    *                        "is_xuan":'1'//选了 0 没选
    *                    },
    *                    {
    *                        "id":116,
    *                        "choice_text":"选项3"
    *                        "is_right":'1'//正确 0 错误
    *                        "is_xuan":'1'//选了 0 没选
    *                    },
    *                    {
    *                        "id":117,
    *                        "choice_text":"选项4"
    *                        "is_right":'1'//正确 0 错误
    *                        "is_xuan":'1'//选了 0 没选
    *                    }
    *                ],
    *            }
    *        ]
    *    }
    *}
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
    *@apiErrorExample 失败返回示例2:
    *
    *{
    *
    *    "code": -2,
    *
    *    "msg": "缺少test_id"
    *
    *}
    *@apiErrorExample 失败返回示例3:
    *
    *{
    *
    *    "code": -3,
    *
    *    "msg": "该试题不存在！"
    *
    *}
    */
    public function testDetails($test_id=''){
        $user = $this->users;
        if(!$test_id){
            echo json_encode(array('code'=>-2,'msg'=>'缺少test_id'),JSON_UNESCAPED_UNICODE);die();
        }
        $test=db('video_test')->field('id,title,time_end,time_start')->where('is_show=1')->where('is_open=1')->where('id='.$test_id)->find();
        if(!$test){
            echo json_encode(array('code'=>-3,'msg'=>'该试题不存在！'),JSON_UNESCAPED_UNICODE);die();
        }
        
         //试题
         $list=db('video_connect_test')
            ->alias('ct')
            ->field('ct.question_id id,questions.question_title,questions.answer_type')
            ->join('mk_video_questions questions','ct.question_id=questions.id')
            ->where('ct.test_id='.$test_id)
            ->select();
        $score=db('video_score')->field('id')->where('user_id='.$user['id'])->find();
       
        foreach($list as &$val){
            $option=Db::name('video_answers')
                ->field('id,choice_text,is_right')
                ->where('is_show=1')
                ->where('question_id='.$val['id'])
                ->order('id asc')
                ->select();
            $result=db('video_answer_history')
                ->field('answer_id,right_answer,is_right')
                ->where('test_id='.$test_id)
                ->where('question_id='.$val['id'])
                ->where('user_id='.$user['id'])
                ->find();
            $answer=explode(',',$result['answer_id']);
            foreach($option as &$value){
                if($score){
                    if(in_array($value['id'],$answer)){
                        $value['is_xuan']='1';
                    }else{
                        $value['is_xuan']='0';
                    }
                }else{
                    $value['is_xuan']='0';
                }
                
            }
           
            
            $val['option']=$option;
          
        }
        
        $test['time_start'] = date('Y-m-d H:i',$test['time_start']);
        $test['time_end'] = date('Y-m-d H:i',$test['time_end']);
        $test['questions']=$list;
        $return = stripslashes(json_encode(array('code'=>200,'data'=>$test),JSON_UNESCAPED_UNICODE));
        echo $return;
    }

    //我的作业
     /**
    * @api {post} My/myWork 我的作业
    * @apiVersion 0.1.0
    * @apiGroup My
    * @apiDescription 我的作业
    *
    * @apiParam {String} token 登录令牌 
    * @apiParam {Number} status 1 全部 2已提交  3未提交
    *
    * @apiSuccess {Number} code 状态码
    * @apiSuccess {String} data 返回搜索结果模型
    *
    * @apiSuccessExample 成功返回示例
    *
    *{
    *    "code":200,
    *    "data":[
    *        {
    *            "work_id":6, //作业id
    *            "worktitle":"11111", //作业标题
    *            "video_id":11, //课程id
    *            "video_title":"4", //课程标题
    *            "upload_time":2020-01-03 14:20, //提交时间
    *            "score":1, //积分
    *            "work_status":2 //状态 1待审核 2已审核 3未提交
    *        },
    *        {
    *            "work_id":7,
    *            "worktitle":"ces",
    *            "video_id":11,
    *            "video_title":"4",
    *            "upload_time":2020-01-03 14:20,
    *            "score":0,
    *            "work_status":1
    *        }
    *    ]
    *}
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
    */
    public function myWork($status='1'){
        $user = $this->users;
        $user_id=$user['id'];
        if($status=='2'){
            //已提交
            $where='(work.work_status=1 or work.work_status=2)';
        }else if($status=='3'){
            //暂存
            $where='work.work_status=3';
        }
        $data=db('video_text_work')
                ->alias('work')
                ->field('work.id work_id,work.worktitle,video.id video_id,video.title video_title,work.upload_time,work.score,work.work_status')
                ->join('mk_video_video video','work.video_id=video.id')
                ->where('work.user_id='.$user_id)
                ->where($where)
                ->order('work.upload_time desc')
                ->select();
        
        foreach ($data as $k=>$v){
            $data[$k]['upload_time'] = date('Y-m-d H:i',$v['upload_time']);
        }
        $return = stripslashes(json_encode(array('code'=>200,'data'=>$data),JSON_UNESCAPED_UNICODE));
        echo $return;
    }

    //我的作业详情
     /**
    * @api {post} My/myWorkDetails 我的作业详情
    * @apiVersion 0.1.0
    * @apiGroup My
    * @apiDescription 我的作业详情
    *
    * @apiParam {String} token 登录令牌 
    * @apiParam {Number} work_id 作业id
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
    *        "work_status":2,//状态 1未审核 2已审核
    *        "score":1, //获得积分
    *        "upload_time":"2020-01-03 14:27" //提交时间
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
    *    "msg": "缺少work_id/该作业不存在！"
    *
    *}
    */
    public function myWorkDetails($work_id=''){
        $user = $this->users;
        $user_id=$user['id'];
        if(!$work_id){
            echo json_encode(array('code'=>0,'msg'=>'缺少work_id'),JSON_UNESCAPED_UNICODE);die();
        }
        $data=db('video_text_work')
                ->field('worktitle,workcontent,work_status,score,upload_time')
                ->where('id='.$work_id)
                ->find();
        if(!$data){
            echo json_encode(array('code'=>0,'msg'=>'该作业不存在！'),JSON_UNESCAPED_UNICODE);die();
        }else{
            $data['upload_time'] = date('Y-m-d H:i',$data['upload_time']);
            $data['workcontent']=str_replace("\"","'",$data['workcontent']);
            $return = stripslashes(json_encode(array('code'=>200,'data'=>$data),JSON_UNESCAPED_UNICODE));
            echo $return;
        }
        
            
    }
    

}