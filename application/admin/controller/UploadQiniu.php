<?php
namespace app\admin\controller;

use think\Db;
use think\Request;
use think\Controller;
use think\facade\Env;
use Qiniu\Auth;
use Qiniu\Storage\UploadManager;
class UploadQiniu extends Common
{
    public function upload(){
        // 获取上传文件表单字段名
        $fileKey = array_keys(request()->file());
        // 获取表单上传文件
        $file = request()->file($fileKey['0']);
        $info=$file->getInfo();
        $filePath =  $info['tmp_name'];
        $bucket = 'swpedu';
        $accessKey = 'MsUTHp5JH9_5Bjoie6_wsNS2NoqxkqrvcshuYA_g';
        $secretKey = 'Givh3XA9wjLkEY6twzTav651sZMxjOOKD4V4J0Kt';
        $auth = new Auth($accessKey, $secretKey);//实例化
        $upToken = $auth->uploadToken($bucket);
        $token = $auth->uploadToken($bucket);
        $uploadMgr = new UploadManager();
        
        $key = time().'.png';  
        
        list($ret, $err) = $uploadMgr->putFile($token, $key, $filePath);  
        if ($err !== null) {  
            $result['code'] =0;
            $result['info'] =  $info['error'];
            $result['url'] = '';
            return $result;
        } else{
            $result['code'] = 1;
            $result['info'] = '图片上传成功!';
            $result['url'] = $ret['key'];
            return $result;
        }
    }

    public function token(){
        $bucket = 'swpedu';
        $accessKey = 'MsUTHp5JH9_5Bjoie6_wsNS2NoqxkqrvcshuYA_g';
        $secretKey = 'Givh3XA9wjLkEY6twzTav651sZMxjOOKD4V4J0Kt';
        $auth = new Auth($accessKey, $secretKey);//实例化
        $upToken = $auth->uploadToken($bucket);
        $token = $auth->uploadToken($bucket);
        $return = json_encode(array('uptoken'=>$token),JSON_UNESCAPED_UNICODE);
        return $return;
    }
}