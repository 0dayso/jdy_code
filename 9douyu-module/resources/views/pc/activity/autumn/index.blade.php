@extends('pc.common.activity')

@section('title', '立秋至 收获时')

@section('keywords', env('META_KEYWORD'))

@section('description', env('META_DESCRIPTION'))

@section('csspage')
    <meta name="format-detection" content="telephone=yes">
    <link rel="stylesheet" type="text/css" href="{{ assetUrlByCdn('/static/activity/autumn/css/index.css')}}">
    <script type="text/javascript" src="{{ assetUrlByCdn('/static/angular/angular.min.js')}}"></script>
    <script type="text/javascript" src="{{ assetUrlByCdn('/static/angular/angular-route.js')}}"></script>
    <script type="text/javascript" src="{{ assetUrlByCdn('/static/angular/angular-animate.min.js')}}"></script>
    <script type="text/javascript" src="{{ assetUrlByCdn('/static/activity/autumn/js/autumn.js')}}"></script>
    <script type="text/javascript" src="{{ assetUrlByCdn('/static/activity/autumn/js/lottery.js')}}"></script>
@endsection

@section('content')
    <div class="autumn-banner">
    	<div class="wrap">
    		<p>{{date('Y年m月d日',$activityTime['start'])}}-{{date('m月d日',$activityTime['end'])}}</p>
    	</div>
    </div>
    <div class="autumn-wrap">
    	<div class="wrap" id="btn-lottery-vip" lottery-level-value="1" attr-images-static="{{env ('STATIC_URL_HTTPS')}}">

            <!-- 初秋 -->
    		<div class="autumn-title">初秋</div>
    		<p class="autumn-title-sub">在活动页面5万元≤单笔投资3月期或6月期项目＜10万元，即可获得1次抽奖机会</p>
            @include('pc.activity.autumn.lotteryLevel1')

            <!-- 深秋 -->
            <div class="autumn-title title2">深秋</div>
            <p class="autumn-title-sub">在活动页面单笔投资3月期或6月期项≥10万，即可获得1次抽奖机会</p>
            @include('pc.activity.autumn.lotteryLevel2')

            <!-- 投资尊享 -->
            <div class="autumn-project" id="invest-page">
                <div class="autumn-project-title">投资尊享</div>
                @include('pc.activity.autumn.project')
            </div>
    	</div>
    </div>

    <!-- rule -->
    <div class="autumn-rule-wrap">
        <div class="autumn-rule">
            <h3>活动规则：</h3>
            <p>1、活动时间：{{date('Y年m月d日',$activityTime['start'])}}-{{date('m月d日',$activityTime['end'])}}；</p>
            <p>2、在活动期间内，仅活动页面进行项目投资才有资格获得活动抽奖机会；</p>
          <p>3、活动期间内，投资3月期或者6月期项目，满足不同奖池的抽奖条件，即可获得一次抽奖机会；<br/>
              <span>• 5万元≤单笔投资额＜10万元，可获得一次初秋奖池抽奖机会；</span><br/>
              <span>• 单笔投资额≥10万元，可获得一次深秋奖池抽奖机会；</span><br/>
              <span>举例：如单笔投资20万元3月期或6月期，即可获得一次深秋奖池的抽奖机会；</span>
          </p>
          <p>4、投资所获得的抽奖机会仅限活动期间有效；抽奖所得奖品均以实物形式发放，客服将在2017年9月30日之前，与您沟通联系确定发放奖品。如在9月30日之前联系用户无回应，则视为自动放弃实物奖品；</p>
          <p>5、1%定期加息券，起投金额30000元，仅限投资3、6、12月期及九安心项目；1.5%定期加息券，起投金额50000元，仅限投资3、6、12月期及九安心项目；加息券自发放之日起，有效期15天；</p>
          <p>6、活动期间内，获奖者提现金额≥10000元，取消其领奖资格；</p>
          <p>7、活动期间如有任何疑问请致电九斗鱼官方客服：400-6686-568，或登录九斗鱼咨询在线客服；</p>
          <p>8、本活动最终解释权归九斗鱼所有；</p>
        </div>
    </div>

    <!-- 弹层-->
    <div class="anniversary-layer1" data-layer="layer-net" style="display: none;"></div>
    <input type="hidden" name="_token"  value="{{csrf_token()}}">
@endsection


@section('jspage')
<script type="text/javascript">
$(function(){
    // 关闭弹层
    $(document).on('click', '[data-close]',function(event){
        event.stopPropagation();
        var target = $(this).attr("data-close");
        $("div[data-layer="+target+"]").hide();
        window.location.reload();
    });
    $(document).on('click', '.invest-page',function(event){
        event.stopPropagation();
        $("div[data-layer=layer-net]").hide();
    });

});
var click=false;

function doLotteryEvent(id) {
    var lotteryLevel    =   'lottery' +  id;
    lottery.init(lotteryLevel);
    if ( click ) {//click控制一次抽奖过程中不能重复点击抽奖按钮，后面的点击不响应
        return false;
    }
    var activity    =   'autumn';
    lottery.speed=100;
    lotteryEvent.doLottery(id,activity);
};
 
</script>
@endsection


