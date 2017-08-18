@extends('pc.common.layout')

@section('header')
    <div class="wrap">
        <div class="login-header">
            <a href="/"><img src="{{assetUrlByCdn('/static/images/new/logo-login-replace.png')}}" width="144" height="80"></a><span>登录</span>
        </div>
    </div>
@endsection

@section('title', '登录')

@section('content')
<div class="t-login-bj-1" style="padding-bottom: 200px;">
    <div class="t-wrap hidden">
        <div class="t-login-left">
            @if(!empty($ad))
                @foreach( $ad as $item )
                    <a href="@if( empty($item['url']) )javascript:void(0)@else {{ $item['url'] }} @endif">
                        <img src="{{ $item['purl'] }}" width="600" height="335"/>
                    </a>
                @endforeach
            @endif
       </div>
        <div class="t-login-right">
            <h3>欢迎登录</h3>
            <form method="post" action="/login/doLogin" id="login-form">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="t-login1">
                    <span> <input type="text" id="username" name="username" value="" class="form-input login-input js_login-input" placeholder="请输入手机号" /></span>
                    <span class="icon-login phone" id="t-phone"></span>
                    <span class="btn-tips tips-msg t-login4" id="usernameTips" style="display: none;"> 请输入正确的手机号 </span>
                </div>
                <div class="t-login2">
                    <span><input type="password" class="form-input login-input js_login-input" placeholder="请输入密码"  name="password" id="password" /></span>
                    <span class="icon-login password" id="t-password-1"></span>
                    <span class="btn-tips tips-msg t-login4" id="passwordTips" style="display: none;"  >6到16位的字母及数字组合</span>
                </div>
                <div class="t-login3">
                    <input type="hidden" name="reffer" value="{{ $reffer or null }}" />
                    <input type="hidden" name="returnUrl" value="{{ $returnUrl or null }}" />
                </div>
                <div class="t-login4 login_notice_msg " id="login_notice_msg">
                    @if(Session::has('msg'))
                        {{ Session::get('msg') }}
                    @endif
                </div>
                <input type="submit" class="btn btn-blue btn-large btn-block" value="登录">
                <p class="t-login5"><a href="/forgetLoginPassword">找回密码</a> | <a href="/register"  >免费注册</a></p>
            </form>
        </div>
        <div class="clear"></div>
    </div>
</div>

@endsection

@section('footer')
<div class="login-bottom">
    <div class="wrap pr">
        <div class="login-bottom-con">
            <img src="{{assetUrlByCdn('/static/images/new/login-bottom-new.png')}}" width="1372" height="158">
        </div>
    </div>
</div>
@endsection


@section('jspage')
    <script src="{{ assetUrlByCdn('static/js/pc2/jquery.plugin.js') }} "></script>
    <script src="{{ assetUrlByCdn('static/js/pc2/common-old.js') }} "></script>
    <script type="text/javascript">
        //login
        $(".js_login-input").focus(function(){
            $(this).parent().next(".icon-login").addClass("on");
        }).blur(function(){
            $(this).parent().next(".icon-login").removeClass("on");
        });


        (function($){
            $.isPlaceholder = function(){
                var input = document.createElement('input');
                return 'placeholder' in input;
            }
            $.fn.iePlaceholder = function(){
                if ( !$.isPlaceholder()){
                    if(!$(this).is("input[type='password']")){
                        if($(this).val()=="" && $(this).attr("placeholder")!=""){
                            $(this).val($(this).attr("placeholder"));
                            $(this).focus(function(){
                                if($(this).val()==$(this).attr("placeholder")) $(this).val("");
                            }).blur(function(){
                                if($(this).val()=="") $(this).val($(this).attr("placeholder"));
                            });

                        }

                    }else{
                        var pwdVal = $(this).attr("placeholder");
                        var passwordText = '<input class="login-input form-input" type="text" value='+pwdVal+' autocomplete="off" />';
                        $(this).after(passwordText);
                        $(this).hide();
                        var thisinput = $(this);
                        $(this).siblings(".login-input").show().focus(function(){
                            $(this).hide();
                            thisinput.show().focus();
                        });
                        thisinput.blur(function(){
                            if(thisinput.val()==''){
                                thisinput.hide();
                                $(this).siblings(".login-input").show();
                            }
                        });
                    }
                }
            }

            $(document).ready(function(){
                $("input[class*=login-input]").each(function(){
                    $(this).iePlaceholder();
                });
                $("#username").focus();
            });
        })(jQuery);
    </script>

@endsection
