<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 16/6/25
 * Time: 下午2:48
 * Desc: 用户注册
 */

namespace App\Http\Controllers\App\User;

use App\Http\Controllers\App\AppController;
use App\Http\Logics\Article\ArticleLogic;

use App\Http\Logics\RequestSourceLogic;
use App\Http\Logics\User\LoginLogic;
use App\Http\Logics\User\RegisterLogic;

use App\Http\Logics\User\SessionLogic;
use Illuminate\Http\Request;

use Session;

class RegisterController extends AppController{


    /**
     * @SWG\Post(
     *   path="/register_agreement",
     *   tags={"APP-User"},
     *   summary="App端注册协议 [User\RegisterController@getAgreement",
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
     *     description="App端注册协议成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="App端注册协议失败。",
     *   )
     * )
     */
    public function getAgreement(){
        
        $articleLogic = new ArticleLogic();

        if($this->compareVersion($this->version, self::THIS_NEW_FIX_VERSION)){

            $viewData = $articleLogic->getRegisterAgreementHtml();

            $this->appReturnJson($viewData);

        }

        $viewData = $articleLogic->getRegisterAgreement();

        return view('app.agreement.register', $viewData);

    }


    /**
     * @SWG\Post(
     *   path="/user_sendSms",
     *   tags={"APP-User"},
     *   summary="App注册发送短信 [User\RegisterController@sendSms",
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
     *     description="App端注册协议成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="App端注册协议失败。",
     *   )
     * )
     */
    public function sendSms( Request $request ){
        $phone = $request->input('phone');

        $registerLogic                = new RegisterLogic();

        $logicResult                  = $registerLogic->sendRegisterSms($phone);

        if($logicResult['status']){
            $logicResult = RegisterLogic::formatAppSendRegisterSms($logicResult['data']);
        }

        $this->appReturnJson($logicResult);

    }

    /**
     * @SWG\Post(
     *   path="/register",
     *   tags={"APP-User"},
     *   summary="注册【app】 -> 设置密码 [App\User\RegisterController@doRegister]",
     *   @SWG\Parameter(
     *      name="name",
     *      in="formData",
     *      description="发送请求的模块名称",
     *      required=true,
     *      type="string",
     *      default="cli_test_user",
     *   ),
     *  @SWG\Parameter(
     *      name="request_source",
     *      in="formData",
     *      description="注册来源",
     *      required=true,
     *      type="string",
     *      @SWG\Items(type="string"),
     *      collectionFormat="multi",
     *      default="ios",
     *      enum={"android", "ios"}
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
     *      name="code",
     *      in="formData",
     *      description="手机验证码【九斗鱼对接 - 暂不验证】",
     *      required=false,
     *      type="string",
     *      default="1234",
     *   ),
     *     @SWG\Parameter(
     *      name="phone",
     *      in="formData",
     *      description="手机号",
     *      required=true,
     *      type="string",
     *      default="13632403818",
     *   ),
     *
     *    @SWG\Parameter(
     *      name="password",
     *      in="formData",
     *      description="密码",
     *      required=true,
     *      type="string",
     *      default="admin123",
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="登陆 -> 登陆成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="登陆 -> 登陆失败。",
     *   )
     * )
     */
    public function doRegister(Request $request){
        //todo 渠道 和 邀请
        $channelId                      = $request->input('channel_id',''); // 渠道号
        $factor                         = $request->input('uuid');     // 非browser 的 客户端 传入的加密 token的因子


        // 注册信息【三端统一注册信息收集】
        $data   = [
            'phone'                     => $request->input('phone'),                                  // 手机号
            'phone_code'                => $request->input('code'),                                   // 手机验证码
            'password'                  => $request->input('password'),                               // 密码
            'aggreement'                => 1,//同意协议
            'request_source'            => RequestSourceLogic::getSource(),
            'invite_phone'              => $request->input('inviteCode'),    //邀请码-填写手机号
            'channel'                   => $request->input('channel',''),
            'channel_id'                => $channelId
        ];

        //数据处理
        $registerLogic                = new RegisterLogic;
        $logicRegisterReturn          = $registerLogic->doRegister($data);

        $logicLoginData               = false;
        //如果创建成功-》请求token
        if($logicRegisterReturn['status']) {
//            $dataLogin = [
//                'factor' => $factor,
//                'username' => $data['phone'],
//                'password' => $data['password']
//            ];
//            $LoginLogic     = new LoginLogic();
//            $logicLoginData = $LoginLogic->in($dataLogin);
            
            //app 注册后 返回注册成功状态 由 app端重新发起登陆接口 以获取token
            $logicLoginData = RegisterLogic::formatAppRegister();
            self::appReturnJson($logicLoginData);
        }
        self::appReturnJson($logicRegisterReturn);
    }
}