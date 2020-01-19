<?php
namespace app\admin\controller;
use app\admin\model\Users as UsersModel;
use think\Controller;
use think\Db;
use think\facade\Request;
use think\validate;
use think\facade\Session;
use app\admin\model\IntegralRedis;
use app\admin\model\Ogroup;
class Users extends Common{
    //会员列表
    public function index(){
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
                ->where('u.email|u.mobile|u.username','like',"%".$key."%")
                ->order('u.reg_time desc')
                ->paginate(array('list_rows'=>$pageSize,'page'=>$page))
                ->toArray();
            foreach ($list['data'] as $k=>$v){
                $list['data'][$k]['reg_time'] = date('Y-m-d H:s',$v['reg_time']);
            }
            return $result = ['code'=>0,'msg'=>'获取成功!','data'=>$list['data'],'count'=>$list['total'],'rel'=>1];
        }
        $config=Db::name('config')->where('name','open_register')->field('value')->find();
        $this->assign('open_register',$config['value']);
        $this->assign('ogroup_id',$ogroup_id);
        return $this->fetch();
    }
    //设置会员状态
    public function usersState(){
        $id=input('post.id');
        $is_lock=input('post.is_lock');
        if(db('users')->where('id='.$id)->update(['is_lock'=>$is_lock])!==false){
            return ['status'=>1,'msg'=>'设置成功!'];
        }else{
            return ['status'=>0,'msg'=>'设置失败!'];
        }
    }
    //添加会员
    public function add(){
        if(request()->isPost()){
            $user = db('users');
            $data = input('post.');
            $level =explode(':',$data['level']);
            $data['level'] = $level[1];
            $ogroup_id =explode(':',$data['ogroup_id']);
            $data['ogroup_id'] = $ogroup_id[1];
            $province_code_id =explode(':',$data['province_code_id']);
            $data['province_code_id'] = isset( $province_code_id[1])?$province_code_id[1]:'';
            $politics_status =explode(':',$data['politics_status']);
            $data['politics_status'] = isset( $politics_status[1])?$politics_status[1]:'';
            
            $validate = new validate([
            'password'  => 'require|min:12|max:20|password',
            ],[
                'require' => '密码不能为空',
                'password.min' => '密码必须为12-20位字符，且由英文和数字组成',
                'password.max' => '密码必须为12-20位字符，且由英文和数字组成',
                'password.password' => '密码必须为12-20位字符，且由英文和数字组成'
            ]);
            if(!$validate->check($data)){
                return $result = ['code'=>0,'msg'=>$validate->getError()];
            }
            $is_user=db('users')->where(['mobile'=>$data['mobile']])->count();
            if($is_user){
                return $result = ['code'=>0,'msg'=>'该手机号已存在！'];
            }
            $data['password'] = sha1(md5($data['password']));
            
            $data['reg_time']=time();
            $data['email']=$data['email'];
            $data['education']=$data['education'];
            $data['marital']=$data['marital'];
            $data['avatar']='';
            if ($user->insertGetId($data)!==false) {
                // $redis = new IntegralRedis('','rankList');
                // $add=$redis->addLeaderboard($val['id'],'0');
                $result['msg'] = '会员添加成功!';
                $result['url'] = url('index');
                $result['code'] = 1;
            } else {
                $result['msg'] = '会员添加失败!';
                $result['code'] = 0;
            }
            return $result;
        }else{
            $province = db('position')->where(['parent_code_id'=>'100000000000'])->select ();
            $user_level=db('user_level')->order('sort')->select();
            $ogroup = Ogroup::where('is_use=1')->where('id','neq','1')->all()->toArray();
            $ogroup_id=session('ogroup_id');

            $this->assign('ogroup_id',$ogroup_id);
            $this->assign('info','null');
            $this->assign('title',lang('edit').lang('user'));
            $this->assign('province',json_encode($province,true));
            $this->assign('user_level',json_encode($user_level,true));
            $this->assign('ogroup',json_encode($ogroup,true));

            $this->assign('city',json_encode('',true));
            $this->assign('district',json_encode('',true));
            $this->assign('street',json_encode('',true));
            $face=db('politics_status')->select();
            $this->assign('politics_status',json_encode($face,true));
            return $this->fetch('edit');
        }
    }
    //修改会员信息
    public function edit($id=''){
        if(request()->isPost()){
            $user = db('users');
            $data = input('post.');
            $level =explode(':',$data['level']);
            $data['level'] = $level[1];
            $ogroup_id =explode(':',$data['ogroup_id']);
            $data['ogroup_id'] = $ogroup_id[1];
            $province_code_id =explode(':',$data['province_code_id']);
            $data['province_code_id'] = isset( $province_code_id[1])?$province_code_id[1]:'';
            $politics_status =explode(':',$data['politics_status']);
            $data['politics_status'] = isset( $politics_status[1])?$politics_status[1]:'';
            if(empty($data['password'])){
                unset($data['password']);
            }else{
                $users=db('users')->field('password')->where('id='.$id)->find();
                if($users['password']==$data['password']){
                    $data['password']=$users['password'];
                }else{
                    $validate = new validate([
                    'password'  => 'min:12|max:20|password',
                    ],[
                        'password.min' => '密码必须为12-20位字符，且由英文和数字组成',
                        'password.max' => '密码必须为12-20位字符，且由英文和数字组成',
                        'password.password' => '密码必须为12-20位字符，且由英文和数字组成'
                    ]);
                    if(!$validate->check($data)){
                        return $result = ['code'=>0,'msg'=>$validate->getError()];
                    }
                    $data['password'] = sha1(md5($data['password']));
                    
                }
            }
            $is_user=db('users')->where('id','neq',$data['id'])->where(['mobile'=>$data['mobile']])->count();
            if($is_user){
                return $result = ['code'=>0,'msg'=>'该手机号已存在！'];
            }
            if ($user->update($data)!==false) {
                $result['msg'] = '会员修改成功!';
                $result['url'] = url('index');
                $result['code'] = 1;
            } else {
                $result['msg'] = '会员修改失败!';
                $result['code'] = 0;
            }
            return $result;
        }else{
            $province = db('position')->where(['parent_code_id'=>'100000000000'])->select();
            $user_level=db('user_level')->order('sort')->select();
            $ogroup = Ogroup::where('is_use=1')->where('id','neq','1')->all()->toArray();
            $info =db('users')
                    ->alias('users')
                    ->field('users.id,users.username,users.nickname,users.truename,users.level,users.mobile,users.password,users.id_card,p.position_name as province_name,c.position_name as city_name,d.position_name as district_name,s.position_name as street_name,users.province_code_id,users.city_code_id,users.district_code_id,users.street_code_id,users.politics_status,users.email,users.education,users.marital,users.ogroup_id')
                    ->join('mk_position p','users.province_code_id=p.code_id','left')
                    ->join('mk_position c','users.city_code_id=c.code_id','left')
                    ->join('mk_position d','users.district_code_id=d.code_id','left')
                    ->join('mk_position s','users.street_code_id=s.code_id','left')
                    ->where('users.id='.$id)
                    ->find();
            // print_r($info);die();
            $ogroup_id=session('ogroup_id');

            $this->assign('ogroup_id',$ogroup_id);
            $this->assign('info',json_encode($info,true));
            $this->assign('data',$info);
            $this->assign('title',lang('edit').lang('user'));
            $this->assign('province',json_encode($province,true));
            $this->assign('user_level',json_encode($user_level,true));
            $this->assign('ogroup',json_encode($ogroup,true));
            $this->assign('city',json_encode('',true));
            $this->assign('district',json_encode('',true));
            $this->assign('street',json_encode('',true));
            $face=db('politics_status')->select();
            $this->assign('politics_status',json_encode($face,true));
            return $this->fetch();
        }
    }

    //审核会员信息
    public function audit($id=''){
        if(request()->isPost()){
            $user = db('users');
            $data = input('post.');

            if ($user->update($data)!==false) {
                $result['msg'] = '审核成功!';
                $result['url'] = url('index');
                $result['code'] = 1;
            } else {
                $result['msg'] = '审核失败!';
                $result['code'] = 0;
            }
            return $result;
        }else{
            $info =db('users')
                ->field('id,audit')
                ->where('id='.$id)
                ->find();
            $this->assign('info',json_encode($info,true));
            return $this->fetch();
        }
    }

    public function getRegion(){
        // $Region=db("region");
        $pid = input("pid");
        $arr = explode(':',$pid);
        $parent_code_id=$arr[1];
        $list=db('position')->where(['parent_code_id'=>$parent_code_id])->select();
        return $list;
    }
    public function getRegion1(){
        // $Region=db("region");
        $parent_code_id = input("pid");
       
        $list=db('position')->where(['parent_code_id'=>$parent_code_id])->select();
        return $list;
    }

    public function usersDel(){
        db('users')->delete(['id'=>input('id')]);
        db('oauth')->delete(['uid'=>input('id')]);
        // $redis = new IntegralRedis('','rankList');
        // $redis->remUser(input('id'));
        db('video_integral_jidu')->where(['user_id'=>input('id')])->delete();
        return $result = ['code'=>1,'msg'=>'删除成功!'];
    }
    public function delall(){
        $map[] =array('id','IN',input('param.ids/a'));
        db('users')->where($map)->delete();
        // print_r(input('param.ids/a'));die();
        $post=input('param.ids/a');
        // $redis = new IntegralRedis('','rankList');
        foreach($post as $val){
            // $redis->remUser($val);
            db('video_integral_jidu')->where(['user_id'=>$val])->delete();
        }
        
        $result['msg'] = '删除成功！';
        $result['code'] = 1;
        $result['url'] = url('index');
        return $result;
    }

    //自动化一键导入
    public function daoru(){
        if(request()->isPost()){
            
        }else{

            return $this->fetch();
        }
    }

    /***********************************会员组***********************************/
    public function userGroup(){
        if(request()->isPost()){
            $userLevel=db('user_level');
            $list=$userLevel->order('sort')->select();
            return $result = ['code'=>0,'msg'=>'获取成功!','data'=>$list,'rel'=>1];
        }
        return $this->fetch();
    }
    public function groupAdd(){
        if(request()->isPost()){
            $data = input('post.');
            db('user_level')->insert($data);
            $result['msg'] = '会员组添加成功!';
            $result['url'] = url('userGroup');
            $result['code'] = 1;
            return $result;
        }else{
            $this->assign('title',lang('add')."会员组");
            $this->assign('info','null');
            return $this->fetch('groupForm');
        }
    }
    public function groupEdit(){
        if(request()->isPost()) {
            $data = input('post.');
            db('user_level')->update($data);
            $result['msg'] = '会员组修改成功!';
            $result['url'] = url('userGroup');
            $result['code'] = 1;
            return $result;
        }else{
            $map['level_id'] = input('param.level_id');
            $info = db('user_level')->where($map)->find();
            $this->assign('title',lang('edit')."会员组");
            $this->assign('info',json_encode($info,true));
            return $this->fetch('groupForm');
        }
    }
    public function groupDel(){
        $level_id=input('level_id');
        if (empty($level_id)){
            return ['code'=>0,'msg'=>'会员组ID不存在！'];
        }
        db('user_level')->where(array('level_id'=>$level_id))->delete();
        return ['code'=>1,'msg'=>'删除成功！'];
    }
    public function groupOrder(){
        $userLevel=db('user_level');
        $data = input('post.');
        $userLevel->update($data);
        $result['msg'] = '排序更新成功!';
        $result['url'] = url('userGroup');
        $result['code'] = 1;
        return $result;
    }


    //答题记录
    public function answerHistory($id=''){

        if(request()->isPost()){
            $user_id=input('post.user_id');
            // echo $user_id;
            $key=input('post.key');
            $page =input('page')?input('page'):1;
            $pageSize =input('limit')?input('limit'):config('pageSize');
            $list=Db::name('video_answer_history')
                ->alias('history')
                ->field('history.id,history.is_right,history.answer_time,video.title,questions.question_title,answers.choice_text')
                ->join('mk_video_video video','history.video_id=video.id')
                ->join('mk_video_questions questions','history.question_id=questions.id')
                ->join('mk_video_answers answers','history.answer_id=answers.id')
                ->join('mk_users users','history.user_id=users.id')
                ->where(['history.user_id'=>$user_id])
                ->where('video.video','like',"%".$key."%")
                ->order('history.id asc')
                ->paginate(array('list_rows'=>$pageSize,'page'=>$page))
                ->toArray();

            foreach ($list['data'] as $k=>$v){
                $list['data'][$k]['answer_time'] = date('Y年m月d日',$v['answer_time']);
            }
            // print_r($list);
            return $result = ['code'=>0,'msg'=>'获取成功!','data'=>$list['data'],'rel'=>1];
        }
        $this->assign('user_id', $id);
        return $this->fetch('answerHistory');
    }
 
   

}