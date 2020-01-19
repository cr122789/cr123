<?php
namespace app\admin\controller;

use think\Controller;
use think\Db;
use think\facade\Request;
use think\validate;
use think\facade\Session;
Class Pages extends Common{

    //单页
    public function pagesList(){
        if(request()->isPost()){
            $key = input('post.key');
            $page =input('page')?input('page'):1;
            $pageSize =input('limit')?input('limit'):config('pageSize');
            $list = Db::name('pages')
                ->field('id,title,content,time')
                ->where('title', 'like', "%" . $key . "%")
                ->where(['column_id'=>'0'])
                ->order('id desc')
                ->paginate(array('list_rows'=>$pageSize,'page'=>$page))
                ->toArray();
          
            foreach ($list['data'] as $k=>$v){
                $list['data'][$k]['time'] = date('Y-m-d H:s',$v['time']);
            }
            return $result = ['code'=>0,'msg'=>'获取成功!','data'=>$list['data'],'count'=>$list['total'],'rel'=>1];
        }
        return $this->fetch('pagesList');
    }
    //单页添加
    public function pagesAdd(){
        if(request()->isPost()){
            $data = input('post.');
            $data['time'] = time();
            db('pages')->insert($data);
            $result['code'] = 1;
            $result['msg'] = '添加成功!';
            $result['url'] = url('pagesList');
            return $result;
        }else{
            $this->assign('title',lang('add').'单页');
            $this->assign('info','null');
            $this->assign('content','');
            return $this->fetch('pagesForm');
        }
    }
    //单页修改
    public function pagesEdit($id){
        if(request()->isPost()){
            $post = Request::except('file');
            // print_r($post);
            $list = [
            	'id' => $post['id'],
            	'title' => $post['title'],
                'content' => $post['content']
            ];
            $res = db('pages')->update($list);
            if($res!=false){
                return ['code'=>1,'msg'=>'修改成功!','url'=>url('pagesList')];
            }else{
                return ['code'=>0,'msg'=>'修改失败!','url'=>url('pagesList')];
            }
        }else{
            $info=db('pages')->where('id',$id)->find();
            $this->assign('info',json_encode($info,true));
            $this->assign('title',lang('edit').'单页内容');
            if($info){
            	$content=$info['content'];
            }else{
            	$content='';
            }
            $this->assign('content',$content);
            return $this->fetch('pagesForm');
        }
    }
    //单页删除
    public function pagesDel(){
        db('pages')->where(array('id'=>input('id')))->delete();
        return ['code'=>1,'msg'=>'删除成功！'];
    }
    //批量删除
    public function pagesDels(){
        $map[] =array('id','IN',input('param.ids/a'));
        db('pages')->where($map)->delete();
        $result['msg'] = '删除成功！';
        $result['code'] = 1;
        $result['url'] = url('pagesList');
        return $result;
    }

}