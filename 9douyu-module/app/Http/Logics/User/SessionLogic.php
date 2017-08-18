<?php
/**
 * Created by PhpStorm.
 * User: hexing
 * Date: 16/4/22
 * Time: 下午5:27
 */

namespace App\Http\Logics\User;

use App\Http\Dbs\User\UserInfoDb;

use Log;

use App\Http\Dbs\User\OAuthAccessTokenDb;

use App\Tools\DesUtils;

use App\Http\Models\Common\CoreApi\UserModel;

/**
 * use SessionLogic::getTokenSession() get session data
 * Class SessionLogic
 * @package App\Http\Logics\User
 */
class SessionLogic
{
    private static $instance = null;

    private $token           = null;
    private $tokenSet        = false;
    private $tokenKey        = null;
    private $tokenKeySet     = false;
    private $factor          = null;
    private $factorSet       = false;

    private $validToken      = null;

    private $tokenSession     = null;
    private $tokenSessionSet  = false;

    private static $cookieDesKey      = 'p1wD3eKB5oR1oyYDHNPpsdL8kBd0WJaJRlhKQoAvzXzVSO20myAx2jMy8Psz4oeS'; // cookie 加密 key

    private function __construct(){}

    /**
     * 初始化session 验证参数 便于全局调用 不用 分层传递
     * @param null $token
     * @param null $tokenKey
     * @param null $factor
     * @return SessionLogic|null
     */
    public static function getInstance($token=null, $tokenKey = null, $factor = null){
        if(is_null(self::$instance)) {
            self::$instance = new self;
            self::$instance->setFactor($factor);
            self::$instance->setToken($token);
            self::$instance->setTokenKey($tokenKey);
        }
        return self::$instance;
    }

    /**
     * set token
     * @param null $token
     */
    private function setToken($token = null){
        if($this->tokenSet)
            return;
        $this->token    = $token;
        $this->tokenSet = true;
    }

    /**
     * set token key
     * @param null $tokenKey
     */
    private function setTokenKey($tokenKey = null){
        if($this->tokenKeySet)
            return;
        $this->tokenKey    = $tokenKey;
        $this->tokenKeySet = true;
    }

    /**
     * set factor
     * @param null $factor
     */
    private function setFactor($factor = null){
        if($this->factorSet)
            return;
        $this->factor    = $factor;
        $this->factorSet = true;
    }

    /**
     * 获取 token
     * @return null
     */
    public function getToken(){
        return $this->token;
    }

    /**
     * 获取tokenKey
     * @return null
     */
    public function getTokenKey(){
        return $this->tokenKey;
    }

    /**
     * 获取factor
     * @return null
     */
    public function getFactor(){
        return $this->factor;
    }

    /**
     * token 调用
     * @param bool|false $bool
     */
    public static function isValidToken($bool = false){
        if($bool)
            self::$instance->validToken = self::$instance->getToken();
        else
            self::$instance->validToken = null;
    }

    /**
     * 获取有效的令牌
     * @return mixed【null|| token】
     */
    public static function getValidToken(){
        return self::$instance->validToken;
    }


    /**
     * 设置当前会话
     * @return bool|object
     */
    public static function setTokenSession(){
        if(self::$instance->tokenSessionSet)
            return;

        if(is_null($token = self::$instance->validToken)){
            return;
        }
        $data = OAuthAccessTokenDb::getUserIdByToken($token);
        if($data === null){
            Log::alert(sprintf('SessionLogic setTokenSession token 有效 找不到oauth2.0信息：%s', $token));
            return;
        }
        if(!isset($data['user_id'])){
            Log::alert(sprintf('SessionLogic setTokenSession  bug data：%s', json_encode($data)));
            return;
        }

        $session = null;
        $user_id  = $data['user_id'];
        try {
            $session = UserModel::getCoreApiUserInfo($user_id);
            $session = self::formatUser($session);
        }catch (\Exception $e){
            $attributes['msg']            = $e->getMessage();
            $attributes['code']           = $e->getCode();
            Log::error(__METHOD__.'Error', $attributes);
        }
        //$session['phone']         = $phone;

        self::$instance->tokenSessionSet = true;
        self::$instance->tokenSession    = $session;
    }


    /**
     * 设置当前家庭账户会话
     * @return bool|object
     */
    public static function setFamilyTokenSession($authToUserId){

        if(is_null($token = self::$instance->validToken)){
            return;
        }

        $data = OAuthAccessTokenDb::getUserIdByToken($token);

        if($data === null){
            Log::alert(sprintf('SessionLogic setTokenSession token 有效 找不到oauth2.0信息：%s', $token));
            return;
        }
        if(!isset($data['user_id'])){
            Log::alert(sprintf('SessionLogic setTokenSession  bug data：%s', json_encode($data)));
            return;
        }

        $session = null;
        try {
            $session = UserModel::getCoreApiUserInfo($authToUserId);
            $session = self::formatUser($session);

        }catch (\Exception $e){
            $attributes['msg']            = $e->getMessage();
            $attributes['code']           = $e->getCode();
            Log::error(__METHOD__.'Error', $attributes);
        }

        self::$instance->tokenSessionSet = true;
        self::$instance->tokenSession    = $session;
    }


    /**
     * 格式化用户数据
     *
     * @param null $userInfo
     * @return null
     */
    protected static function formatUser($session = null){
        if(empty($session))
            return null;
        $userInfo = new UserInfoDb;
        $info     = $userInfo->getByUserId($session['id']);
        if(!empty($info)){
            $session['user_info'] = $info;
        }else{
            $session['user_info'] = null;
        }
        return $session;
    }

    /**
     * 获取信息
     * @return mixed
     */
    public static function getTokenSession(){
        return self::$instance->tokenSession;
    }

    /**
     * 加密cookie
     * @param array $data
     * @return string
     */
    public static function encryptCookie($data = []){
        $DesUtils = new DesUtils;
        if(is_array($data) || !empty($data))
            return $DesUtils->encrypt(json_encode($data), self::$cookieDesKey);
        return null;
    }

    /**
     * 解密cookie
     * @param $string
     * @return array
     */
    public static function decryptCookie($string = null){
        $DesUtils = new DesUtils;
        if(is_string($string) && !empty($string))
            return json_decode($DesUtils->decrypt($string, self::$cookieDesKey), true);
        return null;

    }



}