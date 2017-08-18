@extends('wap.common.wapBase')

@section('title', '用户注册')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ assetUrlByCdn('/static/weixin/css/wap2.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ assetUrlByCdn('/static/weixin/css/novice.css') }}">
    <style>
        body{background-color: #fff;}
    </style>
@endsection
@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <article class="js-username">
        <form action="{{ url('/register/doRegister') }}" method="post" id="registerForm">
            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
            <section class="wap2-input-group w-q-mt">
                <div class="wap2-input-box bbd3">
                    <span class="wap2-input-icon wap2-input-icon6"></span>
                    <input type="text" name="phone" id="phone" placeholder="请填写真实有效的手机号码" value="{{ old('phone') }}">
                    <a href="javascript:;" class="wap2-btn-vcode" id="sendCode" style ="right:0.5rem;" default-value="发验证码" alert-status="off">发验证码</a>
                    {{--<input id="sendCode"  class="wap2-code-btn again"  type="button" default-value="发验证码" value="发验证码" />--}}
                    <!--<a href="javascript:;" class="partner2-arrive-code" id="code" default-value="获取验证码">获取验证码</a>-->
                    <!--<input type="button" class="wap2-code-btn button again" value="获取验证码">-->
                    <!-- <input type="button" class="wap2-code-btn button " value="60s后重新发送"> -->
                </div>
                <div class="wap2-input-box">
                    <span class="wap2-input-icon wap2-input-icon4"></span>
                    <input type="text" name="code" id="code" value="" placeholder="请输入验证码">
                </div>
            </section>
            <section class="wap2-input-group mt1">
                <div class="wap2-input-box pwdTip">
                    <span class="wap2-input-icon wap2-input-icon5"></span>
                    <input type="password" name="password" id="password" placeholder="请设置登录密码（6-16位数字及字母）">
                    <input type="text" name="" id="showPwd" placeholder="请设置登录密码（6-16位数字及字母）">

                    {{--<span class="wap2-eye" ></span>--}}
                </div>
            </section>

            <section class="wap2-input-group mt1">
                <div class="wap2-input-box pwdTip">
                    <span class="wap2-input-icon wap2-input-icon5"></span>
                    <input type="text" class="form-input no_check" name="invite_phone"  placeholder="邀请手机号（选填）" />
                </div>
            </section>
            <p class="wap2-tip wap2-tip1 error" id="tipMsg">
                @if(Session::has('errorMsg'))
                    {{Session::get('errorMsg')}}
                @endif
            </p>
            <input type="hidden" name="channel" value="{{$channel}}">
            <input type="hidden" name="invite_id" value="{{$inviteId}}">
            <input type="hidden" name="type" value="{{$inviteType}}">
            <input type="hidden" name="user_type" value="{{$userType}}">
            <input type="hidden" name="request_source" value="wap" class="mr5">
            <section class="wap2-btn-wrap mb2">
                <p class="wap2-tip-agree">
                    <label for="check">
                        <input type="checkbox" class="appearance-none checkbox"  checked="checked"  name="aggreement" id="check">
                        我同意<span onclick="location.href='{{ env('APP_URL_WAP') }}/registerAgreement'">《九斗鱼个人会员注册协议》</span>
                    </label>
                </p>
                <input type="submit" class="wap2-btn disabled" id="submit-next" data-lock="lock" value="注 册">
            </section>
            <section class="wap2-btn-wrap pb4">
                <a href="{{ url('/login') }}">
                    <input type="button" class="wap2-btn wap2-btn-2" value="已有账号,直接登录">
                </a>
            </section>
        </form>

        @if($package)
            <div class="download-box">
                <div class="ann2promote-download">
                    <span></span>
                    <p><strong>九斗鱼app</strong></p>
                    <p>心安财有余</p>
                    <a href="{{$package}}">立即下载</a>
                </div>
            </div>
        @endif

        @include('wap.user.register.codeItem')
    </article>
@endsection
@section('jsScript')
    <script>
        //邀请人
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $(".js-btn-invite").on("click touched",function(){
            var $this = $(this);
            if(!($this.hasClass("wap2-btn-arrowup"))){
                $this.addClass("wap2-btn-arrowup");
                $(".js-input-invite").show();
            }else{
                $this.removeClass("wap2-btn-arrowup");
                $(".js-input-invite").hide();
            }
        });
        var wh = window.innerHeight;
        $(window).resize(function() {
            var  rwh = window.innerHeight;//浏览器内部高度
            if(rwh < wh){
                $(".download-box").css({"bottom":-70+"px"});
            }else{
                $(".download-box").css({"bottom":0+"px"});
            }
        });
    </script>
    <script type="text/javascript" src="{{ assetUrlByCdn('/static/js/codeCheck.js') }}"></script>
    <script type="text/javascript" src="{{ assetUrlByCdn('/static/weixin/js/wap2/sendCode.js') }}"></script>
    <script type="text/javascript" src="{{ assetUrlByCdn('/static/weixin/js/wap2/loginForms.js') }} "></script>
    <script type="text/javascript" src="{{ assetUrlByCdn('/static/rsa/BigInt.js') }}"></script>
    <script type="text/javascript" src="{{ assetUrlByCdn('/static/rsa/Barrett.js') }}"></script>
    <script type="text/javascript" src="{{ assetUrlByCdn('/static/rsa/RSA_Stripped.js') }}"></script>
    <script type="text/javascript" src="{{ assetUrlByCdn('/static/rsa/jquery.cookie.js') }}"></script>
    <script type="text/javascript" src="{{ assetUrlByCdn('/static/rsa/jquery.base64.js') }}"></script>
    <script type="text/javascript">

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

                $.bindSendCode({'url':'/register/sendSms', type: 'activate', autoPhone: false, timeout: timeout, maxTimeout: maxTimeout,phone:phone});




                //输入或者失去焦点判断
                $("input[name=code]").on({
                    keyup: function(){
                        if(!$.trim($(this).val()) == '') {
                            $(".register-new-btn").removeClass("disabled");
                        } else {
                            $(".register-new-btn").addClass("disabled");
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