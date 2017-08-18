@extends('pc.common.layout')

@section('title', '设置交易密码')

@section('content')
    <div class="t-wrap t-mt30px">
        <div class="t-account5">
            <h3 class="t-accout-title t-mb55px"><span></span>设置交易密码</h3>
            <div class="t-account-step">
                <div class="t-account-line"></div>
                <dl class="t-account-step1">
                    <dt>1</dt>
                    <dd>实名认证并绑卡</dd>
                </dl>
                <dl class="t-account-step2">
                    <dt class="t-mn">2</dt>
                    <dd class="t-blue">设置交易密码</dd>
                </dl>
                <dl class="t-account-step3">
                    <dt>3</dt>
                    <dd>设置成功</dd>
                </dl>

            </div>
            <form method="post" action="/user/setting/doTradingPassword" id="setTP-form" class="mt30">
                <dl class="t-accout-2">
                    <dt class="t-lh36px">设置交易密码</dt>
                    <dd><input type="password" name="password" autocomplete="off" placeholder="请设置6-16位数据与字母组号的密码" class="form-input" value="{{ Input::old('password') }}"></dd>
                </dl>
                <dl class="t-accout-2">
                    <dt class="t-lh36px">确认交易密码</dt>
                    <dd><input type="password" name="password2" autocomplete="off" placeholder="请设置6-16位字母及数字组合的密码" class="form-input" value="{{ Input::old('password') }}"></dd>
                </dl>

                @if(Session::has('errors'))
                    <p class="t-reg-a1 tc t-mt-10px" id="tipMsg">{{  Session::get('errors') }}</p>
                @endif
                <p class="tc t-pb100px ">
                    <input type="submit" class="btn btn-blue btn-large t-w236px" value="确定">
                    <a href="/" class="t-a-2">跳过</a>
                </p>
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
            </form>
        </div>
    </div>
@endsection
