<?php


namespace App\Http\Controllers\Api\Jdy;

use App\Http\Logics\JdyDataApi\RegisterLogic;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use App\Http\Logics\RequestSourceLogic;

use App\Http\Logics\User\UserLogic;

/**
 * 用户相关 todo 九斗鱼 数据对接 对接后 直接移除该文件
 * Class UserController
 * @package App\Http\User
 */
class UserController extends Controller
{

    /**
     * @SWG\Post(
     *   path="/api/jdy/register/doRegister",
     *   tags={"JDY-Api"},
     *   summary="注册【三端】 -> 创建 [Api\Jdy\UserController@doRegister]",
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
     *      default="pc",
     *      enum={"pc","wap", "android", "ios"}
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
     *      name="phone_code",
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
     *      description="密码[加密后字符串]",
     *      required=true,
     *      type="string",
     *      default="63f3aca21fb2281417493b3b209cfcaf:93b9b4a51c125d8902d4d40b2c0",
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
        $data   = [
            'id'                        => $request->input('id'),                                     // 九斗鱼传入注册的ID
            'request_source'            => $request->input('request_source'),                         // 来源
            'phone'                     => $request->input('phone'),                                  // 手机号
            'password'                  => $request->input('password'),                               // 密码
            'phone_code'                => $request->input('phone_code'),                             // 手机验证码
        ];
        
        //数据处理
        $registerLogic                = new RegisterLogic();
        $logicRegisterReturn          = $registerLogic->doRegister($data);

        return self::returnJson(['registerData' => $logicRegisterReturn ]);
    }


    /**
     * @SWG\Post(
     *   path="/api/jdy/user/setting/doVerify",
     *   tags={"JDY-Api"},
     *   summary="实名+绑卡  [Api\Jdy\UserController@doVerify]",
     *   @SWG\Parameter(
     *      name="module_name",
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
     *      type="string",
     *      @SWG\Items(type="string"),
     *      collectionFormat="multi",
     *      default="pc",
     *      enum={"pc","wap"}
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
     *      name="name",
     *      in="formData",
     *      description="姓名",
     *      required=false,
     *      type="string",
     *      default="",
     *   ),
     *     @SWG\Parameter(
     *      name="card_no",
     *      in="formData",
     *      description="银行卡号",
     *      required=true,
     *      type="string",
     *      default="",
     *   ),
     *
     *    @SWG\Parameter(
     *      name="id_card",
     *      in="formData",
     *      description="身份证号",
     *      required=true,
     *      type="string",
     *      default="",
     *   ),
     *    @SWG\Parameter(
     *      name="user_id",
     *      in="formData",
     *      description="用户ID",
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
    public function doVerify(Request $request){

        //姓名
        $name       = $request->input('name','');
        //银行卡号
        $cardNo     = $request->input('card_no','');
        //身份证号
        $idCard     = $request->input('id_card','');

        //用户ID
        $userId     = $request->input('user_id');

        $logic      = new RegisterLogic();

        $from = RequestSourceLogic::getSource();

        $result = $logic->verify($userId,$name,$cardNo,$idCard,$from);

        return self::returnJson(['return' => $result ]);
    }


    /**
     * @SWG\Post(
     *   path="/api/jdy/user/setting/phone/modify",
     *   tags={"JDY-Api"},
     *   summary="修改手机号 -> 执行 [Api\Jdy\UserController@doModifyPhone]",
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
     *      type="string",
     *      @SWG\Items(type="string"),
     *      collectionFormat="multi",
     *      default="pc",
     *      enum={"pc","wap"}
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
     *      description="新手机号",
     *      required=true,
     *      type="string",
     *      default="",
     *   ),
     *     @SWG\Parameter(
     *      name="old_phone",
     *      in="formData",
     *      description="老手机号",
     *      required=true,
     *      type="string",
     *      default="13632403818",
     *   ),
     *
     *   @SWG\Response(
     *     response=200,
     *     description="手机号 -> 修改成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="手机号 -> 修改失败。",
     *   )
     * )
     */
    public function doModifyPhone(Request $request)
    {
        $data        = $request->all();
        $data        = UserLogic::modifyPhoneFormatInput($data);

        $logicResult = RegisterLogic::modifyPhone($data);

        return self::returnJson($logicResult);

    }
    /**
     * @SWG\Post(
     *   path="/api/jdy/user/setting/doPassword",
     *   tags={"JDY-Api"},
     *   summary="修改密码 -> 执行 [Api\Jdy\UserController@doPassword]",
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
     *      type="string",
     *      @SWG\Items(type="string"),
     *      collectionFormat="multi",
     *      default="pc",
     *      enum={"pc","wap"}
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
     *      name="userId",
     *      in="formData",
     *      description="用户ID",
     *      required=true,
     *      type="string",
     *      default="",
     *   ),
     *     @SWG\Parameter(
     *      name="oldPassword",
     *      in="formData",
     *      description="老密码",
     *      required=true,
     *      type="string",
     *      default="",
     *   ),
     *     @SWG\Parameter(
     *      name="newPassword",
     *      in="formData",
     *      description="新密码",
     *      required=true,
     *      type="string",
     *      default="",
     *   ),
     *     @SWG\Parameter(
     *      name="confirmPassword",
     *      in="formData",
     *      description="确认密码【修改失败时 返回错误页面】",
     *      required=true,
     *      type="string",
     *      default="",
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="修改密码 -> 修改成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="修改密码 -> 修改失败。",
     *   )
     * )
     */
    public function doPassword(Request $request){

        $request = $request->all();

        $userId = $request['userId'];

        $userLogic = new UserLogic();

        $res = $userLogic->changePassword($userId,$request['oldPassword'],$request['newPassword'],$request['confirmPassword']);

        return self::returnJson($res);

    }


    /**
     * @SWG\Post(
     *   path="/api/jdy/user/setting/doTradingPassword",
     *   tags={"JDY-Api"},
     *   summary="修改交易密码 -> 执行 [Api\Jdy\UserController@doTradingPassword]",
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
     *      type="string",
     *      @SWG\Items(type="string"),
     *      collectionFormat="multi",
     *      default="pc",
     *      enum={"pc","wap"}
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
     *      name="userId",
     *      in="formData",
     *      description="用户ID",
     *      required=true,
     *      type="string",
     *      default="",
     *   ),
     *     @SWG\Parameter(
     *      name="oldPassword",
     *      in="formData",
     *      description="老密码",
     *      required=true,
     *      type="string",
     *      default="",
     *   ),
     *     @SWG\Parameter(
     *      name="newPassword",
     *      in="formData",
     *      description="新密码",
     *      required=true,
     *      type="string",
     *      default="",
     *   ),
     *     @SWG\Parameter(
     *      name="confirmPassword",
     *      in="formData",
     *      description="确认密码【修改失败时 返回错误页面】",
     *      required=true,
     *      type="string",
     *      default="",
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="修改交易密码 -> 修改成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="修改交易密码 -> 修改失败。",
     *   )
     * )
     */
    public function doTradingPassword( Request $request )
    {

        $request = $request->all();

        $userLogic = new UserLogic();

        $userId = $request['userId'];

        $res = $userLogic->changePassword($userId, $request['oldPassword'], $request['newPassword'], $request['confirmPassword'], 'tradingPassword');

        return self::returnJson($res);

    }


    /**
     * 实名 加 绑卡
     * @param Request $request
     */
    /**
     * @SWG\Post(
     *   path="/user/setting/verifyApi",
     *   tags={"JDY-Api"},
     *   summary="修改交易密码 -> 执行 [Api\Jdy\UserController@verify]",
     *   @SWG\Parameter(
     *      name="name",
     *      in="formData",
     *      description="姓名",
     *      required=true,
     *      type="string",
     *      default="",
     *   ),
     *     @SWG\Parameter(
     *      name="card_no",
     *      in="formData",
     *      description="银行卡号",
     *      required=true,
     *      type="string",
     *      default="",
     *   ),
     *     @SWG\Parameter(
     *      name="id_card",
     *      in="formData",
     *      description="身份证号",
     *      required=true,
     *      type="string",
     *      default="",
     *   ),
     *     @SWG\Parameter(
     *      name="user_id",
     *      in="formData",
     *      description="用户ID",
     *      required=true,
     *      type="integer",
     *      default="",
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="实名+绑卡 -> 修改成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="实名+绑卡 -> 修改失败。",
     *   )
     * )
     */
    public function verify(Request $request){

        //姓名
        $name       = $request->input('name','');
        //银行卡号
        $cardNo     = $request->input('card_no','');
        //身份证号
        $idCard     = $request->input('id_card','');

        $userId     = $request->input('user_id',0);

        $logic      = new UserLogic();


        $from = RequestSourceLogic::getSource();

        $result = $logic->verify($userId,$name,$cardNo,$idCard,$from);

        return self::returnJson($result);
    }
}