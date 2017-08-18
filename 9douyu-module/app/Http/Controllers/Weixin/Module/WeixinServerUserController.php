<?php
/**
 * Created by PhpStorm.
 * User: hexing
 * Date: 16/6/14
 * Time: 下午5:02
 */

namespace App\Http\Controllers\Weixin\Module;

use App\Http\Logics\ThirdApi\JyfLogic;
use App\Tools\DesUtils;
use Log;

use Wechat;

use Config;

use App\Http\Logics\Weixin\UserLogic;

use App\Http\Logics\User\SessionLogic;

use App\Http\Logics\Weixin\BindLogic;

use App\Http\Logics\RequestSourceLogic;

use Illuminate\Http\Request;

use App\Http\Logics\User\LoginLogic;

use App\Http\Logics\User\RegisterLogic;

use App\Http\Controllers\Weixin\WeixinController;

/**
 * 微信服务 用户相关
 *
 * Class WeixinServerUserController
 * @package App\Http\Controllers\Weixin
 */
class WeixinServerUserController extends WeixinController
{
    /**
     * 构造方法追加扩展
     */
    public function appendConstruct(){
        //关闭debug
        \Debugbar::disable();
    }

    /*
     * 登陆
     */
    public function login(){
        $subWeixinLoginUrl = '/wechat/loginCallback';
        return UserLogic::wechatAuthorize('snsapi_userinfo', $subWeixinLoginUrl);
    }

    /**
     * 登陆回调
     */
    public function loginCallback(){
        $userInfo = UserLogic::getUserInfo();
        UserLogic::setUserInfo($userInfo);
        UserLogic::setSession($userInfo);

        $openid   = isset($userInfo['openid']) ? $userInfo['openid'] : 0;

        return redirect('/wechat/loginBind/'. $openid);
    }

    /**
     * 注册
     */
    public function register(){
        $subWeixinRegisterUrl = '/wechat/registerCallback';
        return UserLogic::wechatAuthorize('snsapi_userinfo', $subWeixinRegisterUrl);
    }


    /**
     * 注册回调
     */
    public function registerCallback(){
        $userInfo = UserLogic::getUserInfo();
        UserLogic::setUserInfo($userInfo);
        UserLogic::setSession($userInfo);

        $openid   = isset($userInfo['openid']) ? $userInfo['openid'] : 0;

        return redirect('/wechat/registerBind/'. $openid);
    }

    /**
     * 登陆绑定
     */
    public function loginBind($openId = 0){
        //已经登陆的用户直接绑定
        $session = SessionLogic::getTokenSession();
        if($session) {
            Log::info('loginBind start');
            $logicReturn = BindLogic::bind($session['phone'], $openId, $session['id']);
            Log::info($logicReturn);
            if($logicReturn['status']){
                return $this->bindAfter('绑定成功');
            }else{
                Log::info('bind fail ' . json_encode($logicReturn));
                return $this->bindAfter('绑定失败');
            }
            Log::info('loginBind end');
        }else{
            Log::info('loginBind->loginBindView');

            return $this->loginBindView($openId);
        }
    }

    /**
     * 登陆绑定视图
     */
    protected function loginBindView($openId = 0){
        return view('wap.module.user/index', ['bindOpenid'=> $openId]);
    }

    /**
     * 登陆绑定执行
     */
    public function doLoginBind(Request $request){

        $openid          =  $request->input('openid');
        $phone           =  $request->input('username');
        Log::info('doLoginBind openid:'. $openid. 'phone:'.$phone);
        $data   =[
            'factor'     => '',
            'username'   => $phone,
            'password'   => $request->input('password'),
        ];
        $LoginLogic = new LoginLogic();
        $data       = $LoginLogic->in($data);


        Log::info('doLoginBind start');

        Log::info($data);

        // 如果浏览器访问 写入 cookie
        if($data['status']) {
            $LoginLogic->handleFrom($data['data']);

            $userId = empty($data['data']['userInfo']['id']) ? 0 : $data['data']['userInfo']['id'];
            //尝试绑定
            $logicReturn = BindLogic::bind($phone, $openid, $userId);
            Log::info($logicReturn);

            if($logicReturn['status']){
                return $this->bindAfter('绑定成功');
            }else{
                Log::info('bind fail ' . json_encode($logicReturn));
                return $this->bindAfter('绑定失败');
            }
        }else{
            Log::info('bind login fail ' . json_encode($data));
            return redirect('/wechat/loginBind/'. $openid)->with('msg', $data['msg']);
        }
    }

    /**
     * 注册绑定
     *
     * @param int $openId
     * @return string|void
     */
    public function registerBind($openId = 0){
        Log::info('registerBind->registerBindView');
        return $this->registerBindView($openId);
    }

    /**
     * 执行注册
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Laravel\Lumen\Http\Redirector
     */
    public function doRegisterBind(Request $request){
        $openid          =  $request->input('openid');
        $phone           =  $request->input('phone');
        // 注册信息【三端统一注册信息收集】
        $data   = [
            'request_source'            => $request->input('request_source'),                         // 来源
            'phone'                     => $phone,                                                    // 手机号
            'password'                  => $request->input('password'),                               // 密码
            'phone_code'                => $request->input('phone_code'),                             // 手机验证码
            'invite_phone'              => $request->input('invite_phone', ''),                       // 邀请人手机号
        ];
        //数据处理
        $registerLogic                = new RegisterLogic;
        $logicRegisterReturn          = $registerLogic->doRegister($data);

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

                $userId = empty($data['data']['userInfo']['id']) ? 0 : $data['data']['userInfo']['id'];
                //尝试绑定
                $logicReturn = BindLogic::bind($phone, $openid, $userId);
                Log::info($logicReturn);

                if($logicReturn['status']){
                    return '绑定成功啦';
                }else{
                    //todo log
                    return '绑定失败';
                }
            }
        }

        Log::info('doRegisterBind start');
        Log::info($data);

        return '登陆失败';// todo 重新渲染登陆绑定页面 并附带错误信息
    }


    /**
     * todo 注册绑定视图 testing
     *
     * @param int $openId
     * @return string
     */
    protected function registerBindView($openId = 0){

        $echo = '
            <meta name="csrf-token" content="'.csrf_token().'">

            <form method="post" action="/wechat/doRegisterBind">
                <label> 注册 </label><br />
                <input type="hidden" name="_token" value="'.csrf_token().'" />
                <input type="hidden" name="openid" value="'.$openId.'" />
                <input type="text" name="factor" value="" />：加密因子 pc wap 不填 自动生成
                <input type="text" name="request_source" value="pc" />：来源
                <input type="text" id="phone" name="phone" value="15201594667" />：手机号
                <input type="text" name="password" value="admin132" />：密码
                <button type="button" id="sendSms"> 发送短信验证码 </button>
                <input type="text" name="phone_code" value="123456" />：手机验证码

                <input type="submit" name="authorized" value="注册">
            </form>
            <script src="/js/jquery-1.12.4.min.js"></script>
            <script type="text/javascript">
                $.ajaxSetup({
                    headers: {
                        "X-CSRF-TOKEN": $(\'meta[name="csrf-token"]\').attr(\'content\')
                    }
                });


                $("body").on("click", "#sendSms", function(){
                    var phone =$("#phone").val();
                    console.log(phone);
                	$.post("/register/sendSms",{"phone" : phone}, function(data){
                        console.log(data);
                    }, "json");
              	});
            </script>
            '
        ;

        return $echo;
    }

    /**
     * 解绑
     *
     * @param int $openId
     * @return string
     */
    public function unBind($openId = 0){
        $return = BindLogic::unBind($openId);
        if($return){
            return $this->bindAfter('解绑成功');
        }
        return $this->bindAfter('解绑失败');
    }

    /**
     * 绑定后的页面
     *
     * @param string $msg
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    protected function bindAfter($msg = ''){
        return view('wap.module.user/bindAfter', ['msg' => $msg]);
    }


    /**
     * 公共回调地址
     *
     * @param string $from
     * @param string $mchId
     */
    public function commonCallback($from = '', $mchId=''){

        Log::info(__METHOD__, app('request')->all());

        switch($from){
            case 'ymf':                                         // 站外一码付
                $wechat      = app('wechat');
                $user        = $wechat->oauth->user();
                $openId      = $user->getId();

                $param['open_id'] = $openId;
                $param['mch_id']  = $mchId;
                $signKey          = JyfLogic::JY_APP_KEY;
                $param['signKey'] = DesUtils::signMd5($param, $signKey);

                $paramString      = http_build_query($param);
                header("Location:" . Config::get('ymf.url')  . JyfLogic::JY_CALLBACK_URL . '/' . $mchId . '?' . $paramString);
                exit();
                break;
            case 'jdy_login':
                $this->loginCallback();     // 站内登陆回调
                break;
        }
    }

    /**
     * 获取openid
     *
     */
    public function getOpenId(){
        $from  = app('request')->input('from');
        $mchId = app('request')->input('mchId');
        $key   = app('request')->input('key');

        Log::info(__METHOD__, app('request')->all());

        $param['from']    = $from;
        $param['mchId']    = $mchId;
        $signKey          = JyfLogic::JY_APP_KEY;
        $signKey = DesUtils::signMd5($param, $signKey);

        if($key == $signKey) {
            return JyfLogic::getOpenId($from, $mchId);
        }else {
            header('HTTP/1.1 401 Unauthorized');
            exit;
        }
    }
}