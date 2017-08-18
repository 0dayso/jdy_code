<?php
/**
 * create by Phpstorm
 * @author linguanghui
 * Date 16/07/26  Time Am 10:29
 */

namespace App\Http\Controllers\Weixin\User;

use App\Http\Controllers\App\AppController;
use App\Http\Controllers\Weixin\WeixinController;
use App\Http\Dbs\User\InviteDb;
use App\Http\Logics\Ad\AdLogic;
use App\Http\Logics\Media\ChannelLogic;
use App\Http\Logics\RequestSourceLogic;
use App\Http\Logics\User\SmsLogic;
use Illuminate\Http\Request;
use App\Http\Logics\User\LoginLogic;

use App\Http\Logics\User\SessionLogic;
use App\Http\Logics\User\RegisterLogic;
use Illuminate\Support\Facades\Redirect;
use App\Http\Models\User\UserRegisterModel;
use App\Http\Models\Common\SmsModel as Sms;

use Session;

/**
 * 微信注册模块
 * @author linguanghui
 * Class RegisterController
 */
class RegisterController extends WeixinController{
    /**
     * wap端注册首页
     * @author linguanghui
     */
    public function index(Request $request){
        // todo key = userId=56941 记录邀请关系【目前微信服务号传此参数到该成员方法】
        // 检测是否登陆
        if(SessionLogic::getTokenSession()){
            return Redirect::to('/user')->with('msg','您已经登陆了');
        }


        //手机号
        $phone       = (string)Session::get("WAP_LOGIN_USER_PHONE");

        //推广活动(拉新)的渠道名称
        $channel     = $request->input('channel','');
        //合伙人邀请
        $inviteId    = $request->input('inviteId', RegisterLogic::getInvestIdFromPhone());
        //邀请类型
        $inviteType = $request->input('type','');


        $channel            = (string)Session::put("channel",$channel);
        $inviteId           = (int)Session::put("invite_id",$inviteId);
        $inviteType         = (int)Session::put("type",$inviteType);

        //登录注册流程分离
        /*if( empty($phone) ) {

            $channel            = (string)Session::put("channel",$channel);
            $inviteId           = (int)Session::put("invite_id",$inviteId);
            $inviteType         = (int)Session::put("type",$inviteType);

            return redirect('login');
        }*/


        $channel    = Session::get("channel");
        //合伙人邀请
        $inviteId   = Session::get('invite_id');
        //邀请类型
        $inviteType = Session::get('type');

        $inviteUserType = $inviteId?InviteDb::USER_TYPE_NORMAL:InviteDb::USER_TYPE_MEDIA;

        //默认自动发送验证码
        $sendTimes          = (int)Session::get('SEND_TIMES');
        if($sendTimes < 1){
            $registerLogic                = new RegisterLogic;
            $logicResult                  = $registerLogic->sendRegisterSms($phone);
            if($logicResult['status']){
                Session::put("SEND_CODE_TIME", time());
            }
            Session::put('SEND_TIMES', ++$sendTimes);
            Session::save();
        }

        $leftTime = Sms::getSendCodeLeftTime();

        $logic = new ChannelLogic();

        //获取广告位图片
        $adList = [];
        $adLogic = new AdLogic();
        $appBanner =$adLogic->getAppAdsByPositionId(9,1);

        if(!empty($appBanner['data']) && $appBanner['status'] == true){
            $adList = $appBanner['data'][0];
        }

        $data  = [
            'phone'         => $phone,
            'leftTime'      => $leftTime,
            'channel'       => $channel,
            'inviteId'      => $inviteId,
            'inviteType'    => $inviteType,
            'userType'      => $inviteUserType,
            'package'       => $logic->getPackage($channel),//推广包名
            'adList'        => $adList
        ];

        //return view('wap.user.register.register', $data);
        //WAP登录注册拆分后注册页面
        //return view('wap.user.register.index', $data);

        //按照app3.1.0新版注册改版
        return view('wap.user.register.new', $data);
    }

    /**
     * @desc  注册确认页面
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function registerConfirm(Request $request){
        $smsLogic = new SmsLogic();

        $data   = $request->all();
        $phone  = isset($data['phone'])?$data['phone']:"";
        $code   = isset($data['code']) ?$data['code'] :"";

        $checkStatus = $smsLogic->checkRegisterCode($phone, $code);

        if(!$checkStatus['status']){
            return redirect()->back()->withInput($request->input())->with('errorMsg', $checkStatus['msg']);
        }
        return view('wap.user.register.newdone',$data);
    }

    /**
     * @desc 注册协议
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function agreement(){
        return view('wap.user.register.agreement');
    }


    /**
     * @desc 注册流程处理
     * @author lin.guanghui
     * Date 16/07/26 AM 11:06
     * @return
     */
    public function doRegister(Request $request){
        //检测是否登陆
        if(SessionLogic::getTokenSession()){
            return redirect('/user')->with('msg', '您已经登陆了哦');
        }
        //注册信息搜集
        $data =[
            'request_source'            => RequestSourceLogic::$clientSource[2],         // 来源
            'phone'                     => $request->input('phone'),                     // 手机号
            'password'                  => $request->input('password'),                  // 密码
            'phone_code'                => $request->input('code'),                      // 手机验证码
            'aggreement'                => $request->input('aggreement'),                // 注册协议
            'invite_phone'              => $request->input('invite_phone'),              // 邀请手机号
            'channel'                   => $request->input('channel',''),                // 邀请的自媒体Id
            'invite_id'                 => $request->input('invite_id',''),              // 邀请人的用户Id
            'type'                      => $request->input('type',''),                   // 邀请类型
            'user_type'                 => $request->input('user_type',''),              // 邀请用户类型
        ];

        //如果有自己跳转的url
        $redirectUrl = $request->input('redirect_url');
        $jumpRealName = $request->input('real_name_jump','');

        if( $redirectUrl && !$jumpRealName){

            $redirectUrl .= '?phone='.$data['phone'].'&channel='.$data['channel'];

        }else{

            $redirectUrl = '/user/verify';

        }

        $registerLogic         = new RegisterLogic;

        $logicRegisterReturn   = $registerLogic->doRegister($data);

        //创建成功 WAP登陆  请求token
        if($logicRegisterReturn['status']) {
            $data   =[
                'factor'     => '',
                'username'   => $request->input('phone'),
                'password'   => $request->input('password'),
            ];
            $LoginLogic = new LoginLogic();

            $logicData  = $LoginLogic->in($data);

            // 如果浏览器访问 写入 cookie
            if ($logicData['status']) {
                $LoginLogic->handleFrom($logicData['data']);
            }
            Session::put("SEND_TIMES", null);
            Session::forget('channel');
            Session::forget('invite_id');
            Session::forget('type');
            Session::forget("WAP_LOGIN_USER_PHONE");

            //执行跳转到对应的页面
            return Redirect::to($redirectUrl);

        }

        return redirect()->back()->withInput($request->input())->with('errorMsg', $logicRegisterReturn['msg']);

        //return Redirect::to('/register')->with('errorMsg', $logicRegisterReturn['msg']);
    }

    /**
     * @desc 发送短信验证码
     * @author  lin.guanghui
     * Date 16/07/26 Time PM 13:56
     * @return Json
     */
    public function sendSms(Request $request){
        $phone = $request->input('phone');
        $captcha = $request->input('captcha','');

        $registerLogic = new RegisterLogic;
        $result  = $registerLogic->checkCaptcha($captcha);

        if($result['status']){

            $result = $registerLogic->sendRegisterSms($phone);

            if($result['status']){
                Session::put("SEND_CODE_TIME", time());
                Session::save();
            }

        }

        return self::returnJson($result);
    }
    /**
     * @desc 验证手机验证码
     * @author linguanghui
     */
    public function checkPhoneCode(Request $request){
        $phone = $request->input('phone');
        $code = $request->input('code');

        $codeResult = Sms::checkPhoneCode($code, $phone);
        return self::returnJson($codeResult);
    }

    /**
     * @param Request $request
     * 自媒体推广注册页面
     */
    public function mediaRegister(Request $request){

        $channel = $request->input('channel','');

        if(!$channel || empty($channel)){
            return redirect('register');
        }else{
            return redirect('register?channel='.$channel);
        }

    }

    /**
     * @desc 注册相关信息的ajax验证
     * @param Request $request
     */
    public function registerAjaxFormCheck(Request $request){

        $registerLogic = new RegisterLogic();
        $data = $request->all();

        $return = $registerLogic->registerFormCheck($data);

        self::returnJson($return);
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
