<?php
/**
 * Created by PhpStorm.
 * User: hexing
 * Date: 16/5/10
 * Time: 上午11:09
 */
namespace App\Http\Models\User;

use App\Http\Models\Common\ExceptionCodeModel;

use App\Lang\LangModel;

use App\Http\Models\Common\PasswordModel;

use App\Http\Logics\RequestSourceLogic;

use App\Tools\ToolEnv;

use Config;

use App\Http\Models\Common\SmsModel as Sms;

use Illuminate\Support\Facades\Log;

use App\Http\Models\Common\CoreApi\UserModel as CoreApiUserModel;

use App\Http\Models\Common\ServiceApi\SmsModel;

use Cache;
/**
 * 用户注册model类
 * Class UserRegisterModel
 * @package App\Http\Models\User
 */
class UserRegisterModel extends UserModel{

    const
        REGISTER_SMS_MAX_TIMES_KEY_PRE     = 'jdy_send_register_sms_times_pre_', //注册发送验证码次数缓存key前缀
        SEND_SMS_MAX_TIMES                 = 10,//发送注册认证码次数限制

        END = true;

    public static $codeArr = [
        'validationFieldEmpty'           => 1,

        'validationPhone'                => 2,

        'validationAgreement'            => 3,

        'validationPasswordEqual'        => 4,

        'validationPassword'             => 5,

        'requestFrom'                    => 6,

        'validationCoreData'             => 7,

        'doRegister'                     => 8,

        'doCoreApiRegister'              => 9,

        'sendRegisterSms'                => 10,

        'validationPhoneCode'            => 11,

        'sendRegisterSmsServerError'     => 12,

        'doCoreApiRegisterEmpty'         => 13,

        'validationInvitePhone'          => 14,

        'checkRegisterSmsTimes'          => 15,

    ];


    public static $expNameSpace = ExceptionCodeModel::EXP_MODEL_USER_REGIESTER;


    /**非空验证
     * @param null $field
     * @param $fieldName
     * @return bool
     * @throws \Exception
     */
    public function validationFieldEmpty($field =null, $fieldName){
        if(empty(trim($field))){
            throw new \Exception($fieldName . LangModel::getLang('ERROR_EMPTY'), self::getFinalCode('validationFieldEmpty'));
        }
        return true;
    }


    /**
     * @param null $code
     * @param null $phone
     * @return bool
     * @throws \Exception
     */
    public function validationPhoneCode($phone = null, $code = null){
        $data = Sms::checkPhoneCode($code, $phone);
        if(!$data['status']){
            throw new \Exception(LangModel::getLang('PHONE_VERIFY_CODE_REGISTER_ERROR'), self::getFinalCode('validationPhoneCode'));
        }
        return true;
    }

    /**
     * 请求来源验证
     * @param null $requestFrom
     * @return bool
     * @throws \Exception
     */
    public function validationRequestFrom($requestFrom = null){

        if(!in_array(strtolower($requestFrom), RequestSourceLogic::$clientSource)){

            return RequestSourceLogic::$clientSource[1];

        }

        return $requestFrom;
        
    }

    /**
     * 验证核心接口数据
     * @param array $userInfo
     * @return bool[无数据 返回true 否则抛出异常]
     * @throws \Exception
     */
    public function validationCoreUserInfo($userInfo){
        if(empty($userInfo))
            return true;

        if($userInfo['status'] == UserModel::CORE_STATUS_BLOCK)
            throw new \Exception(LangModel::getLang('USER_REGISTER_STATUS_BLOCK'), self::getFinalCode('validationCoreData'));
        if($userInfo['status'] == UserModel::CORE_STATUS_ACTIVE)
            throw new \Exception(LangModel::getLang('USER_REGISTER_STATUS_ACTIVE'), self::getFinalCode('validationCoreData'));
        return true;
    }

    /**
     * 调用核心接口 注册
     * @return array[有效数据 || null]
     * @throws \Exception
     */
    public static function doCoreApiRegister($data){
        Log::info('调用核心注册Api-data：' . json_encode($data));
        try {
            $realName     = isset($data['real_name']) ? $data['real_name'] : null;
            $identityCard = isset($data['identity_card']) ? $data['identity_card'] : null;

            $data = CoreApiUserModel::doCoreApiRegister($data['phone'], $data['password'], $realName, $identityCard);
            if(empty($data)){
                throw new \Exception(LangModel::getLang('USER_REGISTER_DO_REGISTER'), self::getFinalCode('doCoreApiRegisterEmpty'));
            }
            return $data;

        }catch (\Exception $e){
            Log::info('调用核心注册Api-error：' . json_encode([$e->getCode(), $e->getMessage()]));
        }
        throw new \Exception(LangModel::getLang('USER_REGISTER_DO_REGISTER'), self::getFinalCode('doCoreApiRegister'));
    }
    /**
     * 调用核心接口 创建用户
     * @param $data
     * @return array
     */
    public function doRegister($data){
            $coreApi['phone']    = $data['phone'];
            $coreApi['password'] = PasswordModel::encryptionPassword($data['password']);
            return self::doCoreApiRegister($coreApi);
    }

    /**
     * 两次密码是否一致
     * @param null $password
     * @param null $password_confirm
     * @return bool
     * @throws \Exception
     */
    public function validationPasswordEqual($password=null, $password_confirm=null){
        if($password !== $password_confirm) {
            $message = LangModel::getLang('MODEL_USER_PASSWORD_CONFIRM_NOT_MATCH');
            throw new \Exception($message, self::getFinalCode('validationPasswordEqual'));
        }
        return true;
    }

    /**
     * 注册协议同意与否
     * @param null $agreement
     * @return bool
     * @throws \Exception
     */
    public function validationAgreement($agreement = null){
        if(empty($agreement)) {
            throw new \Exception(LangModel::getLang('USER_REGISTER_AGREEMENT'), self::getFinalCode('validationAgreement'));
        }
        return true;
    }

    /**
     * 发送短信验证码
     * @param null $phone
     * @param string $content
     * @return bool
     * @throws \Exception
     */
    public static function sendRegisterSms($phone = null, $content = ''){
        try {
            if(ToolEnv::getAppEnv() !== 'production') {
                return true;
            }
            self::checkRegisterSmsTimes($phone);

            $data = SmsModel::sendVerify($phone, $content);

            if ($data['status'] === false)
                throw new \Exception(LangModel::getLang('USER_REGISTER_SEND_REGISTER_SMS_ERROR'), self::getFinalCode('sendRegisterSmsServerError'));
        }catch (\Exception $e){
            $data['content'] = $content;
            $data['phone']   = $phone;
            $data['msg']     = $e->getMessage();
            $data['code']    = $e->getCode();

            Log::error(__METHOD__ . 'Error', $data);

            throw new \Exception(LangModel::getLang('USER_REGISTER_SEND_REGISTER_SMS_ERROR'), self::getFinalCode('sendRegisterSms'));
        }

        return true;

    }

    /**
     * 检测发送验证码发送次数限制
     *
     * @param null $phone
     * @throws \Exception
     */
    public static function checkRegisterSmsTimes($phone = null){

        $times = self::getSendRegisterSmsTimes($phone);
        Log::info('checkRegisterSmsTimes : ', [$phone, $times]);
        if ($times >= self::SEND_SMS_MAX_TIMES) {
            $msg = '发送注册验证码达到最大次数限制';
            throw new \Exception($msg, self::getFinalCode('checkRegisterSmsTimes'));
        }

    }

    /**
     * 获取发送注册短信限制
     *
     * @param $phone
     */
    public static function getSendRegisterSmsTimes($phone = null){
        $key   = self::REGISTER_SMS_MAX_TIMES_KEY_PRE . $phone;
        return Cache::get($key);
    }


    /**
     * 发送次数限制
     *
     * @param null $phone
     */
    public static function sendRegisterSmsTimes($phone = null){
        try {
            $key     = self::REGISTER_SMS_MAX_TIMES_KEY_PRE . $phone;
            $times   = Cache::get($key);
            $times   = (int)$times;
            Log::info('checkRegisterSmsTimes : ', [$times, $phone]);
            $minutes = 60*24;//一天有效期
            if (empty($times)) {
                Cache::put($key, 1, $minutes);
            }else {
                Cache::put($key, ($times + 1), $minutes);
            }
        }catch (\Exception $e){
            $returnArray['code']    = $e->getCode();
            $returnArray['msg']     = $e->getMessage();
            //记录发送注册验证码次数挂了 那么 就不记录 允许继续执行
            Log::error(__METHOD__ . ' Error', $returnArray);
        }
    }


    /**
     * @param $invitePhone
     * @return bool
     * @throws \Exception
     * @desc 邀请手机号验证是否为注册手机号
     */
    public static function validationInvitePhone( $invitePhone, $phone ){

        try{

            if($invitePhone == $phone){

                throw new \Exception(LangModel::getLang('USER_REGISTER_INVITE_PHONE_NO_ERROR'), self::getFinalCode('validationInvitePhone'));

            }

            $result = CoreApiUserModel::getBaseUserInfo($invitePhone);

            if(empty($result)){

                throw new \Exception(LangModel::getLang('USER_REGISTER_INVITE_PHONE_ERROR'), self::getFinalCode('validationInvitePhone'));

            }

        }catch (\Exception $e){

            $data['phone']   = $invitePhone;
            $data['msg']     = $e->getMessage();
            $data['code']    = $e->getCode();

            Log::error(__METHOD__ . 'Error', $data);

            throw new \Exception($e->getMessage(), self::getFinalCode('validationInvitePhone'));
        }

        return true;

    }


}