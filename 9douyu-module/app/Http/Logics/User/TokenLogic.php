<?php
/**
 * Created by PhpStorm.
 * User: hexing
 * Date: 16/4/20
 * Time: 上午10:35
 */

namespace App\Http\Logics\User;

use App\Http\Dbs\User\OAuthAccessTokenDb;
use App\Http\Logics\AppLogic;
use App\Tools\ToolDomainCookie;
use Log;

use App\Http\Logics\Logic;

use App\Tools\DesUtils;

use App\Exceptions\User\Login\LoginException;

use App\Http\Logics\RequestSourceLogic;

use Session;

use Cache;
/**
 * token
 * Class TokenLogic
 * @package App\Http\Logics\User
 */
class TokenLogic extends Logic
{

    const
    TOKEN_CREATE_SUCCESS                = 200,                                               // Oauth创建token成功
    TOKEN_CREATE_CODE                   = 'JDY_ERROR_USER_CHECK',                            // 创建token失败且需要题需要提示给用户
    TOKEN_CREATE_PHONE_CODE             = 'JDY_ERROR_PHONE_CHECK',                           // 创建token失败且需要题需要提示给用户
    GRANT_TYPE_PASSWORD                 = 'password',                                        // OAuth2.0 登陆模式
    GRANT_TYPE_REFRESH_TOKEN            = 'refresh_token',                                   // OAuth2.0 刷新token
    IS_CHECK_APP_DES                    = false,                                             // app 是否 检测des

    END_CONST                           = true;

    protected $refreshExpirationTime    = 7200;                                              // 刷新 token 过期时间

    private $tokenDesKey                = 'p1wD3eKB5oR1oyYDHNPpsdL8kBd0WJaJRlhKQoAvzXzVSO20myAx2jMy8Psz4oeS'; // token 加密 key


    /**
     * 获取更新时间（分钟）
     */
    protected function getRefreshTokenExpiresTime(){
        return 5;
    }

    /**
     * 获取refresh token 有效期
     * @return int
     */
    public function getRefreshExpirationTime()
    {
        if(env('OAUTH_REFRESH_TOKEN_LIFETIME'))
            return $this->refreshExpirationTime = env('OAUTH_REFRESH_TOKEN_LIFETIME');
        return $this->refreshExpirationTime;
    }

    /**
     * 获取加密token 的 Key
     * @return string
     */
    public function getTokenDesKey()
    {
        return $this->tokenDesKey;
    }

    /**
     * 请求token
     * @param array $data
     * @return mixed
     * @throws LoginException
     */
    public function requestToken($data = []){


        try {
            // jiudouyu OAuth2.0 grant type
            $data['grant_type'] = self::GRANT_TYPE_PASSWORD;

            $oldPost            = $_POST;
            $_POST              = $data;
            $server             = app()->make('oauth2');
            $OAuthRequest       = \OAuth2\Request::createFromGlobals();
            $response           = $server->handleTokenRequest($OAuthRequest);
            $OAuthCode          = $response->getStatusCode();
            $tokenData          = $response->getParameters();
            if($OAuthCode == self::TOKEN_CREATE_SUCCESS) {
                $_POST    = $oldPost;
                return $tokenData;
            }
            // Oauth2.0 setError 中需要提醒用户的异常
            if(isset($tokenData['error']) && isset($tokenData['error_description'])){
                if($tokenData['error'] == self::TOKEN_CREATE_CODE || $tokenData['error'] == self::TOKEN_CREATE_PHONE_CODE) {
                    throw new LoginException($tokenData['error_description'], LoginException::LOGIN_OAUTH_ERROR);
                }
            }
            // Oauth2.0 setError
            throw new \Exception($tokenData['error'] .' # ' . $tokenData['error_description'], $OAuthCode);

        }catch (LoginException $e){
            Log::info(sprintf('TokenLogic requestToken Exception -1 code：%s message：%s', $e->getCode(), $e->getMessage()));
            $message = $e->getMessage();
        }catch (\Exception $e){
            // OAuth2.0 setError or OAuth2.0 Exception
            Log::alert(sprintf('TokenLogic requestToken Exception -2 code：%s message：%s', $e->getCode(), $e->getMessage()));
            $message = LoginException::LOGIN_OAUTH_ERROR_MESSAGE;
        }
        $_POST    = $oldPost; // reset post
        throw new LoginException($message, LoginException::LOGIN_OAUTH_ERROR);
    }



    /**
     * 请求token
     * @param array $data
     * @return mixed
     * @throws LoginException
     */
    public function requestToken4($data = []){

        $phoneCheck = false;
        try {
            // jiudouyu OAuth2.0 grant type
            $data['grant_type'] = self::GRANT_TYPE_PASSWORD;

            $oldPost            = $_POST;
            $_POST              = $data;
            $server             = app()->make('oauth2');
            $OAuthRequest       = \OAuth2\Request::createFromGlobals();
            $response           = $server->handleTokenRequest($OAuthRequest);
            $OAuthCode          = $response->getStatusCode();
            $tokenData          = $response->getParameters();
            if($OAuthCode == self::TOKEN_CREATE_SUCCESS) {
                $_POST    = $oldPost;
                return $tokenData;
            }
            // Oauth2.0 setError 中需要提醒用户的异常
            if(isset($tokenData['error']) && isset($tokenData['error_description'])){
                if($tokenData['error'] == self::TOKEN_CREATE_PHONE_CODE) {
                    $phoneCheck = true;
                }
                if($tokenData['error'] == self::TOKEN_CREATE_CODE || $tokenData['error'] == self::TOKEN_CREATE_PHONE_CODE) {
                    throw new LoginException($tokenData['error_description'], LoginException::LOGIN_OAUTH_ERROR);
                }
            }
            // Oauth2.0 setError
            throw new \Exception($tokenData['error'] .' # ' . $tokenData['error_description'], $OAuthCode);

        }catch (LoginException $e){
            Log::info(sprintf('TokenLogic requestToken Exception -1 code：%s message：%s', $e->getCode(), $e->getMessage()));
            $message = $e->getMessage();
        }catch (\Exception $e){
            // OAuth2.0 setError or OAuth2.0 Exception
            Log::alert(sprintf('TokenLogic requestToken Exception -2 code：%s message：%s', $e->getCode(), $e->getMessage()));
            $message = LoginException::LOGIN_OAUTH_ERROR_MESSAGE;
        }
        $_POST    = $oldPost; // reset post
        if($phoneCheck){
            throw new LoginException($message, AppLogic::CODE_NO_REGISTER);
        }
        throw new LoginException($message, LoginException::LOGIN_OAUTH_ERROR);
    }


    /**
     * 处理生成的token 信息
     * @param array $tokenData
     * @param null $factor  客户端 需要传入
     * @return array
     */
    public function handleTokenData($tokenData = [], $factor = null){
        if(empty($factor))
            $factor     = $_SERVER['REMOTE_ADDR']; //browser factor

        $return = [
         'access_token'             => $tokenData['access_token'],
         'token_expires_in'         => $tokenData['expires_in'],
         'access_token_key'         => $this->encryptToken($tokenData['access_token'], $factor),
        ];
        Log::info('REMOTE_ADDR_LOGIN:',[ $_SERVER['REMOTE_ADDR'], $return]);

        // refresh token
        if(isset($tokenData['refresh_token'])) {
            $return['refresh_token']                  = $tokenData['refresh_token'];
            $return['refresh_token_expires_in']       = $this->getRefreshExpirationTime();
        }

        return $return;
    }

    /**
     * 加密token
     * @param null $access_token
     * @param $factor
     * @return string
     */
    public function encryptToken($access_token = null, $factor){
        $DesUtils       = new DesUtils;
        $token_des_key  = $this->getTokenDesKey();
        return $DesUtils->encrypt($access_token . $factor, $token_des_key);
    }

    /**
     * 解密token
     * @param null $access_token_hash
     * @return bool|string
     */
    private function decryptToken($access_token_hash = null){
        $token_des_key  = $this->getTokenDesKey();
        $DesUtils       = new DesUtils;
        return $DesUtils->decrypt($access_token_hash, $token_des_key);
    }

    /**
     * des 验证token 有效性
     * @param null $token
     * @param null $tokenKey
     * @param null $factor
     * @return bool
     */
    private function validToken($token=null, $tokenKey = null, $factor = null){
        //todo wap 获取ip 变化
        return true;
        /**
         * 客户端访问不des 验证
         */
        if(!self::IS_CHECK_APP_DES && RequestSourceLogic::isAppRequest()){
            return true;
        }
        if(!$tokenKey){
            return false;
        }
        return (($token.$factor) === $this->decryptToken($tokenKey));
    }

    /**
     * OAuth2.0 验证token 有效性
     * @param null $token
     * @param bool $isCanGet
     * @return bool
     */
    private function checkToken($token = null, $isCanGet = false)
    {
        try {
            $oldPost                = $_POST;
            unset($_POST);
            $_POST['access_token']  = $token;
            $server                 = app()->make('oauth2');
            $OAuthRequest           = \OAuth2\Request::createFromGlobals();
            //todo the OAuth2.0 verify request must be post or put.
            //token get from cookie ...
            $OAuthRequest->isCanGet = $isCanGet;
            if (!$server->verifyResourceRequest($OAuthRequest)) {
                $OAuthCode = $server->getResponse()->getStatusCode();
                $tokenData = $server->getResponse()->getParameters();
                throw new \Exception($tokenData['error'] .' # ' . $tokenData['error_description'], $OAuthCode);
            }else{
                //刷新过期时间
                $isApp = RequestSourceLogic::isAppRequest();
                if(!$isApp)//todo app 兼容老版本 app端过期时间 一年 不需要刷新token过期时间
                    $this->refreshTokenExpires($token);

                $_POST = $oldPost;
                return true;
            }
        } catch (\Exception $e) {
            $_POST = $oldPost;
            Log::alert(sprintf('TokenLogic checkToken Exception code：%s message：%s', $e->getCode(), $e->getMessage()), [$token]);
            return false;
        }
    }

    /**
     * api 验证token
     *
     * @param null $token
     * @return bool
     */
    public function apiCheckToken($token = null){
        return $this->checkToken($token);
    }

    /**
     * 刷新token 过期时间
     */
    private function refreshTokenExpires($access_token){
        //大于1分钟 更新token 过期时间
        $isUpdate = false;
        try {
            $key         = 'updateExpires_' . $access_token;
            $redis       = Cache::store('redis');
            $dateTime    = $redis->get($key);
            $refreshTime = $this->getRefreshTokenExpiresTime();
            if($dateTime) {
                \Log::info('refreshTokenExpires 1:' . $dateTime);
                $afterSaveTime = strtotime($dateTime  . '+' . $refreshTime . ' minute');
                $nowTime       = time();
                if($nowTime  >= $afterSaveTime){
                    $isUpdate = true;
                    $redis->forget($key);
                }
            }else{
                $redis->put($key, date('Y-m-d H:i:s'), (2 * $refreshTime));
            }
        }catch (\Exception $e){

            \Log::info('refreshTokenExpires -1:' . $e->getMessage());
        }

        \Log::info('refreshTokenExpires 2:' . (int)$isUpdate);

        if($isUpdate) {
            // 更新token 过期时间
            OAuthAccessTokenDb::updateExpires($access_token);
            $from       = RequestSourceLogic::getSource();
            // 更新cookie 过期时间
            if($from == RequestSourceLogic::SOURCE_PC || $from == RequestSourceLogic::SOURCE_WAP) {
                $cookieData             = isset($_COOKIE[env('COOKIE_NAME', 'JDY_COOKIES')]) ? $_COOKIE[env('COOKIE_NAME', 'JDY_COOKIES')] : null;
                $token_lifetime         = env('OAUTH_TOKEN_LIFETIME') ? env('OAUTH_TOKEN_LIFETIME') : 3600;//一小时
                setcookie(
                    env('COOKIE_NAME', 'JDY_COOKIES'),
                    $cookieData,
                    time() + (int)$token_lifetime,
                    '/',
                    ToolDomainCookie::getDomain()
                );
            }
        }
    }

    /**
     * 获取有效的token
     * android、ios
     * pc、wap cookies
     * @param null $token
     * @param null $factor
     * @param null $tokenKey
     * @return bool|null|string
     */
    public function getToken($token = null, $tokenKey = null, $factor = null)
    {
        // 是否有效的token
        $isValidToken   = false;
        // OAuth2.0验证token
        $isCanGet       = true;
        // 尝试浏览器cookie取token
        $isApp = RequestSourceLogic::isAppRequest();
        if(!($isApp)){
            $isCanGet    =  true;
            $cookie      = isset($_COOKIE[env('COOKIE_NAME', 'JDY_COOKIES')]) ? $_COOKIE[env('COOKIE_NAME', 'JDY_COOKIES')] : null;
            Log::info('cookie :'. $cookie);
            if($cookie) {
                $tokenData = SessionLogic::decryptCookie($cookie);
                Log::info('cookieData :', [$tokenData]);
                if (isset($tokenData['access_token']) && isset($tokenData['access_token_key'])) {
                    $factor = $_SERVER['REMOTE_ADDR'];  //browser factor
                    $token = $tokenData['access_token'];
                    $tokenKey = $tokenData['access_token_key'];

                    Log::info('REMOTE_ADDR_CHECK:',[ $factor, $token, $tokenKey]);
                }
            }
        }else{
            Log::info('REMOTE_ADDR_CHECK_APP:',[ $factor, $token, $tokenKey, RequestSourceLogic::getSource()]);
        }


        SessionLogic::getInstance($token, $tokenKey, $factor);

        if(!$token){
            return false;
        }

        // des check and oauth2.0 check.
        if ($this->checkToken($token, $isCanGet) && $this->validToken($token, $tokenKey, $factor))  {
            $isValidToken = true;
        }

        if($isValidToken) {
            SessionLogic::isValidToken(true);
            return $token;
        }else{
            return false;
        }
    }

    /**
     * 设置当前会话
     *
     * @param null $token
     * @param null $tokenKey
     * @param null $factor
     */
    public function setSession($token = null, $tokenKey = null, $factor = null){

        Log::info(sprintf('TokenLogic setSession-1：%s', json_encode([$token, $tokenKey, $factor])));
        // 获取有效token
        $token      = $this->getToken($token, $tokenKey, $factor);

        // 根据token 获取用户详情
        if($token){
            Log::info(sprintf('TokenLogic getSession-2 info token：%s', $token));
            //家庭账户用户详情
            if(Session::has('IS_Family')){
                SessionLogic::setFamilyTokenSession(Session::get('IS_Family'));
            }else{
                SessionLogic::setTokenSession();
            }
        }
    }

    /**
     * 刷新token
     * @param $data
     * @throws LoginException
     */
    public function refreshToken($data){
        try {
            $oldPost            = $_POST;
            $data['grant_type'] = self::GRANT_TYPE_REFRESH_TOKEN;
            $_POST              = $data;
            $server = app()->make('oauth2');
            $OAuthRequest = \OAuth2\Request::createFromGlobals();
            $response = $server->handleTokenRequest($OAuthRequest);

            $OAuthCode = $response->getStatusCode();
            $tokenData = $response->getParameters();
            if($OAuthCode == self::TOKEN_CREATE_SUCCESS) {
                $_POST    = $oldPost;
                return $tokenData;
            }
            // Oauth2.0 setError 中需要提醒用户的异常
//            if(isset($tokenData['error']) && ($tokenData['error'] == self::TOKEN_CREATE_CODE) && isset($tokenData['error_description'])){
//                throw new LoginException($tokenData['error_description'], LoginException::LOGIN_OAUTH_ERROR);
//            }
            // Oauth2.0 setError 中需要提醒用户的异常
            if(isset($tokenData['error']) && isset($tokenData['error_description'])){
                if($tokenData['error'] == self::TOKEN_CREATE_CODE || $tokenData['error'] == self::TOKEN_CREATE_PHONE_CODE) {
                    throw new LoginException($tokenData['error_description'], LoginException::LOGIN_OAUTH_ERROR);
                }
            }

            // Oauth2.0 setError
            throw new \Exception($tokenData['error'] .' # ' . $tokenData['error_description'], $OAuthCode);
        }catch (LoginException $e){
            Log::info(sprintf('TokenLogic refreshToken Exception code：%s message：%s', $e->getCode(), $e->getMessage()));
            $message = $e->getMessage();
        }catch (\Exception $e){
            // OAuth2.0 setError or Exception
            Log::alert(sprintf('TokenLogic refreshToken Exception code：%s message：%s', $e->getCode(), $e->getMessage()));
            $message = LoginException::LOGIN_OAUTH_ERROR_MESSAGE;
        }
        $_POST    = $oldPost; // reset post
        throw new LoginException($message, LoginException::LOGIN_OAUTH_ERROR);
    }

    /**
     * 删除过期token
     */
    public static function deleteExpire(){
        try {

            \App\Http\Dbs\User\OAuthAccessTokenDb::deleteExpireRecord();

            \App\Http\Dbs\User\OAuthRefreshTokenDb::deleteExpireRecord();

        }catch (\Exception $e){
            $attributes['msg']            = $e->getMessage();
            $attributes['code']           = $e->getCode();

            Log::error(__METHOD__.'Error', $attributes);

            return self::callError($e->getMessage());
        }

        return self::callSuccess();
    }
}