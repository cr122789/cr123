<?php
namespace app\admin\controller;
use think\Db;
use think\facade\Request;
class System extends Common
{
    /********************************站点管理*******************************/
    //站点设置
    public function system($sys_id=1){
        $table = db('system');
        if(Request::isAjax()) {
            $data = Request::except('file');
            if($table->where('id',1)->update($data)!==false) {
                savecache('System');
                return json(['code' => 1, 'msg' => '站点设置保存成功!', 'url' => url('system/system')]);
            } else {
                return json(array('code' => 0, 'msg' =>'站点设置保存失败！'));
            }
        }else{
            $system = $table->find($sys_id);
            $this->assign('system', json_encode($system,true));
            return $this->fetch();
        }
    }
    public function email(){
        if(Request::isAjax()) {
            $datas = input('post.');
            foreach ($datas as $k=>$v){
               Db::name('config')->where([['name','=',$k],['inc_type','=','smtp']])->update(['value'=>$v]);
            }
            return json(['code' => 1, 'msg' => '邮箱设置成功!', 'url' => url('system/email')]);
        }else{
            $smtp = Db::name('config')->where('inc_type','smtp')->select();
            $info = convert_arr_kv($smtp,'name','value');
            $this->assign('info', json_encode($info,true));
            return $this->fetch();
        }
    }
    public function trySend(){
        $sender = input('email');
        //检查是否邮箱格式
        if (!is_email($sender)) {
            return json(['code' => 0, 'msg' => '测试邮箱码格式有误']);
        }
        $arr = db('config')->where('inc_type','smtp')->select();
        $config = convert_arr_kv($arr,'name','value');
        $content = $config['test_eamil_info'];
        $send = send_email($sender, '测试邮件',$content);
        if ($send) {
            return json(['code' => 1, 'msg' => '邮件发送成功！']);
        } else {
            return json(['code' => 0, 'msg' => '邮件发送失败！']);
        }
    }

    // 系统配置列表
    public function config(){
        if(request()->isPost()){
            $key=input('post.key');
            $page =input('page')?input('page'):1;
            $pageSize =input('limit')?input('limit'):config('pageSize');
            $list=db('config')
                ->paginate(array('list_rows'=>$pageSize,'page'=>$page))
                ->toArray();
            return $result = ['code'=>0,'msg'=>'获取成功!','data'=>$list['data'],'count'=>$list['total'],'rel'=>1];
        }
        return $this->fetch();
    }
    //修改系统配置信息
    public function configedit($id=''){
        if(request()->isPost()){
            $user = db('config');
            $data = input('post.');

            if ($user->update($data)!==false) {
                cache('open_register',null);
                $result['msg'] = '系统配置修改成功!';
                $result['url'] = url('config');
                $result['code'] = 1;
            } else {
                $result['msg'] = '系统配置修改失败!';
                $result['code'] = 0;
            }
            return $result;
        }else{
            $info =db('config')
                ->where('id='.$id)
                ->find();
            $this->assign('info',json_encode($info,true));
            return $this->fetch();
        }
    }

    //长度设置列表
    public function lengthList(){

        if(request()->isPost()){
            $key=input('post.key');
            $page =input('page')?input('page'):1;
            $pageSize =input('limit')?input('limit'):config('pageSize');
            $list=Db::name('video_length')
                ->where('type','like',"%".$key."%")
                ->order('id asc')
                ->paginate(array('list_rows'=>$pageSize,'page'=>$page))
                ->toArray();
            return $result = ['code'=>0,'msg'=>'获取成功!','data'=>$list['data'],'rel'=>1];
        }
        return $this->fetch('lengthList');
    }
    //长度设置开关
    public function isOpen(){
        $id=input('post.id');
        $is_open=input('post.is_open');
        if (empty($id)){
            $result['status'] = 0;
            $result['info'] = '该长度设置不存在!';
            $result['url'] = url('lengthList');
            return $result;
        }
        db('video_length')->where('id='.$id)->update(['is_open'=>$is_open]);
        $result['status'] = 1;
        $result['info'] = '设置成功!';
        $result['url'] = url('lengthList');
        return $result;
    }
    //长度设置编辑
    public function lengthSet($id=''){
        if(request()->isPost()){
            $post = Request::except('file');
            $list = [
                'id' => $post['id'],
                'length' => $post['length'],
                'is_open' =>$post['is_open']
            ];
            $res = db('video_length')->update($list);
            if($res!=false){
                return ['code'=>1,'msg'=>'修改成功!','url'=>url('lengthList')];
            }else{
                return ['code'=>0,'msg'=>'修改失败!','url'=>url('lengthList')];
            }
        }else{
            $info=db('video_length')->where('id',$id)->find();
            
            $this->assign('info',json_encode($info,true));
            $this->assign('title',lang('edit').'字段长度');
            return $this->fetch('lengthSet');
        }
    }

}
