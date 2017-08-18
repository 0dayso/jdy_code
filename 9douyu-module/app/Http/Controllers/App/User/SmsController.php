<?php
/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 16/7/11
 * Time: 17:33
 * Desc: 短信及验证码相关接口,不需要验证用户是否登录
 */
namespace App\Http\Controllers\App\User;

use App\Http\Controllers\App\AppController;
use Illuminate\Http\Request;
use App\Http\Logics\Logic;
use App\Http\Logics\User\SmsLogic;

class SmsController extends AppController{

    /**
     * @SWG\Post(
     *   path="/user_send_voice",
     *   tags={"APP-Sms"},
     *   summary="语音验证码,暂时去掉 [User\SmsController@sendVoiceSms]",
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
     *    @SWG\Parameter(
     *      name="phone",
     *      in="formData",
     *      description="手机号",
     *      required=true,
     *      type="string",
     *      default="",
     *   ),
     *    @SWG\Parameter(
     *      name="type",
     *      in="formData",
     *      description="短信验证码类型 1.register_active-用户激活 2.find_password-找回登录密码 3.modify_phone-修改手机号 4.find_tradingPassword-找回交易密码",
     *      required=true,
     *      type="array",
     *      @SWG\Items(type="string"),
     *      collectionFormat="multi",
     *      default="register_active",
     *      enum={"register_active","find_password","modify_phone","find_tradingPassword"}
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="验证码校验成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="验证码校验失败。",
     *   )
     * )
     */
    public function sendVoiceSms(Request $request){

        $type   = $request->input('type','');
        $phone  = $request->input('phone','');

        $msg    = '系统升级,暂不支持';

        return self::appReturnJson(Logic::callError($msg));

    }


    /**
     * @SWG\Post(
     *   path="/send_sms",
     *   tags={"APP-Sms"},
     *   summary="发送验证码 [User\SmsController@sendSms]",
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
     *      description="客户端",
     *      required=true,
     *      type="string",
     *      default="ios",
     *   ),
     *   @SWG\Parameter(
     *      name="version",
     *      in="formData",
     *      description="客户端版本号",
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
     *      @SWG\Parameter(
     *      name="phone",
     *      in="formData",
     *      description="手机号",
     *      required=true,
     *      type="string",
     *      default="",
     *   ),
     *  @SWG\Parameter(
     *      name="type",
     *      in="formData",
     *      description="短信验证码类型 1.register_active-用户激活 2.find_password-找回登录密码 3.modify_phone-修改手机号 4.find_tradingPassword-找回交易密码",
     *      required=true,
     *      type="array",
     *      @SWG\Items(type="string"),
     *      collectionFormat="multi",
     *      default="find_password",
     *      enum={"register_active","find_password","modify_phone","find_tradingPassword"}
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="发送验证码成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="发送验证码失败。",
     *   )
     * )
     */
    public function sendSms(Request $request){

        $type   = $request->input('type','');
        $phone  = $request->input('phone','');

        $sms    = new SmsLogic();
        $result = $sms->sendSms($phone,$type);

        return self::appReturnJson($result);

    }



    /**
     * @SWG\Post(
     *   path="/verify_loginSms",
     *   tags={"APP-Sms"},
     *   summary="登录模块-登录页面-忘记登录密码-验证验证码 [User\SmsController@checkSmsCode]",
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
     *    @SWG\Parameter(
     *      name="phone",
     *      in="formData",
     *      description="手机号",
     *      required=true,
     *      type="string",
     *      default="",
     *   ),
     *     @SWG\Parameter(
     *      name="code",
     *      in="formData",
     *      description="验证码",
     *      required=true,
     *      type="string",
     *      default="",
     *   ),
     *    @SWG\Parameter(
     *      name="type",
     *      in="formData",
     *      description="短信验证码类型",
     *      required=true,
     *      type="string",
     *      default="find_password",
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="验证码校验成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="验证码校验失败。",
     *   )
     * )
     */
    public function checkSmsCode(Request $request){

        $phone  = $request->input('phone','');
        $code   = $request->input('code','');
        $type   = $request->input('type','');

        $logic  = new SmsLogic();

        $result = $logic->checkCodeByType($phone,$code,$type);

        return self::appReturnJson($result);
    }


    /**
     * @SWG\Post(
     *   path="/check_register_code",
     *   tags={"APP-Sms"},
     *   summary="3.1.0判断验证码是否正左脚 [User\SmsController@checkRegisterCode]",
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
     *      default="3.1.0",
     *   ),
     *   @SWG\Parameter(
     *      name="token",
     *      in="formData",
     *      description="token",
     *      required=true,
     *      type="string",
     *      default="653030e9f8e4f6559669386dfe4f56d4",
     *   ),
     *    @SWG\Parameter(
     *      name="phone",
     *      in="formData",
     *      description="手机号",
     *      required=true,
     *      type="string",
     *      default="",
     *   ),
     *     @SWG\Parameter(
     *      name="code",
     *      in="formData",
     *      description="验证码",
     *      required=true,
     *      type="string",
     *      default="",
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="验证码校验成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="验证码校验失败。",
     *   )
     * )
     */
    public function checkRegisterCode(Request $request){

        $phone  = $request->input('phone','');
        $code   = $request->input('code','');

        $logic  = new SmsLogic();

        $result = $logic->checkRegisterCode($phone,$code);

        return self::appReturnJson($result);
    }
}
