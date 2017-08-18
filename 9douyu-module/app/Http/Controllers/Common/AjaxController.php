<?php
/**
 * Created by PhpStorm.
 * User: caelyn
 * Date: 16/6/18
 * Time: 下午1:35
 */

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use App\Http\Models\Common\SmsModel;
use App\Lang\LangModel;
use Illuminate\Http\Request;
use Log;
use Session;

class AjaxController extends Controller{

    /**
     * 发送手机验证码
     * @param Request $request
     * @return array
     */
    public function sendCode(Request $request){
        $code = SmsModel::getRandCode();
        $phone = $request->input('phone');
        SmsModel::setPhoneVerifyCode($code,$phone);
        $msg = sprintf(LangModel::getLang('SMS_PAY_VERIFY_CODE'),$code);
        $result = SmsModel::verifySms($phone,$msg);
        Log::info(__METHOD__.'['.$phone.']:'.$msg);
        return $result;
    }

    /**
     * check手机验证码
     * @param $request
     * @return array
     */
    public function checkCode(Request $request){
        $code = $request->input('code');
        $phone = $request->input('phone');
        $res = SmsModel::checkPhoneCode($code,$phone);
        return $res;
    }

    /**
     * check图片验证码
     * @param $request
     * @return array
     */
    public function checkCaptcha(Request $request){

        $res = ['status'=>false,'msg'=>'图片验证码不正确'];

        $captcha = $request->input('captcha');

        if($captcha==Session::get('captcha')){

            Session::forget('captcha');

            $res=['status'=>true,'msg'=>'success'];

        }

        return  $res;

    }



}