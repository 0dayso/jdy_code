@extends('pc.common.layout')

@section('title','零钱计划买入确认')

@section('content')

<div class="web-invest-confirm">
    <div class="web-invest-confirm-title">
        <i></i>
        <span>确认买入</span>
    </div>
    <ul class="web-invest-confirm-info">
        <li class="web-invest-confirm-box1 t-curren4-8">
            <i class="web-invest-confirm-icon1"></i><span>买入金额 :</span><strong>{{ number_format($cash) }}</strong>
            <ins> 元</ins>
        </li>
        <li class="web-invest-confirm-box2 t-curren4-9">
            <i class="web-invest-confirm-icon4"></i>
            <span>借款利率 :</span><strong>{{ $rate }}%</strong>
            @if($add_rate > 0)
            <em class="t-curren4-6">＋{{ (float)$add_rate }}%</em><em class="t-curren4-7"></em>
            @endif
        </li>
        <li class="web-invest-confirm-box3">
            <i class="web-invest-confirm-icon3"></i><span>预期每日收益 :</span><strong>{{ number_format($day_interest,2) }}</strong>
            <ins>元</ins>
        </li>
    </ul>
    <div class="web-invest-confirm-funds">
        <p><span>账户余额 :</span><strong>{{ number_format($balance,2) }}</strong>元</p>

        <p><span>余额支付 :</span><strong>{{ number_format($cash,2) }}</strong>元</p>
    </div>

    <form action="/invest/current/doInvest" method="post" id="investConfirm">
        <input type="hidden" name="cash" value="{{ $cash }}" />
        <input type="hidden" name="bonus_id" value="{{ $bonus_id }}" />
        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
        <div class="web-invest-confirm-wrap">
            {{--<p><label><input type="checkbox" id="aggreement" checked="checked">我已阅读并同意 </label>
                <a onclick="window.open('/agreement/current.pdf')" style="cursor:pointer" class="blue">《零钱计划投资服务协议》</a>
            </p>--}}
            <dl class="web-invest-confirm-group clearfix">
                <dt>交易密码 :</dt>
                <dd>
                    <p>
                        <input type="password" id="trading_password" name="trading_password" placeholder="请输入交易密码" class="form-input w210px" autocomplete="off" />
                        {{--<a href="/user/tradingPassword" class="blue" target="_blank">忘记密码</a>--}}
                    </p>

                    <p class="web-tips error">
                        @if(session('msg'))
                            {{ session('msg') }}
                        @endif
                    </p>
                </dd>
                <dt>&nbsp;</dt>
                <dd>
                    <input type="submit" value="同意并确认买入" class="btn btn-red btn-large t-w236px" id="submitBtn">
                    <a class="blue" href="/project/current/detail">返回</a>
                </dd>
            </dl>
        </div>
    </form>
</div>

<!-- 左侧广告位 -->
<script src="{{assetUrlByCdn('/static/js/pc2.js')}}" type="text/javascript"></script>
<script type="text/javascript">
    (function ($) {
        $(document).ready(function () {
            $("#submitBtn").removeAttr('disabled');
            /*$(".poshytip").poshytip({showTimeout: 1});
            $("#aggreement").click(function() {
                if($(this).is(":checked")) {
                    $("#submitBtn").prop("disabled", null).removeClass("disabled");
                } else {
                    $("#submitBtn").prop("disabled", true).addClass("disabled");
                }
            });*/


            //表单


            $("#investConfirm").submit(function(){

                var password = $("#trading_password").val();
                if(password.length<1){
                    $(".error").html("请输入交易密码");
                    return false;
                }else{
                    $("#submitBtn").attr('disabled',true);
                    return true;
                }

            });
        });

    })(jQuery);

</script>

@endsection
