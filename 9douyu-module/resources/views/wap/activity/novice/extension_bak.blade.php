@extends('wap.common.wapBase')

@section('title', '新手活动s10')

@section('keywords', env('META_KEYWORD'))

@section('description', env('META_DESCRIPTION'))

@section('css')

<meta name="format-detection" content="telephone=yes">

<link rel="stylesheet" type="text/css" href="{{assetUrlByCdn('/static/weixin/css/novice.css')}}">
@endsection

@section('content')
    <div class="ann2promote-form">
        <form action="/register/doRegister" method="post" id="registerForm">
            <input type="hidden" name="aggreement" value="1">
            <input type="hidden" name="channel" value="{{ $channel or null }}">
            <input type="hidden" name="redirect_url" value="/Novice/success">
            <div class="ann2promote-input-box">
                <input type="text" placeholder="请输入手机号" id="phone" name="phone" class="ann2promote-input" />
            </div>
            <div class="ann2promote-input-box">
                <input type="password" placeholder="请设置登录密码（6-16位数字,字母）" name="password" class="ann2promote-input" />
            </div>
            <div class="ann2promote-input-box">
                <input type="text" placeholder="请输入验证码" value="" name="code" class="ann2promote-input w9" />
                <input class="ann2promote-code" type="button" id="code" default-value="获取验证码" value="获取验证码" />
            </div>
            @if(Session::has('errorMsg'))
                <p class="ann2promote-tip" id="tips-error">{{ Session::get('errorMsg') }}</p>
            @endif

            <input type="hidden" name="_token" value="{{csrf_token()}}">
            <input type="submit" class="ann2promote-btn" value="注册领取288元现金红包"/>
            <div class="ann2promote-input-box ann2-txt">
                <p class=""><a href="/login" class="blue">已有帐号？</a></p>
                <p><i></i> 账户资金享有银行级别安全保障</p>
            </div>
        </form>
    </div>

    <div class="app9-con">
        <div class="app9-con-title">
            <p>九斗鱼风控获得 <big>央行企业</big> 征信牌照</p>
        </div>
        <ul class="app9-data">
            <li>
                <span><strong>1,297,909</strong> 人</span>平台注册用户
            </li>
            <li>
                <span><strong>2,522,599,900</strong> 元</span>累计出借金额
            </li>
            <li>
                <span><strong>{{ number_format(48555049) }}</strong> 元</span>帮助投资者赚取收益
            </li>
        </ul>
        <div class="app9-con-title2">
            <i></i><span>明星产品</span><i></i><small>（借款利率）</small>
        </div>
        <div class="app9-product">
            <dl>
                <dt><span>7</span>%</dt>
                <dd>九随心</dd>
            </dl>
            <dl>
                <dt><span>9</span>%</dt>
                <dd>九安心</dd>
            </dl>
            <dl>
                <dt><span>12</span>%</dt>
                <dd>九省心</dd>
            </dl>
        </div>
    </div>

    <div class="download-box">
    <div class="ann2promote-download">
        <span></span>
        <p><strong>九斗鱼app</strong></p>
        <p>心安财有余</p>
        <a href="{{$package}}" onclick="_czc.push(['_trackEvent','{{ $channel }}-新手推广页','{{ $channel }}-下载APP']);">立即下载</a>

    </div>
    </div>
    <div class="ann2promote-work download">
        <p>客服时间：09:00-18:00</p>
        <p><span>400-6686-568</span></p>
        <p><i></i><small>投资有风险，理财需谨慎</small><i></i></p>
    </div>
@endsection

@section('jsScript')
    <script src="{{assetUrlByCdn('/static/js/common.js')}}"></script>
    <script src="{{assetUrlByCdn('/static/js/codeCheck.js')}}"></script>
    <script src="{{assetUrlByCdn('/static/js/sendCode.js')}}"></script>

    <script type="text/javascript">

        (function($){
            $(document).ready(function(){
                $.bindSendCode({'url':'/register/sendSms', type: 'register', autoPhone: false, timeout: 0, maxTimeout: 60});
                //输入或者失去焦点判断

                $("form").submit(function(){
                    if($.trim($("input[name=code]").val()) == '') return false;
                });

                // 锚点
                $(".ann2promote-pro-btn a").click(function() {
                    $("#phone").focus();
                });

            })
        })(jQuery);
    </script>
@endsection

