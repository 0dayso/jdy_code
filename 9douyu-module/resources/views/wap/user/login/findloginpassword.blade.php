@extends('wap.common.wapBase')

@section('title', '找回登录密码')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ assetUrlByCdn('/static/weixin/css/novice.css') }}">
    <style>
        body{background: #f2f2f2;}
    </style>
@endsection
@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <article>
        <form action="{{ url('/resetLoginPassword') }}" method="post" id="registerForm">
            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
            <section class="wap2-input-group2 mt1">
                <div class="wap2-input-box">
                    <span class="wap2-input-icon wap2-input-iconphone"></span>
                    <input type="text" name="phone" id="phone" autocomplete="off" placeholder="请输入手机号码" value="{{ old('phone') }}">
                </div>
            </section>
            <section class="wap2-input-group2">
                <div class="wap2-input-box">
                    <span class="wap2-input-icon wap2-input-iconcode"></span>
                    <input type="text" name="captcha" placeholder="请输入校验码" value="">
                    <img  class="wap2-btn-code2" id="captcha" src="/captcha/wx_find_password" width="68" height="36" onclick="this.src=this.src+Math.random()">
                </div>
            </section>
            <section class="wap2-input-group2">
                <div class="wap2-input-box">
                    <span class="wap2-input-icon wap2-input-iconcode"></span>
                    <input type="text" name="code" id="code" placeholder="请输入验证码" value="">
                    {{--<a href="javascript:;" class="wap2-btn-code2" id="sendCode" default-value="获取验证码" alert-status="off">获取验证码</a>--}}
                    <input type="button" class="wap2-btn-code2" id="sendCode" default-value="获取验证码" alert-status="off" value="获取验证码" >

                </div>
            </section>
            <p class="wap2-tip wap2-tip1 error" id="tipMsg">
                @if(Session::has('errorMsg'))
                    {{Session::get('errorMsg')}}
                @endif
            </p>
            <input type="hidden" name="request_source" value="wap" class="mr5">
            <section class="wap2-btn-wrap">
                <input type="submit" class="wap2-btn wap2-btn-blue2 disabled" id="submit-next" value="下一步">
            </section>
        </form>
    </article>
@endsection
@section('jsScript')
    <script type="text/javascript" src="{{ assetUrlByCdn('/static/js/codeCheck.js') }}"></script>
    <script type="text/javascript" src="{{ assetUrlByCdn('/static/weixin/js/wap2/sendCode.js') }}"></script>
    <script type="text/javascript" src="{{ assetUrlByCdn('/static/weixin/js/wap2/loginForms.js') }} "></script>
    <script type="text/javascript" src="{{ assetUrlByCdn('/static/rsa/BigInt.js') }}"></script>
    <script type="text/javascript" src="{{ assetUrlByCdn('/static/rsa/Barrett.js') }}"></script>
    <script type="text/javascript" src="{{ assetUrlByCdn('/static/rsa/RSA_Stripped.js') }}"></script>
    <script type="text/javascript" src="{{ assetUrlByCdn('/static/rsa/jquery.cookie.js') }}"></script>
    <script type="text/javascript" src="{{ assetUrlByCdn('/static/rsa/jquery.base64.js') }}"></script>
    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        (function($){
            $(document).ready(function(){
                //var timeout={:getSendCodeLeftTime()}, maxTimeout = 10;
                var timeout={{$leftTime}}, maxTimeout = {{env('PHONE_CONFIG.TIMEOUT')}};
                var phone  = $("#phone").val();
                var rsa_n= "B5591797A3FDBD931AD5CBD35BED1750CD0E19467F715486B90F047FC009D6E9E8B6DA9A49EE55C9554659CA1A1598799B17DBB56DB2E03336687A8B061B57527F5769FA16F5F42BA111DB798F8DA0BC272C16EE76B82F31189E58B8720DD493854177CE88DB9742999160AE4B597A0F6E0A8BDF18242B247832D86576A148D80DED77CF67D399217C934BB6C31453D021355FABF07F13EFAA0D39B4C9B645BD4269934ACC9F20EA079EEF31597BD71F224C41A8AE911FA195900549B48C75366E4A0BEB3852894C53314A7239B01E106F6BD1A6D098FD1F1369E69530ACFCCDCEC81BEA9BFA62A16CEBB6F699B577E8AD5E660AFEF645C3E6A4B1F9CDE0B4F5";
                setMaxDigits(262);
                var key   = new RSAKeyPair("10001",'10001',rsa_n,2048);
                var token = $("#registerForm").find("input[name=__token__]").val();
                var sid   =  $.cookie('sid');
                var encrypt = token+sid;
                encrypt = encryptedString(key,encrypt,RSAAPP.PKCS1Padding,RSAAPP.RawEncoding);
                encrypt = $.base64.btoa(encrypt);
                $("#registerForm").append("<input type='hidden' name='encrypt' id='encrypt' value='"+encrypt+"'/>");

                $.bindSendCode({'url':'/sendFindPasswordSms', type: 'find_password', autoPhone: false, timeout: timeout, maxTimeout: maxTimeout,phone:phone});




                //输入或者失去焦点判断
                $("input[name=code],input[name=phone]").on({
                    keyup: function(){
                        if($.trim($("#code").val()) != '' && $.trim($("#phone").val()) != '') {
                            $("#submit-next").removeClass("disabled");
                        }else{
                            $("#submit-next").addClass("disabled");
                        }
                    },
                    blur: function() {
                        $(this).keyup();
                    }
                });
                $(".wap2-delete").click(function(){
                    $("#password").val("");
                    $(this).hide();
                })

                $("form").submit(function(){
                    if($.trim($("input[name=code]").val()) == '') return false;
                });

                //点击查看密码
                $(".wap2-eye").click(function(){
                    var rel= $.trim($(".wap2-eye").attr("class"));
                    // alert(rel);
                    if(rel=='wap2-eye'){
                        $(".wap2-eye").addClass('open');
                        $("#password").hide();
                        $("#showPwd").show();

                    }else{
                        $(".wap2-eye").removeClass('open');
                        $(".wap2-eye").attr('type','password');
                        $("#password").show();
                        $("#showPwd").hide();
                    }
                });
            })
        })(jQuery);
    </script>
 @endsection
