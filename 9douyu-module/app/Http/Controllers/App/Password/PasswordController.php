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

class PasswordController extends UserController{


    /**
     * @SWG\Post(
     *   path="/verify_password",
     *   tags={"APP-Password"},
     *   summary="验证登录密码是否正确 [Password\PasswordController@checkPassword]",
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
     *      name="password",
     *      in="formData",
     *      description="登录密码",
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

        $password = $request->input('password','');

        $userId = $this->getUserId();

        $passwordLogic = new PasswordLogic();

        $result = $passwordLogic->checkPassword($password,$userId);

        return self::appReturnJson($result);
    }
    

    /**
     * @SWG\Post(
     *   path="/set_password",
     *   tags={"APP-Password"},
     *   summary="修改登录密码 [Password\PasswordController@changePassword]",
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
     *      name="password",
     *      in="formData",
     *      description="登录密码",
     *      required=true,
     *      type="string",
     *      default="",
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="修改登录密码成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="修改登录密码失败。",
     *   )
     * )
     */
    public function changePassword(Request $request){

        $password = $request->input('password','');

        $userId     = $this->getUserId();

        $passwordLogic = new PasswordLogic();

        $result = $passwordLogic->changePassword($password,$userId);

        return self::appReturnJson($result);
    }
    
}



