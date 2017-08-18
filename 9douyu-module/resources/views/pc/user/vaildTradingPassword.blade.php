<!DOCTYPE html>
<html>
<head>
    <title>Be right back.</title>
    <script src="{{ assetUrlByCdn('/static/js/jquery-1.9.1.min.js')}}"></script>
    <script src="{{ assetUrlByCdn('/js/sendCode.js') }}"></script>
    <script src="{{ assetUrlByCdn('/js/vaildData.js') }}"></script>
    <script src="{{ assetUrlByCdn('/js/tips.js') }}"></script>
</head>
<body>
<div class="container">
    <div class="content">
        <form id="formSub" action="{{ URL('/user/findTradingPassword') }}" method="post" >
            <div class="form-group">
                <label>图片验证码</label>
                <input name="captcha" type="text" value="" class="form-control" >
                <img src="{{ assetUrlByCdn('/captcha/'.time()) }}" id="captcha">
            </div>
            <div class="form-group">
                <label>身份证号码</label>
                <input name="identity_card" type="text" value="" class="form-control" >
            </div>
            <div class="form-group">
                <label>手机号</label>
                <input name="phone" type="text" value="{{ $phone }}" class="form-control" readonly>
            </div>
            <div class="form-group">
                <label>手机验证码</label>
                <input name="code" type="text" value="" class="form-control" >
                <input type="button" value="点击获取" class="main-code" id="code">
            </div>
            <div class="form-group" id="form-tips">
                @if(Session::has('errors'))
                    {{  Session::get('errors') }}
                @endif
            </div>
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <button type="button" id="buttonSub" class="btn btn-lg btn-success col-lg-12">确定</button>
        </form>
    </div>
</div>
<script>
    $('#code').click(function(){
        $.sendCodeCommon();
    });

    $('#captcha').click(function(){
        var src = '/captcha/' + new Date().getTime();
        //console.log(src);
        $(this).attr('src',src);
    });

    $('#buttonSub').click(function(){
        if(!$.checkCaptcha()) return false;
        if(!$.checkIdentityCard({{ $identity_card }})) return false;
        if(!$.checkCode()) return false;
        $('#formSub').submit();
    });
</script>
</body>
</html>
