@extends('pc.common.layout')

@section('header')
    <div class="wrap">
        <div class="login-header">
            <a href="/"><img src="{{assetUrlByCdn('/static/images/new/logo-login-replace.png')}}" width="144" height="80"></a><span>登录</span>
        </div>
    </div>
@endsection

@section('title', '找回密码 - 输入用户名')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="t-wrap t-mt30px">
        <div class="t-account1">
            <h3 class="t-accout-title"><span></span>找回密码</h3>
            <div class="t-account-step">
                <div class="t-account-line"></div>
                <dl class="t-account-step1">
                    <dt class="t-mn">1</dt>
                    <dd class="t-blue">验证身份</dd>
                </dl>
                <dl class="t-account-step2">
                    <dt>2</dt>
                    <dd>设置密码</dd>
                </dl>
                <dl class="t-account-step3">
                    <dt>3</dt>
                    <dd>完成</dd>
                </dl>
            </div>
            <form method="post" action="/resetLoginPassword" id="findPasswordForm">
                <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                <dl class="t-accout-2">
                    <dt class="t-lh36px">手机号</dt>
                    <dd><input type="text"  name="phone" id="phone" autocomplete="off" placeholder="请输入手机号码" class="form-input t-a-1"/></dd>
                </dl>
                <dl class="t-accout-2">
                    <dt class="t-lh36px">校验码</dt>
                    <dd>
                        <input type="text" placeholder="验证码" id="captchaCode" name="captcha" class="form-input t-a-1">
                        <span class="t-code-img" style="left: 258px;"><img id="captcha" style="right:27px;cursor: pointer;" src="/captcha/pc_find_password" width="90" height="36" onclick="this.src=this.src+Math.random()"></span>
                    </dd>
                </dl>
                <dl class="t-accout-2">
                    <dt class="t-lh36px">手机验证码</dt>
                    <dd><input type="text" name="phoneCode" id="phoneCode" placeholder="请输入验证码" class="form-input t-a-1"/>
                        <span class="t-account-img"><input id="code" class="code" type="button" default-value="免费获取验证码" value="免费获取验证码"></span>
                        <!-- <span class="t-account-img">重新发送</span>
                        <span class="t-account-img t-account-img2">60s后重新发送</span>   -->
                    </dd>
                </dl>
                <div class="t-login4 login_notice_msg " id="login_notice_msg" style="text-align: center">
                    @if(Session::has('errorMsg'))
                        {{ Session::get('errorMsg') }}
                    @endif
                </div>

                <p class="tc t-pb100px"><input type="submit" class="btn btn-blue btn-large t-w236px" id="submit-next" value="下一步"/>
                </p>
            </form>
        </div>
    </div>
@endsection
@section('jspage')
    <script src="{{ assetUrlByCdn('/static/js/pc2/formCheck.js') }} "></script>
    <script type="text/javascript" src="{{assetUrlByCdn('/static/js/pc2/findPwd.js')}}"></script>
    <script type="text/javascript" src="{{assetUrlByCdn('/static/js/pc2/sendCode.js')}}"></script>
    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        (function($){
            $(document).ready(function(){
                var timeout=0, maxTimeout = 60;
                var desc    = "秒后重发";
                var pattern = /^(13\d|14[57]|15[012356789]|18\d|17[013678])\d{8}$/;
                //$.bindSendCode({type: 'register', autoPhone: false, timeout: timeout, maxTimeout: maxTimeout});
                $("#code").click(function(){
                    var  phone = $.trim($("#phone").val());
                    var  captcha = $.trim($("input[name=captcha]").val());
                    if(phone == ''){
                        $("#code").addClass("error").val('请输入手机号');
                        $("#phone").css('border-color', '#ff7200');
                        return false;
                    }
                    if(!phone.match(pattern)) {
                        $("#code").addClass("error").val('手机号不正确');
                        $("#phone").css('border-color', '#ff7200');
                        return false;
                    }else{
                        $("#code").addClass("error").val('免费获取验证码');
                        $("#phone").css('border-color', '#cccccc');
                        $("#login_notice_msg").html('').show();
                    }
                    if(captcha == ''){
                        $("#code").addClass("error").val('请输入校验码');
                        $("#captchaCode").css('border-color', '#ff7200');
                        return false;
                    }
                    $.ajax({
                        url : '/resetLoginPassword/sendSms',
                        type: 'POST',
                        dataType: 'json',
                        data: {'phone': phone,'captcha':captcha},
                        success : function(result) {
                            sendRes = result;
                            if(sendRes.captcha === false && options.captcha) {
                                $("#captcha").click();
                            }
                            if(sendRes.status) {
                                if(timeout <= 0) {
                                    timeout = maxTimeout;
                                    $("#code").addClass("disable").val(/*sendRes.msg + "," + */timeout + desc).attr("disabled", true);
                                }
                                var timer = setInterval(function() {
                                    timeout--;

                                    if(timeout > 0) {
                                        $("#code").addClass("disable").val(/*sendRes.msg + "," + */timeout + desc);
                                    } else {
                                        $("#code").removeClass("disable").val($("#code").attr("default-value")).attr("disabled", null);
                                        clearInterval(timer);
                                    }

                                }, 1000);
                                $("#captchaCode").css('border-color', '#cccccc');

                            } else {

                                //$("#code").addClass("error").val(sendRes.msg);
                                $("#login_notice_msg").html(sendRes.msg);
                                $("#captcha").click();
                                $("input[name=captcha]").val('');

                            }
                        },
                        error : function(msg) {
                            $("#code").attr("disabled", null);
                            $("#tipMsg").text("服务器端错误，请点击重新获取");
                            clearInterval(timer);
                        }
                    });
                });
            });
        })(jQuery);
    </script>
@endsection
