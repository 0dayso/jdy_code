@extends('pc.common.layout')

@section('header')
    <div class="wrap">
        <div class="login-header">
            <a href="/"><img src="{{assetUrlByCdn('/static/images/new/logo-login-replace.png')}}" width="144" height="80"></a><span>登录</span>
        </div>
    </div>
@endsection

@section('title', '九斗鱼－修改手机号-成功')

@section('content')

<block name="main">
<div class="t-wrap t-mt30px">
    <div class="t-account1">
        <h3 class="t-accout-title"><span></span>找回密码</h3>
        <div class="t-account-step">
            <div class="t-account-line"></div>
            <dl class="t-account-step1">
                <dt>1</dt>
                <dd>输入用户名</dd>
            </dl>
            <dl class="t-account-step2">
                <dt>2</dt>
                <dd>验证身份</dd>
            </dl>
            <dl class="t-account-step3">
                <dt class="t-mn">3</dt>
                <dd class="t-blue">完成</dd>
            </dl>
        </div>
        <div class="t-a-img"><p>恭喜您！密码设置成功～</p></div>
        <p class="t-a-4">3s后自动返回 账户设置</p>
    </div>
</div>
</block>

@endsection

@section('jspage')
    <script type="text/javascript">
        window.setTimeout(function(){
            window.location = '/login';
        }, 3000);
    </script>
@endsection
