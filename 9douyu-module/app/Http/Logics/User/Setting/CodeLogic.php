<?php
/**
 * Created by PhpStorm.
 * User: hexing
 * Date: 16/6/18
 * Time: 下午3:01
 */

namespace App\Http\Logics\User\Setting;

use App\Http\Logics\Logic;

use App\Http\Models\Common\SmsModel as Sms;

use App\Lang\LangModel;

use App\Http\Models\User\UserRegisterModel;

use App\Http\Models\Common\CoreApi\UserModel as CoreApiUserModel;

use App\Http\Models\User\UserModel;


class CodeLogic extends Logic
{
    /**
     * 修改密码短信验证码发送
     * @param int $phone
     * @return array
     */
    public function sendPhoneModifySms($phone = null){
        try{
            // 验证手机号 有效性
            UserModel::validationPhone($phone);

            // 是否已经注册的用户
            $user = CoreApiUserModel::getBaseUserInfo($phone);

            if($user && $user['status'] == 200){
                return self::callError('手机已注册');
            }
            // 验证码生成
            $code    = Sms::getRandCode();
            $message = LangModel::getLang('PHONE_VERIFY_CODE_MODIFY_PHONE');
            $message = sprintf($message, $code);

            // 发送短信验证码[todo 修改手机号验证码]
            //UserRegisterModel::sendRegisterSms($phone, $message);

            // 设置短信验证码
            Sms::setPhoneVerifyCode($code, $phone);

        }catch (\Exception $e){

            $data['phone']   = $phone;
            $data['msg']     = $e->getMessage();
            $data['code']    = $e->getCode();

            Log::error(__METHOD__ . 'Error', $data);

            return self::callError($e->getMessage());
        }
        return self::callSuccess([], '发送成功');
    }


}