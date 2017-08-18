<?php

/**
 * Created by PhpStorm.
 * User: hexing
 * Date: 16/5/10
 * Time: 下午12:15
 */
namespace App\Http\Models\User;

use App\Http\Dbs\OrderDb;
use App\Http\Dbs\User\UserInfoDb;
use App\Http\Dbs\UserDb;
use App\Http\Logics\Logic;
use App\Http\Models\Common\HttpQuery;
use App\Http\Models\Model;

use App\Http\Models\Common\ExceptionCodeModel;

use App\Lang\LangModel;

use App\Tools\ToolMoney;
use App\Tools\ToolTime;
use Config,Log;

use App\Tools\ToolCurl;

use App\Http\Models\Common\CoreApi\UserModel as CoreApiUserModel;
/**
 * 模块 公用【手机、密码规则、核心用户相关接口调用】
 * Class UserModel
 * @package App\Http\Models\Common\User
 */
class UserModel extends Model
{

    const

        CORE_STATUS_INACTIVE         = 100, // 待激活

        CORE_STATUS_ACTIVE           = 200, // 激活

        CORE_STATUS_BLOCK            = 300, // 锁定

        CORE_STATUS_FROZEN           = 400, // 冻结

        CODE_STATUS_DELETE           = 500, //已删除



        LAST_CONST                   = 0;

    public static $getCoreApiBaseUserInfo   = []; //通过手机号获取核心用户信息的数据


    public static $codeArr = [
        'getCoreApiBaseUserInfo'                    => 1,
        'getCoreApiUserInfo'                        => 2,
        'getUserInfo'                               => 3,
        'doVerify'                                  => 4,
        'checkUserAuthStatusPasswordCheckedError'   => 5,
        'checkUserAuthStatusNameCheckedError'       => 6,


    ];

    public static $expNameSpace = ExceptionCodeModel::EXP_MODEL_USER;



    /**
     * 验证手机号
     * @param null $phone
     * @param $err_msg string 验证手机号错误信息
     * @return bool
     * @throws \Exception
     */
    public static function validationPhone($phone = null, $err_msg ='ERROR_USER_PHONE'){
        $pattern        = '/^(13\d|14[57]|15[012356789]|18\d|17[0135678])\d{8}$/';
        if(!preg_match($pattern, $phone)) {
            throw new \Exception(LangModel::getLang($err_msg), self::getFinalCode('getCoreApiBaseUserInfo'));
        }
        return true;
    }

    /**
     * 验证邮箱
     * @param null $email
     * @param $err_msg string 验证邮箱错误信息
     * @return bool
     * @throws \Exception
     */
    function checkEmailFormat($email = null,  $err_msg ='ERROR_USER_PHONE') {
        //首字符字母或数字
        $pattern = '#^[a-z_\d](?:\.?[a-z_\d\-]+)*@[a-z_\d](?:\.?[a-z_\d\-]+)*\.[a-z]{2,3}$#is';

        if(!preg_match($pattern, $email)) {
            throw new \Exception(LangModel::getLang($err_msg), self::getFinalCode('getCoreApiBaseUserInfo'));
        }

        return true;
    }
    /**
     * @desc 检测手机号是否注册
     * @author linguanghui
     * @param $phone string
     * @return \Exception
     */
    public static function checkRegisterByPhone( $phone )
    {
        if( empty( $phone ) )
            throw new \Exception( '手机号为空', self::getFinalCode( 'checkRegisterByPhone' ) );

        $result = CoreApiUserModel::getBaseUserInfo($phone);

        if( empty( $result ) )
            throw new \Exception( '用户手机号还未注册', self::getFinalCode( 'checkRegisterByPhone' ) );
    }

    /**
     * 核心接口 获取用户基础信息
     * @param $phone
     * @return array[有效数据 || null]
     * @throws \Exception
     */
    public static function getCoreApiBaseUserInfo($phone, $force = true){
        try {

            if($force == false && !empty(self::$getCoreApiBaseUserInfo)){
                return self::$getCoreApiBaseUserInfo;
            }

            $data = CoreApiUserModel::getBaseUserInfo($phone);

            self::$getCoreApiBaseUserInfo = $data;

        }catch (\Exception $e){
            $data['phone']          = $phone;
            $data['msg']            = $e->getMessage();
            $data['code']           = $e->getCode();

            Log::error(__METHOD__.'curl-Error', $data);

            throw new \Exception(LangModel::getLang('USER_GET_FAILURE'), self::getFinalCode('getCoreApiBaseUserInfo'));
        }

        Log::info($data);

        return $data;

    }

    /**
     * 核心接口 获取用户信息
     * @param $userId
     * @return array
     */
    public static function getCoreApiUserInfo($userId){

        $api  = Config::get('coreApi.moduleUser.getCoreApiUserInfo');

        $return = HttpQuery::corePost($api,['user_id' => $userId]);

        if($return['status'] && !empty($return['data'])){

            $return['data']['balance'] = ToolMoney::formatDbCashAdd($return['data']['balance']);

            return $return['data'];

        }else{

            return [];

        }

    }

    /**
     * @param $userId
     * @return array
     * @desc 核心接口 通过用户id获取用户账户中心接口
     */
    public static function getCoreApiUserInfoAccount( $userId ){

        $user = array();

        $api  = Config::get('coreApi.moduleUser.getCoreApiUserInfoAccount');

        $res  = HttpQuery::corePost($api,['user_id' => $userId]);

        if( $res['code'] == Logic::CODE_SUCCESS ){

            $user = $res['data'];

        }

        return $user;

    }


    /**
     * @param $userId
     * @return bool
     * 根据用户ID获取用户帐户信息,若不存在,直接抛异常
     */
    public static function getUserInfo($userId){

        $userInfo = \App\Http\Models\Common\CoreApi\UserModel::getCoreApiUserInfo($userId);
        if(empty($userInfo)){

            throw new \Exception(LangModel::getLang('ERROR_USER_NOT_EXIST'), self::getFinalCode('getUserInfo'));

        }

        return $userInfo;
    }


    /**
     * @param array $userIds
     * @return array
     * @desc 通过多个用户id获取用户信息列表
     */
    public static function getCoreUserListByIds($userIds){

        if(!is_array($userIds)) return[];

        $api  = Config::get('coreApi.moduleUser.getUserListByIds');

        $return = HttpQuery::corePost($api,['user_ids' => implode(',',$userIds)]);

        if( $return['code'] == Logic::CODE_SUCCESS ){

            $list = $return['data'];

            foreach( $list as $key => $user ){

                $list[$key]['balance'] = ToolMoney::formatDbCashAdd($user['balance']);

            }

            return $list;

        }
        return [];

    }

    /**
     * @param $userId
     * @param $name
     * @param $cardNo
     * @param $bankId
     * @param $idCard
     * @throws \Exception
     * 用户实名+绑卡
     */
    public static function doVerify($userId,$name,$cardNo,$bankId,$idCard){

        $result = \App\Http\Models\Common\CoreApi\UserModel::doVerify($userId,$name,$cardNo,$bankId,$idCard);

        if(!$result['status']){

            throw new \Exception($result['msg'], self::getFinalCode('doVerify'));

        }
    }

    /**
     * @param $userId
     * @param $name
     * @param $cardNo
     * @param $bankId
     * @param $idCard
     * @throws \Exception
     * 用户实名+绑卡+交易密码
     */
    public static function doVerifyTradingPassword($userId,$name,$cardNo,$bankId,$idCard, $tradingPassword){

        $result = \App\Http\Models\Common\CoreApi\UserModel::doVerifyTradingPassword($userId,$name,$cardNo,$bankId,$idCard, $tradingPassword);

        if(!$result['status']){

            throw new \Exception($result['msg'], self::getFinalCode('doVerify'));

        }
    }




    /**
     * [创建用户]
     * @param  [string] $phone
     * @param  [string] $password
     * @return [array]
     */
    public static function createUser($phone,$password){
        return CoreApiUserModel::doCoreApiRegister($phone,$password);
    }



    /**
     * @param $userId
     * @return array
     * @param bool $handle
     * 获取用户实名 + 设置交易密码状态
     * 返回结果说明
     * name_checked  false 未实名  true 已实名
     * password_checked  false 未设置交易密码  true 已设置交易密码
     */

    public static function getUserAuthStatus($userInfo){

        $return = [
            'name_checked'      => false,
            'password_checked'  => false,
        ];

        //已实名
        if(!empty($userInfo['real_name']) && !empty($userInfo['identity_card'])){

            $return['name_checked'] = true;
        }
        //未设置交昴密码
        if($userInfo['password_hash'] !== $userInfo['trading_password']
            && !empty($userInfo['trading_password'])){

            $return['password_checked'] = true;

        }
        return $return;

    }

    /**
     * @param $userInfo
     * @return bool
     * @throws \Exception
     * 检测用户实名 + 设置交易密码状态
     */
    public static function checkUserAuthStatus($userInfo){

        $statusData = self::getUserAuthStatus($userInfo);

        //实名
        self::isUserAuth($statusData);

        //交易密码
        self::isUserTrade($statusData);

        return true;

    }

    /**
     * @param $userInfo
     * @return bool
     * @throws \Exception
     * @desc 检测用户实名状态
     */
    public static function checkUserAuth($userInfo){

        $statusData = self::getUserAuthStatus($userInfo);

        //实名
        self::isUserAuth($statusData);

        return true;

    }

    /**
     * @param $userInfo
     * @return bool
     * @throws \Exception
     * @desc 检测用户设置交易密码状态
     */
    public static function checkUserTrade($userInfo){

        $statusData = self::getUserAuthStatus($userInfo);

        //交易密码
        self::isUserTrade($statusData);

        return true;

    }

    /**
     * @param $statusData
     * @throws \Exception
     * 用户是否实名状态
     */
    private static function isUserAuth($statusData){

        //未实名
        if(!$statusData['name_checked']){

            throw new \Exception(LangModel::getLang('ERROR_USER_NAME_CHECKED'), self::getFinalCode('checkUserAuthStatusNameCheckedError'));
        }

    }

    /**
     * @param $statusData
     * @throws \Exception
     * @desc 检测用户设置交易密码状态
     */
    private static function isUserTrade($statusData){

        //未设置交昴密码
        if(!$statusData['password_checked']){

            throw new \Exception(LangModel::getLang('ERROR_USER_PASSWORD_CHECKED'), self::getFinalCode('checkUserAuthStatusPasswordCheckedError'));

        }

    }

    /**
     * @desc 获取用户的注册状态
     * @author lgh
     * @param $statusCode
     * @return string
     */
    public static function getUserStatus($statusCode){

        if(!$statusCode){
            return '';
        }

        $status = '';
        switch($statusCode)
        {
            case self::CORE_STATUS_ACTIVE:
                $status = '已激活';
                break;
            case self::CORE_STATUS_INACTIVE:
                $status = '未激活';
                break;
            case self::CORE_STATUS_BLOCK:
                $status = '已锁定';
                break;
            case self::CODE_STATUS_DELETE:
                $status = '已删除';
                break;
            case self::CORE_STATUS_FROZEN:
                $status = '已冻结';
                break;

        }
        return $status;
    }

}
