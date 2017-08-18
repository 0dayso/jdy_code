<?php
/**
 * Created by PhpStorm.
 * User: jinzhuotao
 * Date: 2017/3/6
 * Time: 下午2:13
 */

namespace App\Http\Controllers\AppApi\V4_0\User;

use App\Http\Controllers\AppApi\AppController;
use App\Http\Logics\User\SmsLogic;
use App\Http\Logics\User\UserLogic;
use App\Tools\ToolEnv;
use Illuminate\Http\Request;
use App\Http\Logics\AppLogic;

class SmsController extends AppController
{
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
     *      default="4.0",
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
     *      default="find_password",
     *      enum={"register_active","find_password","modify_phone","find_tradingPassword"}
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="验证码发送成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="验证码发送失败。",
     *   )
     * )
     **/
    public function sendSms(Request $request){

        $type   = $request->input('type','');
        $phone  = $request->input('phone','');
        $userId = $this->getUserId();

        //修改手机号和找回交易密码判断登录状态
        if($type == 'find_tradingPassword' || $type == 'modify_phone'){
            if(!((int)$userId)){
                return AppLogic::callError(AppLogic::CODE_NO_USER_ID,AppLogic::CODE_NO_USER_ID);
            }
        }
        $sms    = new SmsLogic();
        $result = $sms->sendSms($phone,$type);

        $result['data']['msg'] = $result['msg'];

        if( ToolEnv::getAppEnv() != 'production' ){

            if( $type == 'register_active' ){

                $result['data']['verify_code'] = \Cache::get('PHONE_VERIFY_CODE' .$phone);

            }else{

                $cacheKey = "sms{$phone}_{$type}";

                //写入缓存
                $result['data']['verify_code'] = \Cache::get($cacheKey);

            }

        }

        return self::returnJsonData($result);

    }

    /**
     * @SWG\Post(
     *   path="/check_code",
     *   tags={"APP-Sms"},
     *   summary="验证验证码并判断是否实名 [User\SmsController@checkSmsCode]",
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
     *      default="4.0",
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
     *     description="验证码校验成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="验证码校验失败。",
     *   )
     * )
     **/
    public function checkSmsCode(Request $request)
    {

        $phone = $request->input('phone', '');
        $code = $request->input('code', '');
        $type = $request->input('type', '');

        //验证验证码有效性
        $logic = new SmsLogic();
        $result = $logic->checkCodeByType($phone, $code, $type);

        //判断该用户是否实名认证
        if($result['status'] == true && $type != 'register_active' && $type != 'modify_phone'){
            $userLogic = new UserLogic();
            $result = $userLogic->checkIsRealName($phone);
        }

        return self::returnJsonData($result);
    }
}
