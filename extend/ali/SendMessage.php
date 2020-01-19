<?php
namespace ali;
use think\Controller;
use Overtrue\EasySms\EasySms;

class SendMessage extends Controller{
    public function initialize(){
        parent::initialize();
    }

    /**
     * 阿里云发送短信
     */
    public function ali_send($phone){
        $config = [
            // HTTP 请求的超时时间（秒）
            'timeout' => 5.0,

            // 默认发送配置
            'default' => [
                // 网关调用策略，默认：顺序调用
                'strategy' => \Overtrue\EasySms\Strategies\OrderStrategy::class,

                // 默认可用的发送网关
                'gateways' => [
                    'aliyun',
                ],
            ],
            // 可用的网关配置
            'gateways' => [
                'errorlog' => [
                    'file' => '/tmp/easy-sms.log',
                ],
                'aliyun' => [
                    'access_key_id' => 'LTAIW83Y7xpVPJuQ',
                    'access_key_secret' => 'LkhHAmFj2WpwO3OlSzuxZewVLJ8guz',
                    'sign_name' => '社会工作人才教育网',
                ],
                //...
            ],
        ];

        $easySms = new EasySms($config);
        $rand = rand(1000,9999);
        $res = $easySms->send($phone, [
            'content'  => '您的验证码：'.$rand.'，该验证码10分钟内有效，请勿泄漏于他人！',
            'template' => 'SMS_167970886',
            'data' => [
                'code' => $rand
            ],
        ]);
        $res['aliyun']['result']['send_code'] = $rand;

        return $res['aliyun'];
    }
}