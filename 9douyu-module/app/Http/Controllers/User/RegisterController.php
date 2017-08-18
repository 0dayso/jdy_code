<?php


namespace App\Http\Controllers\User;

use Illuminate\Http\Request;

use App\Http\Logics\User\RegisterLogic;

use App\Http\Logics\User\LoginLogic;

use App\Http\Logics\User\SessionLogic;

use App\Http\Logics\RequestSourceLogic;

use Session;
/**
 * 注册模块
 * Class RegisterController
 * @package App\Http\User
 */
class RegisterController extends UserController
{

    /**
     * 注册demo
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function doRegister(Request $request){
        // 已经登陆的用户
        if(SessionLogic::getTokenSession()) {
            return redirect('/');
        }

        // 注册信息【三端统一注册信息收集】
        $data   = [
            'request_source'            => $request->input('request_source'),                         // 来源
            'phone'                     => $request->input('phone'),                                  // 手机号
            'password'                  => $request->input('password'),                               // 密码
            'phone_code'                => $request->input('phone_code'),                             // 手机验证码
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
            }
            //执行跳转到实名认证
            redirect('/user/setting/verify');
        }
        return self::returnJson(['registerData' => $logicRegisterReturn, 'loginData' => $logicLoginData ]);
    }

    /**
     * 发送注册手机验证码
     */
    public function sendSms(Request $request){
        $phone = $request->input('phone');

        $registerLogic                = new RegisterLogic;
        $logicResult                  = $registerLogic->sendRegisterSms($phone);

        return self::returnJson($logicResult);
    }

    /**
     * todo test 注册页面
     *
     **/
    public function index(){

        return view('pc.user.register');

        /*echo '来源';
        echo 'ios：'. md5('ios')."<br/>";
        echo 'wap：'. md5('wap')."<br/>";
        echo 'pc：'. md5('pc')."<br/>";
        echo 'android：' . md5('android')."<br/>";
        echo '<br/>';
        echo '<pre> 授权登陆客户端：clients：';
        echo '<br/>';
        echo '9e304d4e8df1b74cfa009913198428ab NULL      password refresh_token NULL   NULL
bc54f4d60f1cec0f9a6cb70e13f2127a   NULL      password refresh_token NULL   NULL
c31b32364ce19ca8fcd150a417ecce58   NULL      password refresh_token NULL   NULL
ca4d8c5af3036c2f6d8f533a054457fd   NULL      password refresh_token NULL   NULL';

        echo '<br/>';
        echo '登陆后获取的数据 ：session：'.print_r(SessionLogic::getTokenSession(), true);

        echo '<br/>';

        echo '来源：'.RequestSourceLogic::getSource();
        echo '<br/>';

        echo '请求token后获取的数据 回传已获得登陆状态：token【oauth token】、tokenKey【根据factor加密的字符串】、factor【加密因子】、request_source【来源】';
        echo'
            <meta name="csrf-token" content="'.csrf_token().'">

            <form method="post" action="/register/doRegister">
                <label> 注册 </label><br />
                <input type="hidden" name="_token" value="'.csrf_token().'" />
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
        ;*/

    }

}