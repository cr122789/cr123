<?php
namespace app\api\controller;

use think\Controller;
use think\Db;

use think\validate;
use think\facade\Session;
use Qiniu\Auth;
use Qiniu\Storage\UploadManager;

class Index extends Controller{
	
    public function initialize(){
		header('content-type:text/html;charset=utf-8');
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
        header('Access-Control-Allow-Methods: GET, POST, PUT');
        parent::initialize();
    }
    /**
     * @api {post} Index/courseIndex 首页课程
     * @apiVersion 0.1.0
     * @apiGroup Index
     * @apiDescription 首页课程
     *
     *
      * @apiSuccess {Number} code 状态码
     * @apiSuccess {String} data 返回结果模型
     *
     * @apiSuccessExample 成功返回示例
	*
	*{
	*    "code": 200,
	*    "data": {
	*        "new": [ //最新课程
	*            {
	*                "id": 7, //课程id
	*                "title": "测试tag课程", //课程标题
	*                "duration": "", //视频时长
	*                "video_img": "", //缩略图
	*                "type": "基础知识", //课程类型
	*                "teacher_name": "讲师1", //讲师姓名
	*                "course_type": "1"  // 1 视频课程 2 图文课程
	*            },
	*            {
	*                "id": 6,
	*                "title": "课程6",
	*                "duration": "00:10",
	*                "video_img": "/muke/public/uploads/20191121/f49147ef4112f3430afe0f7cd6647fba.jpeg",
	*                "type": "社区治理",
	*                "teacher_name": "讲师1"
	*                "course_type": "1" 
	*            },
	*            {
	*                "id": 5,
	*                "title": "课程5",
	*                "duration": null,
	*                "video_img": "/muke/public/uploads/20191121/aafb91729f8f1622384028da6c328eef.jpeg",
	*                "type": "政策解读",
	*                "teacher_name": "讲师1"
	*                "course_type": "1" 
	*            },
	*            {
	*                "id": 4,
	*                "title": "课程3",
	*                "duration": null,
	*                "video_img": "/muke/public/uploads/20191121/de6f951f399540b8b1031d3a6b7da6f0.jpg",
	*                "type": "政策解读",
	*                "teacher_name": "讲师1"
	*                "course_type": "1" 
	*            }
	*        ],
	*        "all": [  //所有类型及课程
	*            {  
	*                "id": 1, //类型id
	*                "type": "全能社工", //课程类型（一级）
	*                "fid": 0,
	*                "data": [
	*                    {
	*                        "id": 7, //课程id
	*                        "title": "测试tag课程", //课程标题
	*                        "duration": "", //时长
	*                        "video_img": "", //缩略图
	*                        "type": "基础知识",  //课程类型（二级）
	*                        "teacher_name": "讲师1" //讲师姓名
	*                		 "course_type": "1" 
	*                    },
	*                    {
	*                        "id": 6,
	*                        "title": "课程6",
	*                        "duration": "00:10",
	*                        "video_img": "/muke/public/uploads/20191121/f49147ef4112f3430afe0f7cd6647fba.jpeg",
	*                        "type": "社区治理",
	*                        "teacher_name": "讲师1"
	*                		 "course_type": "1" 
	*                    },
	*                    {
	*                        "id": 5,
	*                        "title": "课程5",
	*                        "duration": null,
	*                        "video_img": "/muke/public/uploads/20191121/aafb91729f8f1622384028da6c328eef.jpeg",
	*                        "type": "政策解读",
	*                        "teacher_name": "讲师1"
	*                		 "course_type": "1" 
	*                    },
	*                    {
	*                        "id": 4,
	*                        "title": "课程3",
	*                        "duration": null,
	*                        "video_img": "/muke/public/uploads/20191121/de6f951f399540b8b1031d3a6b7da6f0.jpg",
	*                        "type": "政策解读",
	*                        "teacher_name": "讲师1"
	*                		 "course_type": "1" 
	*                    }
	*                ]
	*            },
	*            {
	*                "id": 6,
	*                "type": "领头雁",
	*                "fid": 0,
	*                "data": []
	*            }
	*        ]
	*    }
	*}
	*
    *
    *
    */
	public function courseIndex(){	
		//最新课程
		$new=Db::name('video_video')
			->alias('video')
			->field('video.id,video.title,video.duration,video.video_img,type.type,teacher.teacher_name,video.course_type')
			->join('mk_video_type type','video.type_id=type.id')
			->join('mk_video_teacher teacher','video.teacher_id=teacher.id')
			->where('video.is_show=1')
			->order('upload_time desc')
			->limit(4)
			->select();
		
		foreach($new as &$val){
			$val['video_img']=config('default_video').$val['video_img'];
		}
       
		//课程分类及视频
		$type=db('video_type')
    			->where('fid=0')
    			->where('is_show=1')
    			->order('id asc')
    			->limit(5)
    			->select();
    	foreach($type as &$val){
    		$video=db('video_video')
    				->alias('video')
    				->field('video.id,video.title,video.duration,video.video_img,type.type,teacher.teacher_name,video.course_type')
    				->join('mk_video_type type','video.type_id=type.id')
    				->join('mk_video_teacher teacher','video.teacher_id=teacher.id')
    				->where('type.fid='.$val['id'].' or video.type_id='.$val['id'].'')
    				->where('video.is_show=1')
    				->order('video.upload_time desc')
    				->limit(4)
    				->select();
			foreach($video as &$vall){
				$vall['video_img']=config('default_video').$vall['video_img'];
			}
    		$val['data']=$video;
    	}
    	$data['new']=$new;
    	$data['all']=$type;
    	$return = stripslashes(json_encode(array('code'=>200,'data'=>$data),JSON_UNESCAPED_UNICODE));
    	echo $return;

	}

	//轮播图
	 /**
     * @api {post} Index/ad 轮播图
     * @apiVersion 0.1.0
     * @apiGroup Index
     * @apiDescription 轮播图
     *
     *
      * @apiSuccess {Number} code 状态码
     * @apiSuccess {String} data 返回结果模型
     *
     * @apiSuccessExample 成功返回示例
	*{
	*    "code": 200, 
	*    "data": [
	*        {
	*            "name": "社工基础知识从0到100",  //广告名称
	*            "pic": "/muke/public/uploads/20191126/743c1818a41ed24314cb0d222e84a4d9.jpg", //轮播图
	*            "parm_id": "2", //参数id
	*            "parm_type_id": "2",  //广告类型id  1 视频课程广告 2图文类型广告 3单页广告
	*        }, 
	*        {
	*            "name": "社区慕课正式开课了", 
	*            "pic": "/muke/public/uploads/20191126/20b685315104dfb23622fe9e333e6f1e.jpg",
	*            "parm_id": "3",
	*            "parm_type_id": "1",
	*        }, 
	*        {
	*            "name": "快来注册吧", 
	*            "pic": "/cltphp/public/uploads/20180611/814e5f76ef5dce49dfd3dce771631ecf.png",
	*            "parm_id": "4",
	*            "parm_type_id": "3",
	*        }
	*    ]
	*}
    *
     *
     */
	public function ad(){
		$adList = Db::name('ad')->field('name,pic,parm_id,parm_type_id')->where(['type_id'=>1,'open'=>1])->order('sort asc')->select();
		foreach($adList as $key=>$val){
			$adList[$key]['pic']=config('default_video').$adList[$key]['pic'];
		}
		$return = stripslashes(json_encode(array('code'=>200,'data'=>$adList),JSON_UNESCAPED_UNICODE));
    	echo $return;
	}
 /**
     * @api {post} Index/link 友情链接
     * @apiVersion 0.1.0
     * @apiGroup Index
     * @apiDescription 友情链接
     *
     *
      * @apiSuccess {Number} code 状态码
     * @apiSuccess {String} data 返回结果模型
     *
     * @apiSuccessExample 成功返回示例
	*{
	*    "code":200,
	*    "data":[
	*        {
	*            "name":"CLTPHP",
	*            "url":"http://www.cltphp.com/"
	*        },
	*        {
	*            "name":"CLTPHP内容管理系统",
	*            "url":"http://www.cltphp.com/"
	*        },
	*        {
	*            "name":"CLTPHP动态",
	*            "url":"http://www.cltphp.com/news-49.html"
	*        },
	*        {
	*            "name":"关于我们",
	*            "url":"http://cltphp.com/about-8.html"
	*        },
	*        {
	*            "name":"CLTPHP相关知识",
	*            "url":"http://cltphp.com/news-51.html"
	*        }
	*    ]
	*}
    *
     *
     */
	public function link(){
		$linkList = Db::name('link')->field('name,url')->where('open',1)->order('sort asc')->select();
		$return = stripslashes(json_encode(array('code'=>200,'data'=>$linkList),JSON_UNESCAPED_UNICODE));
    	echo $return;
	}
	//网站基本信息
	/**
     * @api {post} Index/system 网站基本信息
     * @apiVersion 0.1.0
     * @apiGroup Index
     * @apiDescription 网站基本信息
     *
     *
      * @apiSuccess {Number} code 状态码
     * @apiSuccess {String} data 返回结果模型
     *
     * @apiSuccessExample 成功返回示例
	*{
	*    "code":200,
	*    "data":{
	*        "name":"社区慕课", //网站名称
	*        "url":"http://cltshow.test/", //网站地址
	*        "title":"社区慕课", //seo标题
	*        "key":"社区慕课，社区，教育", //seo关键词
	*        "des":"中国最大的社区教育网站。", //seo描述
	*        "bah":"京ICP备12003892号-11",//备案号
	*        "copyright":"2019", //版权
	*        "logo":"/muke/public/uploads/20191126/7081b0965714d780f26695d630f5866e.jpeg", //logo
	*        "company":"北京XX文化有限公司" //公司名称
	*        "search":"请输入搜索课程关键词" //搜索栏内容
	*    }
	*}
     *
     */
	public function system(){
		$system = Db::name('system')->field('name,url,title,key,des,bah,copyright,logo,company,search')->find();
		$return = stripslashes(json_encode(array('code'=>200,'data'=>$system),JSON_UNESCAPED_UNICODE));
    	echo $return;
	}

	//栏目列表
	//栏目列表
	/**
     * @api {post} Index/column 栏目列表
     * @apiVersion 0.1.0
     * @apiGroup Index
     * @apiDescription 栏目列表
     *
     *
      * @apiSuccess {Number} code 状态码
     * @apiSuccess {String} data 返回结果模型
     *
     * @apiSuccessExample 成功返回示例
	*{
	*    "code":200,
	*    "data":[
	*        {
	*            "id":1,//一级栏目id
	*            "column_name":"关于我们",//栏目名称
	*            "url":"/about.html",//链接
	*            "seo_title":"1",//seo标题
	*            "seo_key":"1",//seo关键词
	*            "seo_des":"2",//seo描述
	*            "is_page":1,//是否是内容单页 1是 2不是
	*            "column_two":[
	*                {
	*                    "id":2,//二级栏目id
	*                    "column_name":"联系我们",
	*                    "url":"#",
	*                    "seo_title":"1",
	*                    "seo_key":"1",
	*                    "seo_des":"1",
	*                    "is_page":0
	*                }
	*            ]
	*        },
	*        {
	*            "id":4,
	*            "column_name":"讲师招募",
	*            "url":"/join.html",
	*            "seo_title":"讲师招募",
	*            "seo_key":"讲师招募",
	*            "seo_des":"讲师招募",
	*            "is_page":0,
	*            "column_two":[
	*
	*            ]
	*        }
	*    ]
	*}
     *
     */
	public function column(){
		$data=db('column')
				->field('id,column_name,url,seo_title,seo_key,seo_des,is_page')
				->where('is_show=1')
				->where('fid=0')
				->order('sort asc')
				->select();
		foreach($data as &$val){
			$data_1=db('column')
					->field('id,column_name,url,seo_title,seo_key,seo_des,is_page')
					->where('is_show=1')
					->where('fid='.$val['id'])
					->order('sort asc')
					->select();
			$val['column_two']=$data_1;
		}
		$return = stripslashes(json_encode(array('code'=>200,'data'=>$data),JSON_UNESCAPED_UNICODE));
    	echo $return;
	}

	//栏目单页内容
	/**
     * @api {post} Index/page 单页内容
     * @apiVersion 0.1.0
     * @apiGroup Index
     * @apiDescription 单页内容
     * @apiParam {Number} column_id 栏目id
     *
     * @apiSuccess {Number} code 状态码
     * @apiSuccess {String} data 返回结果模型
     *
     * @apiSuccessExample 成功返回示例
	 *
	 *{
	 *    "code":200,
	 *    "data":{
	 *        "id":3,
	 *        "title":"关于我们",
	 *        "content":"<p style="text-align: center;">哈哈哈</p><p><img src="/muke/public/uploads/ueditor/image/20191129/1575010955567804.jpg"    title="1575010955567804.jpg" alt="2.jpg"/></p>"
	 *    }
	 *}
     *
     *@apiErrorExample 失败返回示例1:
     *
     *{
     *
     *    "code": 0,
     *
     *    "msg": "缺少column_id"
     *
     *}
     *@apiErrorExample 失败返回示例2:
     *
     *{
     *
     *    "code": -2,
     *
     *    "msg": "column_id错误"
     *
     *}
     */
	public function page($column_id=''){
		if(!$column_id){
            echo json_encode(array('code'=>0,'msg'=>'缺少column_id'),JSON_UNESCAPED_UNICODE);die();
        }
		$page=db('pages')->field('id,title,content')->where('column_id='.$column_id)->find();
		$page['content']=str_replace("\"","'",$page['content']);
        if(!$page){
        	echo json_encode(array('code'=>-2,'msg'=>'column_id错误'),JSON_UNESCAPED_UNICODE);die();
        }
        $return = stripslashes(json_encode(array('code'=>200,'data'=>$page),JSON_UNESCAPED_UNICODE));
        echo $return;
	}

	//广告单页内容
	/**
     * @api {post} Index/pages 广告单页内容
     * @apiVersion 0.1.0
     * @apiGroup Index
     * @apiDescription 广告单页内容
     *
     * @apiParam {Number} id 单页id
	 * 
     * @apiSuccess {Number} code 状态码
     * @apiSuccess {String} data 返回结果模型
     *
     * @apiSuccessExample 成功返回示例
	 *
	 *{
	 *    "code":200,
	 *    "data":{
	 *        "id":3,
	 *        "title":"关于我们",
	 *        "content":"<p style="text-align: center;">哈哈哈</p><p><img src="/muke/public/uploads/ueditor/image/20191129/1575010955567804.jpg"    title="1575010955567804.jpg" alt="2.jpg"/></p>"
	 * 		  "time":"2019-12-23 15:41"//发布时间   
	 * 		}
	 *}
     *
     *@apiErrorExample 失败返回示例1:
     *
     *{
     *
     *    "code": 0,
     *
     *    "msg": "缺少id"
     *
     *}
     *@apiErrorExample 失败返回示例2:
     *
     *{
     *
     *    "code": -2,
     *
     *    "msg": "id错误"
     *
     *}
     */
	public function pages($id=''){
		if(!$id){
            echo json_encode(array('code'=>0,'msg'=>'缺少d'),JSON_UNESCAPED_UNICODE);die();
        }
		$page=db('pages')->field('id,title,content,time')->where('id='.$id)->find();
		$page['content']=str_replace("\"","'",$page['content']);
        if(!$page){
        	echo json_encode(array('code'=>-2,'msg'=>'id错误'),JSON_UNESCAPED_UNICODE);die();
		}
		$page['time'] = date('Y-m-d H:i',$page['time']);
        $return = stripslashes(json_encode(array('code'=>200,'data'=>$page),JSON_UNESCAPED_UNICODE));
        echo $return;
	}

	  //上传头像
    /**
     * @api {post} Index/upFiles 上传头像
     * @apiVersion 0.1.0
     * @apiGroup Index
     * @apiDescription 上传头像
     *
     * @apiParam {String} head_img 上传图片 
     * @apiSuccess {Number} code 状态码
     * @apiSuccess {String} data 返回结果模型
     *
     * @apiSuccessExample 成功返回示例
     *
     *{
     *    "code":200,
     *    "msg","图片上传成功",
     *    "url":"/cltphp/public/uploads/20180613/fcb729987d8e9339bd9b2e85c85f3028.jpg"
     *}
     *
     *
    
     *@apiErrorExample 失败返回示例1:
     *
     *{
     *
     *    "code": 0,
     *      
     *    "msg": "上传失败，请重试！"
     *
     *}
     *@apiErrorExample 失败返回示例2:
     *
     *{
     *
     *    "code": -2,
     *      
     *    "msg": "请选择要上传的图片"
     *
     *}
     *@apiErrorExample 失败返回示例3:
     *
     *{
     *
     *    "code": -3,
     *      
     *    "msg": "上传图片过大！"
     *
     *}
     *
    */
    public function upFiles(){
        // 获取上传文件表单字段名
      	// print_r($_FILES);die();
        if(!request()->file()){
			$result['code'] = -2;
			$result['data'] = input('post.');
            $result['msg'] = '请选择要上传的图片!';
            echo stripslashes(json_encode($result,JSON_UNESCAPED_UNICODE));die();
        }else{
            $fileKey = array_keys(request()->file());
            // 获取表单上传文件
            $file = request()->file($fileKey['0']);
            // 移动到框架应用根目录/muke/public/uploads/ 目录下
            $info1=$file->getInfo();
            if($info1['size']>1*1024*1024){
                $result['code'] =-3;
                $result['msg'] =  '上传图片过大！';
                $result['url'] = '';
                echo stripslashes(json_encode($result,JSON_UNESCAPED_UNICODE));die();
            }
            $info = $file->getInfo();
            if($info){
				$filePath =  $info['tmp_name'];
				$bucket = 'swpedu';
				$accessKey = 'MsUTHp5JH9_5Bjoie6_wsNS2NoqxkqrvcshuYA_g';
				$secretKey = 'Givh3XA9wjLkEY6twzTav651sZMxjOOKD4V4J0Kt';
				$auth = new Auth($accessKey, $secretKey);//实例化
				$upToken = $auth->uploadToken($bucket);
				$token = $auth->uploadToken($bucket);
				$uploadMgr = new UploadManager();
				$key = time().'.png';  
				list($ret, $err) = $uploadMgr->putFile($token, $key, $filePath);
				if ($err !== null) {  
					$result['code'] =0;
					$result['msg'] =  $info['error'];
					$result['url'] = '';
					echo stripslashes(json_encode($result,JSON_UNESCAPED_UNICODE));die();
				} else{
					$result['code'] = 200;
					$result['msg'] = '图片上传成功!';
					$result['url'] = config('default_video').$ret['key'];
					echo stripslashes(json_encode($result,JSON_UNESCAPED_UNICODE));die();
				}
            }else{
                // 上传失败获取错误信息

                $result['code'] =0;
                $result['msg'] =  '图片上传失败！';
                $result['url'] = '';
                echo stripslashes(json_encode($result,JSON_UNESCAPED_UNICODE));die();
            }
        }
        
    }
    /**
     * @api {post} Index/courseType 精品课程课程分类
     * @apiVersion 0.1.0
     * @apiGroup Index
     * @apiDescription 精品课程课程分类
     *
     *
     * @apiSuccess {Number} code 状态码
     * @apiSuccess {String} data 返回结果模型
     *
     * @apiSuccessExample 成功返回示例
	*
	*{
    *    "code": 200, 
    *    "data": [
    *        {
    *            "id": 1, 
    *            "type": "全能社工", 
    *            "fid": 0, 
    *            "has_two": "1",//有二级分类 0 没有     
    *            "has_video":"1",//有视频 1  没有 0
    *            "data": [
    *                {
    *                    "id": 2, 
    *                    "type": "基础知识"
    *                }, 
    *                {
    *                    "id": 3, 
    *                    "type": "政策解读"
    *                }, 
    *                {
    *                    "id": 4, 
    *                    "type": "社区实务"
    *                }, 
    *                {
    *                    "id": 5, 
    *                    "type": "社区治理"
    *                }
    *            ]
    *        }, 
    *        {
    *            "id": 6, 
    *            "type": "领头雁", 
    *            "fid": 0, 
    *            "has_two": "1",
    *            "has_video":"1",//有视频 1  没有 0
    *            "data": [
    *                {
    *                    "id": 7, 
    *                    "type": "领导力培训"
    *                }
    *            ]
    *        }
    *    ]
    *}
    *
    *
    *
    */
    public function courseType(){
    	$type=db('video_type')
    			->where('fid=0')
                ->where('is_show=1')
				->order('sort asc')
    			->select();
        
    	foreach($type as &$val){
    		$type_two=db('video_type')
    				->field('id,type')
    				->where('fid='.$val['id'])
                    ->where('is_show=1')
    				->order('sort asc')
    				->select();
            $is_fu_video=db('video_video')
                ->where('type_id='.$val['id'])
                ->find();
            if($is_fu_video){
                $val['has_video']='1';
            }else{
                $val['has_video']='0';
            }
            if($type_two){
                $a='0';
                $b='0';
                foreach($type_two as &$vall){
                    $is_zi_video=db('video_video')
                        ->where('type_id='.$vall['id'])
                        ->find();
                    if($is_zi_video){
                        $a+=1;
                        $b+=1;
                    }
                    if($b!='0'){
                        $vall['has_video']='1';
                    }else{
                        $vall['has_video']='0';
                    }
                    $b='0';
                }
                if($a!='0'||$val['has_video']=='1'){
                        $val['has_video']='1';
                    }else{
                        $val['has_video']='0';
                    }
                $val['data']=$type_two;
                $val['has_two']='1';
            }else{
                
                $val['data']=[];
                $val['has_two']='0';

                
            }

    		
    	}
    	$return = stripslashes(json_encode(array('code'=>200,'data'=>$type),JSON_UNESCAPED_UNICODE));
    	echo $return;
    }
}