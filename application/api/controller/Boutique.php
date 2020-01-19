<?php
namespace app\api\controller;

use think\Controller;
use think\Db;
use think\validate;
use think\facade\Session;

class Boutique extends Common{
    // public function initialize(){
    //     parent::initialize();
    // }
    /**
     * @api {post} Boutique/courseIndex 精品课程
     * @apiVersion 0.1.0
     * @apiGroup Boutique
     * @apiDescription 精品课程
     *
     * @apiParam {String} token 登录令牌
     * @apiParam {String} search 搜索关键词  可选
     * @apiParam {Number} type_id 类型id  可选
     * @apiParam {Number} sort 排序方式（1为按时间排序 2为按热度排序）可选
     *
     * @apiSuccess {Number} code 状态码
     * @apiSuccess {String} data 返回结果模型
     *
     * @apiSuccessExample 成功返回示例1
    *{
    *    "code": 200, 
    *    "data": {
    *        "search": "课程", //搜索词
    *        "count": 1, //课程数
    *        "course": [
    *            {
    *                "id": 6, //课程id
    *                "title": "课程6", //课程标题
    *                "duration": "00:10", //视频时长
    *                "video_img": "/muke/public/uploads/20191121/f49147ef4112f3430afe0f7cd6647fba.jpeg", //缩略图
    *                "type": "社区治理", //类型
    *                "teacher_name": "讲师1",//讲师姓名
    *                "course_type":1//1视频课程  2图文课程
    *            }
    *        ]
    *    }
    *}
    *
    * @apiSuccessExample 成功返回示例2
    *{
    *    "code": 404, 
    *    "msg":"抱歉，未搜索到相关课程！",
    *    "data": {
    *        "search": "课程", //搜索词
    *        "count": 0, //课程数
    *    }
    *}
    *
    *
     *
     */
    public function courseIndex($search='',$type_id='',$sort=''){
       $where=1;
       if($type_id){
        //有分类
            $type_count=db('video_type')
                ->where('id='.$type_id)
                ->where('fid=0')
                ->where('is_show=1')
                ->count();
            //大分类
            if($type_count){
                $where.=" and type.fid='".$type_id."' or video.type_id='".$type_id."' and type.is_show=1";
            }else{
                //小分类
                $where.=" and video.type_id='".$type_id."' and type.is_show=1";
            } 
        }
        if($search){
            //检索tag
            $tag=db('video_tag')->field('id,tag')->where(['tag'=>$search])->find();
            if($tag){
                $course_ids=db('video_connect_tag')->field('video_id')->where('tag_id='.$tag['id'])->select();
                if($course_ids){
                    $course_ids = array_column($course_ids, 'video_id');
                    $course_ids = implode(',',$course_ids);
                    $where.=" and (video.title like '%".$search."%' or video.id in (".$course_ids.") ) ";
                }else{
                    $where.=" and video.title like '%".$search."%' ";
                }
                
            }else{
                $where.=" and video.title like '%".$search."%' ";
            }
           
        }
        //排序
        if($sort=='2'){
            $video=db('video_video')
            ->alias('video')
            ->field('video.id,video.title,video.duration,video.video_img,type.type,teacher.teacher_name,video.course_type')
            ->join('mk_video_type type','video.type_id=type.id')
            ->join('mk_video_teacher teacher','video.teacher_id=teacher.id')
            ->where($where)
            ->where('video.is_show=1')
            ->order('video.view_number desc')
            ->select();
        }else{
            $video=db('video_video')
            ->alias('video')
            ->field('video.id,video.title,video.duration,video.video_img,type.type,teacher.teacher_name,video.course_type')
            ->join('mk_video_type type','video.type_id=type.id')
            ->join('mk_video_teacher teacher','video.teacher_id=teacher.id')
            ->where($where)
            ->where('video.is_show=1')
            ->order('video.upload_time desc')
            ->select();
        }
        foreach($video as &$val){
            $val['video_img']=config('default_video').$val['video_img'];
        }
        $count=count($video);
        $data['search']=$search;
        $data['count']=$count;
        $data['course']=$video;
        $data1['search']=$search;
        $data1['count']=$count;
        
        if($count){
            $return = stripslashes(json_encode(array('code'=>200,'data'=>$data),JSON_UNESCAPED_UNICODE));
        }else{
            $return = stripslashes(json_encode(array('code'=>404,'msg'=>'抱歉，未搜索到相关课程！','data'=>$data1),JSON_UNESCAPED_UNICODE));
        }
    
        echo $return;
    }
}