<?php
/**
 * User: zhangshuang
 * Date: 16/4/13
 * Time: 15:42
 * Desc: 用户绑定卡信息相关控制器
 */

namespace App\Http\Controllers\BankCard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Logics\BankCard\RechargeLogic;

class RechargeController extends Controller{


    /**
     * @SWG\Post(
     *   path="/recharge/card/get",
     *   tags={"BankCard"},
     *   summary="获取用户绑定银行卡",
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
     *      default="82692"
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="获取绑定银行卡成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="获取绑定银行卡失败。",
     *   )
     * )
     */
    public function getAuthCardByUserId(Request $request){

        $userId = $request->input('user_id',0); //用户ID
        $logic  = new RechargeLogic();

        //获取用户绑定银行卡
        $result = $logic->getUserAuthCardByUserId($userId);

        return self::returnJson($result);

    }

    /**
     * @SWG\Post(
     *   path="/recharge/card/change",
     *   tags={"BankCard"},
     *   summary="用户更换绑定卡",
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
     *      default="82692"
     *   ),
     *   @SWG\Parameter(
     *      name="bank_id",
     *      in="formData",
     *      description="新卡银行ID",
     *      required=true,
     *      type="integer",
     *      default="9"
     *   ),
     *   @SWG\Parameter(
     *      name="old_card_no",
     *      in="formData",
     *      description="旧银行卡号",
     *      required=true,
     *      type="string",
     *      default="6214830104420491"
     *   ),
     *     @SWG\Parameter(
     *      name="new_card_no",
     *      in="formData",
     *      description="新银行卡事情",
     *      required=true,
     *      type="string",
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="更换绑定银行卡成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="更换绑定银行卡失败。",
     *   )
     * )
     */
    public function changeCard(Request $request){

        $userId     = $request->input('user_id',0); //用户ID
        $bankId     = $request->input('bank_id',0); //新卡银行ID
        $oldCardNo  = $request->input('old_card_no','');//旧卡号
        $newCardNo  = $request->input('new_card_no','');//新卡号

        $logic  = new RechargeLogic();

        //获取用户绑定银行卡
        $result = $logic->changeCard($userId,$bankId,$oldCardNo,$newCardNo);

        return self::returnJson($result);
    }

}