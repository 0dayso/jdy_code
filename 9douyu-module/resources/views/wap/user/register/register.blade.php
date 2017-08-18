@extends('wap.common.wapBase')

@section('title', '用户注册')

@section('keywords', "{{env('META_KEYWORD')}}")

@section('description', "{{env('META_DESCRIPTION')}}")

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ assetUrlByCdn('/static/weixin/css/wap2.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ assetUrlByCdn('/static/weixin/css/novice.css') }}">

    <style>body{background-color: #fff;}</style>
@endsection
@section('content')
    <article class="js-username">
        <form action="/register/doRegister" method="post" id="registerForm">
            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
            <div class="wap2-vcode-mobile">未收到验证码?请点击发送验证码</div>
            <section class="wap2-input-group">
                <div class="wap2-input-box wap2-input-box3 padl">
                    <span class="input-txt">短信验证码</span>
                    <input type="hidden" name="phone" id="phone"  value="{{$phone}}">
                    <input type="text"   name="code"   placeholder="请输入验证码" id="code"  maxlength="6">
                    <a href="javascript:;" class="wap2-btn-vcode" id="sendCode" default-value="收不到？点这里" alert-status="off">收不到？点这里</a>
                    <!--<a href="javascript:;"class="wap2-btn-vcode wap2-btn-disabled">59s后重发</a>-->
                </div>
                <div class="wap2-input-box wap2-input-box3 padl">
                    <span class="input-txt">设置登录密码</span>
                    <input type="password" name="password"  id="password" placeholder="6-16位字母及数字组合" value="" maxlength="16">
                    <span class="wap2-delete"></span>
                </div>
            </section>

            <section class="wap2-input-group mt1">
                <p class="wap2-inviter"><a href="javascript:;" class="js-btn-invite wap2-btn-arrowdown">填写邀请手机号</a></p>
                <div class="wap2-input-box wap2-input-box3 padl js-input-invite" style="display: none;">
                    <span class="input-txt">邀请手机号</span>
                    <input type="text" name="invite_phone"   placeholder="请输入邀请手机号" value="" maxlength="11">
                    <span class="wap2-delete"></span>
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
            <section class="wap2-btn-wrap">
                <input type="submit" id="submit-next" class="wap2-btn wap2-btn-blue disabled register-new-btn" value="下一步">
            </section>

            <p class="wap2-tip-agree">
                <label for="check">
                    <input type="checkbox" class="appearance-none checkbox"  checked="checked"  name="aggreement" id="check">
                    我同意<span onclick="location.href='{{ env('APP_URL_WAP') }}/registerAgreement'">《九斗鱼个人会员注册协议》</span>
                </label>
            </p>
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
    </script>
    <script type="text/javascript" src="{{ assetUrlByCdn('/static/js/codeCheck.js') }}"></script>
    <script type="text/javascript" src="{{ assetUrlByCdn('/static/weixin/js/wap2/sendCode.js') }}"></script>{{--
    <script type="text/javascript" src="{{ assetUrlByCdn('/static/weixin/js/wap2/loginForms.js') }}"></script>--}}
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

            })
        })(jQuery);
    </script>
@endsection