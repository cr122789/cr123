<?php
namespace app\api\controller;

use app\api\model\PoliticsstatusModel;
use think\Controller;

class Politicsstatus extends Controller{
    public function initialize(){
        parent::initialize();
    }

    /**
     * @api {post} Politicsstatus/index 获取政治面貌列表
     * @apiVersion 0.1.0
     * @apiGroup Politicsstatus
     * @apiDescription 获取政治面貌列表
     *
     * @apiSuccess {Number} code 状态码
     * @apiSuccess {String} data 返回搜索结果模型
     *
     * @apiSuccessExample 成功返回示例
     *
     *{
     *    "code": 200,
     *    "data": [
     *        {
     *            "id": 1,
     *            "name": "中共党员"
     *        },
     *        {
     *            "id": 2,
     *            "name": "中共预备党员"
     *        },
     *        {
     *            "id": 3,
     *            "name": "共青团员"
     *        },
     *        {
     *            "id": 4,
     *            "name": "民革会员"
     *        },
     *        {
     *            "id": 5,
     *            "name": "民盟盟员"
     *        },
     *        {
     *            "id": 6,
     *            "name": "民建会员"
     *        },
     *        {
     *            "id": 7,
     *            "name": "民进会员"
     *        },
     *        {
     *            "id": 8,
     *            "name": "农工党党员"
     *        },
     *        {
     *            "id": 9,
     *            "name": "致公党党员"
     *        },
     *        {
     *            "id": 10,
     *            "name": "九三学社社员"
     *        },
     *        {
     *            "id": 11,
     *            "name": "台盟盟员"
     *        },
     *        {
     *            "id": 12,
     *            "name": "无党派人士"
     *        },
     *        {
     *            "id": 13,
     *            "name": "群众"
     *        }
     *    ]
     *}
     *
     */
    public function index(){
        $PoliticsstatusModel = new PoliticsstatusModel();
        $data = $PoliticsstatusModel->index();

        echo json_encode(['code'=>200,'data'=>$data],JSON_UNESCAPED_UNICODE);exit;
    }

}