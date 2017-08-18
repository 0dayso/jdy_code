<?php
/**
 * Created by PhpStorm.
 * User: hexing
 * Date: 16/4/18
 * Time: 下午3:49
 */

namespace App\Http\Controllers\Pc\User;

use App\Http\Controllers\Pc\PcController;

use App\Http\Logics\Ad\AdLogic;
use App\Http\Logics\User\RegisterLogic;
use App\Tools\ToolJump;
use Illuminate\Http\Request;
use App\Http\Logics\User\UserInfoLogic;

use App\Http\Logics\User\LoginLogic;
use App\Http\Logics\User\PasswordLogic;
use App\Http\Logics\User\SessionLogic;
use App\Http\Logics\User\SmsLogic;

use Illuminate\Support\Facades\Redirect;
use App\Http\Models\Common\SmsModel as Sms;
use Session;

/**
 * pc登陆模块
 * Class LoginController
 * @package App\Http\User
 */
class LoginController extends PcController
{
    /**
     * pc 登陆页面
     */
    public function index(){
        //已经登陆的用户
        if(SessionLogic::getTokenSession()) {
            return redirect('/user');
        }

        //登录页面的广告

        $data['ad'] = AdLogic::getUseAbleListByPositionId(15);

        return view('pc.user/login', $data);
    }

    /**
     * pc 登陆
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

        $LoginLogic = new LoginLogic();
        $data       = $LoginLogic->in($data);
        // 如果浏览器访问 写入 cookie
        if($data['status']) {
            $LoginLogic->handleFrom($data['data']);

            //跳转到个人中心 或者 是 设置过 登陆成功跳转页面
            $url = ToolJump::getLoginUrl();
            //检测用户是否做了风险评估
            $userInfoLogic  =   new UserInfoLogic();
            $userAssessment =   $userInfoLogic -> getAssessmentType($data['data']['userInfo']['id']);
            if( empty($userAssessment) || is_null($userAssessment) ) {
                $url    =   '/user';
            }

            return redirect($url);

        }
        return redirect('/login')->with('msg', $data['msg']);

    }

    /**
     * 退出
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Laravel\Lumen\Http\Redirector
     */
    public function out(Request $request){
        $data = LoginLogic::destroy();
        if($data['status'])
            return Redirect::back()->with('msg', '退出成功');
        else
            return Redirect::back()->with('msg', '退出失败');
    }

   /*#######################忘记密码#############################*/

    /**
     * @desc 忘记密码页面
     * @return view
     **/
    public function forgetLoginPassword(){

        return view('pc.user.forgetpassword');
    }

    /**
     * @desc 设置密码发送验证码
     * @return view
     **/
    public function resetLoginPassword(Request $request){

        if($request->isMethod('post')){
            $phone = $request->input('phone');
            $code  = $request->input('phoneCode');
            $type  = $request->input('type','find_password');
            $logic  = new SmsLogic();

            $result = $logic->checkCodeByType($phone,$code,$type);

            Session::put('pc_tel_pwd_'.$phone,$code);
            Session::save();

            if(!$result['status']){
                return redirect()->back()->withInput($request->input())->with('errorMsg', $result['msg']);
            }
        }elseif($request->isMethod('get')){
            $phone = $request->old('phone');
            $code  = $request->old('code');;
        }
        $data = [
            'phone' => $phone,
            'code'  => $code
        ];
        return view('pc.user.resetloginpassword',$data);
    }

    /**
     * @desc 验证设置交易密码的处理
     * @param Request $request
     **/
    public function doResetLoginPassword(Request $request){

        $phone     = $request->input('phone','');
        $code      = $request->input('code','');
        $password  = $request->input('password','');
        $password2 = $request->input('password2','');

        $codeNum   = Session::get('pc_tel_pwd_'.$phone);

        if($codeNum != $code){
            return redirect()->back()->withInput($request->input())->with('errorMsg', '您的验证码已失效');
        }

        if($password !== $password2){
            return redirect()->back()->withInput($request->input())->with('errorMsg', '确认密码与新密码不一致');
        }

        $logic      = new PasswordLogic();
        $result     = $logic->resetPassword($phone,$password);


        if($result['status']){
            Session::forget('pc_tel_pwd_'.$phone);
            return redirect()->to('forgetPasswordSetSuccess');
        }else{
            return redirect()->back()->withInput($request->input())->with('errorMsg', $result['msg']);
        }

    }

    /**
     * @desc 找回登录密码发送短信验证码
     * @param Request $request
     * @return Json
     */
    public function sendSms(Request $request){

        $type   = $request->input('type','find_password');
        $phone  = $request->input('phone','');
        $captcha = $request->input('captcha','');

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
    /**
     * @desc 设置找回登录密码成功页面
     **/
    public function forgetPasswordSetSuccess(){

        return view('pc.user.forgetpasswordsetsuccess');
    }
}
