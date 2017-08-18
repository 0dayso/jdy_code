<?php
/**
 * Created by PhpStorm.
 * User: hexing
 * Date: 16/4/19
 * Time: 上午11:57
 */
namespace App\Http\Logics\User;

use App\Http\Dbs\User\AvatarDb;
use App\Http\Dbs\User\UserInfoDb;
use App\Http\Models\Common\CoreApi\BankCardModel;

use App\Http\Models\User\UserLoginModel;
use App\Lang\AppLang;
use App\Tools\ToolDomainCookie;
use Log;

use App\Http\Logics\Logic;

use App\Exceptions\User\Login\LoginException;

use  App\Http\Dbs\User\OAuthAccessTokenDb;

use App\Http\Logics\RequestSourceLogic;

use App\Http\Models\Common\PasswordModel;

use App\Http\Models\User\UserModel;
use App\Http\Models\User\UserInfoModel;
use Cache;
use Event;
/**
 * 登陆
 * Class LoginLogic
 * @package App\Http\Logics\User
 */
class LoginLogic extends Logic
{
    const IS_FORCE_KICK_OTHER_LOGIN = true; //是否强制踢出其他端的登陆
    const IS_FORCE_PROLONG_TOKEN_EXPIRES = true; //是否强制更新app的token 过期时间
    const IS_FORCE_KICK_OTHER_SAME_USER_LOGIN = false;////是否强制踢出其他端相同账号的登陆

    const
        STATUS_SUCCESS              = 2000,
        STATUS_ERROR                = 4000,
        STATUS_CAN_REGISTER         = 6000, //手机号可以注册，并发送验证码短信成功

        LOGIN_MAX_TIMES             = 5,    // 最多五次失败
        LOGIN_MAX_TIMES_KEY_PRE     = 'jdy_login_times_PRE_',//登陆次数缓存key前缀

    END                             = true;


    /**
     * 记录登陆失败次数
     * 登陆成功 清除 登陆次数
     * 登陆失败 记录登陆次数
     * @param $phone
     */
    public static function logLoginTimes($phone = null, $isLoginSuccess = false){
        return '';
        try {
            $key = self::LOGIN_MAX_TIMES_KEY_PRE . $phone;
            $redis = Cache::store('redis');
            if ($isLoginSuccess) {
                $redis->forget($key);
            } else {
                //$redis->increment($key); 指定不了时间···
                $times = $redis->get($key);
                $times = (int)$times;
                if (empty($times))
                    $redis->put($key, 1, 1);
                else
                    $redis->put($key, ($times + 1), 1);
            }
        }catch (\Exception $e){
            $returnArray['code']    = $e->getCode();
            $returnArray['msg']     = $e->getMessage();
            //记录登陆次数挂了 那么 就不记录 允许继续执行 并 记录日志【'一般redis 挂了 会显示报错'】
            Log::error(__METHOD__ . ' Error', $returnArray);
        }
    }
    /**
     * 获取登陆失败次数【如果外部需要直接调用 则放入try catch 以免redis报错 抛出异常】
     *
     * @param $phone
     */
    public static function getLoginTimes($phone = null){
        $key   = self::LOGIN_MAX_TIMES_KEY_PRE . $phone;
        $redis = Cache::store('redis');
        return $redis->get($key);
    }

    /**
     * 检测登陆次数
     *
     * @param int $phone
     * @return array
     */
    public static function checkLoginTimes($phone = null){
        try {
            $times = self::getLoginTimes($phone);
            if ($times >= self::LOGIN_MAX_TIMES) {
                $msg = '登录密码错误5次,10分钟内不能登录';
                return self::callError($msg);
            }
        }catch (\Exception $e){
            $returnArray['code']    = $e->getCode();
            $returnArray['msg']     = $e->getMessage();
            //检测接口挂了允许登陆 并记录 错误日志
            Log::error(__METHOD__ . ' Error', $returnArray);
        }

        return self::callSuccess();
    }
    /**
     * 登陆
     *
     * @param array $data
     * @return array
     */
    public function in($data = []){
        try{
            $logData               = $data;
            unset($logData['password']);
            // 验证手机号 有效性
            UserModel::validationPhone($data['username']);

            // 验证密码 有效性[不能使纯数字或字母, 长度]
            PasswordModel::validationPassword($data['password']);


            $factor                = $data['factor'];
            $clientId              = $this->getClientId();
            // if public
            if($clientId)
                $data['client_id'] = $clientId;
            //reset Oauth2.0 param
            unset($data['factor']);

            //request token
            $TokenLogic            = new TokenLogic;

            Log::info('__LOGIN_ERROR_', [1, $logData]);
            $tokenData             = $TokenLogic->requestToken($data);
            Log::info('__LOGIN_ERROR_', [2, $tokenData]);
            // encrypt token and formatting token data
            $return                = $TokenLogic->handleTokenData($tokenData, $factor);
            Log::info('__LOGIN_ERROR_', [3, $return]);
            $userInfo              = UserModel::getCoreApiBaseUserInfo($data['username'], false);
            $return['userInfo']    = $userInfo;
            Log::info('__LOGIN_ERROR_', [4, $return]);
            // 踢出其他端的登陆
            $this->kickOtherLogin($tokenData['access_token'], $userInfo['id']);
            // 延长app token 过期时间
            $this->prolongAppTokenExpires($tokenData['access_token']);

            $callResult            = self::callSuccess($return);
            //拼接登录的设备信息
            $return['client']   =   [
                'version'       =>  isset($data['app_version']) ? $data['app_version'] : '',
                'client_version'=>  isset($data['client_version']) ? $data['client_version'] : '',
                'client_type'   =>  isset($data['client_type']) ? $data['client_type'] : '',
                'uuid'          =>  $factor,
            ];

        }catch (\Exception $e){
            Log::info('__LOGIN_ERROR_', [5, $e]);
            Log::info(sprintf('LoginLogic in Exception code：%s message：%s param：%s', $e->getCode(), $e->getMessage(), json_encode($logData)));
            $callResult            = self::callError($e->getMessage(), $e->getCode());
        }

        //登录成功事件
        $return['request_source'] = RequestSourceLogic::getSource();
        Event::fire(new \App\Events\User\LoginSuccessEvent(
            ['data' => $return]
        ));

        return $callResult;
    }

    /**
     * 登陆 app4.0+
     *
     * @param array $data
     * @return array
     */
    public function in4($data = []){
        try{
            $logData               = $data;
            unset($logData['password']);
            // 验证手机号 有效性
            UserModel::validationPhone($data['username']);

            // 验证密码 有效性[不能使纯数字或字母, 长度]
            PasswordModel::validationPassword($data['password']);


            $factor                = $data['factor'];
            $clientId              = $this->getClientId();
            // if public
            if($clientId)
                $data['client_id'] = $clientId;
            //reset Oauth2.0 param
            unset($data['factor']);

            //request token
            $TokenLogic            = new TokenLogic;

            Log::info('__LOGIN_ERROR_', [1, $logData]);
            $tokenData             = $TokenLogic->requestToken4($data);
            Log::info('__LOGIN_ERROR_', [2, $tokenData]);
            // encrypt token and formatting token data
            $return                = $TokenLogic->handleTokenData($tokenData, $factor);
            Log::info('__LOGIN_ERROR_', [3, $return]);
            $userInfo              = UserModel::getCoreApiBaseUserInfo($data['username'], false);
            $return['userInfo']    = $userInfo;
            Log::info('__LOGIN_ERROR_', [4, $return]);
            // 踢出其他端的登陆
            $this->kickOtherLogin($tokenData['access_token'], $userInfo['id']);
            // 延长app token 过期时间
            $this->prolongAppTokenExpires($tokenData['access_token']);

            #sessionid
            $cookieData = [
                'access_token'     => $return['access_token'],
                'token_expires_in' => $return['token_expires_in'],
                'access_token_key' => $return['access_token_key'],
                'client'           => RequestSourceLogic::getSource(),
            ];
            $tokenRecord          = OAuthAccessTokenDb::getUserIdByToken($return['access_token']);
            $expireAt             = $tokenRecord['expires'];

            $setCookie =[
                'name'      => env('COOKIE_NAME', 'JDY_COOKIES'),
                'value'     => urlencode(SessionLogic::encryptCookie($cookieData)),
                'expire_at' => $expireAt,
                'path'      => '/',
                'domain'    => ToolDomainCookie::getDomain(),
            ];
            $return['set_cookie'] = $setCookie;
            Log::info('__LOGIN_ERROR_', [5, $return]);
            $callResult           = self::callSuccess($return);
            //拼接登录的设备信息
            $return['client']   =   [
                'version'       =>  isset($data['app_version']) ? $data['app_version'] : '',
                'client_version'=>  isset($data['client_version']) ? $data['client_version'] : '',
                'client_type'   =>  isset($data['client_type']) ? $data['client_type'] : '',
                'uuid'          =>  $factor,
            ];

        }catch (\Exception $e){
            Log::info('__LOGIN_ERROR_', [5, $e]);
            Log::info(sprintf('LoginLogic in Exception code：%s message：%s param：%s', $e->getCode(), $e->getMessage(), json_encode($logData)));
            $callResult            = self::callError($e->getMessage(), $e->getCode());
        }

        //登录成功事件
        $return['request_source'] = RequestSourceLogic::getSource();
        Event::fire(new \App\Events\User\LoginSuccessEvent(
            ['data' => $return]
        ));

        return $callResult;
    }
    /**
     * 延长app token 有效期 兼容老版本app
     */
    protected function prolongAppTokenExpires($token){
        $isAppRequest = RequestSourceLogic::isAppRequest();
        if(!$isAppRequest || !self::IS_FORCE_PROLONG_TOKEN_EXPIRES)
            return ;
        OAuthAccessTokenDb::prolongAppTokenExpires($token);
    }

    /**
     * 格式化app登陆后需要的数据
     *
     * @param array $data
     * @return array
     */
    public static function formatAppLoginInData($data = []){
        $userInfo = $data['userInfo'];
        $data = [
            'items'=>[
                'id'                     => $userInfo['id'],
                'phone'                  => $userInfo['phone'],
                'real_name'              => $userInfo['real_name'],
                'identity_card'          => $userInfo['identity_card'],
                'real_name_auth'         => !empty($userInfo["real_name"]) ? "on" : "off",
                'trade_password_status'  => ($userInfo['password'] == $userInfo['trading_password'] || empty($userInfo['trading_password'])) ? 'off' : 'on',
                'username'               => $userInfo['phone'],
            ],

            'token' => $data['access_token']
        ];

        return self::callSuccess($data);
    }
    /**
     * 格式化app4登陆后需要的数据
     *
     * @param array $data
     * @return array
     */
    public static function formatApp4LoginInData($data = []){
        $userInfo   = $data['userInfo'];
        $userInfoDb = new UserInfoDb;
        $userExtends= $userInfoDb->getByUserId($userInfo['id']);


        //用户银行卡相关信息
        $userBankCardInfo = BankCardModel::getUserBindCard($userInfo['id']);
        if(!empty($userBankCardInfo)){
            $userBankCardInfo['real_name'] = $userInfo['real_name'];
        }
        $userLogic = new UserLogic;
        $userBank  = $userLogic->formatAppUserBank($userBankCardInfo);

        //头像信息
        //用户头像
        $userAvatar = AvatarDb::getUserAvatarByUserId($userInfo['id']);

        $avatar = '';
        if(isset($userAvatar['avatar_url'])){
            $avatarUrl  = $userAvatar['avatar_url'];
            $avatar   = strpos($avatarUrl,'ttp://www.9douyu.com') ? assetUrlByCdn(substr($avatarUrl,strlen('http://www.9douyu.com'))) : assetUrlByCdn($avatarUrl);
        }

        $userInfoModel = new UserInfoModel();

        $data = [
            'id'                     => $userInfo['id'],
            'phone'                  => $userInfo['phone'],
            'real_name'              => $userInfo['real_name'],
            'identity_card'          => $userInfo['identity_card'],
            'real_name_auth'         => !empty($userInfo["real_name"]) ? "on" : "off",
            'trade_password_status'  => ($userInfo['password'] == $userInfo['trading_password'] || empty($userInfo['trading_password'])) ? 'off' : 'on',
            'username'               => $userInfo['phone'],
            'token'                  => $data['access_token'],
            'avatar'                 => $avatar,
            'assessment'             => !isset($userExtends["assessment_score"]) || is_null($userExtends["assessment_score"]) ? '' : UserInfoModel::assessmentType($userExtends["assessment_score"]),
            'assessmentUrl'          => env('APP_URL_WX') . "/article/questionnaire",

            'email'                  => !empty($userExtends["email"]) ? $userExtends["email"] : '',
            'address_text'           => !empty($userExtends["address_text"]) ? $userExtends["address_text"] : '',
            'user_bank'              => $userBank,
            'bank_card_notice'       => AppLang::APP_BANK_CARD_NOTICE,
            'set_cookie'             => $data['set_cookie'],
        ];

        return self::callSuccess($data);
    }


    /**
     * app 检测手机号
     *
     * @param null $phone
     * @return array
     */
    public static function checkPhone($phone = null){
        try{
            // 验证手机号有效性
            UserModel::validationPhone($phone);
            // 获取核心接口
            $userInfo  = UserModel::getCoreApiBaseUserInfo($phone);

            if(empty($userInfo)){
                $returnArray = [
                    "status"    =>  self::STATUS_CAN_REGISTER,
                    "msg"     =>  "此手机号可以注册,短信验证码发送成功"
                ];
            }else {
                $returnArray = [
                    "status"  => self::STATUS_SUCCESS,
                    "msg" => "此手机号已经存在，去登录"
                ];
            }

        }catch (\Exception $e){
            $returnArray['status']    = self::STATUS_ERROR;
            $returnArray['msg']     = $e->getMessage();
            Log::error(__METHOD__ . 'Error', $returnArray);
            return self::callError($returnArray['msg']);
        }

        return self::callSuccess($returnArray);
    }

    /**
     * 登出其他端的登陆
     * @param null $token
     * @param null $userId
     */
    protected function kickOtherLogin($token = null, $userId = null){
//        if(self::IS_FORCE_KICK_OTHER_SAME_USER_LOGIN){
//            if(RequestSourceLogic::isAppRequest()){
//                OAuthAccessTokenDb::kickOtherLogin($token, $userId);
//            }
//        }

        if(!self::IS_FORCE_KICK_OTHER_LOGIN)
            return;
        OAuthAccessTokenDb::kickOtherLogin($token, $userId);
    }


    /**
     * 退出
     * @return array 已经登陆的用户
     */
    public static function destroy($token = null){

        //已经登陆的用户
        $callResult = self::callSuccess([], '退出账号成功');

        if( $session = SessionLogic::getTokenSession()) {
            //过期access_token
            if(OAuthAccessTokenDb::expire($session['id'])){

                $callResult = self::callSuccess([], '退出账号成功');

                $from       = RequestSourceLogic::getSource();

                if($from == RequestSourceLogic::SOURCE_PC || $from == RequestSourceLogic::SOURCE_WAP) {
                    //销毁cookie
                    setcookie(
                        env('COOKIE_NAME', 'JDY_COOKIES'),
                        '',
                        time() - 1,
                        '/',
                        ToolDomainCookie::getDomain()
                    );
                }
            }
        }
        return $callResult;
    }

    /**
     * 通过来源 获取clientID 如果不传判定为浏览器请求登陆
     * @return string
     * @throws LoginException
     */
    protected function getClientId(){
        $from       = RequestSourceLogic::getSource();
        if(!in_array($from, RequestSourceLogic::$clientSource)){
            Log::info(sprintf('LoginLogic getClientId Exception from：%s', $from));
            throw new LoginException(LoginException::LOGIN_OAUTH_ERROR_MESSAGE, LoginException::LOGIN_OAUTH_ERROR);
        }
        return $this->setClientId($from);
    }

    /**
     * 封装clientId
     * @param null $from
     * @return string
     */
    public function setClientId($from = null){
        return md5($from);
    }

    /**
     * 浏览器 处理 写入cookie
     * @param array $data
     */
    public function handleFrom($data = []){
        $from  = RequestSourceLogic::getSource();
        if($from == RequestSourceLogic::SOURCE_PC || $from == RequestSourceLogic::SOURCE_WAP) {
            $cookieData = [
                'access_token'     => $data['access_token'],
                'token_expires_in' => $data['token_expires_in'],
                'access_token_key' => $data['access_token_key'],
                'client'           => $from,
            ];
            setcookie(
                env('COOKIE_NAME', 'JDY_COOKIES'),
                SessionLogic::encryptCookie($cookieData),
                time() + (int)$data['token_expires_in'],
                '/',
                ToolDomainCookie::getDomain()
            );
        }
    }


    /**
     * 请求延长登陆时间
     * @param $data
     * @return array
     */
    public function prolongSession($data){
        try{
            //refreshToken
            $logData               = $data;
            $factor                = $data['factor'];
            $clientId              = $this->getClientId();
            // if public
            if($clientId)
                $data['client_id'] = $clientId;

            //reset Oauth2.0 param
            unset($data['factor']);

            $TokenLogic            = new TokenLogic;
            Log::info(sprintf('LoginLogic prolongSession data：%s', print_r($data, true)));

            $tokenData             = $TokenLogic->refreshToken($data);

            // encrypt token and format token data
            $return                = $TokenLogic->handleTokenData($tokenData, $factor);
            $callResult            = self::callSuccess($return);

        }catch (\Exception $e){
            Log::info(sprintf('LoginLogic prolongSession Exception code：%s message：%s param：%s', $e->getCode(), $e->getMessage(), json_encode($logData)));
            $callResult            = self::callError($e->getMessage(), $e->getCode());
        }
        return $callResult;
    }

    /**
     * @desc 创建登录记录
     * @param $data
     * @return array
     */
    public function createUserLoginHistory($data){

        try{
            $userModel = new UserLoginModel();
            $userModel->createUserLoginHistory($data);
        }catch(\Exception $e){
            $data['msg']     = $e->getMessage();
            $data['code']    = $e->getCode();

            Log::error(__METHOD__ . 'Error', $data);
            return self::callError($e->getMessage());
        }
        return self::callSuccess([], '登录记录创建成功');
    }

}
