<?php
namespace app\admin\controller;

use think\Controller;
use think\Db;
use think\facade\Request;
use think\validate;
use think\facade\Session;
Class Column extends Common{
	//栏目管理
	public function columnList(){
    	if(request()->isPost()){
            $list=Db::name('column')
                ->order('id asc')
                ->select();
            $list=self::tree($list);

            return $result = ['code'=>0,'msg'=>'获取成功!','data'=>$list,'rel'=>1];
        }
        return $this->fetch('columnList');
    
	}
	 //修改是否导航可见
    public function isshow(){
        $id=input('post.id');
        $is_show=input('post.is_show');
        if (empty($id)){
            $result['status'] = 0;
            $result['info'] = '导航ID不存在!';
            $result['url'] = url('columnList');
            return $result;
        }
        db('column')->where('id='.$id)->update(['is_show'=>$is_show]);
        $result['status'] = 1;
        $result['info'] = '设置成功!';
        $result['url'] = url('columnList');
        return $result;
    }
    //排序
    public function listOrder(){
        $data = input('post.');
        if(db('column')->update($data)!==false){
            cache('column', NULL);
            return $result = ['msg' => '操作成功！','url'=>url('index'), 'code' => 1];
        }else{
            return $result = ['code'=>0,'msg'=>'操作失败！'];
        }
    }

    //添加
    public function columnAdd(){
    	
    	if(request()->isPost()){
            $post = Request::except('file');
            $list = [
                'column_name' => $post['column_name'],
                'url' => $post['url'],
                'fid' => $post['fid'],
                'is_show' => $post['is_show'],
                'sort' => $post['is_show'],
                'seo_title' => $post['seo_title'],
                'seo_key' => $post['seo_key'],
                'seo_des' => $post['seo_des'],
                'is_page' => $post['is_page']
            ];

            $res = db('column')->insertGetId($list);
            if($post['is_page']=='1'){
                $data2['column_id']=$res['id'];
                $data2['title'] = $post['column_name'];
                $data2['content'] = '';
                
                $page=db('pages');
                $page->insert($data2);
            }
            if($res){
                return ['code'=>1,'msg'=>'添加成功!','url'=>url('columnList')];
            }else{
                return ['code'=>0,'msg'=>'添加失败!','url'=>url('columnList')];
            }
        }else{
            $this->assign('info','null');
            $this->assign('title','添加栏目');
            $column=db('column')->where('fid=0')->select();
            $this->assign('column',$column);
            return $this->fetch('columnForm');
        }
    
    }
    public function columnEdit($id=''){
        if(request()->isPost()){
            $post = Request::except('file');
            $list = [
            	'id' => $post['id'],
            	'column_name' => $post['column_name'],
                'url' => $post['url'],
                'fid' => $post['fid'],
                'is_show' => $post['is_show'],
                'sort' => $post['sort'],
                'seo_title' => $post['seo_title'],
                'seo_key' => $post['seo_key'],
                'seo_des' => $post['seo_des'],
                'is_page' => $post['is_page']
            ];
            
            $column=db('column')->where(['id' => $post['id']])->find();
            $res = db('column')->update($list);
            if($column['is_page']=='0' && $post['is_page']=='1'){
            	$data2['column_id']=$post['id'];
                $data2['title'] = $post['column_name'];
                $data2['content'] = '';
                db('pages')->insert($data2);
            }else if($column['is_page']=='1' && $post['is_page']=='0'){
            	 $page=db('pages')->where(['column_id' => $post['id']])->delete();
            }
            if($res!=false){
                return ['code'=>1,'msg'=>'修改成功!','url'=>url('columnList')];
            }else{
                return ['code'=>0,'msg'=>'修改失败!','url'=>url('columnList')];
            }
        }else{
            $info=db('column')->where('id',$id)->find();
            $this->assign('info',json_encode($info,true));
            $fid=$info['fid'];
            $this->assign('fid',$fid);
            // print_r($info);die();
            $this->assign('title',lang('edit').'栏目信息');
            $column=db('column')->where('fid=0')->where('id','neq',$id)->select();
            $this->assign('column',$column);
            return $this->fetch('columnForm');
        }
    }

    public function columnDel(){
        $count=db('column')->where('fid='.input('id'))->count();
        if($count>0){
            return $result = ['code'=>2,'msg'=>'该先删除该栏目的子栏目!'];
        }else{		
            // db('video_teacher')->delete(['id'=>input('id')]);
            db('column')->where(['id'=>input('id')])->delete();
            return $result = ['code'=>1,'msg'=>'删除成功!'];
        }
        
    }
    //单页编辑
    public function pageEdit($column_id=''){
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
                return ['code'=>1,'msg'=>'修改成功!','url'=>url('columnList')];
            }else{
                return ['code'=>0,'msg'=>'修改失败!','url'=>url('columnList')];
            }
        }else{
            $info=db('pages')->where('column_id',$column_id)->find();
            $this->assign('info',json_encode($info,true));
            $this->assign('title',lang('edit').'栏目内容');
            if($info){
            	$content=$info['content'];
            }else{
            	$content='';
            }
            $this->assign('content',$content);
            return $this->fetch('pageEdit');
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
                $value['column_name'] = $flg.$value['column_name'];
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

    
}