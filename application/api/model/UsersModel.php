<?php
namespace app\api\model;
use think\Model;
use think\Db;
use app\api\model\IntegralRedis;
use think\Exception;
class UsersModel extends Model
{
    protected $table = 'users';

    /**
     * 更新登录时间
     * @param $users
     * @return array
     */
    public function lastLogin($users){
        Db::name($this->table)->where("id","=",$users['id'])->update(['last_login'=>time()]);
    }
    /**
     * 更新token时间
     * @param $users
     * @return array
     */
    public function lastToken($users){
        $beginToday=mktime(0,0,0,date('m'),date('d'),date('Y'));
        $integral_type=db('video_integral_type')
                ->field('node,is_open')
                ->where('id=1')
                ->find();
        $redis = new IntegralRedis('','rankList');
        $user_islogin=db('video_integral')->where('type_id=1')->where('user_id='.$users['id'])->where('play_time','gt',$beginToday)->count();
        $jidu=$redis->jidu();
        $userss=$redis->isUser($users['id'],$jidu);
        if(($user_islogin=='0')&&($integral_type['is_open']=='1')){
            $list=[
                'type_id'=>'1',
                'play_time'=>time(),
                'user_id'=>$users['id'],
                'time'=>time(),
                'integral'=>$integral_type['node'],
            ];
            $list['weiyi']=$users['id'].time();
            try{
                $result1=db('video_integral')->insert($list);
            }catch(\Exception $e){
            }
            $integral=$users['integral']+$integral_type['node'];
            db('users')->where('id='.$users['id'])->update(['integral'=>$integral,'last_login'=>time()]);
            
            // 季度
            $isJidu=$redis->isJidu();
            if($isJidu){
                //季度积分已开启
                try{
                    if($userss){
                        //如果有这条数据  添加积分
                        $jidujf=$userss['integral']+$integral_type['node'];
                        db('video_integral_jidu')->where('id='.$userss['id'])->update(['integral'=>$jidujf,'update_time'=>time(),'weiyi'=>$list['weiyi']]);
                    }else{
                        //插入数据
                        db('video_integral_jidu')->insert(['user_id'=>$users['id'],'integral'=>$integral_type['node'],'create_time'=>time(),'jidu'=>$jidu,'weiyi'=>$list['weiyi']]);
                    }
                }catch(\Exception $es){
                    if($es->getCode()=='10501'){
                    }
                }
            }
        }
       
        Db::name($this->table)->where("id","=",$users['id'])->update(['last_token'=>time()]);
    }

    /**
     * 获取用户的指定个人信息
     * @param $token
     * @return array|null|\PDOStatement|string|Model
     */
    public function users($token){
        $users=Db::name($this->table)->where('token',$token)->field('id,mobile,username,nickname,truename,sex,id_card,avatar,province_code_id,city_code_id,district_code_id,street_code_id,politics_status,last_login,last_token,integral,email,education,marital,ogroup_id')->find();
        return $users;
    }

    /**
     * 获取用户的全部个人信息
     * @param $token
     * @return array|null|\PDOStatement|string|Model
     */
    public function tokenUsers($token){
        $users=Db::name($this->table)->where('token',$token)->find();
        return $users;
    }

    /**
     * 根据账号获取用户的个人信息
     * @param $username
     * @return array|null|\PDOStatement|string|Model
     */
    public function usernameUsers($username){
        $users=Db::name($this->table)->where('username',$username)->find();
        return $users;
    }

    /**
     * 根据手机号获取用户的个人信息
     * @param $mobile
     * @return array|null|\PDOStatement|string|Model
     */
    public function mobileUsers($mobile){
        $users=Db::name($this->table)->where('mobile',$mobile)->find();
        return $users;
    }

    /**
     * 登录生成token
     * @param $users
     * @return string
     */
    public function createToken($users) {
        //生成新的token
        $str = md5(uniqid(md5(microtime(true)), true));
        $token = sha1($str.$users['id']);
        Db::name($this->table)->where("id","=",$users['id'])->update(['token'=>$token]);
        $this->lastLogin($users);
        $this->lastToken($users);
        return $token;
    }

    /**
     * 登录生成token
     * @param $users
     * @return string
     */
    public function logincreateToken($users) {
        //生成新的token
        $str = md5(uniqid(md5(microtime(true)), true));
        $token = sha1($str.$users['id']);
        Db::name($this->table)->where("id","=",$users['id'])->update(['token'=>$token]);
        $this->lastToken($users);
        return $token;
    }

    /**
     * 生成随机的用户名称
     * @param int $length
     * @return string
     */
    private function generate_username($length = 8) {
        // 密码字符集，可任意添加你需要的字符
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789-_';
        $username = '';
        for ( $i = 0; $i < $length; $i++ )
        {
            // 这里提供两种字符获取方式
            // 第一种是使用substr 截取$chars中的任意一位字符；
            // 第二种是取字符数组$chars 的任意元素
            $username .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
//            $username .= $chars[ mt_rand(0, strlen($chars) - 1) ];
        }
        return $username;
    }

    /**
     * 检测账号和密码是否正确
     * @param $users
     * @param $password
     * @return array
     */
    public function checkUsers($users,$password){
        if(empty($users)){
            return ['code'=>-2, 'msg'=>'账号不存在'];
        }else{
            if ($users['password'] != sha1(md5($password))) {
                return ['code' => -3, 'msg' => '密码错误'];
            }else{
                return null;
            }
        }
    }

    /**
     * 检测手机号和验证码是否正确
     * @param $users
     * @param $code
     * @return array|null
     */
    public function checkCode($mobile,$code){
        $identify = db('identify')->where('identify_phone',$mobile)->order('identify_id desc')->find();
        if($code != $identify['identify_code']){
            return array('code'=>-3,'msg'=>'验证码不正确');
        }
        if($identify['identify_time'] <= config('identify_time')){
            return array('code'=>-3,'msg'=>'验证码已过期');
        }else{
            return null;
        }
    }

    /**
     * 重置密码
     * @param $data
     * @return array
     */
    public function resetPass($data){
        $password = md5($data['password']);
        $res = Db::name($this->table)->where("mobile",$data['mobile'])->update(['password'=>$password]);
        if($res !== false){
            return array('code'=>200,'msg'=>'密码已重置');
        }else{
            return array('code'=>-200,'msg'=>'密码重置失败');
        }
    }

    /**
     * 手机号注册
     * @param $mobile
     * @return array|null
     */
    public function register($mobile,$openRegister){
        // 生成账号
        $username = $this->generate_username();
        $data = [
            "mobile"=>$mobile,
            'reg_time'=>time(),
            'username'=>$username,
            'avatar'=>config('default_avatar')
        ];
        if($openRegister == 2){
            $data['audit'] = 2;
        }
        $res = Db::name($this->table)->insertGetId($data);

        if(!$res){
            return array('code'=>-200,'msg'=>'注册失败');
        }else{
            
            return null;
        }
    }

    /**
     * 完善用户信息
     * @param $data
     * @return array
     */
    public function complete($data){
        $token = $data['token'];
        unset($data['token']);
        $res=Db::name($this->table)->where('token',$token)->update($data);
        if($res !== false){
            $user = $this->tokenUsers($token);
            if($this->checkPass($user)){
                $return = $this->checkIsAudit($user);
                if( $return !== true){
                    return $return;
                }
                return ['code' => 200, 'msg' => '设置成功'];
            }else{
                return ['code'=>-20,'msg'=>'请设置密码'];
            }
        }else{
            return ['code' => -200, 'msg'=>'设置失败'];
        }
    }

    /**
     * 设置密码
     * @param $data
     * @return array
     */
    public function setPass($data){
        $token = $data['token'];
        $password = $data['password'];
        $res=Db::name($this->table)->where('token',$token)->update(['password'=>sha1(md5($password))]);
        if($res !== false){
            $user = $this->tokenUsers($token);
            if($this->checkInfo($user)){
                return ['code' => 200, 'msg' => '设置成功'];
            }else{
                return ['code'=>-30, 'msg'=>'请完善信息'];
            }
        }else{
            return ['code' => -200, 'msg'=>'设置失败'];
        }
    }

    /**
     * 绑定手机号
     * @param $data
     * @return array
     */
    public function bindingMobile($data){
        $token = $data['token'];
        $mobile = $data['mobile'];
        $res=Db::name($this->table)->where('token',$token)->update(['mobile'=>$mobile]);
        if($res !== false){
            return ['code' => 200, 'msg' => '绑定成功'];
        }else{
            return ['code' => -200, 'msg'=>'绑定失败'];
        }
    }

    /**
     * 退出登录
     * @param $token
     * @return array
     */
    public function logOut($token){
        $res=Db::name($this->table)->where('token',$token)->update(['token'=>'']);
        if($res !== false){
            return ['code' => 200, 'msg' => '退出登录成功'];
        }else{
            return ['code' => -200, 'msg'=>'退出登录失败'];
        }
    }

    /**
     * 检测用户是否设置密码
     * @param $user
     * @return bool
     */
    public function checkPass($user){
        if(!$user['password']){
            return false;
        }else{
            return true;
        }
    }

    /**
     * 检测用户是否已完善信息
     * @param $user
     * @return bool
     */
    public function checkInfo($user){
        if(!$user['nickname'] || !$user['truename'] || !$user['id_card'] || !$user['politics_status']) {
            return false;
        }else{
            return true;
        }
    }

    /**
     * 检测用户是否需要审核
     * @param $user
     * @return bool
     */
    public function checkIsAudit($user){
        if($user['audit'] == 1) {
            return true;
        }elseif($user['audit'] == 2){
            return ['code'=>-40,'msg'=>'等待管理员审核'];
        }elseif($user['audit'] == 0){
            return ['code'=>-50,'msg'=>'审核未通过'];
        }
    }
}

