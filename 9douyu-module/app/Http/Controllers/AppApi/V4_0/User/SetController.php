<?php
/**
 * Created by PhpStorm.
 * User: jinzhuotao
 * Date: 2017/3/3
 * Time: 下午12:32
 */

namespace App\Http\Controllers\AppApi\V4_0\User;

use App\Http\Controllers\AppApi\AppController;
use App\Http\Logics\AppLogic;
use App\Http\Logics\User\PasswordLogic;
use App\Http\Logics\User\SuggestLogic;
use App\Http\Logics\User\UserInfoLogic;
use Illuminate\Http\Request;

class SetController extends AppController
{
    /**
     * @param Request $request
     * @return array
     */
    /**
     * @SWG\Post(
     *   path="/update_email",
     *   tags={"APP-User"},
     *   summary="个人信息-修改邮箱地址 [User\SetController@updateEmail]",
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
     *      name="email",
     *      in="formData",
     *      description="邮箱地址",
     *      required=true,
     *      type="string",
     *      default="",
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
     **/
    public function updateEmail(Request $request){

        $userId = $this->getUserId();

        $email  = $request->input('email');

        if(!isset($email)){
            return AppLogic::callError(AppLogic::CODE_MISSING_PARAMETERS,AppLogic::CODE_MISSING_PARAMETERS);
        }

        $logic  = new UserInfoLogic();

        $res = $logic->setUserEmail( $userId, $email );

        return self::returnJsonData($res);
    }

    /**
     * @param Request $request
     * @return array
     */
    /**
     * @SWG\Post(
     *   path="/update_address",
     *   tags={"APP-User"},
     *   summary="个人信息-修改详细地址 [User\SetController@updateAddress]",
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
     *      name="address",
     *      in="formData",
     *      description="详细地址",
     *      required=false,
     *      type="string",
     *      default="",
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
     **/
    public function updateAddress(Request $request){

        $userId = $this->getUserId();

        $address  = $request->input('address');

        if(!isset($address)){
            return AppLogic::callError(AppLogic::CODE_MISSING_PARAMETERS,AppLogic::CODE_MISSING_PARAMETERS);
        }

        $logic  = new UserInfoLogic();

        $res = $logic->setUserAddress( $userId, $address );

        return self::returnJsonData($res);
    }

    /**
     * @SWG\Post(
     *   path="/find_password",
     *   tags={"APP-User"},
     *   summary="找回登录密码 [User\SetController@findPassword]",
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
     *      description="手机号",
     *      required=true,
     *      type="string",
     *      default="",
     *   ),
     *   @SWG\Parameter(
     *      name="pwd",
     *      in="formData",
     *      description="新密码",
     *      required=true,
     *      type="string",
     *      default="",
     *   ),
     *   @SWG\Parameter(
     *      name="new_pwd",
     *      in="formData",
     *      description="重复新密码",
     *      required=true,
     *      type="string",
     *      default="",
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="找回登录密码成功",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="找回登录密码失败。",
     *   )
     * )
     **/
    public function findPassword(Request $request){

        $phone  = $request->input('phone','');
        $pwd    = $request->input('pwd','');
        $newPwd = $request->input('new_pwd','');

        $logic      = new PasswordLogic();
        $result     = $logic->resetPassword($phone,$pwd,$newPwd);

        return self::returnJsonData($result);

    }

    /**
     * @SWG\Post(
     *   path="/find_trading",
     *   tags={"APP-User"},
     *   summary="找回交易密码 [User\SetController@findTradingPwd]",
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
     *      name="pwd",
     *      in="formData",
     *      description="新密码",
     *      required=true,
     *      type="string",
     *      default="",
     *   ),
     *   @SWG\Parameter(
     *      name="new_pwd",
     *      in="formData",
     *      description="重复新密码",
     *      required=true,
     *      type="string",
     *      default="",
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="找回登录密码成功",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="找回登录密码失败。",
     *   )
     * )
     **/
    public function findTradingPwd(Request $request){

        $userId = $this->getUserId();
        $pwd    = $request->input('pwd','');
        $newPwd = $request->input('new_pwd','');

        $logic      = new PasswordLogic();
        $result     = $logic->modifyTradingPassword($pwd,$userId,$newPwd);

        return self::returnJsonData($result);

    }

    /**
     * @SWG\Post(
     *   path="/update_password",
     *   tags={"APP-User"},
     *   summary="修改登录密码 [User\SetController@updatePassword]",
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
     *      name="old_pwd",
     *      in="formData",
     *      description="原密码",
     *      required=true,
     *      type="string",
     *      default="",
     *   ),
     *   @SWG\Parameter(
     *      name="new_pwd",
     *      in="formData",
     *      description="新密码",
     *      required=true,
     *      type="string",
     *      default="",
     *   ),
     *   @SWG\Parameter(
     *      name="repeat_pwd",
     *      in="formData",
     *      description="重复新密码",
     *      required=true,
     *      type="string",
     *      default="",
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="修改登录密码成功",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="修改登录密码失败。",
     *   )
     * )
     **/
    public function updatePassword(Request $request){

        $userId    = $this->getUserId();
        $oldPwd    = $request->input('old_pwd','');
        $newPwd    = $request->input('new_pwd','');
        $repeatPwd = $request->input('repeat_pwd','');

        $logic      = new PasswordLogic();
        $result     = $logic->updatePasswordV4($userId,$oldPwd,$newPwd,$repeatPwd);

        return self::returnJsonData($result);

    }

    /**
     * @SWG\Post(
     *   path="/update_trading_password",
     *   tags={"APP-User"},
     *   summary="修改交易密码 [User\SetController@updateTradingPassword]",
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
     *      name="old_pwd",
     *      in="formData",
     *      description="原密码",
     *      required=true,
     *      type="string",
     *      default="",
     *   ),
     *   @SWG\Parameter(
     *      name="new_pwd",
     *      in="formData",
     *      description="新密码",
     *      required=true,
     *      type="string",
     *      default="",
     *   ),
     *   @SWG\Parameter(
     *      name="repeat_pwd",
     *      in="formData",
     *      description="重复新密码",
     *      required=true,
     *      type="string",
     *      default="",
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="修改交易密码成功",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="修改交易密码失败。",
     *   )
     * )
     **/
    public function updateTradingPassword(Request $request){

        $userId    = $this->getUserId();
        $oldPwd    = $request->input('old_pwd','');
        $newPwd    = $request->input('new_pwd','');
        $repeatPwd = $request->input('repeat_pwd','');

        $logic      = new PasswordLogic();
        $result     = $logic->updateTradingPasswordV4($userId,$oldPwd,$newPwd,$repeatPwd);

        return self::returnJsonData($result);

    }

    /**
     * @SWG\Post(
     *   path="/more",
     *   tags={"APP-User"},
     *   summary="更多 [User\SetController@more]",
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
     *      required=false,
     *      type="string",
     *      default="653030e9f8e4f6559669386dfe4f56d4",
     *   ),
     *   @SWG\Parameter(
     *      name="uuid",
     *      in="formData",
     *      description="uuid",
     *      required=true,
     *      type="string",
     *      default="653030e9f8e4f6559669386dfe4f56d4",
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="更多成功",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="更多失败。",
     *   )
     * )
     **/
    /**
     * @param Request $request
     * @return array
     * 更多接口
     */
    public function more(Request $request){

        $faqInfo        = $this->setFaqInfo();
        $aboutUsInfo    = $this->aboutUs();

        $items = [
            "faqInfo"               =>  $faqInfo,
            'aboutUs'               =>  $aboutUsInfo,
        ];

        $result = AppLogic::callSuccess($items);

        $result['status'] = true;

        return self::returnJsonData($result);

    }

    /**
     * @return array
     * @desc 常见问题
     */
    private function setFaqInfo()
    {
        $faqInfo    =   [
            'title' =>  '常见问题',
            'url'   =>  env('APP_URL_WX') . "/article/hotQuestion"
        ];

        return $faqInfo;
    }


    /**
     * @return array
     * @desc 常见问题
     */
    private function aboutUs()
    {

        $url = '';
        $faqInfo = [];

        if($url){
            $faqInfo    =   [
                'title' =>  '关于我们',
                'url'   =>  $url,
            ];
        }

        return $faqInfo;
    }

    /**
     * @SWG\Post(
     *   path="/feedback",
     *   tags={"APP-User"},
     *   summary="添加用户反馈意见 [User\SetController@feedback]",
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
     *      name="phone_type",
     *      in="formData",
     *      description="手机型号",
     *      required=true,
     *      type="string",
     *      default="iphone 6s",
     *   ),
     *     @SWG\Parameter(
     *      name="phone_version",
     *      in="formData",
     *      description="手机版本",
     *      required=true,
     *      type="string",
     *      default="9.1.10",
     *   ),
     *     @SWG\Parameter(
     *      name="content",
     *      in="formData",
     *      description="意见内容",
     *      required=true,
     *      type="string",
     *      default="提现到账太慢",
     *   ),
     *     @SWG\Parameter(
     *      name="phone_sysytem_version",
     *      in="formData",
     *      description="手机操作版本",
     *      required=true,
     *      type="integer",
     *      default="9.1",
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="添加用户反馈意见成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="添加用户反馈意见失败。",
     *   )
     * )
     */
    public function feedback(Request $request){

        $post['content']                = $request->input('content','');//内容
        //设备信息
        $post['client']                 = $request->input('client',''); //来源端 ios android
        $post['version']                = $request->input('version','');    //版本号
        $post['phone_type']             = $request->input('phone_type',''); //手机型号
        $post['phone_version']          = $request->input('phone_version','');
        $post['phone_system_version']   = $request->input('phone_system_version',''); //操作系统版本

        $post['user_id']                = $this->getUserId();


        $logic      = new SuggestLogic();
        $result     = $logic->addSuggest($post);

        return self::returnJsonData($result);
    }

    /**
     * @SWG\Post(
     *   path="/check_trading_pwd",
     *   tags={"APP-User"},
     *   summary="验证支付密码是否正确 [User\SetController@checkTradingPassword]",
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
     *      name="trading_password",
     *      in="formData",
     *      description="支付密码",
     *      required=true,
     *      type="string",
     *      default="123qwe",
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="验证成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="验证失败。",
     *   )
     * )
     */
    public function checkTradingPassword( Request $request ){

        $tradingPassword = $request->input('trading_password');

        $userId = $this->getUserId();

        $passwordLogic = new PasswordLogic();

        $checkResult = $passwordLogic->checkTradingPasswordForApp($tradingPassword, $userId);

        return self::returnJsonData($checkResult);

    }


}
