
@extends('pc.common.layout')

@section('title','零钱计划买入成功')

@section('content')

<!-- project begins -->
<div class="t-wrap t-mt30px">
    <div class="t-invest">
        <div class="t-invest-left">
            <div class="t-invest-1">
                <p>恭喜您，买入成功！</p>
            </div>

            <div  class="t-invest-4">
                <table class="t-invest-2">
                    <thead>
                    <tr>
                        <td>买入金额</td>
                        <td>借款利率</td>
                        <td>每日收益</td>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>{{ number_format($cash) }}</td>
                        <td>{{ $rate }}% @if($add_rate > 0)<span class="t-curren4-4">+{{ (float)$add_rate }}%</span><i class="t-curren4-5"></i>@endif</td>
                        <td>{{ number_format($day_interest,2) }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>

            <div class="t-invest-3">
                <a href="/project/current/detail" class="btn btn-red btn-large btn-block fl t-w236px" >继续买入</a><a href="/user" class="btn btn-blue btn-large btn-block fr t-w236px">返回我的账户</a>
            </div>
        </div>

        <div class="t-invest-right invest-success-coupon ">
            <a href="/"><img src="{{assetUrlByCdn('/static/images/error-new.png')}}"  width="280" height="370" /></a>
        </div>

    </div>

</div>

<!-- 左侧广告位 -->
<script type="text/javascript">
    (function($){
        $(document).ready(function(){
            $('#closed').click(function(){
                $('#bonusNotice').hide();
            });
            $('#noUsedBonus').click(function(){
                $('#bonusNotice').hide();
            });
        });
    })(jQuery)
</script>

@endsection
