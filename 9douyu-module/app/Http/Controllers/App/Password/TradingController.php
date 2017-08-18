<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 16/6/25
 * Time: 下午2:54
 * Desc: 用户密码
 */

namespace App\Http\Controllers\App\Password;

use App\Http\Controllers\App\UserController;
use App\Http\Logics\User\PasswordLogic;
use Illuminate\Http\Request;
use App\Http\Logics\Logic;

class TradingController extends UserController{

    /**
     * @SWG\Post(
     *   path="/verify_trade",
     *   tags={"APP-Password"},
     *   summary="交易密码弹出框(新版该接口可废弃) [Password\TradingController@verifyTrade]",
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
     *     description="获取信息成功。",
     *   ),
     * )
     */
    public function verifyTrade(){

        $result = Logic::callSuccess([]);

        return self::appReturnJson($result);

    }



    /**
     * @SWG\Post(
     *   path="/check_tradePassword",
     *   tags={"APP-Password"},
     *   summary="验证交易密码是否正确 [Password\TradingController@checkPassword]",
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
     *      name="tradePassword",
     *      in="formData",
     *      description="交易密码",
     *      required=true,
     *      type="string",
     *      default="",
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="验证登录密码成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="验证登录密码失败。",
     *   )
     * )
     */
    public function checkPassword(Request $request){

        $password   = $request->input('tradePassword');

        $userId     = $this->getUserId();

        $logic      = new PasswordLogic();

        //这里由于App检测出用户的交易密码不正确,需要返回的验证码有区别,为了不影响Logic的正常逻辑,所以用新写的app的检测方法

        $result     = $logic->checkTradingPasswordForApp($password,$userId);

        return self::appReturnJson($result);
    }


    /**
     * @SWG\Post(
     *   path="/set_new_trade",
     *   tags={"APP-Password"},
     *   summary="修改交易密码 [Password\TradingController@changePassword]",
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
     *      name="old_password",
     *      in="formData",
     *      description="原交易密码",
     *      required=true,
     *      type="string",
     *      default="",
     *   ),
     *   @SWG\Parameter(
     *      name="password",
     *      in="formData",
     *      description="新交易密码",
     *      required=true,
     *      type="string",
     *      default="",
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="修改交易密码成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="修改交易密码失败。",
     *   )
     * )
     */
    public function changePassword(Request $request){

        $newPassword   = $request->input('password','');    //新密码
        $oldPassword   = $request->input('old_password','');//旧密码
        
        $userId        = $this->checkUserIdIsLogin();
        
        $logic         = new PasswordLogic();
        $result        = $logic->changeTradingPassword($newPassword,$oldPassword,$userId);

        return self::appReturnJson($result);
    }


    /**
     * @SWG\Post(
     *   path="/do_forget_tradepassword",
     *   tags={"APP-Password"},
     *   summary="修改交易密码,不验证旧的交易密码 [Password\TradingController@modifyPassword]",
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
     *      name="password",
     *      in="formData",
     *      description="新交易密码",
     *      required=true,
     *      type="string",
     *      default="",
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="修改交易密码成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="修改交易密码失败。",
     *   )
     * )
     */
    public function modifyPassword(Request $request){
        
        $password = $request->input('password','');    //新密码

        $userId        = $this->getUserId();

        $logic         = new PasswordLogic();
        $result        = $logic->modifyTradingPassword($password,$userId);

        return self::appReturnJson($result);
    }
    
}

