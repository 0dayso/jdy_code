<?php
/**
 * User: zhangshuang
 * Date: 16/4/13
 * Time: 15:41
 * Desc: 提现相关的控制器
 */

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Http\Logics\Order\WithdrawLogic;
use Illuminate\Http\Request;
use App\Tools\ToolMoney;
use App\Http\Logics\Order\OrderLogic;

class WithdrawController extends Controller{


    private $logic = null;

    public function __construct(Request $request){


        parent::__construct($request);
        
        $this->logic = new WithdrawLogic();

    }

    /**
     * @SWG\Post(
     *   path="/withdraw/order/create",
     *   tags={"Order"},
     *   summary="创建提现订单",
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
     *    @SWG\Parameter(
     *      name="handing_fee",
     *      in="formData",
     *      description="手续费(若无手续费可不传)",
     *      required=false,
     *      type="integer",
     *     default="5"
     *   ),
     *     @SWG\Parameter(
     *      name="cash",
     *      in="formData",
     *      description="提现金额",
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
     *     @SWG\Parameter(
     *      name="type",
     *      in="formData",
     *      description="提现方式(2000-T+1)",
     *      required=true,
     *      type="integer",
     *     default="2000"
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
     *     description="创建提现订单成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="创建提现订单失败。",
     *   )
     * )
     */
    public function makeOrder(Request $request){

        $orderId    = $request->input('order_id','');//订单号
        $userId     = $request->input('user_id',0); //用户ID
        $handingFee = $request->input('handing_fee',0);//提现手续费
        $cash       = $request->input('cash',0); //充值金额(单位：元)
        $bankId     = $request->input('bank_id',0);//银行ID
        $cardNo     = $request->input('card_no','');//银行卡号
        $type       = $request->input('type',0);//提现类型
        $from       = $request->input('from','');//来源平台 wap pc android ios
        $version    = $request->input('version','');//app端设备版本号

        //金额转化为分
        $cash       = ToolMoney::formatDbCashAdd($cash);
        $handingFee = ToolMoney::formatDbCashAdd($handingFee);

        $result     = $this->logic->makeOrder($orderId,$userId,$handingFee,$cash,$bankId,$cardNo,$type,$from,$version);

        return self::returnJson($result);
    }



    /**
     * @SWG\Post(
     *   path="/withdraw/order/failed",
     *   tags={"Order"},
     *   summary="提现失败订单处理",
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

        $result = $this->logic->failedOrder($orderId,$tradeNo,$note);

        return self::returnJson($result);
        
    }

    /**
     * @SWG\Post(
     *   path="/withdraw/order/success",
     *   tags={"Order"},
     *   summary="提现成功订单处理",
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

        $result = $this->logic->succOrder($orderId,$tradeNo);

        return self::returnJson($result);
    }


    /**
     * @SWG\Post(
     *   path="/withdraw/order/submitToBank",
     *   tags={"Order"},
     *   summary="提现订单提交银行处理",
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
     *     description="提现订单提交银行处理成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="提现订单提交银行处理失败。",
     *   )
     * )
     */
    public function submitToBank(Request $request){


        $orderId    = $request->input('order_id','');//订单号
        $result     = $this->logic->submitToBank($orderId);

        return self::returnJson($result);
    }

    /**
     * @SWG\Post(
     *   path="/withdraw/order/cancel",
     *   tags={"Order"},
     *   summary="取消提现",
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
     *      description="取消原因",
     *      required=true,
     *      type="string",
     *      default="手续费过高"
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="取消提现处理成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="取消提现处理失败。",
     *   )
     * )
     */
    public function cancelOrder(Request $request){

        $orderId   = $request->input('order_id','');//订单号
        $note      = $request->input('note','');//取消原因

        $result = $this->logic->cancelOrder($orderId,$note);

        return self::returnJson($result);
    }


    /**
     * @SWG\Post(
     *   path="/withdraw/getNum",
     *   tags={"Order"},
     *   summary="获取本月已提现次数(排除提现失败订单)",
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
     *      type="string",
     *      default="82692"
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="获取本月有效提现次数成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="获取本月有效提现次数成功。",
     *   )
     * )
     */
    public function getWithdrawNum(Request $request){
        
        $userId = $request->input('user_id','');//用户ID
        
        $result = $this->logic->getWithdrawNum($userId);

        return self::returnJson($result);
    }

    /**
     * @SWG\Post(
     *   path="/withdraw/order/getWithdrawOrders",
     *   tags={"Order"},
     *   summary="获取所有提现订单",
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
     *     @SWG\Parameter(
     *      name="type",
     *      in="formData",
     *      description="订单类型 (1-充值  2-提现)",
     *      required=true,
     *      type="string",
     *      default="2"
     *   ),
     *   @SWG\Parameter(
     *      name="page",
     *      in="formData",
     *      description="页码",
     *      required=true,
     *      type="integer",
     *      default="1"
     *   ),
     *   @SWG\Parameter(
     *      name="size",
     *      in="formData",
     *      description="每页量",
     *      required=true,
     *      type="integer",
     *      default="20"
     *   ),
     *   @SWG\Parameter(
     *      name="userId",
     *      in="formData",
     *      description="用户ID",
     *      required=false,
     *      type="integer",
     *      default=""
     *   ),
     *   @SWG\Parameter(
     *      name="order_id",
     *      in="formData",
     *      description="订单ID",
     *      required=false,
     *      type="integer",
     *      default=""
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
     *      name="status",
     *      in="formData",
     *      description="订单状态(0-全部 200-成功，300-待处理，301-处理中402-取消提现，500-失败)",
     *      required=true,
     *      type="array",
     *      @SWG\Items(type="integer"),
     *      collectionFormat="multi",
     *      default="0",
     *      enum={"0","200","300","301","402","500"}
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="获取所有提现订单成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="获取所有提现订单失败。",
     *   )
     * )
     */
    public function getWithdrawOrders(Request $request){

        $all = $request->all();

        $logic = new OrderLogic();

        $result = $logic->getAdminList($all);

        return self::returnJson($result);
        
    }
    /**
     * @SWG\Post(
     *   path="/withdraw/order/getWithdrawStatistics",
     *   tags={"Order"},
     *   summary="获取提现统计数据",
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
     *      description="用户id",
     *      required=false,
     *      type="string",
     *      default=""
     *   ),
     *   @SWG\Parameter(
     *      name="app_request",
     *      in="formData",
     *      description="提现订单来源",
     *      required=false,
     *      type="string",
     *      default="pc"
     *   ),
     *   @SWG\Parameter(
     *      name="status",
     *      in="formData",
     *      description="订单状态(0-全部 200-成功，300-待处理，301-处理中，401-超时订单，402-取消提现，500-失败)",
     *      required=true,
     *      type="array",
     *      @SWG\Items(type="integer"),
     *      collectionFormat="multi",
     *      default="0",
     *      enum={"0","200","300","301","401","402","500"}
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="获取提现数据统计成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="获取提现数据统计失败。",
     *   )
     * )
     */
    public function getWithdrawStatistics(Request $request){
        $all = $request->all();

        $result = $this->logic->getWithdrawStatistics($all);

        return self::returnJson($result);
    }
    /**
     * @SWG\Post(
     *   path="/withdraw/order/getWithdrawUserCashFive",
     *   tags={"Order"},
     *   summary="获取时间段内提现大于5万用户的信息",
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
     *     description="获取时间段内提现大于5万用户的信息成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="获取时间段内提现大于5万用户的信息失败。",
     *   )
     * )
     */
    public function getWithdrawUserCashFive(Request $request){
        $all = $request->all();

        $result = $this->logic->getWithdrawUserCashFive($all);

        return self::returnJson($result);
    }

}