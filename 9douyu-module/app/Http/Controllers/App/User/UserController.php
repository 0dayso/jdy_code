<?php
/**
 * Created by PhpStorm.
 * User: liuqiuhui
 * Date: 16/6/29
 * Time: 下午3:21
 */

namespace App\Http\Controllers\App\user;

use App\Http\Logics\Logic;
use App\Http\Logics\RequestSourceLogic;
use App\Http\Logics\User\SmsLogic;
use App\Http\Logics\User\UserLogic;
use Illuminate\Http\Request;
use App\Http\Models\Common\CoreApi\UserModel;
use App\Tools\ToolMoney;
use App\Http\Controllers\App\UserController as BaseUserController;

class UserController extends BaseUserController
{


    /**
     * @SWG\Post(
     *   path="/user_info",
     *   tags={"APP-User"},
     *   summary="我的资产 [User\UserController@index]",
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
     *   @SWG\Response(
     *     response=200,
     *     description="获取账户信息成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="获取账户信息失败。",
     *   )
     * )
     */
    /**
     * @param Request $request
     * @return array
     * @desc 我的资产	 user_info
     */
    public function index(Request $request){

        $userId = $this->getUserId();

        $logic  = new UserLogic();

        $data = $logic->getAppUserInfo($userId);

        /*if(!empty($data['data']['user_pic_url']) && is_array($data['data']['user_pic_url'])){
            foreach($data['data']['user_pic_url'] as $key=>$value){

                if(!empty($value['location_url'])){

                    switch ($this->client){

                        case RequestSourceLogic::SOURCE_IOS:{
                            $subDomain = env('IOS_SUB_DOMAIN');
                            break;
                        }
                        case RequestSourceLogic::SOURCE_ANDROID:{
                            $subDomain = env('ANDROID_SUB_DOMAIN');
                            break;
                        }
                    }

                    //跳转页面
                    $url = $value['location_url'];

                    $httpQuery = [

                        'version' => $this->version,
                        'client' => $this->client,
                        'token'	=> $this->token,
                        //'url' => $url,
                    ];

                    $query = http_build_query($httpQuery);

                    $data['data']['user_pic_url'][$key]['location_url'] = "http://".$subDomain.env('MAIN_DOMAIN')."/app_sign_login?".$query.'&url='.$url;

                }

            }
        }*/

        return self::appReturnJson( $data );

    }

    /**
     * @SWG\Post(
     *   path="/user_balance",
     *   tags={"APP-User"},
     *   summary="用户余额 [User\UserController@getBalance]",
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
     *   @SWG\Response(
     *     response=200,
     *     description="获取用户余额成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="获取用户余额失败。",
     *   )
     * )
     */
    public function getBalance(){

    	$userId = $this->getUserId();

    	$user = UserModel::getCoreApiUserInfo($userId);

    	$res['balance'] = ToolMoney::formatDbCashDelete($user['balance']);

    	return $this->appReturnJson(self::callSuccess($res));
    }


    /**
     * @SWG\Post(
     *   path="/do_edit_phone",
     *   tags={"APP-User"},
     *   summary="账户中心-修改手机号-设置新手机号 [User\UserController@doEditPhone]",
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
     *     description="获取用户余额成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="获取用户余额失败。",
     *   )
     * )
     */
    public function doEditPhone(Request $request){
        
        $type   = $request->input('type','modify_phone');
        $phone  = $request->input('phone','');
        $code   = $request->input('code','');

        $userId = $this->checkUserIdIsLogin();

        $logic  = new UserLogic();
        $result = $logic->doEditPhone($userId,$phone,$code,$type);

        return self::appReturnJson($result);
    }


    /**
     * @SWG\Post(
     *   path="/verify_identity",
     *   tags={"APP-User"},
     *   summary="账户中心-忘记交易密码-验证身份证 [User\UserController@verifyIdentity]",
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
     *      name="identityCard",
     *      in="formData",
     *      description="身份证号",
     *      required=true,
     *      type="string",
     *      default="",
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="验证身份证号成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="验证身份证号失败。",
     *   )
     * )
     */
    public function verifyIdentity(Request $request){

        $idCard     = $request->input('identityCard','');
        
        $userId     = $this->getUserId();
        $logic      = new UserLogic();
        $result     = $logic->verifyIdentity($userId,$idCard);
        
        return self::appReturnJson($result);
    }



    /**
     * @SWG\Post(
     *   path="/forget_password",
     *   tags={"APP-User"},
     *   summary="账户中心-短信验证 [User\UserController@checkSmsCode]",
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
     */
    public function checkSmsCode(Request $request){

        $phone  = $request->input('phone','');
        $code   = $request->input('code','');
        $type   = $request->input('type','');

        $userId = $this->getUserId();

        $logic  = new SmsLogic();

        $result = $logic->checkCodeByType($phone,$code,$type);

        return self::appReturnJson($result);
    }

    /**
     * @SWG\Post(
     *   path="/phone_code_record",
     *   tags={"APP-User"},
     *   summary="记录用户手机信息,功能暂时去掉,直接返回成功 [User\UserController@addPhoneErrorRecord]",
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
     *      name="phone_type",
     *      in="formData",
     *      description="手机类型",
     *      required=true,
     *      type="string",
     *      default="",
     *   ),
     *     @SWG\Parameter(
     *      name="phone_version",
     *      in="formData",
     *      description="手机系统版本号",
     *      required=true,
     *      type="string",
     *      default="",
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="记录用户手机信息成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="记录用户手机信息失败。",
     *   )
     * )
     */
    /**
     * 该接口暂时实现逻辑,调用较少
     */
    public function addPhoneErrorRecord(Request $request){

        $phone              = $request->input('phone','');      //手机号
        $phoneType          = $request->input('phone_type',''); //手机类型
        $phoneVersion       = $request->input('phone_version','');//手机版本号

        return self::appReturnJson(Logic::callSuccess());
    }

    /**
     * @SWG\Post(
     *   path="/user_notice",
     *   tags={"APP-User"},
     *   summary="获取用户消息,功能已去掉,直接返回成功 [User\UserController@userNoticeList]",
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
     *      name="p",
     *      in="formData",
     *      description="页数",
     *      required=true,
     *      type="string",
     *      default="1",
     *   ),
     *     @SWG\Parameter(
     *      name="size",
     *      in="formData",
     *      description="每页显示记录数",
     *      required=true,
     *      type="string",
     *      default="20",
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="获取用户消息成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="获取用户消息失败。",
     *   )
     * )
     */

    /**
     * @param Request $request
     * @return array
     * 功能去掉
     */
    public function userNoticeList(Request $request){

        $page              = $request->input('p',1);      //页数
        $size              = $request->input('size',10); //每页显示记录数

        return self::appReturnJson(Logic::callSuccess());
    }


    

    /**
     * @SWG\Post(
     *   path="/user_yesterday_interest",
     *   tags={"APP-User"},
     *   summary="昨日收益 [User\UserController@userYesterdayInterest]",
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
     *     description="昨日收益获取成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="昨日收益获取失败。",
     *   )
     * )
     */
    /**
     * @param Request $request
     * @return array
     * @desc 昨日收益
     */
    public function userYesterdayInterest(Request $request)
    {

        $userId = $this->getUserId();

        $logic = new UserLogic();

        $result = $logic->getUserYesterdayInterest($userId);

        return self::appReturnJson($result);

    }

}