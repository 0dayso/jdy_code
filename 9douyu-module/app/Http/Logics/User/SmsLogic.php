<?php
/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 16/7/5
 * Time: 12:00
 */

namespace App\Http\Logics\User;

use App\Http\Models\Common\CheckLimitModel;
use App\Http\Logics\Logic;
use App\Http\Models\Common\CoreApi\UserModel;
use App\Http\Models\Common\SmsModel;
use App\Http\Models\Common\ValidateModel;
use App\Lang\LangModel;
use App\Tools\ToolEnv;
use Cache;

class SmsLogic extends Logic{

    //当发送的验证码类型为此值时,修改的手机号的用户不能存在,否则报错
    private $smsType = 'modify_phone';

    //注册发送
    private $smsRegisterType = 'register_active';
    /**
     * @param $phone
     * @param $code
     * @param $type
     * @bool $isClean 为true时清除验证码缓存
     * @return array
     * 根据类型检测验证码是否正确
     */
    public function checkCodeByType($phone,$code,$type,$isClean=true){

        try{

            ValidateModel::isPhone($phone);

            ValidateModel::isSmsCode($code);

            $smsTypeList = $this->smsType();
            //短信类型错误
            if(!array_key_exists($type,$smsTypeList)){

                return self::callError(LangModel::getLang('ERROR_SMS_TYPE'));
            }

            $cacheKey = '';

            if( $type == 'register_active' ){

                $cacheKey = 'PHONE_VERIFY_CODE' .$phone;

                $oldCode = \Cache::get($cacheKey);

            }else{

                $cacheKey = "sms{$phone}_{$type}";

                //写入缓存
                $oldCode = \Cache::get($cacheKey);

            }

            if(empty($oldCode)){

                return self::callError('验证码不存在或已失效,请重新发送');

            }else if($oldCode !== $code){

                return self::callError('验证码错误');

            }else{

                if($isClean == true){
                    \Cache::forget($cacheKey);
                }

                return self::callSuccess([]);
            }

        }catch(\Exception $e){

            return self::callError($e->getMessage());

        }
    }

    /**
     * @param $phone
     * @param $code
     * 根据类型检测验证码是否正确
     */
    public function checkRegisterCode($phone,$code){

        $result = SmsModel::checkPhoneCode($code,$phone,false);

        if($result['status']){

            return self::callSuccess();
        }else{

            return self::callError($result['msg']);
        }


    }


    private function smsType(){

         return array(
            "register_active"      => LangModel::getLang('PHONE_VERIFY_CODE_ACTIVATE'),//注册激活
            "find_password"        => LangModel::getLang('PHONE_VERIFY_CODE_FORGET_PASSWORD'),//找回密码
            "modify_phone"         => LangModel::getLang('PHONE_VERIFY_CODE_NEW_PHONE'),//修手机号
            "find_tradingPassword" => LangModel::getLang('PHONE_VERIFY_CODE_COMMON'),//找回交易密码
        );

    }

    /**
     * @param $userId
     * @param $phone
     * @param $type
     * @return array
     *
     */
    public function sendSms($phone,$type){

        $return = [
            'status' => false,
            'code'   => self::CODE_ERROR,
            'data'  => [
                'items' => ['__EMPTY__' => '__EMPTY__'],
                'buttonTrue'    => '再等等',
                'buttonFalse'   => '放弃',
            ],
            'msg'   => '发送验证码失败'
        ];

        try{

            ValidateModel::isPhone($phone);
            $smsTypeList = $this->smsType();
            //短信类型错误
            if(!array_key_exists($type,$smsTypeList)){

                $return['msg'] = LangModel::getLang('ERROR_SMS_TYPE');
                return $return;
            }

            //增加短信验证码发送次数的验证
            CheckLimitModel::checkLimit('check_'.$type."_".$phone);

            $userInfo = UserModel::getBaseUserInfo($phone);

            //若修改手机号,但该手机号已存在,则报错
            if($type === $this->smsType){

                if(!empty($userInfo)){
                    $return['msg'] = LangModel::getLang('ERROR_PHONE_EXIST');
                    return $return;
                }
            }elseif($type === $this->smsRegisterType){
                $registerLogic = new RegisterLogic;
                $sendResult = $registerLogic->sendRegisterSms($phone);
            }else{
                if(empty($userInfo)){
                    $return['msg'] = LangModel::getLang('ERROR_USER_NOT_EXIST');
                    return $return;
                }
            }

            if($type !== $this->smsRegisterType) {
                $cacheKey = "sms{$phone}_{$type}";
                $smsCode = SmsModel::getRandCode();

                //写入缓存
                $cacheResult = Cache::put($cacheKey, $smsCode, 30);

                $msg = sprintf($smsTypeList[$type], $smsCode);

                \Log::info('发送验证码: type: '. $type . '手机号:'. $phone . ' msg:'. $msg);

                $sendResult = SmsModel::verifySms($phone, $msg);
            }

            if($sendResult['status']){
                $return['status']   = true;
                $return['code']     = self::CODE_SUCCESS;
                $return['msg']      = LangModel::getLang('PHONE_VERIFY_CODE_RETURN_NOTICE');
            }else{
                return $sendResult;
            }

            CheckLimitModel::setCount('check_'.$type."_".$phone,1);
        }catch(\Exception $e){

            $return['msg'] = $e->getMessage();

        }


        return $return;
    }

}
