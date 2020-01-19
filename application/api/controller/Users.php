<?php
/**
 * Created by PhpStorm.
 * User: Lin
 * Date: 2019/11/21
 * Time: 14:45
 */

namespace app\api\controller;

use app\api\model\UsersModel;
use think\validate;
use app\api\model\IntegralRedis;
class Users extends Common
{

    /**
     * @api {post} Users/checkOldPass 检测原密码是否正确
     * @apiVersion 0.1.0
     * @apiGroup Users
     * @apiDescription 检测原密码是否正确
     *
     * @apiParam {String} token 登录令牌
     * @apiParam {String} password 原密码
     *
     * @apiSuccess {Number} code 状态码
     * @apiSuccess {String} data 返回搜索结果模型
     *
     * @apiSuccessExample 成功返回示例
     *
     *{
     *    "code": 200,
     *    "msg": '密码正确'
     *}
     *
     * @apiErrorExample 失败返回示例1
     *
     *{
     *    "code": -1,
     *    "msg": "请登录"
     *}
     *
     * @apiErrorExample 失败返回示例2
     *
     *{
     *    "code": -3,
     *    "msg": "原密码错误"
     *}
     *
     */
    public function checkOldPass(){
        $token = input('post.token');
        $password = input('post.password');
        $UsersModel = new UsersModel();
        $users = $UsersModel->tokenUsers($token);
        if($users['password'] != sha1(md5($password))){
            echo json_encode(['code' => -3, 'msg' => '原密码错误'],JSON_UNESCAPED_UNICODE);exit;
        }else{
            echo json_encode(['code'=>200,'msg'=>'密码正确'],JSON_UNESCAPED_UNICODE);exit;
        }
    }

    /**
     * @api {post} Users/sendMsg 修改密码/原绑定手机号获取短信验证码
     * @apiVersion 0.1.0
     * @apiGroup Users
     * @apiDescription 修改密码/原绑定手机号获取短信验证码
     *
     * @apiParam {String} token 登录令牌
     * @apiParam {Number} mobile 手机号
     *
     * @apiSuccess {Number} code 状态码
     * @apiSuccess {String} msg 提示信息
     *
     * @apiSuccessExample 成功返回示例
     *
     *{
     *    "code": 200,
     *    "msg": "发送成功"
     *}
     *
     * @apiErrorExample 失败返回示例1
     *
     *{
     *    "code": -1,
     *    "msg": "请登录"
     *}
     *
     * @apiErrorExample 失败返回示例2
     *
     *{
     *    "code": 0,
     *    "msg": "手机号不能为空/手机号不正确"
     *}
     *
     * @apiErrorExample 失败返回示例3
     *
     *{
     *    "code": -200,
     *    "msg": "mysql error"
     *}
     *
     * @apiErrorExample 失败返回示例4
     *
     *{
     *    "code": -30,
     *    "msg": "阿里云错误提示信息"
     *}
     *
     */
    public function sendMsg(){
        if(request()->isPost()) {
            $post = input('post.');
            $token = $post['token'];
            $mobile = $post['mobile'];
            $validate = new Validate([
                'mobile|手机号'  => 'require|mobile'
            ],[
                'mobile.mobile' => '手机号不正确'
            ]);
            $data = [
                'mobile'  => $mobile
            ];
            if (!$validate->check($data)) {
                echo json_encode(array('code'=>0,'msg'=>$validate->getError()),JSON_UNESCAPED_UNICODE);exit;
            }
            $UsersModel = new UsersModel();
            $users = $UsersModel->tokenUsers($token);
            if($users['mobile'] != $mobile){
                echo json_encode(array('code'=>0,'msg'=>'手机号不正确'),JSON_UNESCAPED_UNICODE);exit;
            }
            $SendMsg = new SendMsg();
            $return = $SendMsg->send($mobile);

            echo json_encode($return,JSON_UNESCAPED_UNICODE);exit;
        }
    }

    /**
     * @api {post} Users/checkCode 修改密码/原绑定手机号检测手机号和验证码是否正确
     * @apiVersion 0.1.0
     * @apiGroup Users
     * @apiDescription 修改密码/原绑定手机号检测手机号和验证码是否正确
     *
     * @apiParam {String} token 登录令牌
     * @apiParam {Number} mobile 手机号
     * @apiParam {Number} code 验证码
     *
     * @apiSuccess {Number} code 状态码
     * @apiSuccess {String} msg 提示信息
     *
     * @apiSuccessExample 成功返回示例
     *
     *{
     *    "code": 200,
     *    "msg": "手机号和验证码一致"
     *}
     *
     * @apiErrorExample 失败返回示例1
     *
     *{
     *    "code": -1,
     *    "msg": "请登录"
     *}
     *
     * @apiErrorExample 失败返回示例2
     *
     *{
     *    "code": 0,
     *    "msg": "请输入手机号/手机号不正确/请输入验证码"
     *}
     *
     * @apiErrorExample 失败返回示例3
     *
     *{
     *    "code": -3,
     *    "msg": "验证码不正确/验证码已过期"
     *}
     *
     */
    public function checkCode(){
        $post = input('post.');
        $token = $post['token'];
        $mobile = $post['mobile'];
        $code = $post['code'];
        $validate = new validate([
            'mobile'  => 'require|mobile',
            'code' => 'require',
        ],[
            'mobile.require' => '请输入手机号',
            'mobile.mobile' => '手机号不正确',
            'code.require' => '请输入验证码',
        ]);
        if (!$validate->check($post)) {
            echo json_encode(['code'=>0,'msg'=>$validate->getError()],JSON_UNESCAPED_UNICODE);exit;
        }
        $UsersModel = new UsersModel();
        $users = $UsersModel->tokenUsers($token);
        if($users['mobile'] != $mobile){
            echo json_encode(array('code'=>0,'msg'=>'手机号不正确'),JSON_UNESCAPED_UNICODE);exit;
        }
        $return = $UsersModel->checkCode($mobile,$code);
        if($return){
            echo json_encode($return,JSON_UNESCAPED_UNICODE);exit;
        }else{
            echo json_encode(['code'=>200,'msg'=>'手机号和验证码一致'],JSON_UNESCAPED_UNICODE);exit;
        }
    }

    /**
     * @api {post} Users/newmSendMsg 新绑定手机号获取短信验证码
     * @apiVersion 0.1.0
     * @apiGroup Users
     * @apiDescription 新绑定手机号获取短信验证码
     *
     * @apiParam {String} token 登录令牌
     * @apiParam {Number} mobile 手机号
     *
     * @apiSuccess {Number} code 状态码
     * @apiSuccess {String} msg 提示信息
     *
     * @apiSuccessExample 成功返回示例
     *
     *{
     *    "code": 200,
     *    "msg": "发送成功"
     *}
     *
     * @apiErrorExample 失败返回示例1
     *
     *{
     *    "code": -1,
     *    "msg": "请登录"
     *}
     *
     * @apiErrorExample 失败返回示例2
     *
     *{
     *    "code": 0,
     *    "msg": "手机号不能为空/手机号不正确"
     *}
     *
     * @apiErrorExample 失败返回示例3
     *
     *{
     *    "code": -2,
     *    "msg": "该手机号已被绑定"
     *}
     *
     * @apiErrorExample 失败返回示例4
     *
     *{
     *    "code": -200,
     *    "msg": "mysql error"
     *}
     *
     * @apiErrorExample 失败返回示例5
     *
     *{
     *    "code": -30,
     *    "msg": "阿里云错误提示信息"
     *}
     *
     */
    public function newmSendMsg(){
        if(request()->isPost()) {
            $post = input('post.');
            $token = $post['token'];
            $mobile = $post['mobile'];
            $validate = new Validate([
                'mobile|手机号'  => 'require|mobile'
            ],[
                'mobile.mobile' => '手机号不正确'
            ]);
            $data = [
                'mobile'  => $mobile
            ];
            if (!$validate->check($data)) {
                echo json_encode(array('code'=>0,'msg'=>$validate->getError()),JSON_UNESCAPED_UNICODE);exit;
            }
            $UsersModel = new UsersModel();
            $users = $UsersModel->mobileUsers($mobile);
            if($users){
                echo json_encode(array('code'=>-2,'msg'=>'该手机号已被绑定'),JSON_UNESCAPED_UNICODE);exit;
            }
            $SendMsg = new SendMsg();
            $return = $SendMsg->send($mobile);

            echo json_encode($return,JSON_UNESCAPED_UNICODE);exit;
        }
    }

    /**
     * @api {post} Users/bindingMobile 绑定手机号
     * @apiVersion 0.1.0
     * @apiGroup Users
     * @apiDescription 绑定手机号
     *
     * @apiParam {String} token 登录令牌
     * @apiParam {Number} mobile 手机号
     * @apiParam {Number} code 验证码
     *
     * @apiSuccess {Number} code 状态码
     * @apiSuccess {String} msg 提示信息
     *
     * @apiSuccessExample 成功返回示例
     *
     *{
     *    "code": 200,
     *    "msg": "绑定成功"
     *}
     *
     * @apiErrorExample 失败返回示例1
     *
     *{
     *    "code": -1,
     *    "msg": "请登录"
     *}
     *
     * @apiErrorExample 失败返回示例2
     *
     *{
     *    "code": 0,
     *    "msg": "请输入手机号/手机号不正确/请输入验证码"
     *}
     *
     * @apiErrorExample 失败返回示例3
     *
     *{
     *    "code": -2,
     *    "msg": "该手机号已被绑定"
     *}
     *
     * @apiErrorExample 失败返回示例4
     *
     *{
     *    "code": -3,
     *    "msg": "验证码不正确/验证码已过期"
     *}
     *
     * @apiErrorExample 失败返回示例5
     *
     *{
     *    "code": -200,
     *    "msg": "绑定失败"
     *}
     *
     */
    public function bindingMobile(){
        $post = input('post.');
        $mobile = $post['mobile'];
        $code = $post['code'];
        $validate = new validate([
            'mobile'  => 'require|mobile',
            'code' => 'require',
        ],[
            'mobile.require' => '请输入手机号',
            'mobile.mobile' => '手机号不正确',
            'code.require' => '请输入验证码',
        ]);
        if (!$validate->check($post)) {
            echo json_encode(['code'=>0,'msg'=>$validate->getError()],JSON_UNESCAPED_UNICODE);exit;
        }
        $UsersModel = new UsersModel();
        $users = $UsersModel->mobileUsers($mobile);
        if($users){
            echo json_encode(array('code'=>-2,'msg'=>'该手机号已被绑定'),JSON_UNESCAPED_UNICODE);exit;
        }
        $return = $UsersModel->checkCode($mobile,$code);
        if($return){
            echo json_encode($return,JSON_UNESCAPED_UNICODE);exit;
        }
        $return = $UsersModel->bindingMobile($post);

        echo json_encode($return,JSON_UNESCAPED_UNICODE);exit;
    }

    /**
     * @api {post} Users/setPass 设置密码
     * @apiVersion 0.1.0
     * @apiGroup Users
     * @apiDescription 设置密码
     *
     * @apiParam {String} token 登录令牌
     * @apiParam {String} password 新密码
     * @apiParam {String} repassword 重复新密码
     *
     * @apiSuccess {Number} code 状态码
     * @apiSuccess {String} data 返回搜索结果模型
     *
     * @apiSuccessExample 成功返回示例
     *
     *{
     *    "code": 200,
     *    "msg": '设置成功'
     *}
     *
     * @apiErrorExample 失败返回示例1
     *
     *{
     *    "code": -1,
     *    "msg": "请登录"
     *}
     *
     * @apiErrorExample 失败返回示例2
     *
     *{
     *    "code": 0,
     *    "msg": "请输入新密码/密码必须为12-20位字符，且由英文和数字组成/请输入重复新密码/重复密码和密码不一致"
     *}
     *
     * @apiErrorExample 失败返回示例3
     *
     *{
     *    "code": -200,
     *    "msg": "设置失败"
     *}
     *
     * @apiErrorExample 失败返回示例4
     *
     *{
     *    "code": -30,
     *    "msg": "请完善信息"
     *}
     *
     */
    public function setPass(){
        $post = input('post.');
        $validate = new validate([
            'password'  => 'require|min:12|max:20|password',
            'repassword' => 'require|confirm:password',
        ],[
            'password.require' => '请输入新密码',
            'password.min' => '密码必须为12-20位字符，且由英文和数字组成',
            'password.max' => '密码必须为12-20位字符，且由英文和数字组成',
            'password.password' => '密码必须为12-20位字符，且由英文和数字组成',
            'repassword.require' => '请输入重复新密码',
            'repassword.confirm' => '重复密码和密码不一致'
        ]);
        $data = [
            'password'  => $post['password'],
            'repassword' => $post['repassword']
        ];
        if (!$validate->check($data)) {
            echo json_encode(array('code'=>0,'msg'=>$validate->getError()),JSON_UNESCAPED_UNICODE);exit;
        }
        $UsersModel = new UsersModel();
        $return = $UsersModel->setPass($post);
        echo json_encode($return,JSON_UNESCAPED_UNICODE);exit;
    }

    /**
     * @api {post} Users/info 用户个人信息
     * @apiVersion 0.1.0
     * @apiGroup Users
     * @apiDescription 用户个人信息
     *
     * @apiParam {String} token 登录令牌
     *
     * @apiSuccess {Number} code 状态码
     * @apiSuccess {String} data 返回搜索结果模型
     *
     * @apiSuccessExample 成功返回示例
     *
     *{
     *    "code": 200,
     *    "msg": {
     *        "id": 2, // id
     *        "mobile": "15731179306", // 手机号
     *        "username": "whos1234", // 账号
     *        "nickname": "lin", // 昵称
     *        "truename": "吝祥露", // 姓名
     *        "sex": 0, // 性别（1男0女）
     *        "id_card": null, // 身份证号
     *        "avatar": "localhost/partyLearn/public/static/admin/images/0.jpg", // 头像
     *        "email": "2272636812", //邮箱
     *        "email_type" :"@qq.com"  //邮箱后缀
     *        "province_code_id": null, // 省份/直辖市地区编码
     *        "city_code_id": null, // 市区地区编码
     *        "district_code_id": null, // 县/区地区编码
     *        "street_code_id": null, // 街道/乡镇地区编码
     *        "politics_status": null, // 政治面貌id
     *        "province": null, // 省份/直辖市地区名称
     *        "city": null, // 市区地区名称
     *        "district": null, // 县/区地区名称
     *        "street": null // 街道/乡镇地区名称
     *    }
     *}
     *
     * @apiErrorExample 失败返回示例
     *
     *{
     *    "code": -1,
     *    "msg": "请登录"
     *}
     *
     * @apiErrorExample 失败返回示例
     *
     *{
     *    "code": -20,
     *    "msg": "请设置密码"
     *}
     *
     * @apiErrorExample 失败返回示例
     *
     *{
     *    "code": -30,
     *    "msg": "请完善信息"
     *}
     *
     */
    public function info(){
        $users = $this->users;
        
        $email=$users['email'];
        
        if($email){
            $users['email']=strstr ( $email ,  '@' ,  true );
            $users['email_type']  =  substr(strstr ( $email ,  '@' ),1);
        }else{
            $users['email']='';
            $users['email_type']  =  '';
        }
        
        $Position = new Position();
        $users['province'] = $users['province_code_id'] ? $Position->position($users['province_code_id']) : null;
        $users['city'] = $users['city_code_id'] ? $Position->position($users['city_code_id']) : null;
        $users['district'] = $users['district_code_id'] ? $Position->position($users['district_code_id']) : null;
        $users['street'] = $users['street_code_id'] ? $Position->position($users['street_code_id']) : null;

        echo json_encode(['code'=>200,'msg'=>$users],JSON_UNESCAPED_UNICODE);exit;
    }

    /**
     * @api {post} Users/completeInfo 完善信息
     * @apiVersion 0.1.0
     * @apiGroup Users
     * @apiDescription 完善信息
     *
     * @apiParam {String} token 登录令牌
     * @apiParam {String} [avatar] 头像URL
     * @apiParam {String} nickname 昵称
     * @apiParam {String} truename 真实姓名
     * @apiParam {String} id_card 身份证号
     * @apiParam {String} email 邮箱
     * @apiParam {String} education 最高学历
     * @apiParam {String} marital 婚育状况
     * @apiParam {Number} province_code_id 省份/直辖市地区编码
     * @apiParam {Number} city_code_id 市区地区编码
     * @apiParam {Number} district_code_id 县/区地区编码
     * @apiParam {Number} street_code_id 街道/乡镇地区编码
     * @apiParam {Number} politics_status 政治面貌id
     *
     * @apiSuccess {Number} code 状态码
     * @apiSuccess {String} data 返回搜索结果模型
     *
     * @apiSuccessExample 成功返回示例
     *
     *{
     *    "code": 200,
     *    "msg": "设置成功"
     *}
     *
     *@apiErrorExample 失败返回示例1
     *
     *{
     *    "code": -1,
     *    "msg": "请登录"
     *}
     *
     *@apiErrorExample 失败返回示例2
     *
     *{
     *    "code": 0,
     *    "msg": "请填写昵称 /请填写真实姓名 /请填写身份证号 /身份证号不正确 /请选择省/直辖市 /请选择政治面貌"
     *}
     *
     *@apiErrorExample 失败返回示例3
     *
     *{
     *    "code": -200,
     *    "msg": "设置失败"
     *}
     *
     *@apiErrorExample 失败返回示例4
     *
     *{
     *    "code": -20,
     *    "msg": "请设置密码"
     *}
     *
     *@apiErrorExample 失败返回示例5
     *
     *{
     *    "code": -40,
     *    "msg": "等待管理员审核"
     *}
     *
     */
    public function completeInfo(){
        if(request()->isPost()){
            $users = $this->users;
            $post = input('post.');
            $validate = new validate([
//                'avatar'  => 'require',
                'nickname'  => 'require',
                'truename'  => 'require',
                'id_card'  => 'require|idCard',
                // 'province_code_id' => 'require',
                // 'city_code_id' => 'require',
                // 'district_code_id' => 'require',
                // 'street_code_id' => 'require',
                'politics_status' => 'require',
                // 'email'  =>'email',
                // 'education'=>'require',
                // 'marital'=>'require',
            ],[
//              'avatar.require'  => '请上传头像',
                'nickname.require'  => '请填写昵称',
                'truename.require'  => '请填写真实姓名',
                'id_card.require'  => '请填写身份证号',
                'id_card.idCard' => '身份证号不正确',
                // 'province_code_id.require' => '请选择省/直辖市',
                // 'city_code_id.require' => '请选择市',
                // 'district_code_id.require' => '请选择区/县',
                // 'street_code_id.require' => '请选择街道/乡镇',
                'politics_status.require' => '请选择政治面貌',
                // 'email.email' => '邮箱格式不正确',
                // 'education.require' => '请选择学历',
                // 'marital.require' => '请选择婚育状况',
            ]);
            
            if (!$validate->check($post)) {
                echo json_encode(array('code'=>0,'msg'=>$validate->getError()),JSON_UNESCAPED_UNICODE);die;
            }
            if($post['avatar']){
                $post['avatar']=substr($post['avatar'],strripos($post['avatar'],"/")+1);
            }
            $integral=db('video_integral_type')->field('is_open,node')->where('id=8')->find();
            $is_has=db('video_integral')->where('user_id='.$users['id'])->where('type_id=8')->find();
            if(!$is_has&&$integral['is_open']=='1'){
                if($post['avatar']!=''&&$post['nickname']!=''&&$post['truename']!=''&&$post['id_card']!=''&&$post['province_code_id']!=''&&$post['city_code_id']!=''&&$post['district_code_id']!=''&&$post['street_code_id']!=''&&$post['politics_status']!=''&&$post['email']!=''&&$post['education']!=''&&$post['marital']!=''){
                   
                    //加分
                    $list_one=[
                        'type_id'=>'8',
                        'play_time'=>time(),
                        'integral'=>$integral['node'],
                        'user_id'=>$users['id'],
                        'time'=>time(),
                        'reason'=>'完善信息加分'
                    ];
                    $result=db('video_integral')->insert($list_one);
                    $integrals=$users['integral']+$integral['node'];
                    db('users')->where('id='.$users['id'])->update(['integral'=>$integrals]);
                    //redis积分
                    // $redis = new IntegralRedis('','rankList');
                    // $redis->addNode($users['id'],$integral['node']);
                    //季度积分  已经取消
                    // $isJidu=$redis->isJidu();
                    
                    // if($isJidu){
                        
                    //     //季度积分已开启
                    //     $time=time();
                    //     $jidu=$redis->jidu($time);
                    //     $is_user=$redis->isUser($users['id'],$jidu);
                        
                    //     if($is_user){
                    //         //如果有这条数据  添加积分
                    //         $jidujf=$is_user['integral']+$integral['node'];
                    //         echo $jidujf;
                    //         db('video_integral_jidu')->where('id='.$is_user['id'])->update(['integral'=>$jidujf,'update_time'=>time()]);
                    //     }else{
                    //         //插入数据
                    //         db('video_integral_jidu')->insert(['user_id'=>$users['id'],'integral'=>$integral['node'],'create_time'=>time(),'jidu'=>$jidu]);
                    //     }
                    // }
                }
            }
        
            $UsersModel = new UsersModel();
            $return = $UsersModel->complete($post);
            echo json_encode($return,JSON_UNESCAPED_UNICODE);exit;
        }
    }

    /**
     * @api {post} Users/logOut 退出登录
     * @apiVersion 0.1.0
     * @apiGroup Users
     * @apiDescription 退出登录
     *
     * @apiParam {String} token 登录令牌
     *
     * @apiSuccess {Number} code 状态码
     * @apiSuccess {String} msg 返回提示信息
     * @apiSuccess {String} data 返回搜索结果模型
     *
     * @apiSuccessExample 成功返回示例
     *
     *{
     *    "code": 200,
     *    "msg": "退出登录成功"
     *}
     *
     *@apiErrorExample 失败返回示例1
     *
     *{
     *    "code": -1,
     *    "msg": "请登录"
     *}
     *
     *@apiErrorExample 失败返回示例2
     *
     *{
     *    "code": -200,
     *    "msg": "退出登录失败"
     *}
     *
     */
    public function logOut(){
        if(request()->isPost()) {
            $token = input('post.token');

            $UsersModel = new UsersModel();
            // 退出登录清除token
            $return = $UsersModel->logOut($token);
            echo json_encode($return,JSON_UNESCAPED_UNICODE);exit;
        }
    }
}