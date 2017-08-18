@extends('pc.common.layout')

@section('title', '实名认证绑定银行卡')

@section('content')

    <div class="t-wrap t-mt30px">
    <div class="t-account5">
        <h3 class="t-accout-title t-mb55px"><span></span>实名认证绑定银行卡</h3>
        <div class="t-account-step">
            <div class="t-account-line"></div>
            <dl class="t-account-step1">
                <dt class="t-mn">1</dt>
                <dd class="t-blue">实名认证并绑卡</dd>
            </dl>
            <dl class="t-account-step2">
                <dt>2</dt>
                <dd>设置交易密码</dd>
            </dl>
            <dl class="t-account-step3">
                <dt>3</dt>
                <dd>设置成功</dd>
            </dl>
        </div>
        <form method="post" action="/user/setting/doVerify" id="verifyForm" class="mt30">
            <dl class="t-accout-2">
                <dt class="t-lh36px">持卡人</dt>
                <dd><input type="text" class="form-input" name="name" role-value="请输入正确的名字" placeholder="请输入持卡人姓名" value="{{ Input::old('name') }}" ></dd>
            </dl>
            <dl class="t-accout-2">
                <dt class="t-lh36px">身份证号</dt>
                <dd><input type="text" name="id_card" class="form-input" role-value="仅支持大陆身份证" placeholder="请输入身份证号码" value="{{ Input::old('id_card') }}">
                </dd>
            </dl>
            <dl class="t-accout-2">
                <dt class="t-lh36px">银行卡号</dt>
                <dd><input type="text" name="card_no" class="form-input" role-value="" placeholder="请输入银行卡号" value="{{ Input::old('card_no') }}">
                </dd>
            </dl>
            @if(Session::has('errors'))
                <p class="t-reg-a1 tc t-mt-10px" id="tipMsg">{{  Session::get('errors') }}</p>
            @endif

            <p class="tc">
                <input type="submit" class="btn btn-blue btn-large t-w236px" value="确定">
                <a href="/user" class="t-a-2">跳过</a>
            </p>
            <p class="tc t-pb100px t-mt10px">* 温馨提示：为保证您的正常使用，在快捷充值的时需要开通银联无卡支付功能，如未开通请联系银行客服开通。</p>
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
        </form>
    </div>
    <!--<span id="yourCode"></span>-->
</div>

@endsection
