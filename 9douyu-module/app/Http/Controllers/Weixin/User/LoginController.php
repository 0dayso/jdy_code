<?php
/**
 * Created by PhpStorm.
 * User: hexing
 * Date: 16/4/18
 * Time: 下午3:49
 */

namespace App\Http\Controllers\Weixin\User;

use App\Http\Controllers\Weixin\WeixinController;

use App\Http\Logics\User\PasswordLogic;
use App\Http\Logics\User\RegisterLogic;
use App\Http\Logics\User\SmsLogic;
use App\Tools\ToolJump;
use Illuminate\Http\Request;

use App\Http\Logics\User\LoginLogic;

use App\Http\Logics\User\SessionLogic;

use Illuminate\Support\Facades\Redirect;

use App\Http\Models\Common\SmsModel as Sms;

use Session;
/**
 * 微信 登陆模块
 * Class LoginController
 * @package App\Http\Weixin\User
 */
class LoginController extends WeixinController
{
    /**
     * 微信 登陆/注册 检测手机号页面
     */
    public function checkPhone(){
        //已经登陆的用户
        if(SessionLogic::getTokenSession()) {
            return redirect('/user')->with('msg', '您已经登陆了哦');
        }

        return view('wap.user.login/check');
    }

    /**
     * 检测手机号
     *
     * @param Request $request
     * @return Redirect
     */
    public function doCheckPhone(Request $request )
    {
        $phone       = $request->input('username');

        $logicReturn = LoginLogic::checkPhone($phone);

        if ($logicReturn['status']) {

            Session::put("WAP_LOGIN_USER_PHONE", $phone);
            Session::save();

            if ($logicReturn['data']['status'] == LoginLogic::STATUS_SUCCESS) {
                return redirect('login/index');
            } else {
                return redirect('register');
            }
        }else{
            $msg = $logicReturn['msg'];
            return Redirect::back()->with('msg', $msg);
        }
    }

    /**
     * 登陆输入密码页面
     */
    public function index(){
        //已经登陆的用户
        if(SessionLogic::getTokenSession()) {
            return redirect('/user')->with('msg', '您已经登陆了哦');
        }

        //手机号
        $username       = (string)Session::get("WAP_LOGIN_USER_PHONE");
        if( empty($username) ) {
            return redirect('login');
        }
        // todo 错误登录次数超过最大错误登录次数，则显示验证码
        $checkLoginTimes = LoginLogic::checkLoginTimes($username);
        if(!$checkLoginTimes['status']){
            //
        }

        $data  = [
            'username' => $username,
        ];

        return view('wap.user.login/login', $data);
    }

    /**
     * @return mixed
     * @desc 登录注册流程拆分后的登录页面
     */
    public function login(){
        //已经登陆的用户
        if(SessionLogic::getTokenSession()) {
            return redirect('/user')->with('msg', '您已经登陆了哦');
        }

        return view('wap.user.login/index');
    }

    /**
     * 微信 登陆
     *
     * @param Request $request
     * @return Redirect
     */
    public function doLogin(Request $request){

        //已经登陆的用户
        if(SessionLogic::getTokenSession()) {
            return redirect('/user');
        }

        $data   =[
            'factor'     => '',
            'username'   => $request->input('username'),
            'password'   => $request->input('password'),
        ];

        //检测登陆次数限制
        $checkLoginTimes = LoginLogic::checkLoginTimes($data['username']);
        if(!$checkLoginTimes['status']){
            return redirect('/login/index')->with('msg', $checkLoginTimes['msg']);
        }

        $LoginLogic = new LoginLogic();

        $logicData  = $LoginLogic->in($data);

        if($logicData['status']){
            $LoginLogic->handleFrom($logicData['data']);
            LoginLogic::logLoginTimes($data['username'], true);//清除登陆次数

            $url = ToolJump::getLoginUrl();
            Session::forget("WAP_LOGIN_USER_PHONE");
            return redirect($url);
        }else{
            LoginLogic::logLoginTimes($data['username']); //记录登陆次数
           // return redirect('/login/index')->with('msg', $logicData['msg']);
            //拆分流程后登录失败跳转页面
            return redirect('/login')->with('msg', $logicData['msg'])->withInput();
        }
    }

    /**
     * 退出
     *
     * @param Request $request
     * @return mixed
     */
    public function out(Request $request){
        $data = LoginLogic::destroy();
        if($data['status'])
            return Redirect::back()->with('msg', '退出成功');
        else
            return Redirect::back()->with('msg', '退出失败');
    }
/*######################### 登录页面-忘记密码 ################################################*/
    /**
     * @desc 找回登录密码
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function findLoginPassword(){
        $data = [
            'leftTime' => Sms::getSendCodeLeftTime()
        ];

        return view('wap.user.login.findloginpassword',$data);
    }

    /**
     * @desc 重置登录密码
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function resetLoginPassword(Request $request){

        if($request->isMethod('post')){
            $phone = $request->input('phone');
            $code  = $request->input('code');
            $type  = $request->input('type','find_password');
            $logic  = new SmsLogic();

            $result = $logic->checkCodeByType($phone,$code,$type);

            Session::put('web_tel_pwd_'.$phone,$code);
            Session::save();

            if(!$result['status']){
                return redirect()->back()->withInput($request->input())->with('errorMsg', $result['msg']);
            }
        }elseif($request->isMethod('get')){
            $phone = $request->old('phone');
            $code  = $request->old('code');
        }
        $data = [
            'phone' => $phone,
            'code'  => $code
        ];
        return view('wap.user.login.resetloginpassword', $data);
    }

    /**
     * @desc  重新设置登录密码
     * @param Request $request
     * @return mixed
     */
    public function doResetLoginPassword(Request $request){

        $phone      = $request->input('phone','');
        $code       = $request->input('code','');
        $password   = $request->input('password','');

        $codeNum   = Session::get('web_tel_pwd_'.$phone);

        if($codeNum != $code){
            return redirect()->back()->withInput($request->input())->with('errorMsg', '您的验证码已失效');
        }

        $logic      = new PasswordLogic();
        $result     = $logic->resetPassword($phone,$password);

        if($result['status']){
            Session::forget('web_tel_pwd_'.$phone);
            return redirect()->to('login');
        }else{
            return redirect()->back()->withInput($request->input())->with('errorMsg', $result['msg']);
        }

    }

    /**
     * @desc 找回登录密码发送短信验证码
     * @param Request $request
     * @return Json
     */
    public function sendFindPasswordSms(Request $request){

        $type   = $request->input('type','find_password');
        $phone  = $request->input('phone','');
        $captcha= $request->input('captcha','');

        $registerLogic = new RegisterLogic();
        $result  = $registerLogic->checkCaptcha($captcha);

        if($result['status']){
            $sms    = new SmsLogic();
            $result = $sms->sendSms($phone,$type);

            if($result['status']){
                Session::put("SEND_CODE_TIME", time());
                Session::save();
            }
        }

        return self::returnJson($result);
    }
}
