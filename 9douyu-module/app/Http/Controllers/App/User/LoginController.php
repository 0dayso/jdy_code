<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 16/6/25
 * Time: 下午2:48
 * Desc: 用户登录
 */
namespace App\Http\Controllers\App\user;

use App\Http\Controllers\App\AppController;

use Illuminate\Http\Request;

use App\Http\Logics\User\LoginLogic;
/**
 *
 * Class LoginController
 * @package App\Http\Controllers\App\user
 */
class LoginController extends AppController{



    public function appendConstruct(){

    }

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
            if($logicReturn['data']['status'] == self::CODE_SUCCESS){
                $logicReturn['msg']  = $logicReturn['data']['msg'];
                $logicReturn['data'] = [];
                $this->appReturnJson($logicReturn, self::CODE_SUCCESS);
            }else{
                $logicReturn['msg']  = $logicReturn['data']['msg'];
                $logicReturn['data'] = [];

                $this->appReturnJson($logicReturn, self::CODE_PHONE_CAN_REGISTER);
            }
        }
        $this->appReturnJson($logicReturn);
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
     *   @SWG\Parameter(
     *      name="bd_user_id",
     *      in="formData",
     *      description="bd_user_id",
     *      required=false,
     *      type="string",
     *      default="",
     *   ),
     *     @SWG\Parameter(
     *      name="phone_type",
     *      in="formData",
     *      description="设备型号",
     *      required=false,
     *      type="string",
     *      default="",
     *   ),
     *     @SWG\Parameter(
     *      name="phone_version",
     *      in="formData",
     *      description="系统版本号",
     *      required=false,
     *      type="string",
     *      default="",
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

        $pushUserId   =  $request->input("bd_user_id");
        $version      =  $request->input("version","");

        //todo 添加设备信息

        //todo 绑定推送ID

        $data   =[
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
            $this->appReturnJson($checkLoginTimes);
        }

        $LoginLogic = new LoginLogic();
        $logicData  = $LoginLogic->in($data);

        if($logicData['status']){
            LoginLogic::logLoginTimes($data['username'], true);//清除登陆次数

            $logicData = LoginLogic::formatAppLoginInData($logicData['data']);
        }else{
            LoginLogic::logLoginTimes($data['username']);//记录登陆次数
        }

        $this->appReturnJson($logicData);
    }

    /**
     * 登出
     *
     * @param Request $request
     */


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

        $this->appReturnJson($LoginLogic);
    }

}