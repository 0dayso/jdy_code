<?php
/**
 * Created by PhpStorm.
 * User: linguanghui
 * Date: 17/1/24
 * Time: 下午 19:16
 */

namespace App\Http\Controllers\AppApi\V4_0\User;

use App\Http\Controllers\AppApi\AppController;
use Illuminate\Http\Request;
use App\Http\Logics\AppLogic;
use App\Http\Logics\User\UserLogic;


class UserController extends AppController{

    /**
     * @SWG\Post(
     *   path="/user_assets",
     *   tags={"APP-User"},
     *   summary="用户中心我的资产 [User\UserController@index]",
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
     *      default="4.0",
     *   ),
     *   @SWG\Parameter(
     *      name="token",
     *      in="formData",
     *      description="token",
     *      required=true,
     *      type="string",
     *      default="6f6b568f305d49a65cedb1bf3625c380167f645d",
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="获取账户信息成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="获取账户信息失败。",
     *   )
     * )
     * @desc 用户中心我的资产
     * @param $request array
     * @return array
     */
    public function index(Request $request){

        $userId = $this->getUserId();

        if(empty($userId)){
            return AppLogic::callError(AppLogic::CODE_NO_USER_ID);
        }

        $userLogic = new UserLogic();

        $userInfo  = $userLogic->getAppUserInfo($userId);

        $userInfo  = $userLogic->formatAppV4UserInfo($userInfo['data'], $userId);

        return AppLogic::callSuccess($userInfo);
    }

    /**
     * @param Request $request
     * @return array
     */
    /**
     * @SWG\Post(
     *   path="/user_base_info",
     *   tags={"APP-User"},
     *   summary="个人信息【数据合并到登陆后返回数据】 [User\UserController@userInfo]",
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
     *   @SWG\Response(
     *     response=200,
     *     description="获取数据成功",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="获取数据失败。",
     *   )
     * )
     */
    public function userInfo(Request $request){

        $userId = $this->getUserId();

        $userLogic = new UserLogic();

        $userInfo  = $userLogic->getAppV4UserInfo($userId);

        return $this->returnJsonData($userInfo);

    }

    /**
     * @SWG\Post(
     *   path="/check_real_name",
     *   tags={"APP-User"},
     *   summary="验证身份(实名认证) [User\UserController@checkRealName]",
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
     *   @SWG\Parameter(
     *      name="phone",
     *      in="formData",
     *      description="电话号",
     *      required=true,
     *      type="string",
     *      default="",
     *   ),
     *   @SWG\Parameter(
     *      name="name",
     *      in="formData",
     *      description="姓名",
     *      required=true,
     *      type="string",
     *      default="",
     *   ),
     *   @SWG\Parameter(
     *      name="identity",
     *      in="formData",
     *      description="身份证号",
     *      required=true,
     *      type="string",
     *      default="",
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="数据验证成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="数据验证失败。",
     *   )
     * )
     */
    public function checkRealName(Request $request){

        $phone    = $request->input('phone','');
        $name     = $request->input('name','');
        $identity = $request->input('identity','');

        $userLogic = new UserLogic();

        $userInfo  = $userLogic->checkUserBaseInfo($phone, $name, $identity);

        return $this->returnJsonData($userInfo);

    }

    /**
     * @SWG\Post(
     *   path="/do_edit_phone",
     *   tags={"APP-User"},
     *   summary="修改手机号 [User\UserController@doEditPhone]",
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
     *    @SWG\Parameter(
     *      name="code",
     *      in="formData",
     *      description="验证码",
     *      required=true,
     *      type="string",
     *      default="",
     *   ),
     *   @SWG\Parameter(
     *      name="type",
     *      in="formData",
     *      description="验证码的类型",
     *      required=true,
     *      type="string",
     *      default="modify_phone",
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="修改手机号成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="修改手机号失败。",
     *   )
     * )
     */
    public function doEditPhone(Request $request){

        $type   = $request->input('type','modify_phone');
        $phone  = $request->input('phone','');
        $code   = $request->input('code','');

        $userId = $this->getUserId();

        $logic  = new UserLogic();
        $result = $logic->doEditPhone($userId,$phone,$code,$type);

        return self::returnJsonData($result);
    }
}

