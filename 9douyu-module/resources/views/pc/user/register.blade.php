@extends('pc.common.layout')

@section('title', '会员注册')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <form action="/register/doRegister" method="post" id="registerForm">
        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
        <div class="t-wrap t-mt30px">
            <div class="t-register" style="height:600px;">
                <div class="t-reg-step">
                    {{--<div class="t-reg-line"></div>
                    <dl class="t-reg-step1">
                        <dt class="t-mn">1</dt>
                        <dd class="t-blue">创建账号</dd>
                    </dl>
                    <dl class="t-reg-step2">
                        <dt>2</dt>
                        <dd>验证联系方式</dd>
                    </dl>
                    <dl class="t-reg-step3">
                        <dt>3</dt>
                        <dd>注册成功</dd>
                    </dl>--}}
                    <dl class="t-reg-step1">
                        <dt class="t-mn"></dt>
                        <dd class="t-blue">创建账号</dd>
                    </dl>
                    <dl class="t-reg-step3">
                        <dt></dt>
                        <dd>有账号?<a href="/login">直接登录</a></dd>
                    </dl>
                </div>
                <div class="pr hidden">
                    <dl class="reg-dl">
                        <dt>手机号</dt>
                        <dd><input type="text" placeholder="手机号" id="phone" name="phone" value="" maxlength="11" class="form-input">
                        </dd> <!--如果是提示错误在当前class加上red-->
                        <dt>密码</dt>
                        <dd><input type="password" placeholder="6到16位的字母及数字组合" name="password" id="password" class="form-input"></dd>
                        <dt>确认密码</dt>
                        <dd><input type="password" placeholder="6到16位的字母及数字组合" name="password2" id="password2" class="form-input"></dd>
                        <dt>校验码</dt>
                        <dd>
                            <p class="pr">
                                <input type="text" id="captchaCode" placeholder="校验码" name="captcha" class="form-input">
                                <span class="t-code-img"><img id="captcha" style="right:27px;cursor: pointer;" src="/captcha/pc_register" width="90" height="40" onclick="this.src=this.src+Math.random()"></span>
                            </p>
                        </dd>
                        <dt>短信验证码</dt>
                        <dd>
                            <input type="text" placeholder="请输入验证码" name="phone_code" id="phoneCode" class="form-input" style="width: 110px;" value="">
                            <input id="code" class="btn-code" type="button" default-value="发送验证码" value="发送验证码" style="width: 100px">
                           {{-- <a id="code" class="btn-code" type="button" default-value="发送验证码" value="发送验证码" style="width: 100px">发送验证码</a>--}}
                        </dd>

                        <dt>邀请手机号（选填）</dt>
                        <dd>
                            <input type="text" class="form-input no_check" name="invite_phone" placeholder="邀请手机号">
                        </dd>

                        <dd>
                            <p class="t-reg-a"><label>
                                    <input type="checkbox" name="aggreement" id="aggreement" checked="checked" class="form-radio t-mr15px">
                                    我同意<a href="/registerAgreement" target="_blank" class="t-reg-blue">《九斗鱼个人会员注册协议》</a></label></p>

                            <p class="t-reg-a1" id="system-message">
                                @if(Session::has('errorMsg'))
                                    {{Session::get('errorMsg')}}
                                @endif
                            </p>
                            <p>
                                <input type="hidden" name="request_source" value="1" class="mr5">

                                <button type="submit" id="submitBtn" name="submitBtn" class="btn btn-blue btn-large t-w236px"> 立即注册 </button>
                            </p>
                        </dd>

                    </dl>
                    <div class="t-reg-right">
                        <img src="{{assetUrlByCdn('/static/images/new/register.png')}}" width="280" height="370">                </div>
                </div>
            </div>
        </div>
        </form>
@section('jspage')
    <script type="text/javascript" src="{{assetUrlByCdn('/static/js/pc2/register.js')}}"></script>
     <script type="text/javascript" src="{{assetUrlByCdn('/static/js/pc2/codeCheck.js')}}"></script>
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
           var pattern = /^(13\d|14[57]|15[012356789]|18\d|17[0135678])\d{8}$/;
            //$.bindSendCode({type: 'register', autoPhone: false, timeout: timeout, maxTimeout: maxTimeout});
            $("#code").click(function(){
             var  phone = $.trim($("#phone").val());
             var  captcha = $.trim($("input[name=captcha]").val());
                if(phone == ''){
                    $("#code").addClass("error").val('请输入手机号');
                    return false;
                }
                if(!phone.match(pattern)) {
                    $("#code").addClass("error").val('手机号不正确');
                    borderColor('phone',1);
                    return false;
                }
                if(captcha == ''){
                    $("#code").addClass("error").val('请输入校验码');
                    $.register.borderColor('captchaCode',1);
                    return false;
                }
            $.ajax({
                url : '/register/sendSms',
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
                        $.register.borderColor('captchaCode',2);

                    } else {

                        //$("#code").addClass("error").val(sendRes.msg);
                        $("#system-message").text(sendRes.msg);
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
@endsection

