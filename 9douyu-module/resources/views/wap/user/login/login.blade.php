@extends('wap.common.wapBase')

@section('title', '登录')

@section('cssStyle')
    <style>
        body{background-color: #fff;}
    </style>
@endsection

@section('content')

        <form action="/login/doLogin" method="post" id="loginForm">
            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
            <section class="wap2-input-group mt1">
                <div class="wap2-input-box">
                    <h1>
                        &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp;{{ substr($username, 0,3) }} {{ substr($username, 3,4) }} {{ substr($username, -4) }}
                    </h1>
                </div>
            </section>
            <section class="wap2-input-group mt1">
                <div class="wap2-input-box wap2-input-box3">
                    <span class="input-txt">登录密码</span>
                    <input type="hidden" name="username"  id="username" value="{{ $username }}">
                    <input type="password" name="password" id="password" placeholder="请输入登录密码" value="">
                    <input type="text"  name="" id="showPwd" placeholder="请输入登录密码（6-16位数字及字母）">
                    <input type="hidden" name="reffer" value="weixin">
{{--                    <input type="hidden" name="returnUrl" value="{{ $returnUrl }}">--}}
                    {{--<input type="hidden" name="wxId" value="{{ $wxId }}" />--}}
                    <span class="wap2-delete"></span>
                </div>
            </section>
            @if ( !empty($showCaptcha) ) 
                <section class="wap2-input-group mt1">
                    <div class="wap2-input-box">
                        <span class="wap2-input-icon wap2-input-icon4"></span>
                        <input type="text" name="code" id="code" placeholder="请输入验证码">
                        <a href="javascript:;" class="wap2-code-link" style="border:none;">
                            {{--<img id="captcha" style="right:27px;height:36px;width:100px" src="{{ App\Tools\ToolUrl::getUrl('/util/createCode') }}?t=1"  onclick="this.src=this.src.substring(0,this.src.indexOf('?')+1)+Math.random()" />--}}
                        </a>
                    </div>
                </section>
            @endif
            <p class="wap2-tip wap2-tip1 error">
                @if(Session::has('msg'))
                    {{ Session::get('msg') }}
                @endif
            </p>
            <section class="wap2-btn-wrap mb2">
                <input type="submit" class="wap2-btn wap2-btn-blue disabled" id="submit-next" data-lock="lock" value="登录">
                <input type="hidden" name="_token" value="{{csrf_token()}}">
            </section>
            {{--<p class="tr mr1"><a href="/login/forgetLoginPassword" class="blue f13">忘记密码?</a></p>--}}
        </form>
@endsection

@section('jsScript')
    @include('wap.common.js')
    <script src="{{assetUrlByCdn('/static/weixin/js/wap2/loginForms.js')}}"></script>
    <script type="text/javascript">
        (function($){
            $(document).ready(function(){
                $(".wap2-delete").click(function(){
                    $("#password").val("");
                    $(this).hide();
                })
            });
        })(jQuery);
    </script>
@endsection
