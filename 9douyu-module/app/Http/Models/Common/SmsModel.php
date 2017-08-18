<?php
/**
 * Created by PhpStorm.
 * User: hexing
 * Date: 16/6/4
 * Time: 下午6:37
 */

namespace App\Http\Models\Common;

use App\Http\Models\Model;

use Session;
use Config;

/**
 * 内部方法 @晓蓉 从controller copy 过来
 * Class Sms
 * @package App\Http\Models\Common
 */
class SmsModel extends Model
{
    /**
     * 获取验证码
     * @param string $base    验证码组成字符
     * @param number $length  默认长度
     * @return string
     */
    public static function getRandCode($base = '0123456789', $length = 6) {
        $shuffleBase    = str_shuffle($base);
        $code           = '';
        $min            = 0;
        $max            = $length - 1;
        for($i = 0; $i < $length; $i++) {
            $key    = rand($min, $max);
            $code  .= $shuffleBase[$key];
        }
        return $code;
    }

    /**
     * 设置短信验证码session
     * @param $code
     * @param $phone
     * @return bool
     */
    public static function setPhoneVerifyCode($code, $phone){
        \Cache::put('PHONE_VERIFY_CODE'. $phone, $code, 30);
        \Cache::put('PHONE_VERIFY_NUMBER' . $phone, $phone, 30);

        \Log::info('setPhoneVerifyCode'. $code . '#' . $phone);

        return true;
    }

    /**
     * 手机验证码正确性检测 （正确 && 不超时）
     * @param string $code
     * @param string $phone
     * @return boolean
     */
    public static function checkPhoneCode($code, $phone, $isClean = true){
        $resultArr = array(
            'status' => true,
            'msg'    => '',
        );
        \Log::info('checkPhoneCode1'. $code . '#' . $phone);

        $sessionPhone =\Cache::get('PHONE_VERIFY_NUMBER' . $phone);

        $sessionCode = \Cache::get('PHONE_VERIFY_CODE' .$phone);

        \Log::info('checkPhoneCode2'. $sessionCode . '#' . $sessionPhone);
        if(empty($code) || empty($phone) || ($code !== $sessionCode) || ($phone !== $sessionPhone)) {
            $resultArr['msg']    = "手机验证码错误";
            $resultArr['status'] = false;
        }else{
            if($isClean == true){
                \Cache::forget('PHONE_VERIFY_CODE' . $phone);
                \Cache::forget('PHONE_VERIFY_NUMBER'. $phone);
            }
        }
        return $resultArr;
    }
    /**
     * 调用短信服务
     * @param $phone
     * @param $msg
     * @return mixed
     */
    public static function verifySms($phone,$msg){
        $res = \App\Http\Models\Common\ServiceApi\SmsModel::sendVerify($phone,$msg);
        Session::put("SEND_CODE_TIME",time());
        return $res;
    }


    
    /**
     * 获取跳秒剩余时间
     * @return number
     */
    public static function getSendCodeLeftTime() {
        $sendTime       = self::getSendCodeTime();
        if(empty($sendTime)) return 0;
        $leftTime       = Config::get('phone.TIMEOUT') - (time() - $sendTime);
        return $leftTime;
    }

    /**
     * 获取上一次发送短信时间
     * @return number
     */
    public static function getSendCodeTime() {
        $sendTime = Session::get("SEND_CODE_TIME");
        return $sendTime;
    }
}