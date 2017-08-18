<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 16/6/25
 * Time: 下午2:53
 * Desc: 实名认证
 */
namespace App\Http\Controllers\AppApi\V4_0\User;

use App\Http\Controllers\AppApi\AppController;

use App\Http\Logics\AppLogic;
use App\Http\Logics\RequestSourceLogic;

use App\Http\Logics\User\UserLogic;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

/**
 * 验卡实名认证相关
 *
 * Class VerifyController
 * @package App\Http\Controllers\AppApi\V4_0\User
 */
class VerifyController extends AppController{


        /**
         * @SWG\Post(
         *   path="/get_user_verify_status",
         *   tags={"APP-User"},
         *   summary="输入密码 -> 登陆 [User\VerifyController@verifyStatus]",
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
         *   @SWG\Response(
         *     response=200,
         *     description="实名状态 -> 成功。",
         *   ),
         *   @SWG\Response(
         *     response=500,
         *     description="实名状态 -> 失败。",
         *   )
         * )
         */
        public function verifyStatus()
        {
            $logicReturn = UserLogic::verifyStatus4();

            return $this->returnJsonData($logicReturn);
        }

        /**
         * @SWG\Post(
         *   path="/user_verify_bank_card",
         *   tags={"APP-User"},
         *   summary="实名+绑卡+交易密码 [User\VerifyController@checkCard]",
         *   @SWG\Parameter(
         *      name="name",
         *      in="formData",
         *      description="发送请求的模块名称",
         *      required=true,
         *      type="string",
         *      default="cli_test_user",
         *   ),
         *   @SWG\Parameter(
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
         *      name="real_name",
         *      in="formData",
         *      description="姓名",
         *      required=true,
         *      type="string",
         *      default="",
         *   ),
         *     @SWG\Parameter(
         *      name="bank_card",
         *      in="formData",
         *      description="银行卡号",
         *      required=true,
         *      type="string",
         *      default="",
         *   ),
         *    @SWG\Parameter(
         *      name="card_no",
         *      in="formData",
         *      description="身份证号",
         *      required=true,
         *      type="string",
         *      default="",
         *   ),
         *    @SWG\Parameter(
         *      name="trading_password",
         *      in="formData",
         *      description="支付密码",
         *      required=true,
         *      type="string",
         *      default="qwe123",
         *   ),
         *    @SWG\Parameter(
         *      name="bankId",
         *      in="formData",
         *      description="银行卡所属行Id【紧用于测试环境】表名：module_bank",
         *      required=true,
         *      type="string",
         *      default="1",
         *   ),
         *   @SWG\Response(
         *     response=200,
         *     description="实名+绑卡+交易密码 -> 成功。",
         *   ),
         *   @SWG\Response(
         *     response=500,
         *     description="实名+绑卡+交易密码 -> 失败。",
         *   )
         * )
         */
        public function checkCard(Request $request)
        {
            $userId           = $this->getUserId();
            $name             = $request->input('real_name', '');
            $idCard           = $request->input('card_no', '');
            $cardNo           = $request->input('bank_card', '');
            $tradingPassword  = $request->input('trading_password', '');

            $from             = RequestSourceLogic::getSource();
            $logic            = new UserLogic();

            $bankId           = null;
            if(!\App::environment("production")) {
                //ip 白名单 本地或测试环境无法成功module_bank
                $bankId = app('request')->input('bankId', 1); //默认工商银行
            }
            $result           = $logic->doVerifyTradingPassword($userId, $name, $cardNo, $idCard, $from, $tradingPassword, $bankId);

            return $this->returnJsonData($result);
        }

        /**
         * @SWG\Post(
         *   path="/check_login",
         *   tags={"APP-User"},
         *   summary="验证用户是否登录 [User\VerifyController@checkIsLogin]",
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
         *   @SWG\Response(
         *     response=200,
         *     description="登录状态",
         *   ),
         *   @SWG\Response(
         *     response=500,
         *     description="无登录状态",
         *   )
         * )
         */
        /**
         * @param Request $request
         * @return array
         */
        public function checkIsLogin(Request $request)
        {
            return AppLogic::callSuccess();
        }
}