<?php
namespace app\api\model;
use think\Model;
use think\Db;
use ali\SendMessage;

class SendMsgModel extends Model
{
    protected $table = 'identify';

    /**
     * 发送短信验证码
     * @param $phone
     * @return array
     */
    public function send($phone){
        $sendMessage = new SendMessage();
        $res = $sendMessage->ali_send($phone);
        if($res['status'] == 'success'){
            if(Db::name($this->table)->insert(['identify_phone'=>$phone,'identify_code'=>$res['result']['send_code'],'identify_time'=>time()])){
                return array('code'=>200,'msg'=>'发送成功');
            }else{
                return array('code'=>-200,'msg'=>'mysql error');
            }
        }elseif($res['status'] == 'failure'){
            return array('code'=>-30,'msg'=>$res['exception']->raw['Message']);
        }
    }
}

