<?php
/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 16/6/16
 * Time: 09:58
 */
namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Http\Logics\Order\OrderLogic;
use Illuminate\Http\Request;

class OrderController extends Controller{

    /**
     * @SWG\Post(
     *   path="/order/get",
     *   tags={"Order"},
     *   summary="查询订单信息",
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
     *    @SWG\Parameter(
     *      name="order_id",
     *      in="formData",
     *      description="订单号(订单格式以JDY_为前缀)",
     *      required=true,
     *      type="string",
     *      default="JDY_201606052200248596"
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="查询订单信息成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="查询订单信息失败。",
     *   )
     * )
     */
    public function getOrder(Request $request){

        $orderId   = $request->input('order_id','');    //订单号

        $logic      = new OrderLogic();

        $result     = $logic->getOrder($orderId);

        return self::returnJson($result);
    }


    /**
     * @SWG\Post(
     *   path="/order/getList",
     *   tags={"Order"},
     *   summary="根据类型、用户ID、偏移量、每页条数 获取列表数据",
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
     *      default="10"
     *   ),
     *   @SWG\Parameter(
     *      name="type",
     *      in="formData",
     *      description="类型【1 充值 2 提现】",
     *      required=true,
     *      type="array",
     *      @SWG\Items(type="string"),
     *      default="1",
     *      enum={"1", "2"}
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="获取订单信息成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="获取订单信息失败。",
     *   )
     * )
     */
    public function getOrderLists(Request $request){
        $data             = $request->all();

        $orderLogic       = new OrderLogic;

        $result           = $orderLogic->getLists($data);

        self::returnJson($result);
    }
    

    /**
     * @SWG\Post(
     *   path="/order/getAdminList",
     *   tags={"Order"},
     *   summary="根据支付通道、状态\用户ID、偏移量、每页条数 获取列表数据",
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
     *      name="page",
     *      in="formData",
     *      description="页码",
     *      required=true,
     *      type="integer",
     *      default="1"
     *   ),
     *   @SWG\Parameter(
     *      name="type",
     *      in="formData",
     *      description="订单类型 (1-充值  2-提现)",
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
     *      name="pay_type",
     *      in="formData",
     *      description="支付渠道(0-全部 1000-京东网银，1001-融宝网银，1101-连连认证支付，1102-易宝认证支付，1201-钱袋宝代扣，1202-联动优势代扣，1203-翼支付代扣，1204-融宝代扣)",
     *      required=true,
     *      type="array",
     *      @SWG\Items(type="integer"),
     *      collectionFormat="multi",
     *      default="0",
     *      enum={"0","1000","1001","1101","1102","1201","1202","1203","1204"}
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
     *     description="获取资金流水成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="获取资金流水失败。",
     *   )
     * )
     */
    public function getAdminList(Request $request){

        $all = $request->all();

        $logic = new OrderLogic();

        $result = $logic->getAdminList($all);

        return self::returnJson($result);
    }
}