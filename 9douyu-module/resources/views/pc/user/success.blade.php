@extends('pc.common.layout')

@section('title', '设置成功')

@section('content')

    <div class="t-wrap t-mt30px">
        <div class="t-account5">
            <h3 class="t-accout-title t-mb55px"><span></span>实名认证及交易密码设置</h3>
            <div class="t-account-step">
                <div class="t-account-line"></div>
                <dl class="t-account-step1">
                    <dt>1</dt>
                    <dd>实名认证并绑卡</dd>
                </dl>
                <dl class="t-account-step2">
                    <dt>2</dt>
                    <dd>设置交易密码</dd>
                </dl>
                <dl class="t-account-step3">
                    <dt class="t-mn">3</dt>
                    <dd class="t-blue">设置成功</dd>
                </dl>
            </div>
            <div class="t-a-img t-mb55px"><p>设置成功～现在就去投资！</p></div>
            <p class="tc t-mb55px"><a href="/project/index" class="btn btn-blue btn-large t-w236px">立即出借</a></p>
        </div>
    </div>

@endsection
