@extends('wap.common.wapBase')

@section('title', '九斗鱼')

@section('keywords', env('META_KEYWORD'))

@section('description', env('META_DESCRIPTION'))

@section('content')
<article>
    <section class="w-bc hidden">
        <p class="center mt25px"><span class="gray-title-bj1 font15px plr15px">付款给九斗鱼</span></p>
        <p class="center w-fff-color mt15px"><span class="font30px">{{ $cash }}</span><span>元</span></p>
    </section>
    <section class="wap2-input-group lh2rem">
        <p><span>银行卡信息</span></p>
        <p class="w-bule-color"><span class="font15px">{{ substr($cardNo, 0, 4) . "****" . substr($cardNo, -4) }}</span></p>
    </section>
    <section class="mt15px mb1">
        <div class="ml1" id="balance_child_tab">
            <a class="blue-title-bj w-37" page="1" type="1">身份信息</a>
        </div>
    </section>
    <form action="{{ URL('/pay/sumaSubmit') }}" method="post" id="rechargeSub">
        <section class="wap2-input-group" id="balance_child">
            <div class="wap2-input-box2 bbd3">
                <p class="fr"><span class="color8c">{{ substr($realName, 0, 3) . "****" . substr($realName, -3) }}</span></p>
                <p><span class="wap2-icon wap2-icon-2"></span>姓名</p>
            </div>
            <div class="wap2-input-box2 bbd3">
                <p class="fr"><span class="color8c">（{{ substr($identityCard, 0, 4) . "****" . substr($identityCard, -4) }}）</span></p>
                <p><span class="wap2-icon wap2-icon-2"></span>身份证</p>
            </div>

            <div class="wap2-input-box2 bbd3">
                <p class="fr"><span class="mr2 blue">
                    <input type="tel" placeholder="{{ $phone }}" name="phone" id="phone" tips="error_tip" value="{{ $phone }}">
                        </span></p>
                <p><span class="wap2-icon wap2-icon-7"></span>手机号码</p>
            </div>
            <div class="wap2-input-box2">

                <div class="wap2-input-box">
                    <span class="wap2-input-icon wap2-input-icon4"></span>
                    <input type="text" placeholder="请输入验证码" name="code">
                    <input type="button" value="免费获取验证码"  class="wap2-code-link again" style="width:50%;height:50%;" default-value="免费获取验证码" id="code" class="code"/>
                </div>
                <div style="display:none"><input id="agree" checked="checked"  type="checkbox" value="1"></div>

                <input type="hidden"  name="from"     value="app">
                <input type="hidden"  name="cash"     value="{{ $cash }}"     />
                <input type="hidden"  name="order_id" value="{{ $orderId }}"  />
                <input type="hidden"  name="name"     value="{{ $realName }}" />
                <input type="hidden"  name="card_no"  value="{{ $cardNo }}"   />
                <input type="hidden"  name="id_card"  value="{{ $identityCard }}" />
                <input type="hidden"  name="_token"   value="{{ csrf_token() }}"  />
                <input type="hidden"  name="bank_id"  value="{{ $bankId }}">
                <input type="hidden"  name="randomValidateId" id="randomValidateId" >
                <input type="hidden"  name="tradeId"          id="tradeId"          >
                <input type="hidden"  name="isSend"           id="isSend"    value="no">
            </div>

        </section>
        <section class="wap2-tip error">
            <p id="form-tips">@if(Session::has('errors')) {{ Session::get('errors') }} @endif</p>
        </section>
        <section class="wap2-btn-wrap mb2">
            <input type="button" id="paySub" class="wap2-btn" value="确认">
        </section>
    </form>
</article>
@endsection

@section('jsScript')
<script src="{{ assetUrlByCdn('/static/js/jquery.cookie.js') }}"></script>
<script src="{{ assetUrlByCdn('/static/js/recharge.js') }}"></script>
<script>
    $.sendCode('suma');
    $.paySub('suma');
</script>
  
@endsection
