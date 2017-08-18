@extends('wap.common.activity')

@section('title', '夏不为利 畅享七月')

@section('keywords', env('META_KEYWORD'))

@section('description', env('META_DESCRIPTION'))

@section('css')
<meta name="format-detection" content="telephone=yes">
<link rel="stylesheet" type="text/css" href="{{assetUrlByCdn('/static/weixin/activity/july/css/july.css')}}">
@endsection

@section('content')
    <article>
    	<div class="july-banner">
            <input type="hidden" name="_token"  value="{{csrf_token()}}">
            <p>{{date('Y-m-d',$activityTime['start'])}} --{{date('m-d',$activityTime['end'])}}</p>
        </div>
        <div class="july-wrap">
            <div class="july-tab">
                <span class="july-3 active">3月期项目</span>
                <span class="july-6">6月期项目</span>
            </div>
            <div class="july-tab-main" style="display: block;">
                @include('wap.activity.july.lotteryOne');
            </div>

            <div class="july-tab-main july-6">
                @include('wap.activity.july.lotteryTwo')
            </div>
            <span id="investModule"></span>
            @include('wap.activity.july.project')
        </div>

        <div class="july-rule">
            <h3>-活动规则-</h3>
            <p><span>1.</span>活动期间内，累计投资优选项目3月期或6月期达到奖品对应金额，即可获得对应的实物奖品。投资金额不可使用加息券或红包；</p>
            <p><span>2.</span>参与领取奖品者，活动期间提现金额≥10000元，取消其领奖资格；</p>
            <p><span>3.</span>活动所得奖品以实物形式发放，将在2017年8月30日之前，与您沟通联系确定发放奖品。如在2017年8月30日之前联系未果，则视为自动放弃奖励；</p>
            <p><span>4.</span>活动期间如有任何疑问请致电九斗鱼官方客服：400-6686-568，或登录九斗鱼咨询在线客服；</p>
            <p><span>5.</span>活动最终解释权归九斗鱼所有。</p>
        </div>
    </article>

@endsection


@section('jsScript')
    <script>
    $(function(){
    	// tab数据切换

    	$('.july-tab span').on("touchend",function(){
    		var index = $(this).index();
    		$(this).addClass('active').siblings('.july-tab span').removeClass('active');
    		$('.july-tab-main').eq(index).show().siblings('.july-tab-main').hide();
    	});
    })
    </script>
@endsection
