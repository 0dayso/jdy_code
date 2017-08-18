<?php
/**
 * User: zhangshuang
 * Date: 16/4/23
 * Time: 16:40
 * Desc: 三要素成功后，实名 + 绑卡操作
 */

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Logics\User\VerifyLogic;
use Illuminate\Http\Request;

class VerifyController extends Controller{

    /**
     * @SWG\Post(
     *   path="/user/verify",
     *   tags={"User"},
     *   summary="实名+绑卡",
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
     *     default=82692
     *   ),
     *    @SWG\Parameter(
     *      name="name",
     *      in="formData",
     *      description="姓名",
     *      required=true,
     *      type="string",
     *   ),
     *    @SWG\Parameter(
     *      name="card_no",
     *      in="formData",
     *      description="银行卡号",
     *      required=true,
     *      type="string",
     *   ),
     *      @SWG\Parameter(
     *      name="bank_id",
     *      in="formData",
     *      description="银行ID",
     *      required=true,
     *      type="integer",
     *     default="6"
     *   ),
     *      @SWG\Parameter(
     *      name="id_card",
     *      in="formData",
     *      description="身份证号",
     *      required=true,
     *      type="string",
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="实名+绑卡成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="实名+绑卡成功。",
     *   )
     * )
     */
    public function verify(Request $request){

        $userId             = $request->input('user_id',0);     //用户ID
        $name               = $request->input('name','');       //姓名
        $cardNo             = $request->input('card_no','');    //银行卡号
        $bankId             = $request->input('bank_id',0);     //银行ID
        $idCard             = $request->input('id_card','');    //身份证号


        $logic = new VerifyLogic();
        $result  = $logic->verify($userId,$name,$cardNo,$bankId,$idCard);

        return self::returnJson($result);
    }

    /**
     * @SWG\Post(
     *   path="/user/verifyTradingPassword",
     *   tags={"User"},
     *   summary="实名+绑卡+交易密码",
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
     *     default=82692
     *   ),
     *    @SWG\Parameter(
     *      name="name",
     *      in="formData",
     *      description="姓名",
     *      required=true,
     *      type="string",
     *   ),
     *    @SWG\Parameter(
     *      name="card_no",
     *      in="formData",
     *      description="银行卡号",
     *      required=true,
     *      type="string",
     *   ),
     *      @SWG\Parameter(
     *      name="bank_id",
     *      in="formData",
     *      description="银行ID",
     *      required=true,
     *      type="integer",
     *     default="6"
     *   ),
     *      @SWG\Parameter(
     *      name="id_card",
     *      in="formData",
     *      description="身份证号",
     *      required=true,
     *      type="string",
     *   ),
     *      @SWG\Parameter(
     *      name="trading_password",
     *      in="formData",
     *      description="交易密码",
     *      required=true,
     *      type="string",
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="实名+绑卡+交易密码 成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="实名+绑卡+交易密码 成功。",
     *   )
     * )
     */
    public function verifyTradingPassword(Request $request){

        $userId             = $request->input('user_id',0);     //用户ID
        $name               = $request->input('name','');       //姓名
        $cardNo             = $request->input('card_no','');    //银行卡号
        $bankId             = $request->input('bank_id',0);     //银行ID
        $idCard             = $request->input('id_card','');    //身份证号
        $tradingPassword    = $request->input('trading_password','');    //交易密码


        $logic = new VerifyLogic();
        $result  = $logic->verifyTradingPassword($userId,$name,$cardNo,$bankId,$idCard, $tradingPassword);

        return self::returnJson($result);
    }

    /**
     * @SWG\Post(
     *   path="/user/realName",
     *   tags={"User"},
     *   summary="实名",
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
     *     default=82692
     *   ),
     *    @SWG\Parameter(
     *      name="name",
     *      in="formData",
     *      description="姓名",
     *      required=true,
     *      type="string",
     *   ),
     *      @SWG\Parameter(
     *      name="id_card",
     *      in="formData",
     *      description="身份证号",
     *      required=true,
     *      type="string",
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="实名成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="实名成功。",
     *   )
     * )
     */
    public function realName(Request $request){

        $userId             = $request->input('user_id',0);     //用户ID
        $name               = $request->input('name','');       //姓名
        $idCard             = $request->input('id_card','');    //身份证号


        $logic = new VerifyLogic();
        $result  = $logic->realName($userId,$name,$idCard);

        return self::returnJson($result);
    }



}