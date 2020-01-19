<?php
namespace app\api\model;
use think\Model;
use think\Db;
use app\api\controller\SendMessage;
use think\facade\Session;
class Users extends Model
{
    protected $table = 'users';

    public function users($userId){
        $users=Db::name($this->table)->where('id',$userId)->field('email,password,paypwd,sex,birthday,reg_time,last_login,last_ip,qq,mobile_validated,oauth,openid,unionid,email_validated,level,token,sign,status',true)->find();
        return $users;
    }

    /**
     * 密码登录
     */
    public function login($data){
        $user=Db::name($this->table)->where('username',$data['username'])->find();
        if($user) {
            if($user['password'] != md5($data['password'])){
                return ['code' => 0, 'msg' => '密码错误']; //密码错误
            }else{
                $return = $this->loginState($user);
                if($return['code']){
                    return $return;
                }
                return ['code' => 200, 'msg' => '登录成功']; //信息正确
            }
        }else{
            return ['code' => 0, 'msg' => '账号不存在']; //账号不存在
        }
    }

    /**
     * 验证码登录
     */
    public function codeLogin($data){
        $user=Db::name($this->table)->where('mobile',$data['mobile'])->find();
        if(!$user){
            return array('code'=>-10,'msg'=>'手机号尚未注册');
        }else{
            $identify = db('identify')->where('identify_phone',$data['mobile'])->order('identify_id desc')->where('identify_time','>=',(time()-60*10))->find();
            if(!$identify){
                return array('code'=>-1,'msg'=>'验证码已过期');
            }else{
                if($data['code'] != $identify['identify_code']){
                    return array('code'=>-2,'msg'=>'验证码不正确');
                }else{
                    $return = $this->loginState($user);
                    if($return['code']){
                        return $return;
                    }
                    return array('code'=>200,'msg'=>'登录成功');
                }

            }
        }
    }

    /**
     * 重置密码
     */
    public function resetPass($data){
        $user=Db::name($this->table)->where('mobile',$data['mobile'])->find();
        if(!$user){
            return array('code'=>-10,'msg'=>'手机号尚未注册');
        }else{
            $identify = db('identify')->where('identify_phone',$data['mobile'])->order('identify_id desc')->where('identify_time','>=',(time()-60*10))->find();
            if(!$identify){
                return array('code'=>-3,'msg'=>'验证码已过期');
            }else{
                if($data['code'] != $identify['identify_code']){
                    return array('code'=>-2,'msg'=>'验证码不正确');
                }else{
                    $password = md5($data['password']);
                    $res = Db::name($this->table)->where("mobile","=",$data['mobile'])->update(['password'=>$password]);
                    if($res !== false){
                        session(null);
                        return array('code'=>200,'msg'=>'密码已重置');
                    }else{
                        return array('code'=>-20,'msg'=>'密码重置失败');
                    }
                }

            }
        }
    }

    /**
     * 注册
     */
    public function register($data){
        $user = Db::name($this->table)->field('*')->where("mobile","=",$data['mobile'])->find();

        if($user){
            return array('code'=>-10,'msg'=>'手机号已注册');
        }else{
            $identify = db('identify')->where('identify_phone',$data['mobile'])->order('identify_id desc')->where('identify_time','>=',(time()-60*10))->find();
            if(!$identify){
                return array('code'=>-1,'msg'=>'验证码已过期');
            }else{
                if($data['code'] != $identify['identify_code']){
                    return array('code'=>-2,'msg'=>'验证码不正确');
                }
                $password = md5($data['password']);
                $res = Db::name($this->table)->insertGetId(["username"=>$data['mobile'],"mobile"=>$data['mobile'],'password'=>$password,'reg_time'=>time(),]);
                if($res){
                    session('username', $data['mobile']);
                    session('uid', $res);
                    Db::name($this->table)->where("id","=",$res)->update(['last_login'=>time()]);
                    return array('code'=>200,'msg'=>'注册成功，请完善个人信息','data'=>$res);
                }else{
                    session(null);
                    return array('code'=>-20,'msg'=>'注册失败');
                }
            }
        }
    }

    /**
     * 注册发送短信验证码
     */
    public function mobileCode($data){
        $user = Db::name($this->table)->field('*')->where("mobile","=",$data['mobile'])->find();
        if($user){
            return array('code'=>-10,'msg'=>'手机号已注册');
        }else{
            $sendMessage = new SendMessage();
            $res = $sendMessage->ali_send($data['mobile']);
            if($res['status'] == 'success'){
                if(Db::name('identify')->insert(['identify_phone'=>$data['mobile'],'identify_code'=>$res['result']['send_code'],'identify_time'=>time()])){
                    return array('code'=>200,'msg'=>'发送成功');
                }else{
                    return array('code'=>-20,'msg'=>'mysql error');
                }
            }elseif($res['status'] == 'failure'){
                return array('code'=>-30,'msg'=>$res['exception']->raw['Message']);
            }
        }
    }

    /**
     * 登录/找回密码发送短信验证码
     */
    public function mobileLoginCode($data){
        $user = Db::name($this->table)->field('*')->where("mobile","=",$data['mobile'])->find();
        if(!$user){
            return array('code'=>-10,'msg'=>'手机号尚未注册');
        }else{
            $sendMessage = new SendMessage();
            $res = $sendMessage->ali_send($data['mobile']);
            if($res['status'] == 'success'){
                if(Db::name('identify')->insert(['identify_phone'=>$data['mobile'],'identify_code'=>$res['result']['send_code'],'identify_time'=>time()])){
                    return array('code'=>200,'msg'=>'发送成功');
                }else{
                    return array('code'=>-20,'msg'=>'mysql error');
                }
            }elseif($res['status'] == 'failure'){
                return array('code'=>-30,'msg'=>$res['exception']->raw['Message']);
            }
        }
    }

    /**
     * 判断用户信息是否完善
     */
    public function checkUserInfo(){
        $userId = session('uid');
        $user=Db::name($this->table)->where('id',$userId)->find();
        if(!$user['truename'] || !$user['sex'] || !$user['street_code_id']) {
            return ['code' => -100, 'msg' => '请完善信息','data'=>$user['id']];exit;
        }
    }

    /**
     * 完善用户信息
     */
    public function complete($data){
        $user_id = session('uid');
        if(!$user_id){
            echo json_encode(['code'=>0,'msg'=>'user_id错误']);die;
        }
        unset($data['user_id']);
        $data['is_lock'] = 1;
        $res=Db::name($this->table)->where('id',$user_id)->update($data);
        $success = '信息已完善，登录成功';
        $error = '信息完善失败';
        if($res !== false){
            return ['code' => 200, 'msg' => $success];
        }else{
            return ['code' => -2, 'msg'=>$error];
        }
    }

    protected function loginState($user){
        $this->saveLoginState($user);
        if(!$user['truename'] || !$user['sex'] || !$user['street_code_id']) {
            return ['code' => -100, 'msg' => '请完善信息','data'=>$user['id']];exit;
        }
        Db::name($this->table)->where("id","=",$user['id'])->update(['last_login'=>time()]);
    }


    protected function saveLoginState($user){
        session('username', $user['username']);
        session('uid', $user['id']);
    }
}

