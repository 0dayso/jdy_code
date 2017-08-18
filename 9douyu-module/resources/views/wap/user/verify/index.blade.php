@extends('wap.common.wapBase')
@section('title', '身份证认证')

@section('keywords', env('META_KEYWORD'))

@section('description', env('META_DESCRIPTION'))

@section('content')
    <article>
        <!--<if condition="$isPartner eq 'partner'">-->
        <!--<div class="wap2-verify-success"><p>注册成功<br>完成实名认证即可免费领取100元</p></div>-->
        <!--</if>-->
        <form action="/user/doVerify" method="POST" id="identity-verify">
            <section class="wap2-input-group mt1">

                <!-- <div class="wap2-verify-img">
                    <if condition="$isPartner eq 'partner'">
                        <img src="__PUBLIC2__/weixin/images/wap2/x-verify-img_100.png">
                    <else/>
                        <img src="__PUBLIC2__/weixin/images/wap2/x-verify-img_100.png">
                    </if>
                </div> -->
                <div class="wap2-input-box bbd3">
                    <span class="wap2-input-icon wap2-input-icon1"></span>
                    <input type="text" name="name" id="real_name" class='require' placeholder="您的真实姓名" value="{{ Input::old('name') }}">
                </div>
                <div class="wap2-input-box bbd3">
                    <span class="wap2-input-icon wap2-input-icon2"></span>
                    <input type="text" name="id_card" class="require wapForm-check-identityCard" placeholder="您的身份证号" value="{{ Input::old('id_card') }}">
                </div>
                <div class="wap2-input-box">
                    <span class="wap2-input-icon wap2-input-icon8"></span>
                    <input type="text" name="card_no" class="require wapForm-check-identityCard" placeholder="您的银行卡号" value="{{ Input::old('card_no') }}">
                </div>
                <!--<div class="wap2-input-box">-->
                <!--<span class="wap2-input-icon wap2-input-icon3"></span>-->
                <!--<input type="password" name="tradepassword" class="require wapForm-check-checkPassword" placeholder="设置交易密码 6-16位的字母及数字组合">-->
                <!--</div>-->
            </section>
            <section class="wap2-tip error">
                <p id="error_tip">@if(Session::has('errors')) {{ Session::get('errors') }} @endif</p>
            </section>
            <section class="wap2-btn-wrap">
                <input type="submit" class="wap2-btn wap2-btn-blue2" value="完成">

                <input type="hidden" name="_token" value="{{csrf_token()}}">

            </section>
                <div class="wap2-verify-txt">
                    <p>温馨提示：为保证您的正常使用，在快捷充值的时需要开通银联无卡支付功能，如未开通请联系银行客服开通。</p>
                </div>
        </form>
    </article>


@endsection
<!--<script type="text/javascript">-->
<!--(function($){-->

<!--$(document).ready(function(){-->
<!--$(".wap2-btn").click(function(){-->
<!--$(".wap2-pop-wrap").attr();-->
<!--});-->

<!--});-->
<!--})(jQuery);-->

<!--</script>-->
