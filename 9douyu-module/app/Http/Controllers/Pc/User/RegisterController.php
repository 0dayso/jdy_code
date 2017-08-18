<?php
/**
 * @desc PC端注册流程
 * create by phpstorm
 * date 16/07/21
 * Time 19:38 PM
 */

namespace App\Http\Controllers\Pc\User;

namespace App\Http\Controllers\Pc\User;

use App\Http\Controllers\App\AppController;
use App\Http\Controllers\Pc\PcController;

use App\Http\Logics\User\RegisterLogic;
use Illuminate\Http\Request;

use App\Http\Logics\User\LoginLogic;

use App\Http\Logics\User\SessionLogic;

use Illuminate\Support\Facades\Redirect;
use Session;

class RegisterController  extends PcController
{
    /**
     * PC 注册页面
     * @author linguanghui
     * Date 16/07/21 PM 19:47
     */
    public function index(){
        //判断用户是否已经登陆
        if(SessionLogic::getTokenSession()){
            return redirect('/user');
        }
        return view('pc.user.register');
    }

    /**
     * @desc 注册流程处理函数
     * @author lin.guanghui
     * Date 16/07/21 Pm 20:37
     */
    public function doRegister(Request $request){
        //判断用户是否已经登陆
        if(SessionLogic::getTokenSession()){
            return redirect('/user');
        }
        //注册信息搜集
        $data =[
            'request_source'            => $request->input('request_source'),                         // 来源
            'phone'                     => $request->input('phone'),                                  // 手机号
            'password'                  => $request->input('password'),                               // 密码
            'phone_code'                => $request->input('phone_code'),                             // 手机验证码
            'aggreement'                => $request->input('aggreement'),                             // 注册协议
            'invite_phone'              => $request->input('invite_phone'),                           // 邀请手机号
            'channel'                   => $request->input('channel',''),
        ];
        $registerLogic         = new RegisterLogic;
        $logicRegisterReturn   = $registerLogic->doRegister($data);

        $logicLoginData               = false;
        //如果创建成功-》请求token -》pc 或 wap 登陆
        if($logicRegisterReturn['status']) {
            $dataLogin = [
                'factor' => $request->input('factor'),  // 非browser 的 客户端 传入的加密 token的因子
                'username' => $data['phone'],
                'password' => $data['password']
            ];
            $LoginLogic     = new LoginLogic();
            $logicLoginData = $LoginLogic->in($dataLogin);

            // 如果浏览器访问 写入 cookie
            if ($logicLoginData['status']) {
                $LoginLogic->handleFrom($logicLoginData['data']);
            }
            //执行跳转到实名认证
          return Redirect::to('/user/setting/verify');
        }
        //self::returnJson(['registerData' => $logicRegisterReturn, 'loginData' => $logicLoginData ]);
        return Redirect::to('/register')->with('errorMsg', $logicRegisterReturn['msg']);
    }

    /**
     * @desc 发送注册手机验证码
     */
    public function sendSms(Request $request){
        $phone   = $request->input('phone');
        $captcha = $request->input('captcha','');

        $registerLogic = new RegisterLogic;
        $result  = $registerLogic->checkCaptcha($captcha);

        if($result['status']){
            $result = $registerLogic->sendRegisterSms($phone);
        }

        return self::returnJson($result);
    }


    /**
     * @param Request $request
     * @desc 测试获取手机的号的验证码
     */
    public function getTestingPhoneCode( Request $request ){

        $phone = $request->input('phone', '');

        $sign = $request->input('sign', '');

        $signKey = env('LOGIN_SIGN');

        if( md5(md5($phone).$signKey) == $sign && !empty($sign) ){

            $sessionPhone = \Cache::get('PHONE_VERIFY_NUMBER' . $phone);

            if( $sessionPhone != $phone ){

                return AppController::callError('手机号有误');

            }

            $sessionCode = \Cache::get('PHONE_VERIFY_CODE' .$phone);

            exit($sessionCode);

        }else{

            exit('非法请求');

        }

    }

}