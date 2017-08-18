@extends('wap.common.wapBase')

@section('title', '填写手机号')

@section('keywords', env('META_KEYWORD'))

@section('description', env('META_DESCRIPTION'))

@section('css')
    <meta name="format-detection" content="telephone=yes">
    <link rel="stylesheet" type="text/css" href="{{ assetUrlByCdn('/static/weixin/css/familyAccount.css') }}">
@endsection

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="family-tel">{{ $familyRole }}</div>
    <form name="form1" id="form1" method="post" action="/family/doPostPhone">
    <section class="family-tel1">
        <div class="family-tel1-1 bbd3">
            <input type="text" name="phone" id="phone" tips="m-tips" placeholder="请输入对方手机号" value="{{ old('phone') }}">
        </div>
        <div class="family-tel1-1">
            <input type="text" name="code" placeholder="请输入验证码" value="" style="width: 50%">
            <input class="family-tel1-code" type="button" id="code" default-value="获取验证码" value="获取验证码" disabled unselectable="on">
        </div>
    </section>
    <section class="wap2-tip error m-tips f-c text-error" id="m-tips" style=" width: auto; display:block">@if(Session::has('error')) {{ Session::get('error') }} @endif</section>
    <section class="family-tel2">
        <input type="hidden" name="familyRole" id="familyRole" value="{{ $familyRole }}">
        <input type="button"  value="下一步" class="family-btn">
        <p class="js-after-send-code family-tel2-1 hide">验证码已发送至对方手机号，打电话通知对方：
            <a class="js-call-family family-call" style="color:#3cb8ff;"></a>（点击号码拨号）
        </p>
        <p class="family-contact family-tel2-1">联系客服：
            <a class="call-customer" style="color:#3cb8ff;" 
            @if($client=='ios')
                href="tel:4006686568"
            @else 
                onclick="window.jiudouyu.gotoCall('4006686568')"
            @endif
            >400-6686-568</a>
        </p>
    </section>
        <input type="hidden" name="_token" value="{{csrf_token()}}">
        <input type="hidden" name="familyRole" value="{{ $familyRole }}">
    </form>
@endsection

@section('jsScript')
    <script src="{{ assetUrlByCdn('/static/js/pc2/codeCheck.js') }}"></script>
    <script src="{{ assetUrlByCdn('/static/js/pc2/sendCode.js') }}"></script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        (function($){
            var ishttps = 'https:' == document.location.protocol ? true: false;
            var webUrl = "{{ env('APP_URL_WX') }}/family/sendCode";
            if(ishttps){
                webUrl = "{{ env('APP_URL_WX') }}/family/sendCode";
            }
            //发送验证码
            var timeout={{ $leftTime }}, maxTimeout = {{ Config::get('phone.TIMEOUT') }};
            $.bindSendCode({type: 'family_account', autoPhone: true, timeout: timeout, maxTimeout: maxTimeout, url: webUrl });

            var client = '{{ $client }}';
            var customer = $(".call-customer").text().replace(/-/g,'');

            if(client == 'ios'){
                $(".call-customer").attr('href','tel:'+customer);
            }else{
                $(".call-customer").attr('onclick',"window.jiudouyu.gotoCall('"+customer+"')");
            }

            $('.family-btn').click(function(){
                if(!$.phone_check($("#phone"))){
                    return false;
                }
                if($.trim($("input[name=code]").val())==''){
                    $('#m-tips').text('请输入验证码');
                    return false;
                }
                $.ajax({
                    url : '/family/checkPhoneVerify',
                    type: 'POST',
                    dataType: 'json',
                    data: {'code': $("input[name=code]").val(),'phone':$("#phone").val()},
                    success : function(data) {
                        if(data.status===false) {
                            $('#m-tips').text(data.msg);
                            return false;
                        } else {
                            $("#form1").submit();
                        }
                    }
                });
            });
        
            $("#code").click(function(){
                var phone = $("#phone").val().replace(/\D/g, '');
                if(phone.length != 11) {
                    return true;
                }
                $(".family-call").text(phone);
                if(client == 'ios'){
                    $(".family-call").attr('href','tel:'+phone);
                }else{
                    $(".family-call").attr('onclick',"window.jiudouyu.gotoCall('"+phone+"')");
                }
                $(".js-after-send-code").removeClass('hide');
                $(".family-contact").removeClass('family-tel2-1');
            });

        })(jQuery);

    </script>
@endsection

