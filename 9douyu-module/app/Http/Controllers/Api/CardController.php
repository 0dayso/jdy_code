<?php
/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 16/7/23
 * Time: 11:29
 * 融宝卡鉴权对外接口
 */

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Tools\IPLimit;
use Illuminate\Http\Request;
use App\Http\Logics\BankCard\CardLogic;
use Log;

class CardController extends Controller{

    public function __construct(Request $request){

        $ip =  $request->ip();

        $requestData = $request->all();
        $requestData['ip'] = $ip;

        Log::info('check_card_request_data',$requestData);

        //ip判断
        $result = IPLimit::coreRequestIpCheck($ip);

        if(!$result['status']){

            return self::returnJson($result);

            exit();
        }

    }

    /**
     * @param Request $request
     * 融宝储蓄卡三\四要素鉴权
     */
    /**
     * @SWG\Post(
     *   path="/checkDepositCard",
     *   summary="融宝储蓄卡三\四要素鉴权 [Api\CardController@checkDepositCard]",
     *   @SWG\Parameter(
     *      name="userName",
     *      in="formData",
     *      description="姓名",
     *      required=true,
     *      type="string",
     *      default="",
     *   ),
     *   @SWG\Parameter(
     *      name="userIdentity",
     *      in="formData",
     *      description="身份证号",
     *      required=true,
     *      type="string",
     *      default="",
     *   ),
     *   @SWG\Parameter(
     *      name="cardNo",
     *      in="formData",
     *      description="银行卡号",
     *      required=true,
     *      type="string",
     *      default="",
     *   ),
     *   @SWG\Parameter(
     *      name="phone",
     *      in="formData",
     *      description="手机号",
     *      required=false,
     *      type="string",
     *      default="",
     *   ),
     *   @SWG\Parameter(
     *      name="partnerId",
     *      in="formData",
     *      description="商户ID",
     *      required=true,
     *      type="integer",
     *      default=""
     *   ),
     *   @SWG\Parameter(
     *      name="tradeNo",
     *      in="formData",
     *      description="交易流水号",
     *      required=false,
     *      type="integer",
     *      default=""
     *   ),
     *   @SWG\Parameter(
     *      name="sign",
     *      in="formData",
     *      description="签名",
     *      required=true,
     *      type="string",
     *      default="",
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="储蓄卡鉴权成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="储蓄卡鉴权失败。",
     *   )
     * )
     */
    public function checkDepositCard(Request $request){

        $name           = $request->input('userName','');
        $idCard         = $request->input('userIdentity','');
        $cardNo         = $request->input('cardNo','');
        $phone          = $request->input('phone','');
        $partnerId      = $request->input('partnerId','');
        $tradeNo        = $request->input('tradeNo','');
        $sign           = $request->input('sign','');


        $cardLogic = new CardLogic();
        $result = $cardLogic->checkDepositCard($name,$idCard,$cardNo,$phone,$partnerId,$tradeNo,$sign);

        return self::returnJson($result);

    }

    /**
     * @param Request $request
     * 融宝信用卡四\六要素鉴权
     */

    /**
     * @SWG\Post(
     *   path="/api/checkCard",
     *   summary="融宝储蓄卡/信用卡鉴权 [Api\CardController@checkCard] 快金专用",
     *   @SWG\Parameter(
     *      name="sign",
     *      in="formData",
     *      description="签名",
     *      required=true,
     *      type="string",
     *      default="",
     *   ),
     *   @SWG\Parameter(
     *      name="name",
     *      in="formData",
     *      description="姓名",
     *      required=true,
     *      type="string",
     *      default="",
     *   ),
     *   @SWG\Parameter(
     *      name="id_card",
     *      in="formData",
     *      description="身份证号",
     *      required=true,
     *      type="string",
     *      default="",
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
     *      name="phone",
     *      in="formData",
     *      description="手机号",
     *      required=true,
     *      type="string",
     *      default="",
     *   ),
     *   @SWG\Parameter(
     *      name="cvv2",
     *      in="formData",
     *      description="信用卡后三位校验码",
     *      required=false,
     *      type="integer",
     *      default=""
     *   ),
     *   @SWG\Parameter(
     *      name="validthru",
     *      in="formData",
     *      description="信用卡用效期",
     *      required=false,
     *      type="string",
     *      default="",
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="信用卡鉴权成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="信用卡鉴权失败。",
     *   )
     * )
     */
    public function checkCard(Request $request){

        $params = $request->all();

        $cardLogic = new CardLogic();
        $result = $cardLogic->checkCard($params);

        return self::returnJson($result);

    }


    /**
     * @param Request $request
     * 融宝信用卡四\六要素鉴权
     */

    /**
     * @SWG\Post(
     *   path="/api/fetchCardInfo",
     *   summary="卡bin接口 [Api\CardController@checkCard] 快金专用",
     *   @SWG\Parameter(
     *      name="sign",
     *      in="formData",
     *      description="签名",
     *      required=true,
     *      type="string",
     *      default="",
     *   ),
     *   @SWG\Parameter(
     *      name="card_no",
     *      in="formData",
     *      description="银行卡号",
     *      required=true,
     *      type="string",
     *      default="",
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="银行卡信息获取成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="银行卡信息获取失败。",
     *   )
     * )
     */
    public function fetchCardInfo(Request $request){

        $cardNo = $request->input('card_no','');

        $sign   = $request->input('sign','');

        $cardLogic = new CardLogic();
        $result = $cardLogic->fetchCardInfo($cardNo,$sign);

        return self::returnJson($result);

    }
}
