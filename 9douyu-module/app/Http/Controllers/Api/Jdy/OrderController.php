<?php
/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 16/7/21
 * Time: 16:44
 */

namespace App\Http\Controllers\Api\Jdy;

use App\Http\Controllers\Controller;
use App\Tools\ToolMoney;
use Illuminate\Http\Request;
use App\Http\Logics\Pay\RechargeLogic;
use App\Http\Logics\Pay\WithdrawLogic;

class OrderController extends Controller{

    /**
     * @param Request $request
     * @return array|void
     * 创建支付订单对接module
     * todo 上线后可删除
     */
    /**
     * @SWG\Post(
     *   path="/order/recharge/doCreateApi",
     *   tags={"JDY-Api"},
     *   summary="创建支付订单 [Api\Jdy\OrderController@doCreateApi]",
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
     *     default="200"
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
    public function doCreateApi(Request $request){


        $userId     = $request->input('user_id',0);     //用户ID
        $cash       = (int)$request->input('cash',0);   //充值金额

        $cash       = ToolMoney::formatDbCashAdd($cash);
        $payChannel = $request->input('type',0);        //支付通道
        $from       = $request->input('from','');       //三端来源
        $bankId     = $request->input('bank_id',0);     //银行ID
        $cardNo     = $request->input('card_no','');    //银行卡号
        $orderId    = $request->input('order_id','');   //订单号

        $version    = $request->input('version','');    //APP版本号
        $logic      = new RechargeLogic();

        $return = $logic->createOrder($userId,$cash,$bankId,$cardNo,$payChannel,$from,$version,$orderId);

        return self::returnJson($return);
    }


    /**
     * @param Request $request
     * @return array|void
     * 支付订单成功对接module
     * todo 上线后可删除
     */
    /**
     * @SWG\Post(
     *   path="/order/recharge/doSuccApi",
     *   tags={"JDY-Api"},
     *   summary="支付订单成功对接 [Api\Jdy\OrderController@doSuccApi]",
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
     *      description="交易流水号",
     *      required=false,
     *      type="string",
     *     default=""
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="支付订单处理成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="支付订单处理失败。",
     *   )
     * )
     */
    public function doSuccApi(Request $request){


        $orderId    = $request->input('order_id','');   //订单号
        $tradeNo    = $request->input('trade_no','');    //交易流水号

        $logic      = new RechargeLogic();

        $result = $logic->doSucc($orderId,$tradeNo);

        return self::returnJson($result);
    }


    /**
     * @param Request $request
     * @return array|void
     * 支付订单失败对接module
     * todo 上线后可删除
     */
    /**
     * @SWG\Post(
     *   path="/order/recharge/doFailedApi",
     *   tags={"JDY-Api"},
     *   summary="支付订单成功对接 [Api\Jdy\OrderController@doFailedApi]",
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
     *      description="交易流水号",
     *      required=false,
     *      type="string",
     *     default=""
     *   ),
     *    @SWG\Parameter(
     *      name="note",
     *      in="formData",
     *      description="支付失败原因",
     *      required=false,
     *      type="string",
     *     default=""
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="支付订单处理成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="支付订单处理失败。",
     *   )
     * )
     */
    public function doFailedApi(Request $request){


        $orderId    = $request->input('order_id','');   //订单号
        $tradeNo    = $request->input('trade_no','');    //交易流水号
        $note       = $request->input('note','');//支付失败原因

        $logic      = new RechargeLogic();

        $result = $logic->doFailed($orderId,$tradeNo,$note);

        return self::returnJson($result);
    }


    /**
     * @SWG\Post(
     *   path="/pay/withdraw/submitApi",
     *   tags={"JDY-Api"},
     *   summary="九斗鱼对接提现创建订单 [Api\Jdy\OrderController@submitApi]",
     *   @SWG\Parameter(
     *      name="name",
     *      in="formData",
     *      description="发送请求的模块名称",
     *      required=true,
     *      type="string",
     *      default="cli_test_user",
     *   ),
     *   @SWG\Parameter(
     *      name="version",
     *      in="formData",
     *      description="版本号",
     *      required=false,
     *      type="string",
     *      default="",
     *   ),
     *   @SWG\Parameter(
     *      name="token",
     *      in="formData",
     *      description="token",
     *      required=false,
     *      type="string",
     *      default="",
     *   ),
     *   @SWG\Parameter(
     *      name="userId",
     *      in="formData",
     *      description="用户Id",
     *      required=true,
     *      type="integer",
     *      default="1",
     *   ),
     *   @SWG\Parameter(
     *      name="cash",
     *      in="formData",
     *      description="提现金额",
     *      required=true,
     *      type="integer",
     *      default="100",
     *   ),
     *   @SWG\Parameter(
     *      name="trading_password",
     *      in="formData",
     *      description="交易密码",
     *      required=true,
     *      type="string",
     *      default="",
     *   ),
     *   @SWG\Parameter(
     *      name="bank_id",
     *      in="formData",
     *      description="银行卡ID",
     *      required=true,
     *      type="integer",
     *      default="1",
     *   ),
     *   @SWG\Parameter(
     *      name="card_no",
     *      in="formData",
     *      description="银行卡号",
     *      required=true,
     *      type="string",
     *      default="",
     *   ),
     *   @SWG\Parameter(
     *      name="from",
     *      in="formData",
     *      description="来源",
     *      required=true,
     *      type="string",
     *      default="pc",
     *      enum={"pc","wap","ios","android"}
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="提现成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="提现失败。",
     *   )
     * )
     */
    public function submitApi(Request $request){
        $data = $request->all();
        $withdrawLogic = new WithdrawLogic();
        //验证数据
        $vaild = $withdrawLogic->vaildData($data);
        if(!$vaild['status']){
            return self::returnJson($vaild);
        }
        //创建订单
        $result = $withdrawLogic->createOrder($data);

        return self::returnJson($result);
    }

}