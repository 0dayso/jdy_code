<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 16/6/25
 * Time: 下午2:51
 * Desc: 提现订单
 */
namespace App\Http\Controllers\AppApi\V4_0\Order;

use App\Http\Controllers\AppApi\AppController;
use App\Http\Logics\AppLogic;
use App\Http\Logics\Order\WithdrawLogic;
use App\Http\Logics\RequestSourceLogic;
use App\Tools\ToolMoney;
use Illuminate\Http\Request;

class WithdrawController extends AppController{

    /**
     * @SWG\Post(
     *   path="/pre_withdraw",
     *   tags={"APP-Order"},
     *   summary="提现预览 [Order\WithdrawController@preDoWithdraw']",
     *   @SWG\Parameter(
     *      name="name",
     *      in="formData",
     *      description="发送请求的模块名称",
     *      required=true,
     *      type="string",
     *      default="cli_test_user",
     *   ),
     *  @SWG\Parameter(
     *      name="client",
     *      in="formData",
     *      description="客户端来源",
     *      required=true,
     *      type="array",
     *      @SWG\Items(type="string"),
     *      collectionFormat="multi",
     *      default="ios",
     *      enum={"ios","android"}
     *   ),
     *   @SWG\Parameter(
     *      name="version",
     *      in="formData",
     *      description="版本号",
     *      required=true,
     *      type="string",
     *      default="4.0.0",
     *   ),
     *   @SWG\Parameter(
     *      name="token",
     *      in="formData",
     *      description="token",
     *      required=true,
     *      type="string",
     *      default="653030e9f8e4f6559669386dfe4f56d4",
     *   ),
     *   @SWG\Parameter(
     *      name="cash",
     *      in="formData",
     *      description="提现金额",
     *      required=true,
     *      type="integer",
     *      default="100",
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="提现预览成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="提现预览失败。",
     *   )
     * )
     */
    public function preDoWithdraw(Request $reqeust){

        $userId     = $this->getUserId();
        $cash       = (float)$reqeust->input('cash',0);

        $cash       = ToolMoney::formatDbCashAdd($cash);
        
        $logic      = new WithdrawLogic();

        $result = $logic->preWithdraw($userId,$cash);

        if(!$result['status'])
            $result['code'] = AppLogic::CODE_ERROR;

        return $this->returnJsonData($result);
    }


    /**
     * @SWG\Post(
     *   path="/user_withdraw",
     *   tags={"APP-Order"},
     *   summary="创建提现订单 [Order\WithdrawController@doWithdraw]",
     *   @SWG\Parameter(
     *      name="name",
     *      in="formData",
     *      description="发送请求的模块名称",
     *      required=true,
     *      type="string",
     *      default="cli_test_user",
     *   ),
     *  @SWG\Parameter(
     *      name="client",
     *      in="formData",
     *      description="客户端来源",
     *      required=true,
     *      type="array",
     *      @SWG\Items(type="string"),
     *      collectionFormat="multi",
     *      default="ios",
     *      enum={"ios","android"}
     *   ),
     *   @SWG\Parameter(
     *      name="version",
     *      in="formData",
     *      description="版本号",
     *      required=true,
     *      type="string",
     *      default="4.0.0",
     *   ),
     *   @SWG\Parameter(
     *      name="token",
     *      in="formData",
     *      description="token",
     *      required=true,
     *      type="string",
     *      default="653030e9f8e4f6559669386dfe4f56d4",
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
     *      name="bank_card_id",
     *      in="formData",
     *      description="提现银行卡ID",
     *      required=true,
     *      type="integer",
     *      default="",
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
    public function doWithdraw(Request $request){


        $data               = $request->all();
        $data['user_id']    = $this->getUserId();
        $cash               = (float)$request->input('cash',0);
        $data['cash']       = ToolMoney::formatDbCashAdd($cash);
        $data['from']       = RequestSourceLogic::getSource();

        $logic              = new WithdrawLogic();
        $result = $logic->doWithdraw($data);

        if(!$result['status'])
            $result['code'] = AppLogic::CODE_ERROR;

        return $this->returnJsonData($result);
    }
}