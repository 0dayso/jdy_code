<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 16/6/25
 * Time: 下午2:53
 * Desc: 实名认证
 */
namespace App\Http\Controllers\App\user;

use App\Http\Controllers\App\UserController;
use App\Http\Logics\RequestSourceLogic;
use App\Http\Logics\User\UserLogic;
use Illuminate\Http\Request;

class VerifyController extends UserController{


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
         *      default="2.2.3",
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
                $logicReturn = UserLogic::verifyStatus();

                //未登录
                if (!$logicReturn['status']) {
                        $this->appReturnJson($logicReturn, self::CODE_LOGIN_EXPIRE);//文档里说明：4010 跳转至登陆
                }
                $this->appReturnJson($logicReturn, self::CODE_SUCCESS);
        }

        /**
         * @SWG\Post(
         *   path="/user_verify_tiecard",
         *   tags={"APP-User"},
         *   summary="三要素验卡 [User\VerifyController@checkCard]",
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
         *      default="2.2.3",
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
         *      name="username",
         *      in="formData",
         *      description="姓名",
         *      required=true,
         *      type="string",
         *      default="",
         *   ),
         *     @SWG\Parameter(
         *      name="userbankcard",
         *      in="formData",
         *      description="银行卡号",
         *      required=true,
         *      type="string",
         *      default="",
         *   ),
         *    @SWG\Parameter(
         *      name="usericard",
         *      in="formData",
         *      description="身份证号",
         *      required=true,
         *      type="string",
         *      default="",
         *   ),
         *   @SWG\Response(
         *     response=200,
         *     description="实名+绑卡 -> 成功。",
         *   ),
         *   @SWG\Response(
         *     response=500,
         *     description="实名+绑卡 -> 失败。",
         *   )
         * )
         */
        public function checkCard(Request $request)
        {

                $userId = $this->getUserId();
                $name = $request->input('username', '');
                $idCard = $request->input('usericard', '');
                $cardNo = $request->input('userbankcard', '');

                $from = RequestSourceLogic::getSource();
                $logic = new UserLogic();

                $result = $logic->verify($userId, $name, $cardNo, $idCard, $from);

                return self::appReturnJson($result);

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
         *      default="2.2.3",
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

                $result= $this->checkIsLoginApi();

                return self::appReturnJson($result);

        }
}