<?php
namespace app\api\controller;

use app\api\model\SendMsgModel;
use think\Controller;
use think\validate;
class SendMsg extends Controller{
    public function initialize(){
        // header('content-type:text/html;charset=utf-8');
        // header('Access-Control-Allow-Origin: *');
        // header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
        // header('Access-Control-Allow-Methods: GET, POST, PUT');

        parent::initialize();
    }

    /**
     * 发送短信
     * @param $phone
     * @return array
     */
    public function send($phone){
        $SendMsgModel = new SendMsgModel();
        $return = $SendMsgModel->send($phone);

        return $return;
    }

}