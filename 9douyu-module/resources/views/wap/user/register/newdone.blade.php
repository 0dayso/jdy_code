@extends('wap.common.wapBase')

@section('title', '用户注册')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ assetUrlByCdn('/static/weixin/css/novice.css') }}">
    <style>
        body{background: #f2f2f2;}
    </style>
@endsection
@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <article>
        <form action="{{ url('/register/doRegister') }}" method="post" id="registerConfirmForm">
            <section class="wap2-input-group2 mt1">
                <div class="wap2-input-box">
                    <span class="wap2-input-icon wap2-input-iconset"></span>
                    <input type="password" name="password" id="reg_password" autocomplete="off" placeholder="请设置登录密码" value="">
                </div>
            </section>
            <section class="wap2-input-text">
                <p class="blue2">填写邀请码（选填）</p>
            </section>
            <section class="wap2-input-group2">
                <div class="wap2-input-box">
                <span class="wap2-input-icon wap2-input-iconinvite"></span>
                    <input type="text" name="invite_phone" placeholder="请填写邀请人的手机号/邀请码" value="">
                </div>
            </section>
            <p class="wap2-tip wap2-tip1 error mb1" id="tipMsg">
                @if(Session::has('errorMsg'))
                    {{Session::get('errorMsg')}}
                @endif
            </p>
            <section class="wap2-btn-wrap">
                <input type="hidden" name="phone" value="{{$phone}}">
                <input type="hidden" name="code" value="{{$code}}">
                <input type="hidden" name="channel" value="{{$channel}}">
                <input type="hidden" name="invite_id" value="{{$invite_id}}">
                <input type="hidden" name="type" value="{{$type}}">
                <input type="hidden" name="user_type" value="{{$user_type}}">
                <input type="hidden" name="aggreement" value="{{$aggreement}}">
                <input type="hidden" name="request_source" value="wap" class="mr5">
                <input type="hidden" name="_token" value="{{ csrf_token() }}" />
            <input type="button" class="wap2-btn wap2-btn-blue2 submit-finished disabled" id="submit-next" value="注册完成 " data-lock="data-lock">
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
    </script>
@endsection