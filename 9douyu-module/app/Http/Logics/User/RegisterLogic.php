<?php

namespace App\Http\Logics\User;

use App\Http\Dbs\Notice\NoticeDb;
use App\Http\Dbs\User\InviteDb;
use App\Http\Logics\Ad\AdLogic;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Lang;
use Log;

use Event;

use App\Http\Logics\Logic;

use App\Http\Models\User\UserRegisterModel;

use App\Http\Models\User\UserInfoModel;

use App\Lang\LangModel;

use App\Http\Logics\RequestSourceLogic;

use App\Http\Models\Common\PasswordModel;

use App\Http\Models\User\UserModel;

use App\Http\Models\Common\SmsModel as Sms;

use \App\Http\Models\Common\CoreApi\UserModel as CoreApiUserModel;
use Session;

/**
 * 注册
 * Class LoginLogic
 * @package App\Http\Logics\User
 */
class RegisterLogic extends Logic
{
    /**
     * 注册
     * @param array $data
     * @return array
     */
    public function doRegister($data = []){
        try {
            $userRegisterModel         = new UserRegisterModel;

            //$data['request_source']    = RequestSourceLogic::getSource();
            //检测是否勾选了注册协议
            $userRegisterModel->validationAgreement($data['aggreement']);
            // 必填字段验证 [手机、密码、手机验证码、请求来源]
            $userRegisterModel->validationFieldEmpty($data['phone'], LangModel::getLang('USER_REGISTER_FIELD_PHONE'));
            $userRegisterModel->validationFieldEmpty($data['password'], LangModel::getLang('USER_REGISTER_FIELD_PASSWORD'));
            $userRegisterModel->validationFieldEmpty($data['phone_code'], LangModel::getLang('MODEL_USER_FIELD_PHONE_CODE'));
            $userRegisterModel->validationFieldEmpty($data['request_source'], LangModel::getLang('USER_REGISTER_FIELD_REQUEST_FROM'));

            // 来源验证
            $data['request_source'] = $userRegisterModel->validationRequestFrom($data['request_source']);

            // 验证手机号 有效性
            UserModel::validationPhone($data['phone']);

            //验证邀请手机号的有效性
            if( isset($data['invite_phone']) && $data['invite_phone'] != '' ){

                // 验证手机号 有效性
                UserModel::validationPhone($data['invite_phone'], 'ERROR_INVITE_PHONE');

                $userRegisterModel::validationInvitePhone($data['invite_phone'], $data['phone']);

                $data['user_type'] = InviteDb::USER_TYPE_NORMAL;

            }

            // 验证密码 有效性[不能使纯数字或字母, 长度、是否与交易密码相同]
            PasswordModel::validationPassword($data['password']);

            // 验证手机验证码 有效性
            if(\App::environment("production")) {
                $userRegisterModel->validationPhoneCode($data['phone'], $data['phone_code']);
            }
            // 核心接口验证[注册过的 和 锁定的]
            $userInfo                   = UserModel::getCoreApiBaseUserInfo($data['phone']);


            $userRegisterModel->validationCoreUserInfo($userInfo);

            //创建用户
            $data['coreApiData']        = $userRegisterModel->doRegister($data);

        }catch (\Exception $e){
            $data['msg']     = $e->getMessage();
            $data['code']    = $e->getCode();
            $data['userInfo']= isset($userInfo) ? $userInfo : null;

            $logData = $data;
            unset($logData['password']);
            Log::error(__METHOD__ . 'Error', $logData);

            return self::callError($e->getMessage());
        }
        //创建成功 [记录附加信息、邀请关系、活动]
        Event::fire(new \App\Events\User\RegisterSuccessEvent(['data' => $data]));

        return self::callSuccess($data, '创建用户成功');
    }

    /**
     * 注册成功返回的数据
     *
     * @param array $data
     * @return array
     */
    public static function formatAppRegister($data = []){
        $msg         =  "注册成功";
        $items2      =  '完成实名认证 即可免费领取现金';//todo 老系统 ad 中取数据
        $data = [
            'items'         => ["__EMPTY" => "__EMPTY"],

            'msg'           => $msg,
            'items2'        => $items2,
        ];
        return self::callSuccess($data);
    }

    /**
     *
     * 注册后获取新手活动广告
     */
    public static function getRegisterAfterAd($positionId = 0){
        $data =  AdLogic::getUseAbleListByPositionId($positionId);

        if(!empty($data)){
            $data = AdLogic::formatAdData( $data[0] );
        }

        return  $data;
    }

    /**
     *
     * app4.1注册后获取新手活动广告
     */
    public static function getRegisterAfterAdV41($positionId = 0){
        $data =  AdLogic::getUseAbleListByPositionId($positionId);

        if(!empty($data)){
            $data = AdLogic::formatV41AdData( $data[0] );
        }

        return  $data;
    }

    /**
     * 用户扩展详情记录
     * @param $data
     * @return array
     */
    public function createUserInfo($data){
        try {
            $userInfoModel = new UserInfoModel;
            $userInfoModel->create($data);
        }catch (\Exception $e){
            $data['msg']     = $e->getMessage();
            $data['code']    = $e->getCode();

            Log::error(__METHOD__ . 'Error', $data);
            return self::callError($e->getMessage());
        }

        return self::callSuccess([], '创建成功');
    }


    /**
     * 注册短信验证码发送
     * @param int $phone
     * @return array
     */
    public function sendRegisterSms($phone = null){

        try{

            // 验证手机号 有效性
            UserModel::validationPhone($phone);

            $userInfo  = \App\Http\Models\Common\CoreApi\UserModel::getBaseUserInfo($phone);

            if( !empty($userInfo) ){

                return self::callError('手机号已注册');

            }

            // 验证码生成
            $code    = Sms::getRandCode();
            $message = LangModel::getLang('PHONE_VERIFY_CODE_REGISTER');
            $message = sprintf($message, $code);

            Log::info('手机号'.$phone.' 注册发送短信验证码' . $message);

            //为了通过测试流程,同时方便查看验证码,我们先设置验证码,再发送,通过其他接口再获取手机的短信验证码
            // 设置短信验证码
            Sms::setPhoneVerifyCode($code, $phone);

            // 发送短信验证码
            UserRegisterModel::sendRegisterSms($phone, $message);



        }catch (\Exception $e){
            $data['phone']   = $phone;
            $data['msg']     = $e->getMessage();
            $data['code']    = $e->getCode();

            Log::error(__METHOD__ . 'Error', $data);

            return self::callError($e->getMessage());
        }
        //发送成功则记录发送次数
        UserRegisterModel::sendRegisterSmsTimes($phone);

        return self::callSuccess([], '发送成功');
    }





    /**
     * 格式化app发送验证码后需要的数据
     *
     * @param array $data
     * @return array
     */
    public static function formatAppSendRegisterSms($data = []){
        $msg         =  "验证码正在打飞机过来，这就放弃财富增值的机会了吗？";
        $buttonTrue  =  '再等等';
        $buttonFalse =  '放弃';

        $data = [
            'items'         => ["__EMPTY" => "__EMPTY"],

            'msg'           => $msg,
            'buttonTrue'    => $buttonTrue,
            'buttonFalse'   => $buttonFalse,
        ];


        return self::callSuccess($data);
    }

    /**
     * 通过传入手机号获取邀请id
     */
    public static function getInvestIdFromPhone(){
        try {
            $request      = app('request');
            $invite_phone = $request->input('invite_phone');
            // 验证手机号 有效性
            UserModel::validationPhone($invite_phone);

            $userInfo = \App\Http\Models\Common\CoreApi\UserModel::getBaseUserInfo($invite_phone);

            if(!empty($userInfo['id'])) {
                return $userInfo['id'];
            }
        }catch (\Exception $e){
            \Log::info(__METHOD__, [$e->getLine(), $e->getMessage()]);
        }
        return '';
    }

    /**
     * @desc 注册表单检测验证 register3.1.0
     * @param array $data
     * @return array
     */
    public function registerFormCheck($data = []){
        try{
            $userRegisterModel         = new UserRegisterModel;

            $userInfo  = \App\Http\Models\Common\CoreApi\UserModel::getBaseUserInfo($data['phone']);

            if( !empty($userInfo) ){

                return self::callError('手机号已注册');

            }

            //检测是否勾选了注册协议
            $userRegisterModel->validationAgreement($data['aggreement']);

            // 必填字段验证 [手机、密码、手机验证码、请求来源]
            $userRegisterModel->validationFieldEmpty($data['phone'], LangModel::getLang('USER_REGISTER_FIELD_PHONE'));
            $userRegisterModel->validationFieldEmpty($data['password'], LangModel::getLang('USER_REGISTER_FIELD_PASSWORD'));
            $userRegisterModel->validationFieldEmpty($data['phone_code'], LangModel::getLang('MODEL_USER_FIELD_PHONE_CODE'));
            // 验证手机号 有效性
            UserModel::validationPhone($data['phone']);


            //验证邀请手机号的有效性
            if( isset($data['invite_phone']) && $data['invite_phone'] != '' ) {

                // 验证手机号 有效性
                UserModel::validationPhone($data['invite_phone'], 'ERROR_INVITE_PHONE');

                $userRegisterModel::validationInvitePhone($data['invite_phone'], $data['phone']);
            }

            // 验证密码 有效性[不能使纯数字或字母, 长度、是否与交易密码相同]
            PasswordModel::validationPassword($data['password']);

        }catch (\Exception $e){

            return self::callError($e->getMessage());
        }

        return self::callSuccess();
    }

    /**
     * check图片验证码
     * @param  string $code
     * @return array
     */
    public function checkCaptcha( $code = '' ){

        $captcha = Session::get('captcha');

        Session::forget('captcha');

        if($code == '' || $captcha != $code){

            return  self::callError('校验码不正确');

        }

        return self::callSuccess();

    }

}