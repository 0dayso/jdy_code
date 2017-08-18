<?php
/**
 * User: zhangshuang
 * Date: 16/4/13
 * Time: 15:41
 * Desc: 充值订单相关控制器
 */

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Http\Logics\Order\OrderLogic;
use Illuminate\Http\Request;
use App\Http\Logics\Order\RechargeLogic;
use App\Tools\ToolMoney;

class RechargeController extends Controller{

    private $logic = null;

    public function __construct(Request $request){

        parent::__construct($request);

        $this->logic = new RechargeLogic();

    }

    /**
     * @SWG\Post(
     *   path="/recharge/order/create",
     *   tags={"Order"},
     *   summary="创建充值订单",
     *   @SWG\Parameter(
     *      name="name",
     *      in="formData",
     *      description="发送请求的模块名称",
     *      required=true,
     *      type="string",
     *      default="cli_test_user",
     *   ),
     *    @SWG\Parameter(
     *      name="sign",
     *      in="formData",
     *      description="数据校验",
     *      required=true,
     *      type="string",
     *   ),
     *   @SWG\Parameter(
     *      name="order_id",
     *      in="formData",
     *      description="订单号(订单格式以JDY_为前缀)",
     *      required=true,
     *      type="string",
     *      default="JDY_201606052200248596"
     *   ),
     *    @SWG\Parameter(
     *      name="user_id",
     *      in="formData",
     *      description="用户ID",
     *      required=true,
     *      type="integer",
     *     default="82692"
     *   ),
     *     @SWG\Parameter(
     *      name="cash",
     *      in="formData",
     *      description="充值金额",
     *      required=true,
     *      type="integer",
     *     default="2"
     *   ),
     *     @SWG\Parameter(
     *      name="bank_id",
     *      in="formData",
     *      description="银行ID",
     *      required=true,
     *      type="integer",
     *     default="6"
     *   ),
     *     @SWG\Parameter(
     *      name="card_no",
     *      in="formData",
     *      description="银行卡号",
     *      required=true,
     *      type="integer",
     *   ),
     *   @SWG\Parameter(
     *      name="type",
     *      in="formData",
     *      description="支付渠道(1000-京东网银，1001-融宝网银，1101-连连认证支付，1102-易宝认证支付，1201-钱袋宝代扣，1202-联动优势代扣，1203-翼支付代扣，1204-融宝代扣)",
     *      required=true,
     *      type="array",
     *      @SWG\Items(type="integer"),
     *      collectionFormat="multi",
     *      default="1101",
     *      enum={"1000","1001","1101","1102","1201","1202","1203","1204"}
     *   ),
     *    @SWG\Parameter(
     *      name="from",
     *      in="formData",
     *      description="三端来源",
     *      required=true,
     *      type="array",
     *      @SWG\Items(type="string"),
     *      collectionFormat="multi",
     *      default="pc",
     *      enum={"pc", "wap", "android","ios"}
     *   ),
     *     @SWG\Parameter(
     *      name="version",
     *      in="formData",
     *      description="App版本号",
     *      required=false,
     *      type="string",
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="创建充值订单成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="创建充值订单失败。",
     *   )
     * )
     */
    public function makeOrder(Request $request){

        $orderId   = $request->input('order_id','');//订单号
        $userId    = $request->input('user_id',0); //用户ID
        $cash      = $request->input('cash',0); //充值金额(单位：元)
        $bankId    = $request->input('bank_id',0);//银行ID
        $cardNo    = $request->input('card_no','');//银行卡号
        $type      = $request->input('type',0);//支付渠道
        $from      = $request->input('from','');//来源平台 wap pc android ios
        $version   = $request->input('version','');//app端设备版本号

        //金额转化为分
        $cash = ToolMoney::formatDbCashAdd($cash);

        $result     = $this->logic->makeOrder($orderId,$userId,$cash,$bankId,$cardNo,$type,$from,$version);

        return self::returnJson($result);
    }


    /**
     * @SWG\Post(
     *   path="/recharge/order/success",
     *   tags={"Order"},
     *   summary="充值成功订单处理",
     *   @SWG\Parameter(
     *      name="name",
     *      in="formData",
     *      description="发送请求的模块名称",
     *      required=true,
     *      type="string",
     *      default="cli_test_user",
     *   ),
     *    @SWG\Parameter(
     *      name="sign",
     *      in="formData",
     *      description="数据校验",
     *      required=true,
     *      type="string",
     *   ),
     *   @SWG\Parameter(
     *      name="order_id",
     *      in="formData",
     *      description="订单号(订单格式以JDY_为前缀)",
     *      required=true,
     *      type="string",
     *      default="JDY_201606052200248596"
     *   ),
     *     @SWG\Parameter(
     *      name="trade_no",
     *      in="formData",
     *      description="支付流水号",
     *      required=false,
     *      type="string",
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="成功订单处理成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="成功订单处理失败。",
     *   )
     * )
     */
    public function succOrder(Request $request){

        $orderId   = $request->input('order_id','');//订单号
        $tradeNo   = $request->input('trade_no','');//流水号

        $result     = $this->logic->succOrder($orderId,$tradeNo);

        return self::returnJson($result);
    }


    /**
     * @SWG\Post(
     *   path="/recharge/order/failed",
     *   tags={"Order"},
     *   summary="充值失败订单处理",
     *   @SWG\Parameter(
     *      name="name",
     *      in="formData",
     *      description="发送请求的模块名称",
     *      required=true,
     *      type="string",
     *      default="cli_test_user",
     *   ),
     *    @SWG\Parameter(
     *      name="sign",
     *      in="formData",
     *      description="数据校验",
     *      required=true,
     *      type="string",
     *   ),
     *   @SWG\Parameter(
     *      name="order_id",
     *      in="formData",
     *      description="订单号(订单格式以JDY_为前缀)",
     *      required=true,
     *      type="string",
     *      default="JDY_201606052200248596"
     *   ),
     *   @SWG\Parameter(
     *      name="trade_no",
     *      in="formData",
     *      description="支付流水号",
     *      required=false,
     *      type="string",
     *   ),
     *   @SWG\Parameter(
     *      name="note",
     *      in="formData",
     *      description="支付原因",
     *      required=false,
     *      type="string",
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="失败订单处理成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="失败订单处理失败。",
     *   )
     * )
     */
    public function failedOrder(Request $request){

        $orderId   = $request->input('order_id','');//订单号
        $tradeNo   = $request->input('trade_no','');//流水号
        $note      = $request->input('note','');    //失败原因

        $result     = $this->logic->failedOrder($orderId,$tradeNo,$note);

        return self::returnJson($result);
    }


    /**
     * @SWG\Post(
     *   path="/recharge/order/timeout",
     *   tags={"Order"},
     *   summary="充值超时订单处理",
     *   @SWG\Parameter(
     *      name="name",
     *      in="formData",
     *      description="发送请求的模块名称",
     *      required=true,
     *      type="string",
     *      default="cli_test_user",
     *   ),
     *    @SWG\Parameter(
     *      name="sign",
     *      in="formData",
     *      description="数据校验",
     *      required=true,
     *      type="string",
     *   ),
     *   @SWG\Parameter(
     *      name="order_id",
     *      in="formData",
     *      description="订单号(订单格式以JDY_为前缀)",
     *      required=true,
     *      type="string",
     *      default="JDY_201606052200248596"
     *   ),
     *   @SWG\Parameter(
     *      name="note",
     *      in="formData",
     *      description="超时原因",
     *      required=false,
     *      type="string",
     *      default=""
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="超时订单处理成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="超时订单处理失败。",
     *   )
     * )
     */
    public function timeoutOrder(Request $request){

        $orderId   = $request->input('order_id','');//订单号
        $note      = $request->input('note','');//超时原因
        $result     = $this->logic->timeoutOrder($orderId,$note);

        return self::returnJson($result);
    }



    /**
     * @SWG\Post(
     *   path="/recharge/order/getInvalidOrderNum",
     *   tags={"Order"},
     *   summary="获取用户支付通道当日无效订单数量",
     *   @SWG\Parameter(
     *      name="name",
     *      in="formData",
     *      description="发送请求的模块名称",
     *      required=true,
     *      type="string",
     *      default="cli_test_user",
     *   ),
     *    @SWG\Parameter(
     *      name="sign",
     *      in="formData",
     *      description="数据校验",
     *      required=true,
     *      type="string",
     *   ),
     *   @SWG\Parameter(
     *      name="user_id",
     *      in="formData",
     *      description="用户ID",
     *      required=true,
     *      type="integer",
     *     default="82692"
     *   ),
     *   @SWG\Parameter(
     *      name="type",
     *      in="formData",
     *      description="支付渠道(1000-京东网银，1001-融宝网银，1101-连连认证支付，1102-易宝认证支付，1201-钱袋宝代扣，1202-联动优势代扣，1203-翼支付代扣，1204-融宝代扣)",
     *      required=true,
     *      type="array",
     *      @SWG\Items(type="integer"),
     *      collectionFormat="multi",
     *      default="1101",
     *      enum={"1000","1001","1101","1102","1201","1202","1203","1204"}
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="获取用户支付通道当日无效订单数量成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="获取用户支付通道当日无效订单数量失败。",
     *   )
     * )
     */
    public function getUserTodayInvalidOrderNumByPayChannel(Request $request){

        $userId    = $request->input('user_id','');//用户ID
        $payType   = $request->input('type',0); //支付通道

        $result     = $this->logic->getUserTodayInvalidOrderNumByPayChannel($userId,$payType);

        return self::returnJson($result);
    }


    /**
     * @SWG\Post(
     *   path="/recharge/order/getSuccOrderNum",
     *   tags={"Order"},
     *   summary="获取用户除网银支付外的成功充值订单数量",
     *   @SWG\Parameter(
     *      name="name",
     *      in="formData",
     *      description="发送请求的模块名称",
     *      required=true,
     *      type="string",
     *      default="cli_test_user",
     *   ),
     *    @SWG\Parameter(
     *      name="sign",
     *      in="formData",
     *      description="数据校验",
     *      required=true,
     *      type="string",
     *   ),
     *   @SWG\Parameter(
     *      name="user_id",
     *      in="formData",
     *      description="用户ID",
     *      required=true,
     *      type="integer",
     *     default="82692"
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="获取用户除网银支付外的成功充值订单数量成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="获取用户除网银支付外的成功充值订单数量失败。",
     *   )
     * )
     */
    public function getUserSuccOrderNum(Request $request){

        $userId = $request->input('user_id',0);

        $result = $this->logic->getUserSuccOrderNum($userId);

        return self::returnJson($result);


    }


    /**
     * @SWG\Post(
     *   path="/recharge/order/getSuccPayChannel",
     *   tags={"Order"},
     *   summary="获取用户非网银支付成功的充值渠道列表",
     *   @SWG\Parameter(
     *      name="name",
     *      in="formData",
     *      description="发送请求的模块名称",
     *      required=true,
     *      type="string",
     *      default="cli_test_user",
     *   ),
     *    @SWG\Parameter(
     *      name="sign",
     *      in="formData",
     *      description="数据校验",
     *      required=true,
     *      type="string",
     *   ),
     *   @SWG\Parameter(
     *      name="user_id",
     *      in="formData",
     *      description="用户ID",
     *      required=true,
     *      type="integer",
     *     default="82692"
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="获取用户非网银支付成功的充值渠道列表成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="获取用户非网银支付成功的充值渠道列表失败。",
     *   )
     * )
     */
    public function getLastedSuccPayChannel(Request $request){

        $userId = $request->input('user_id',0);

        $result = $this->logic->getLastedSuccPayChannel($userId);

        return self::returnJson($result);
    }


   
    /**
     * @SWG\Post(
     *   path="/recharge/order/getRechargeStatistics",
     *   tags={"Order"},
     *   summary="获取充值统计数据",
     *   @SWG\Parameter(
     *      name="name",
     *      in="formData",
     *      description="发送请求的模块名称",
     *      required=true,
     *      type="string",
     *      default="cli_test_user",
     *   ),
     *    @SWG\Parameter(
     *      name="sign",
     *      in="formData",
     *      description="数据校验",
     *      required=true,
     *      type="string",
     *      default="209c02k29",
     *   ),
     *   @SWG\Parameter(
     *      name="start_time",
     *      in="formData",
     *      description="开始时间",
     *      required=false,
     *      type="string",
     *      default=""
     *   ),
     *   @SWG\Parameter(
     *      name="end_time",
     *      in="formData",
     *      description="结束时间",
     *      required=false,
     *      type="string",
     *      default=""
     *   ),
     *   @SWG\Parameter(
     *      name="userId",
     *      in="formData",
     *      description="用户ID",
     *      required=false,
     *      type="string",
     *      default=""
     *   ),
     *   @SWG\Parameter(
     *      name="app_request",
     *      in="formData",
     *      description="订单来源",
     *      required=false,
     *      type="string",
     *      default="pc"
     *   ),
     *   @SWG\Parameter(
     *      name="pay_type",
     *      in="formData",
     *      description="支付渠道(0-全部 1000-京东网银，1001-融宝网银，1101-连连认证支付，1102-易宝认证支付，1201-钱袋宝代扣，1202-联动优势代扣，1203-翼支付代扣，1204-融宝代扣)",
     *      required=false,
     *      type="array",
     *      @SWG\Items(type="integer"),
     *      collectionFormat="multi",
     *      default="0",
     *      enum={"0","1000","1001","1101","1102","1201","1202","1203","1204"}
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="获取充值数据统计成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="获取充值数据统计失败。",
     *   )
     * )
     */
    public function getRechargeStatistics(Request $request){

        $all = $request->all();

        $result = $this->logic->getRechargeStatistics($all);

        return self::returnJson($result);

    }

    /**
     * @SWG\Post(
     *   path="/recharge/order/missSuccess",
     *   tags={"Order"},
     *   summary="掉单处理",
     *   @SWG\Parameter(
     *      name="name",
     *      in="formData",
     *      description="发送请求的模块名称",
     *      required=true,
     *      type="string",
     *      default="cli_test_user",
     *   ),
     *    @SWG\Parameter(
     *      name="sign",
     *      in="formData",
     *      description="数据校验",
     *      required=true,
     *      type="string",
     *   ),
     *   @SWG\Parameter(
     *      name="order_id",
     *      in="formData",
     *      description="订单号(订单格式以JDY_为前缀)",
     *      required=true,
     *      type="string",
     *      default="JDY_201606052200248596"
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="掉单处理成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="掉单处理失败。",
     *   )
     * )
     */
    public function missOrderHandle(Request $request){
        
        $orderId = $request->input('order_id','');
        
        $result = $this->logic->missOrderHandle($orderId);

        return self::returnJson($result);
    }

    /**
     * @SWG\Post(
     *   path="/recharge/order/getFailRechargeOrderByTime",
     *   tags={"Order"},
     *   summary="获取某段时间内充值失败的用户订单信息",
     *   @SWG\Parameter(
     *      name="name",
     *      in="formData",
     *      description="发送请求的模块名称",
     *      required=true,
     *      type="string",
     *      default="cli_test_user",
     *   ),
     *    @SWG\Parameter(
     *      name="sign",
     *      in="formData",
     *      description="数据校验",
     *      required=true,
     *      type="string",
     *   ),
     *   @SWG\Parameter(
     *      name="start_time",
     *      in="formData",
     *      description="开始时间",
     *      required=false,
     *      type="string",
     *      default=""
     *   ),
     *   @SWG\Parameter(
     *      name="end_time",
     *      in="formData",
     *      description="结束时间",
     *      required=false,
     *      type="string",
     *      default=""
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="获取某段时间内充值失败的用户订单信息成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="获取某段时间内充值失败的用户订单信息失败。",
     *   )
     * )
     */
    public function getFailRechargeOrderByTime(Request $request){

        $startTime = $request->input('start_time');
        $endTime   = $request->input('end_time');

        $result = $this->logic->getFailRechargeOrderByTime($startTime, $endTime);

        return self::returnJson($result);
    }

    /**
     * @param Request $request
     * @return array
     * @desc 充值订单总额
     */
    public function getRechargeOrderTotal( Request $request)
    {
        $all    =   $request->all();

        $result =   $this->logic->getRechargeOrderTotal($all);

        return self::returnJson($result);
    }
}