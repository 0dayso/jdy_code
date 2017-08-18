@extends('pc.common.activity')

@section('title', '鱼你前行，耀我新生')

@section('keywords', env('META_KEYWORD'))

@section('description', env('META_DESCRIPTION'))

@section('csspage')
    <meta name="format-detection" content="telephone=yes">
    <link rel="stylesheet" type="text/css" href="{{ assetUrlByCdn('/static/activity/thirdanniversary/css/three.css') }}">
	<script type="text/javascript" src="{{ assetUrlByCdn('/static/angular/angular.min.js')}}"></script>
	<script type="text/javascript" src="{{ assetUrlByCdn('/static/angular/angular-route.js')}}"></script>
	<script type="text/javascript" src="{{ assetUrlByCdn('/static/angular/angular-animate.min.js')}}"></script>
	<script type="text/javascript" src="{{ assetUrlByCdn('/static/activity/thirdanniversary/js/anniversary.three.js')}}"></script>
@endsection
@section('content')
	<!--顶部总数统计--->
	@include('pc.activity.thirdAnniversary.template.summation')
	<div class="anniversary-banner">
		<p>{{date("Y年m月d日",$activityTime['start'])}}-{{date("m月d日",$activityTime['end'])}}</p>
	</div>
	<div class="anniversary-main">
		<div class="anniversary-wrap">

			<!-- 疯狂捞金雨 --><a name="float-two"></a>
			<div class="anniversary-rain">
				<div class="anniversary-title">疯狂捞金雨</div>
				<p>登录九斗鱼APP每天猛戳红包雨</p>
			</div>

			<!-- 嘉年华专享 -->
			<a name="float-three"></a>
			@include('pc.activity.thirdAnniversary.template.thirdProject')
			<!--嘉年华的模块-->
			@include('pc.activity.thirdAnniversary.template.Jnh')
		    <!-- 每天随机抽奖伴手礼 -->
			<a name="float-four"></a>
			<div class="anniversary-box bottom1">
				<ins class="anniversary-dec"></ins>
				<div class="anniversary-prize">
					<p><big>天天嗨购  惊喜不断</big></p>
					<p>每日在优选项目中，随机抽选3名买入者，获得九斗鱼三周年伴手礼一份</p>
				</div>
				<div class="anniversary-prize-img">
					<img src="{{ assetUrlByCdn('/static/activity/thirdanniversary/images/three-gift.png') }}" />
				</div>
				@include('pc.activity.thirdAnniversary.template.lotteryRecord')
			</div>
		    <!-- rule -->
		    <div class="anniversary-rule">
		    	<h3>< 活动规则 ></h3>
		    	<p><span>1.</span>活动时间：{{date("Y年m月d日",$activityTime['start'])}}-{{date("m月d日",$activityTime['end'])}} </p>
		    	<p><span>2.</span>活动期间内，每日会在当日投资优选项目为3万的整数倍的投资者中随机抽选一名，获得当日的惊喜奖；于次日11点公布昨日中奖信息，节假日顺延；</p>
		    	<p><span>3.</span>活动期间内，获奖者提现金额≥10000元，取消其领奖资格；</p>
		    	<p><span>4.</span>活动所得奖品以实物形式发放，客服将在2017年7月30日之前，与您沟通联系确定发放奖品；如再次期间联系未果，则视为自动放弃奖励；</p>
		    	<p><span>5.</span>活动期间如有任何疑问请致电九斗鱼官方客服：400-6686-568，或登录九斗鱼咨询在线客服；</p>
		    	<p><span>6.</span>网贷有风险、投资需谨慎。</p>
		    </div>

		</div>
	</div>

<div class="anniversary-float">
	<a href="#float-one">活动数据</a>
	<a href="#float-two">抢现金红包</a>
	<a href="#float-three">投资狂嗨</a>
	<a href="#float-four">惊喜不断</a>
</div>
<input type="hidden" name="_token"  value="{{csrf_token()}}">

@endsection


@section('jspage')
<script type="text/javascript">
	$(document).delegate(".investClick",'click',function () {
		var  projectId  =   $(this).attr("attr-data-id");
		if( !projectId ||projectId==0){
			return false;
		}
		var act_token   =   '{{$actToken}}_' + projectId;
		var _token      =   $("input[name='_token']").val();
		$.ajax({
			url      :"/activity/setActToken",
			data     :{act_token:act_token,_token:_token},
			dataType :'json',
			type     :'post',
			success : function() {

				window.location.href='/project/detail/' + projectId;
			}, error : function() {
				window.location.href='/project/detail/' + projectId;
			}
		});

	})
$(function(){})
    //list滚动
    function AutoScroll1(obj) {
        $(obj).find("ul").animate({
            marginTop: "-40px"
        }, 500, function() {
            $(this).css({ marginTop: "0px" }).find("li:first").appendTo(this);
        });
    }

    var myar1 = setInterval('AutoScroll1("#scrollDiv1")', 2000);
    $("#scrollDiv1").hover(

    	function() {clearInterval(myar1) },
     	function() {myar1 = setInterval('AutoScroll1("#scrollDiv1")', 2000)

 }); //当鼠标放上去的时候，滚动停止，鼠标离开的时候滚动开始
</script>

@endsection





