<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 16/6/25
 * Time: 下午2:48
 * Desc: 用户登录
 */
namespace App\Http\Controllers\AppApi\V4_0\User;

use App\Http\Controllers\AppApi\AppController;

use App\Http\Logics\AppLogic;

use Illuminate\Http\Request;

use App\Http\Logics\User\LoginLogic;

/**
 * 登陆
 *
 * Class LoginController
 * @package App\Http\Controllers\AppApi\V4_0\User
 */
class LoginController extends AppController{


    /**
     * @SWG\Post(
     *   path="/check_phone",
     *   tags={"APP-User"},
     *   summary="输入手机号 -> 检测 [User\LoginController@checkPhone]",
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
     *      name="phone",
     *      in="formData",
     *      description="手机号",
     *      required=true,
     *      type="string",
     *      default="",
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="输入手机号 -> 检测成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="输入手机号 -> 检测失败。",
     *   )
     * )
     */
    public function checkPhone( Request $request ){
        $phone       = $request->input('phone');
        $logicReturn = LoginLogic::checkPhone($phone);
        if($logicReturn['status']){
            if($logicReturn['data']['status'] == LoginLogic::STATUS_CAN_REGISTER){
                return AppLogic::callSuccess([],AppLogic::CODE_PHONE_NOT_EXIST, $logicReturn['data']['msg']);
            }else{
                return AppLogic::callSuccess([],AppLogic::CODE_PHONE_EXIST, $logicReturn['data']['msg']);
            }
        }else{
            return AppLogic::callError(AppLogic::CODE_PHONE_FORMAT, $logicReturn['msg']);
        }
    }

    /**
     * @SWG\Post(
     *   path="/login",
     *   tags={"APP-User"},
     *   summary="输入密码 -> 登陆 [User\LoginController@doLogin]",
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
     *      name="phone",
     *      in="formData",
     *      description="手机号",
     *      required=true,
     *      type="string",
     *      default="13581913818",
     *   ),
     *     @SWG\Parameter(
     *      name="password",
     *      in="formData",
     *      description="密码",
     *      required=true,
     *      type="string",
     *      default="admin123",
     *   ),

     *     @SWG\Parameter(
     *      name="uuid",
     *      in="formData",
     *      description="uuid",
     *      required=false,
     *      type="string",
     *      default="",
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="输入密码 -> 登陆成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="输入密码 -> 登陆失败。",
     *   )
     * )
     */
    public function doLogin( Request $request ){
        $version      =  $request->input("version","");

        $data         =[
            'factor'        => $request->input('uuid'),
            'username'      => $request->input('phone'),
            'password'      => $request->input('password'),
            'client_type'   => $request->input('phone_type'),
            'client_version'=> $request->input('phone_version'),
            'app_version'   => $version,
        ];

        //检测登陆次数限制
        $checkLoginTimes = LoginLogic::checkLoginTimes($data['username']);
        if(!$checkLoginTimes['status']){
            return AppLogic::callError(AppLogic::CODE_ERROR, $checkLoginTimes['msg']);
        }

        $LoginLogic = new LoginLogic();
        $logicData  = $LoginLogic->in4($data);

        if($logicData['status']){
            LoginLogic::logLoginTimes($data['username'], true);//清除登陆次数
            $logicData = LoginLogic::formatApp4LoginInData($logicData['data']);
            return AppLogic::callSuccess($logicData['data']);
        }else{
            LoginLogic::logLoginTimes($data['username']);//记录登陆次数
            if($logicData['code'] == AppLogic::CODE_NO_REGISTER){
                $code = AppLogic::CODE_NO_REGISTER;
            }else{
                $code = AppLogic::CODE_ERROR;
            }
            return AppLogic::callError($code, $logicData['msg']);
        }
    }

    /**
     * @SWG\Post(
     *   path="/logout",
     *   tags={"APP-User"},
     *   summary="登出 [User\LoginController@doLogout]",
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
     *     description="登出成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="登出失败。",
     *   )
     * )
     */
    public function doLogout( Request $request ){

        $token      =  $request->input("token");

        $LoginLogic = LoginLogic::destroy($token);

        return AppLogic::callSuccess([], AppLogic::CODE_SUCCESS, $LoginLogic['msg']);
    }

}