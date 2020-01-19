<?php
namespace app\api\controller;

use think\Controller;
use app\api\model\UsersModel;
use app\api\model\ConfigModel;
use think\validate;
class Register extends Controller{
    public function initialize(){
        // header('content-type:text/html;charset=utf-8');
        // header('Access-Control-Allow-Origin: *');
        // header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
        // header('Access-Control-Allow-Methods: GET, POST, PUT');

        parent::initialize();
    }

    /**
     * @api {post} Register/index 注册
     * @apiVersion 0.1.0
     * @apiGroup Register
     * @apiDescription 注册
     *
     * @apiParam {Number} mobile 手机号
     * @apiParam {Number} code 4位短信验证码
     *
     * @apiSuccess {Number} code 状态码
     * @apiSuccess {String} data 返回搜索结果模型
     *
     * @apiSuccessExample 成功返回示例
     *
     *{
     *    "code": 200,
     *    "msg": "注册成功"
     *}
     *
     *@apiErrorExample 失败返回示例1
     *
     *{
     *    "code": 0,
     *    "msg": "手机号不能为空/手机号不正确/验证码不能为空/验证码必须是数字"
     *}
     *
     * @apiErrorExample 失败返回示例2
     *
     *{
     *    "code": -2,
     *    "msg": "该手机号已注册"
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
     *    "msg": "注册失败"
     *}
     *
     */
    public function index(){
        if(request()->isPost()){
            $post = input('post.');
            $validate = new validate([
                'mobile|手机号'  => 'require|mobile',
                'code|验证码'  => 'require|number'
            ],[
                'mobile.mobile' => '手机号不正确',
            ]);
            $data = [
                'mobile'  => $post['mobile'],
                'code'  => $post['code'],
            ];
            if (!$validate->check($data)) {
                echo json_encode(array('code'=>0,'msg'=>$validate->getError()),JSON_UNESCAPED_UNICODE);die;
            }
            $mobile = $post['mobile'];
            $code = $post['code'];
            $UsersModel = new UsersModel();
            $users = $UsersModel->mobileUsers($mobile);
            if($users){
                echo json_encode(['code'=>-2,'msg'=>'该手机号已注册'],JSON_UNESCAPED_UNICODE);exit;
            }
            // 检测手机号和验证码是否正确
            $return = $UsersModel->checkCode($mobile,$code);
            if($return){
                echo json_encode($return,JSON_UNESCAPED_UNICODE);exit;
            }
            // 查询注册功能开启状态
            $ConfigModel = new ConfigModel();
            $config = $ConfigModel->index('open_register');
            $return = $UsersModel->register($mobile,$config['value']);
            if($return){
                echo json_encode($return,JSON_UNESCAPED_UNICODE);exit;
            }
            $users = $UsersModel->mobileUsers($mobile);
            // 登录生成token
            $token = $UsersModel->createToken($users);
            
            echo json_encode(['code'=>200,'msg'=>'注册成功','data'=>['token'=>$token]],JSON_UNESCAPED_UNICODE);exit;
        }
    }

    /**
     * @api {post} Register/sendMsg 注册获取短信验证码
     * @apiVersion 0.1.0
     * @apiGroup Register
     * @apiDescription 注册获取短信验证码
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
     * @apiErrorExample 失败返回示例2
     *
     *{
     *    "code": -2,
     *    "msg": "该手机号已注册"
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
                echo json_encode(array('code'=>0,'msg'=>$validate->getError()),JSON_UNESCAPED_UNICODE);die;
            }
            $UsersModel = new UsersModel();
            $users = $UsersModel->mobileUsers($mobile);
            if($users){
                echo json_encode(['code'=>-2,'msg'=>'该手机号已注册'],JSON_UNESCAPED_UNICODE);exit;
            }
            $SendMsg = new SendMsg();
            $return = $SendMsg->send($mobile);
            echo json_encode($return,JSON_UNESCAPED_UNICODE);die;
        }
    }

    /**
     * @api {post} Register/isRegister 查询注册功能开启状态
     * @apiVersion 0.1.0
     * @apiGroup Register
     * @apiDescription 首页
     *
     * @apiSuccess {Number} code 状态码
     * @apiSuccess {String} msg 提示信息
     * @apiSuccess {Obj} data 返回搜索结果模型
     *
     * @apiSuccessExample 成功返回示例
     *
     *{
     *    "code": 200,
     *    "data": 1 //注册控制开关：1代表可以注册，2代表注册需要审核，3代表关闭注册功能
     *}
     *
     */
    public function isRegister(){
        if(request()->isPost()) {
            $openRegister = cache('open_register');
            if(!$openRegister){
                $ConfigModel = new ConfigModel();
                $config = $ConfigModel->index('open_register');

                $openRegister = $config['value'];
                cache('open_register',$openRegister);
            }
            echo json_encode(['code'=>200,'data'=>intval($openRegister)],JSON_UNESCAPED_UNICODE);die;
        }
    }

}