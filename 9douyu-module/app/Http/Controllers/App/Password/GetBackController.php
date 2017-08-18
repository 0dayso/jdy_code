<?php
/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 16/7/11
 * Time: 19:04
 * Desc: 找回交易密码,不需要验证是否登录
 */
namespace App\Http\Controllers\App\Password;


use App\Http\Controllers\App\AppController;
use Illuminate\Http\Request;
use App\Http\Logics\User\PasswordLogic;

class GetBackController extends AppController{

    /**
     * @SWG\Post(
     *   path="/get_login",
     *   tags={"APP-Password"},
     *   summary="登录页面-忘记登录密码-设置新的登录密码 [Password\GetBackController@setPassword]",
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
     *     @SWG\Parameter(
     *      name="phone",
     *      in="formData",
     *      description="手机号",
     *      required=true,
     *      type="string",
     *      default="",
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
    public function resetPassword(Request $request){

        $phone      = $request->input('phone','');
        $password   = $request->input('password','');

        $logic      = new PasswordLogic();
        $result     = $logic->resetPassword($phone,$password);

        return self::appReturnJson($result);
    }
}
