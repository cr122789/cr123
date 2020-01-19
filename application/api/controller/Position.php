<?php

/**
 * Created by PhpStorm.
 * User: Lin
 * Date: 2019/11/07
 * Time: 15:54
 */
namespace app\api\controller;

use think\Controller;
use app\api\model\PositionModel;

class Position extends Controller
{
    public function __construct(){
        parent::__construct();
    }

    /**
     * @api {post} Position/index 获取地区信息
     * @apiVersion 0.1.0
     * @apiGroup Position
     * @apiDescription 地区信息（根据上级地区编码（parent_code_id）获取同一级别的地区信息）
     *
     * @apiParam {Number} [parent_code_id=100000000000] 上级地区编码
     *
     * @apiSuccess {Number} code 状态码
     * @apiSuccess {String} msg 提示信息
     * @apiSuccess {Array} data 结果集
     *
     * @apiSuccessExample 成功返回示例
     *
     *{
     *    "code": 200,
     *    "data": [
     *        {
     *            "id": 1, // id
     *            "code_id": 110000000000, // 地区编码
     *            "position_name": "北京市", // 地区名称
     *            "parent_code_id": 100000000000, // 上级地区编码
     *            "level": 1 // 地区等级
     *        },
     *        {
     *            "id": 7488,
     *            "code_id": 120000000000,
     *            "position_name": "天津市",
     *            "parent_code_id": 100000000000,
     *            "level": 1
     *        },
     *        {
     *            "id": 13391,
     *            "code_id": 130000000000,
     *            "position_name": "河北省",
     *            "parent_code_id": 100000000000,
     *            "level": 1
     *        },
     *        {
     *            "id": 69423,
     *            "code_id": 140000000000,
     *            "position_name": "山西省",
     *            "parent_code_id": 100000000000,
     *            "level": 1
     *        },
     *        {
     *            "id": 100736,
     *            "code_id": 150000000000,
     *            "position_name": "内蒙古自治区",
     *            "parent_code_id": 100000000000,
     *            "level": 1
     *        },
     *        {
     *            "id": 116731,
     *            "code_id": 160000000000,
     *            "position_name": "辽宁省",
     *            "parent_code_id": 100000000000,
     *            "level": 1
     *        },
     *        {
     *            "id": 134797,
     *            "code_id": 170000000000,
     *            "position_name": "吉林省",
     *            "parent_code_id": 100000000000,
     *            "level": 1
     *        },
     *        {
     *            "id": 147576,
     *            "code_id": 180000000000,
     *            "position_name": "黑龙江省",
     *            "parent_code_id": 100000000000,
     *            "level": 1
     *        },
     *        {
     *            "id": 163841,
     *            "code_id": 190000000000,
     *            "position_name": "上海市",
     *            "parent_code_id": 100000000000,
     *            "level": 1
     *        },
     *        {
     *            "id": 170106,
     *            "code_id": 200000000000,
     *            "position_name": "江苏省",
     *            "parent_code_id": 100000000000,
     *            "level": 1
     *        },
     *        {
     *            "id": 194101,
     *            "code_id": 210000000000,
     *            "position_name": "浙江省",
     *            "parent_code_id": 100000000000,
     *            "level": 1
     *        },
     *        {
     *            "id": 226236,
     *            "code_id": 220000000000,
     *            "position_name": "安徽省",
     *            "parent_code_id": 100000000000,
     *            "level": 1
     *        },
     *        {
     *            "id": 246299,
     *            "code_id": 230000000000,
     *            "position_name": "福建省",
     *            "parent_code_id": 100000000000,
     *            "level": 1
     *        },
     *        {
     *            "id": 264750,
     *            "code_id": 240000000000,
     *            "position_name": "江西省",
     *            "parent_code_id": 100000000000,
     *            "level": 1
     *        },
     *        {
     *            "id": 288107,
     *            "code_id": 250000000000,
     *            "position_name": "山东省",
     *            "parent_code_id": 100000000000,
     *            "level": 1
     *        },
     *        {
     *            "id": 368133,
     *            "code_id": 260000000000,
     *            "position_name": "河南省",
     *            "parent_code_id": 100000000000,
     *            "level": 1
     *        },
     *        {
     *            "id": 422972,
     *            "code_id": 270000000000,
     *            "position_name": "湖北省",
     *            "parent_code_id": 100000000000,
     *            "level": 1
     *        },
     *        {
     *            "id": 453459,
     *            "code_id": 280000000000,
     *            "position_name": "湖南省",
     *            "parent_code_id": 100000000000,
     *            "level": 1
     *        },
     *        {
     *            "id": 485023,
     *            "code_id": 290000000000,
     *            "position_name": "广东省",
     *            "parent_code_id": 100000000000,
     *            "level": 1
     *        },
     *        {
     *            "id": 512536,
     *            "code_id": 300000000000,
     *            "position_name": "广西壮族自治区",
     *            "parent_code_id": 100000000000,
     *            "level": 1
     *        },
     *        {
     *            "id": 530424,
     *            "code_id": 310000000000,
     *            "position_name": "海南省",
     *            "parent_code_id": 100000000000,
     *            "level": 1
     *        },
     *        {
     *            "id": 533677,
     *            "code_id": 320000000000,
     *            "position_name": "重庆市",
     *            "parent_code_id": 100000000000,
     *            "level": 1
     *        },
     *        {
     *            "id": 545955,
     *            "code_id": 330000000000,
     *            "position_name": "四川省",
     *            "parent_code_id": 100000000000,
     *            "level": 1
     *        },
     *        {
     *            "id": 604401,
     *            "code_id": 340000000000,
     *            "position_name": "贵州省",
     *            "parent_code_id": 100000000000,
     *            "level": 1
     *        },
     *        {
     *            "id": 623533,
     *            "code_id": 350000000000,
     *            "position_name": "云南省",
     *            "parent_code_id": 100000000000,
     *            "level": 1
     *        },
     *        {
     *            "id": 639580,
     *            "code_id": 360000000000,
     *            "position_name": "西藏自治区",
     *            "parent_code_id": 100000000000,
     *            "level": 1
     *        },
     *        {
     *            "id": 645848,
     *            "code_id": 370000000000,
     *            "position_name": "陕西省",
     *            "parent_code_id": 100000000000,
     *            "level": 1
     *        },
     *        {
     *            "id": 667825,
     *            "code_id": 380000000000,
     *            "position_name": "甘肃省",
     *            "parent_code_id": 100000000000,
     *            "level": 1
     *        },
     *        {
     *            "id": 687018,
     *            "code_id": 390000000000,
     *            "position_name": "青海省",
     *            "parent_code_id": 100000000000,
     *            "level": 1
     *        },
     *        {
     *            "id": 692210,
     *            "code_id": 400000000000,
     *            "position_name": "宁夏回族自治区",
     *            "parent_code_id": 100000000000,
     *            "level": 1
     *        },
     *        {
     *            "id": 695380,
     *            "code_id": 410000000000,
     *            "position_name": "新疆维吾尔自治区",
     *            "parent_code_id": 100000000000,
     *            "level": 1
     *        },
     *        {
     *            "id": 712162,
     *            "code_id": 710000000000,
     *            "position_name": "台湾",
     *            "parent_code_id": 100000000000,
     *            "level": 1
     *        },
     *        {
     *            "id": 712563,
     *            "code_id": 810000000000,
     *            "position_name": "香港",
     *            "parent_code_id": 100000000000,
     *            "level": 1
     *        },
     *        {
     *            "id": 712585,
     *            "code_id": 820000000000,
     *            "position_name": "澳门",
     *            "parent_code_id": 100000000000,
     *            "level": 1
     *        }
     *    ]
     *}
     *
     */
    public function index(){
        $parentCodeId = input('post.parent_code_id') ? input('post.parent_code_id') : 100000000000;
        $PositionModel = new PositionModel();
        $data = $PositionModel->index($parentCodeId);

        echo json_encode(['code'=>200,'data'=>$data],JSON_UNESCAPED_UNICODE);exit;
    }

    /**
     * 根据code_id获取地区信息
     * @param $codeId
     * @return array|null|\PDOStatement|string|\think\Model
     */
    public function position($codeId){
        $PositionModel = new PositionModel();
        $position = $PositionModel->position($codeId);
        $positionName = $position['position_name'];
        return $positionName;
    }

}