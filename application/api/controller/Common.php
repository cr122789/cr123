<?php
namespace app\api\controller;
use think\Controller;
use app\api\model\UsersModel;
use think\facade\Session;
use app\api\model\IntegralRedis;

class Common extends Controller
{
    protected $users = [];

    public function initialize()
    {
        header('content-type:text/html;charset=utf-8');
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
        header('Access-Control-Allow-Methods: GET, POST, PUT');

        $post = input('post.');
        $token = $post['token'];

        if (!$token) {
            echo json_encode(['code'=>-1,'msg'=>'请登录'],JSON_UNESCAPED_UNICODE);exit;
        }else{

            $UsersModel = new UsersModel();
            $users = $UsersModel->users($token);
            if($users['last_token'] <= config('token_time')){
                echo json_encode(['code'=>-1,'msg'=>'请登录'],JSON_UNESCAPED_UNICODE);exit;
            }else{
                $user = $UsersModel->tokenUsers($token);
                
                $uri = explode('/',$_SERVER['REQUEST_URI']);
                $method = $uri[count($uri)-1];
                if($method != 'setPass' && $method != 'completeInfo'){
                    if(!$UsersModel->checkPass($user)){
                        echo json_encode(['code'=>-20,'msg'=>'请设置密码'],JSON_UNESCAPED_UNICODE);exit;
                    }
                    if(!$UsersModel->checkInfo($user)) {
                        echo json_encode(['code'=>-30, 'msg'=>'请完善信息'],JSON_UNESCAPED_UNICODE);exit;
                    }
                    $return = $UsersModel->checkIsAudit($user);
                    if( $return !== true){
                        echo  json_encode($return,JSON_UNESCAPED_UNICODE);exit;
                    }
                }
                $UsersModel->lastToken($users);
            }
          
            $this->users = $UsersModel->users($token);

            if(!($this->users)){
                $this->users = [];
                $user_id='1';
                $user_id=session('user_id',$user_id);
            }else{
                $user_id=$this->users['id'];
                $user_id=session('user_id',$user_id);
                $this->users['avatar'] = $this->users['avatar'] ? config('default_video').$this->users['avatar'] : '/muke/public/static/admin/images/0.jpg';
            }
           
        }
    }
}
