<?php
/**
 * User: caelyn
 * Date: 16/7/7
 * Time: 下午1:35
 */

namespace App\Tools;

use App\Http\Logics\Family\FamilyLogic;

use App\Http\Logics\User\SessionLogic;

use Session;

class FamilyAuth{

    const
        LOGIN_SESSION_KEY_TPL = 'FAMILY_AUTH_CURRENT_USER_ID_%d';   //当前登录的授权用户session键值
    
    protected static $defaultInstance = null;       //默认加密对象
    protected $securityKey = null;                  //加密密串
    
    public function __construct($securityKey = false) {
        if(empty($securityKey)) {
            $securityKey = env('FAMILY_ACCOUNT_SECURITY_KEY');
        }
        
        $this->securityKey = $securityKey;
    }

    /**
     * 获取以默认加密串创建的授权工具类对象
     * @return null|static
     */
    protected static function getDefaultInstance() {
        if(empty(self::$defaultInstance)) {
            self::$defaultInstance = new static();
        }
        
        return self::$defaultInstance;
    }

    /**
     * 获取加密串
     * @param $authToUserId 被授权人用户id
     * @param $authFromUserId 授权人用户id
     * @param bool|false $securityKey 加密串
     * @return string
     */
    public static function getSecurityStr($authToUserId, $authFromUserId, $securityKey = false) {
        if(empty($securityKey)) {
            $securityKey = self::getDefaultInstance()->securityKey;
        }
        $day = date('Ymd');
        return md5(implode('_', array($authFromUserId, $authToUserId, $day, $securityKey)));
    }

    /**
     * 获取加密串及用户信息拼接的url授权串
     * @param $authToUserId
     * @param $authFromUserId
     * @param bool|false $securityKey
     * @return string
     */
    public static function getAuthStr($authToUserId, $authFromUserId, $securityKey = false) {
        if(empty($authToUserId) || empty($authFromUserId)) {
            return '';
        }
        $securityStr = self::getSecurityStr($authToUserId, $authFromUserId, $securityKey);
        return sprintf("%s_%s_%s", $authToUserId, $authFromUserId, $securityStr);
    }

    /**
     * 检测授权串正确性 (1. 授权串校验成功 2. 存在授权关系)
     * @param $authStr
     * @param bool|false $securityKey
     * @return bool
     */
    public static function checkSecurityStr($authStr, $securityKey = false) {
        if(empty($securityKey)) {
            $securityKey = self::getDefaultInstance()->securityKey;
        }
        
        $securityInfo = self::parseSecurityInfo($authStr);
        
        //提供密串校验成功
        $expectedSecurityStr = self::getSecurityStr($securityInfo['authToUserId'], $securityInfo['authFromUserId'], $securityKey);
        $isCorrectSecurity = ($securityInfo['securityStr'] === $expectedSecurityStr);
        if(!$isCorrectSecurity) {
            return false;
        }
        
        //两个用户有授权关系
        $hasAuthConnect = FamilyLogic::hasAuthAccount($securityInfo['authToUserId'], $securityInfo['authFromUserId']);
        if(!$hasAuthConnect) {
            return false;
        }
        
        return true;
    }

    /**
     * 解析授权串
     * @param $authStr
     * @return array
     */
    public static function parseSecurityInfo($authStr) {
        list($authToUserId, $authFromUserId, $securityStr) = explode('_', $authStr);
        
        return [
            'authToUserId'      => $authToUserId,
            'authFromUserId'    => $authFromUserId,
            'securityStr'       => $securityStr,
        ];
    }

    /**
     * 通过授权串自动登录
     * @param $authStr
     * @return bool
     */
    public static function authLogin($authStr) {
        $authFlag = self::checkSecurityStr($authStr);
        if (empty($authFlag)) {
            return false;
        }

        $securityInfo = self::parseSecurityInfo($authStr);
        
        //记录被授权用户id信息，退出授权时使用
        Session::put(self::_getLoginSessionKey($securityInfo['authFromUserId']), $securityInfo['authToUserId']);

        //自动登录授权用户账号
        self::_autoLogin($securityInfo['authFromUserId']);

        Session::put('IS_Family',$securityInfo['authFromUserId']);
        
        return true;
    }

    /**
     * 通过授权串退出授权登录，并自动登录自己账户
     * @param $authStr
     * @return bool
     */
    public static function authLogout($authStr) {
        $authFlag = self::checkSecurityStr($authStr);
        if (empty($authFlag)) {
            return false;
        }

        $securityInfo = self::parseSecurityInfo($authStr);

        //更换登录用户
        $authToUserId = Session::get(self::_getLoginSessionKey($securityInfo['authFromUserId']));
        if($authToUserId != $securityInfo['authToUserId']) {
            return false;
        }
        Session::forget(self::_getLoginSessionKey($securityInfo['authFromUserId']));  //退出授权登录
        //自动登录授权用户账号
        self::_autoLogin($authToUserId);

        Session::forget('IS_Family');

        return true;
    }

    /**
     * 获取被授权人id
     * @param $userId
     * @return mixed
     */
    public static function getAuthToUserId($userId) {

        return Session::get(self::_getLoginSessionKey($userId));
    }
    
    /**
     * 获取登录session键值
     * @param $authFromUserId
     * @return string
     */
    protected static function _getLoginSessionKey($authFromUserId) {
        return sprintf(self::LOGIN_SESSION_KEY_TPL, $authFromUserId);
    }

    /**
     * 通过授权串自动登录
     * @param $authFromUserId
     */
    protected static function _autoLogin($authFromUserId) {

        SessionLogic::setFamilyTokenSession($authFromUserId);

    }

    /**
     * 刷新授权用户登录状态（进入家庭账户首页使用）
     * @param $loginUserId  当前登录用户id
     */
    public static function refreshAuthLoginStatus($loginUserId) {
        //获取当前登录用户授权登录信息（没授权则为空，有授权则得到被授权用户id）
        $authToUserId = self::getAuthToUserId($loginUserId);

        //没有授权登录信息，直接返回
        if(empty($authToUserId)) {
            return true;
        }
        //当前登录用户不是被授权用户，切换到被授权用户
        if($loginUserId != $authToUserId) {
            self::_autoLogin($authToUserId);
            Session::forget('IS_Family');
        } 
    }

}