<?php
namespace app\admin\controller;
use think\facade\Request;

use app\admin\model\Survey as SurveyModel;
class Survey extends Common{
    //问卷列表
    public function index(){
        if(request()->isPost()){
            $key=input('post.key');
            $page =input('page')?input('page'):1;
            $pageSize =input('limit')?input('limit'):config('pageSize');
            $list=db('survey_names')
                ->field('*')
                ->where('title','like',"%".$key."%")
                ->order('id desc')
                ->paginate(array('list_rows'=>$pageSize,'page'=>$page))
                ->toArray();
            foreach ($list['data'] as $k=>$v){
                $list['data'][$k]['updatetime'] = date('Y年m月d日',$v['updatetime']);
                $list['data'][$k]['question_num'] = db('survey_questions')->where(['survey_id'=>$v['id']])->count();
            }
            return $result = ['code'=>0,'msg'=>'获取成功!','data'=>$list['data'],'count'=>$list['total'],'rel'=>1];
        }
        return $this->fetch();
    }

    public function add(){
        if(request()->isPost()){
            $post = Request::except('file');
            $list = [
                'title' => $post['title'],
                'question_score' => $post['question_score'],
                'sort' => $post['sort'],
                'admin_id' => session('aid'),
                'title_bg' => $post['title_bg'],
                'createtime' => time(),
                'updatetime' => time()
            ];
            $res = db('survey_names')->insert($list);
            if($res){
                return ['code'=>1,'msg'=>'添加成功!','url'=>url('index')];
            }else{
                return ['code'=>0,'msg'=>'添加失败!','url'=>url('index')];
            }
        }else{
            $this->assign('info','null');
            $this->assign('title','创建问卷');
            return $this->fetch('form');
        }
    }

    public function edit($id=''){
        if(request()->isPost()){
            $post = Request::except('file');
            $list = [
                'id' => $post['id'],
                'title' => $post['title'],
                'question_score' => $post['question_score'],
                'sort' => $post['sort'],
                'title_bg' => $post['title_bg'],
                'updatetime' => time()
            ];
            $res = db('survey_names')->update($list);
            if($res!=false){
                return ['code'=>1,'msg'=>'修改成功!','url'=>url('index')];
            }else{
                return ['code'=>0,'msg'=>'修改失败!','url'=>url('index')];
            }
        }else{
            $info=db('survey_names')->where('id',$id)->find();
            $this->assign('info',json_encode($info,true));
            $this->assign('title',lang('edit').'问卷');
            return $this->fetch('form');
        }
    }

    public function eidtOrder(){
        $ad=db('survey_names');
        $data = input('post.');
        if($ad->update($data)!==false){
            cache('surveyNamesList', NULL);
            return $result = ['msg' => '操作成功！','url'=>url('index'), 'code' =>1];
        }else{
            return $result = ['code'=>0,'msg'=>'操作失败！'];
        }
    }
    public function surveyDel(){
        db('survey_names')->delete(['id'=>input('id')]);
        return $result = ['code'=>1,'msg'=>'删除成功!'];
    }
    public function delall(){
        $map[] =array('id','IN',input('param.ids/a'));
        db('survey_names')->where($map)->delete();
        $result['msg'] = '删除成功！';
        $result['code'] = 1;
        $result['url'] = url('index');
        return $result;
    }

    /***********************************问题列表***********************************/
    public function survey_questions($id=''){
        if(request()->isPost()){
            $survey_id = input('post.survey_id');
            $list=db('survey_questions')->where('survey_id',$survey_id)->select();
            foreach($list as &$v){
                $v['updatetime'] = date('Y年m月d日',$v['updatetime']);
                $v['answer_num'] = db('survey_answers')->where('question_id',$v['id'])->count();
                $v['answer_right_num'] = db('survey_answers')->where(['question_id'=>$v['id'],'is_right'=>1])->count();
            }
            return $result = ['code'=>0,'msg'=>'获取成功!','data'=>$list,'rel'=>1];
        }
        $survey = db('survey_names')->where('id',$id)->find();

        $this->assign('title', $survey['title']);
        $this->assign('survey_id', $id);

        return $this->fetch();
    }
    public function questionAdd($survey_id = ''){
        if(request()->isPost()){
            $post = Request::except('file');
            $questionlist = [
                'question' => $post['question'],
                'answer_type' => 1,
                'survey_id' => $post['survey_id'],
                'createtime' => time(),
                'updatetime' => time()
            ];
            $questionres = db('survey_questions')->insertGetId($questionlist);
            foreach($post['answer'] as $k => $v){
                $answerslist = [
                    'question_id' => $questionres,
                    'choice_text' => $v,
                    'is_right' => $post['is_right'][$k],
                ];
                $answersres = db('survey_answers')->insert($answerslist);
            }
            if($questionres && $answersres){
                return ['code'=>1,'msg'=>'添加成功!','url'=>url('survey_questions',['id'=>$post['survey_id']])];
            }else{
                return ['code'=>0,'msg'=>'添加失败!','url'=>url('survey_questions',['id'=>$post['survey_id']])];
            }
        }else{
            $info = array();
            $this->assign('info',json_encode($info,true));
            $this->assign('survey_id',$survey_id);
            $this->assign('title','创建问题');
            return $this->fetch('questionForm');
        }
    }
    public function questionEdit($question_id = ''){
        if(request()->isPost()){
            $post = Request::except('file');
            $questionlist = [
                'question' => $post['question'],
                'answer_type' => 1,
                'survey_id' => $post['survey_id'],
                'updatetime' => time()
            ];
            $questionres = db('survey_questions')->where(['id'=>$post['question_id']])->update($questionlist);
            db('survey_answers')->where(['question_id'=>$post['question_id']])->delete();
            foreach($post['answer'] as $k => $v){
                $answerslist = [
                    'question_id' => $post['question_id'],
                    'choice_text' => $v,
                    'is_right' => $post['is_right'][$k],
                ];
                $answersres = db('survey_answers')->insert($answerslist);
            }
            if($questionres && $answersres){
                return ['code'=>1,'msg'=>'编辑成功!','url'=>url('survey_questions',['id'=>$post['survey_id']])];
            }else{
                return ['code'=>0,'msg'=>'编辑失败!','url'=>url('survey_questions',['id'=>$post['survey_id']])];
            }
        }else{
            $info = db('survey_questions')->where(['id'=>$question_id])->find();
            $answers = db('survey_answers')->where(['question_id'=>$question_id])->order('id asc')->select();
            $this->assign('info',json_encode($info,true));
            $this->assign('answers',$answers);
            $this->assign('survey_id',$info['survey_id']);
            $this->assign('title','创建问题');
            return $this->fetch('questionForm');
        }
    }
    public function questionDel(){
        $id = input('post.id');
        db('survey_questions')->where(array('id'=>$id))->delete();
        db('survey_answers')->where(array('question_id'=>$id))->delete();
        return ['code'=>1,'msg'=>'删除成功！'];
    }


    /***********************************答题记录***********************************/
    public function survey_score(){
        if(request()->isPost()){
            $key=input('post.key');
            $page =input('page')?input('page'):1;
            $pageSize =input('limit')?input('limit'):config('pageSize');
            $list=db('survey_score')->alias('ss')
                ->join(config('database.prefix') . 'users u', 'ss.user_id = u.id', 'left')
                ->join(config('database.prefix') . 'survey_names sn', 'ss.survey_id = sn.id', 'left')
                ->field('ss.*,u.username,u.truename,sn.title')
                ->where('sn.title|u.username|u.truename','like',"%".$key."%")
                ->order('ss.id desc')
                ->paginate(array('list_rows'=>$pageSize,'page'=>$page))
                ->toArray();
            return $result = ['code'=>0,'msg'=>'获取成功!','data'=>$list['data'],'count'=>$list['total'],'rel'=>1];
        }
        return $this->fetch();
    }

    public function survey_questionnaires($survey_id){
        if(request()->isPost()){
            $page =input('page')?input('page'):1;
            $pageSize =input('limit')?input('limit'):config('pageSize');
            $list=db('survey_questionnaires')->alias('sqn')
                ->join(config('database.prefix') . 'survey_questions sq', 'sqn.question_id = sq.id', 'left')
                ->join(config('database.prefix') . 'survey_answers sa', 'sqn.answer_id = sa.id', 'left')
                ->where("sqn.survey_id",$survey_id)
                ->field('sqn.*,sq.question,sa.choice_text')
                ->paginate(array('list_rows'=>$pageSize,'page'=>$page))
                ->toArray();
            return $result = ['code'=>0,'msg'=>'获取成功!','data'=>$list['data'],'count'=>$list['total'],'rel'=>1];
        }
        $this->assign('survey_id', $survey_id);

        return $this->fetch();
    }

    /***********************************答题成绩***********************************/
    public function grade(){
        if(request()->isPost()){
            $key=input('post.key');
            $page =input('page')?input('page'):1;
            $pageSize =input('limit')?input('limit'):config('pageSize');
            $list=db('users')->alias('u')
                ->join(config('database.prefix') . 'position p', 'p.code_id = u.street_code_id', 'left')
                ->field('u.id,u.username,u.truename,u.sex,p.position_name')
                ->where('u.username|u.truename|p.position_name','like',"%".$key."%")
                ->order('u.id desc')
                ->paginate(array('list_rows'=>$pageSize,'page'=>$page))
                ->toArray();
            foreach($list['data'] as &$val){
                //答题进度条
                $surveyCount=db('survey_names')->field('id')->count();
                $myCount=db('survey_score')->field('score')->where(['user_id'=>$val['id']])->count();
                $surveyCount = $surveyCount ? $surveyCount : 1;
                //进度条
                $val['schedule']=round(($myCount/$surveyCount)*100).'%';
                $score=db('survey_score')->field('sum(score) score')->where(['user_id'=>$val['id']])->find();
                $myCount = $myCount ? $myCount : 1;
                $averageScore = intval($score['score']/$myCount);
                if($averageScore>=85){
                    $val['appraise']='卓越';
                }else if($averageScore>=75 && $averageScore<85){
                    $val['appraise']='优秀';
                }else if($averageScore>=60 && $averageScore<75){
                    $val['appraise']='良好';
                }else{
                    $val['appraise']='一般';
                }
            }
            return $result = ['code'=>0,'msg'=>'获取成功!','data'=>$list['data'],'count'=>$list['total'],'rel'=>1];
        }
        return $this->fetch();
    }

    public function grade_details($user_id){
        if(request()->isPost()){
            $page =input('page')?input('page'):1;
            $pageSize =input('limit')?input('limit'):config('pageSize');
            $list=db('survey_score')->alias('ss')
                ->join(config('database.prefix') . 'survey_names sn', 'ss.survey_id = sn.id', 'left')
                ->where("ss.user_id",$user_id)
                ->field("ss.*,sn.title")
                ->paginate(array('list_rows'=>$pageSize,'page'=>$page))
                ->toArray();
            return $result = ['code'=>0,'msg'=>'获取成功!','data'=>$list['data'],'count'=>$list['total'],'rel'=>1];
        }
        $this->assign('user_id', $user_id);

        return $this->fetch();
    }


}