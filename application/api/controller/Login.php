<?php
namespace app\api\controller;
use think\Controller;
use app\api\model\UsersModel;
use think\validate;

class Login extends Controller
{
    public function initialize(){
        header('content-type:text/html;charset=utf-8');
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
        header('Access-Control-Allow-Methods: GET, POST, PUT');

        parent::initialize();
    }

    /**
     * @api {post} Login/index 密码登录
     * @apiVersion 0.1.0
     * @apiGroup Login
     * @apiDescription 密码登录
     *
     * @apiParam {String} mobile 账号或者手机号
     * @apiParam {String} password 密码
     *
     * @apiSuccess {Number} code 状态码
     * @apiSuccess {String} msg 返回提示信息
     * @apiSuccess {String} data 返回搜索结果模型
     *
     * @apiSuccessExample 成功返回示例
     *
     *{
     *    "code": 200,
     *    "msg": "登录成功"
     *}
     *
     * @apiSuccessExample 成功返回示例
     *
     *{
     *    "code": 230,
     *    "msg": "登录成功，请完善信息"
     *    "data": {
     *        "token": "42c5c0950febe50632680d624fc13adb0324479b"
     *    }
     *}
     *
     *@apiErrorExample 失败返回示例1
     *
     *{
     *    "code": 0,
     *    "msg": "账号不能为空/密码不能为空"
     *}
     *
     *@apiErrorExample 失败返回示例2
     *
     *{
     *    "code": -2,
     *    "msg": "账号不存在"
     *}
     *
     *@apiErrorExample 失败返回示例3
     *
     *{
     *    "code": -3,
     *    "msg": "密码错误"
     *}
     *
     *@apiErrorExample 失败返回示例
     *
     *{
     *    "code": -40,
     *    "msg": "等待管理员审核"
     *}
     *
     *@apiErrorExample 失败返回示例
     *
     *{
     *    "code": -50,
     *    "msg": "审核未通过"
     *}
     *
     */
    public function index(){
        if(request()->isPost()) {
            $data = input('post.');
            $validate = new validate([
                'mobile|账号'  => 'require',
                'password|密码' => 'require'
            ]);
            if (!$validate->check($data)) {
                echo json_encode(['code'=>0,'msg'=>$validate->getError()],JSON_UNESCAPED_UNICODE);exit;
            }
            $mobile = $data['mobile'];
            $password = $data['password'];
            $UsersModel = new UsersModel();
            $users = $UsersModel->mobileUsers($mobile);
            if(!$users){
                $users = $UsersModel->usernameUsers($mobile);
            }
            // 检测账号和密码是否正确
            $return = $UsersModel->checkUsers($users,$password);
            if($return){
                echo json_encode($return,JSON_UNESCAPED_UNICODE);exit;
            }
            $return = $UsersModel->checkIsAudit($users);
            if( $return !== true){
                echo json_encode($return,JSON_UNESCAPED_UNICODE);exit;
            }
            // 登录生成token
            $token = $UsersModel->logincreateToken($users);
            if(!$UsersModel->checkInfo($users)){
                echo json_encode(['code'=>230,'msg'=>'登录成功，请完善信息','data'=>['token'=>$token]],JSON_UNESCAPED_UNICODE);exit;
            }
            echo json_encode(['code'=>200,'msg'=>'登录成功','data'=>['token'=>$token]],JSON_UNESCAPED_UNICODE);exit;
        }
    }

    public function wxLogin(){
        $Sns = new Sns();
        $return = $Sns->login('weixin');
        return $return;
        $data = $return;
        echo json_encode(['code'=>200,'data'=>$data]);exit;
    }

    /**
     * @api {post} Login/code 验证码登录
     * @apiVersion 0.1.0
     * @apiGroup Login
     * @apiDescription 验证码登录
     *
     * @apiParam {Number} mobile 手机号
     * @apiParam {Number} code 短信验证码
     *
     * @apiSuccess {Number} code 状态码
     * @apiSuccess {String} msg 返回提示信息
     * @apiSuccess {String} data 返回搜索结果模型
     *
     * @apiSuccessExample 成功返回示例
     *
     *{
     *    "code": 200,
     *    "msg": "登录成功"
     *}
     *
     * @apiSuccessExample 成功返回示例
     *
     *{
     *    "code": 220,
     *    "msg": "登录成功，请设置密码"
     *    "data": {
     *        "token": "42c5c0950febe50632680d624fc13adb0324479b"
     *
     *}
     *
     * @apiSuccessExample 成功返回示例
     *
     *{
     *    "code": 230,
     *    "msg": "登录成功，请完善信息"
     *    "data": {
     *        "token": "42c5c0950febe50632680d624fc13adb0324479b"
     *
     *}
     *
     *@apiErrorExample 失败返回示例1
     *
     *{
     *    "code": 0,
     *    "msg": "手机号不能为空/手机号不正确/验证码不能为空/验证码必须是数字"
     *}
     *
     *@apiErrorExample 失败返回示例2
     *
     *{
     *    "code": -2,
     *    "msg": "该手机号未被注册"
     *}
     *
     *@apiErrorExample 失败返回示例3
     *
     *{
     *    "code": -3,
     *    "msg": "验证码不正确/验证码已过期"
     *}
     *
     *@apiErrorExample 失败返回示例
     *
     *{
     *    "code": -40,
     *    "msg": "等待管理员审核"
     *}
     *
     *@apiErrorExample 失败返回示例
     *
     *{
     *    "code": -50,
     *    "msg": "审核未通过"
     *}
     *
     */
    public function code(){
        if(request()->isPost()) {
            $data = input('post.');
            $validate = new validate([
                'mobile|手机号' => 'require|mobile',
                'code|验证码' => 'require|number',
            ],[
                'mobile.mobile' => '手机号不正确'
            ]);
            if (!$validate->check($data)) {
                echo json_encode(['code'=>0,'msg'=>$validate->getError()],JSON_UNESCAPED_UNICODE);exit;
            }
            $mobile = $data['mobile'];
            $code = $data['code'];
            $UsersModel = new UsersModel();
            // 检测手机号和验证码是否正确
            $return = $UsersModel->checkCode($mobile,$code);
            if($return){
                echo json_encode($return,JSON_UNESCAPED_UNICODE);exit;
            }
            $users = $UsersModel->mobileUsers($mobile);
            if(!$users){
                echo json_encode(['code'=>-2,'msg'=>'该手机号未被注册'],JSON_UNESCAPED_UNICODE);exit;
            }
            $return = $UsersModel->checkIsAudit($users);
            if( $return !== true){
                echo json_encode($return,JSON_UNESCAPED_UNICODE);exit;
            }
            // 登录生成token
            $token = $UsersModel->logincreateToken($users);
            if(!$UsersModel->checkPass($users)){
                echo json_encode(['code'=>220,'msg'=>'登录成功，请设置密码','data'=>['token'=>$token]],JSON_UNESCAPED_UNICODE);exit;
            }
            if(!$UsersModel->checkInfo($users)){
                echo json_encode(['code'=>230,'msg'=>'登录成功，请完善信息','data'=>['token'=>$token]],JSON_UNESCAPED_UNICODE);exit;
            }
            echo json_encode(['code'=>200,'msg'=>'登录成功','data'=>['token'=>$token]],JSON_UNESCAPED_UNICODE);exit;
        }
    }

    /**
     * @api {post} Login/sendMsg 登录/找回密码获取短信验证码
     * @apiVersion 0.1.0
     * @apiGroup Login
     * @apiDescription 登录获取短信验证码
     *
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
            $SendMsg = new SendMsg();
            $return = $SendMsg->send($mobile);

            echo json_encode($return,JSON_UNESCAPED_UNICODE);exit;
        }
    }

    /**
     * @api {post} Login/setNewPass 找回密码
     * @apiVersion 0.1.0
     * @apiGroup Login
     * @apiDescription 找回密码
     *
     * @apiParam {Number} mobile 手机号
     * @apiParam {Number} code 短信验证码
     * @apiParam {String} password 密码
     * @apiParam {String} repassword 确认密码
     *
     * @apiSuccess {Number} code 状态码
     * @apiSuccess {String} msg 返回提示信息
     * @apiSuccess {String} data 返回搜索结果模型
     *
     * @apiSuccessExample 成功返回示例
     *
     *{
     *    "code": 200,
     *    "msg": "密码已重置"
     *}
     *
     *@apiErrorExample 失败返回示例1
     *
     *{
     *    "code": 0,
     *    "msg": "手机号不能为空/手机号不正确/验证码不能为空/密码不能为空/密码长度不能小于 12/密码长度不能超过 20/密码必须包含英文与数字/确认密码不能为空/确认密码和密码不一致"
     *}
     *
     *@apiErrorExample 失败返回示例2
     *
     *{
     *    "code": -2,
     *    "msg": "该手机号未被注册"
     *}
     *
     *@apiErrorExample 失败返回示例3
     *
     *{
     *    "code": -3,
     *    "msg": "验证码不正确/验证码已过期"
     *}
     *
     * @apiErrorExample 失败返回示例4
     *
     *{
     *    "code": -200,
     *    "msg": "密码重置失败"
     *}
     *
     */
    public function setNewPass(){
        if(request()->isPost()) {
            $data = input('post.');
            $validate = new validate([
                'mobile|手机号'  => 'require|mobile',
                'code|验证码' => 'require',
                'password|密码'  => 'require|min:12|max:20|password',
                'repassword|确认密码' => 'require|confirm:password'
            ],[
                'mobile.mobile' => '手机号不正确',
                'password.password' => '密码必须包含英文与数字',
                'repassword.confirm' => '确认密码和密码不一致'
            ]);
            if (!$validate->check($data)) {
                echo json_encode(['code'=>0,'msg'=>$validate->getError()],JSON_UNESCAPED_UNICODE);exit;
            }
            $mobile = $data['mobile'];
            $code = $data['code'];
            $UsersModel = new UsersModel();
            // 检测手机号和验证码是否正确
            $return = $UsersModel->checkCode($mobile,$code);
            if($return){
                echo json_encode($return,JSON_UNESCAPED_UNICODE);exit;
            }
            $users = $UsersModel->mobileUsers($mobile);
            if(!$users){
                echo json_encode(['code'=>-2,'msg'=>'该手机号未被注册'],JSON_UNESCAPED_UNICODE);exit;
            }
            $return = $UsersModel->resetPass($data);
            echo json_encode($return,JSON_UNESCAPED_UNICODE);exit;
        }
    }

}