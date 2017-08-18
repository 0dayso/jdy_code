@extends('pc.common.activity')

@section('title', '鱼你前行，耀我新生')

@section('keywords', env('META_KEYWORD'))

@section('description', env('META_DESCRIPTION'))

@section('csspage')
    <meta name="format-detection" content="telephone=yes">
    <link rel="stylesheet" type="text/css" href="{{ assetUrlByCdn('/static/activity/thirdanniversary/css/two.css') }}">
	<script type="text/javascript" src="{{ assetUrlByCdn('/static/angular/angular.min.js')}}"></script>
	<script type="text/javascript" src="{{ assetUrlByCdn('/static/angular/angular-route.js')}}"></script>
	<script type="text/javascript" src="{{ assetUrlByCdn('/static/angular/angular-animate.min.js')}}"></script>
    <script type="text/javascript" src="{{ assetUrlByCdn('/static/activity/thirdanniversary/js/anniversary.two.js')}}"></script>
@endsection
@section('content')
    <!--顶部总数统计- -->
    @include('pc.activity.thirdAnniversary.template.summation')
	<div class="anniversary-banner">
		<p>{{date("Y年m月d日",$activityTime['start'])}}-{{date("d日",$activityTime['end'])}}</p>
	</div>
	<div class="anniversary-main">
		<div class="anniversary-wrap">

			<!-- 邀请人 --><a name="float-two"></a>
			<div class="anniversary-title"></div>
			<div class="anniversary-invite">
				<p class="anniversary-invite-txt1">呼朋唤友抢60元现金红包</p>
				<p class="anniversary-invite-txt2">活动期间每邀请一个好友注册九斗鱼<br>邀请人可获得3元现金红包</p>
				<p class="anniversary-invite-txt3">好友下载九斗鱼APP——注册后填写邀请人手机号</p>
			</div>

			<!-- 投资赢豪礼 -->
			<a name="float-three"></a>
		    <div class="anniversary-title title2"></div>
			@include('pc.activity.thirdAnniversary.template.Invite')
			<!-- 项目 -->
			@include('pc.activity.thirdAnniversary.template.secondProject')

		    <!-- 每天随机抽奖伴手礼 -->
			<a name="float-four"></a>
			<div class="anniversary-buy">
				<h3>天天嗨购  惊喜不断</h3>
				<p>每日在优选项目中，随机抽选3名买入者，获得九斗鱼三周年伴手礼一份</p>
		    	<img src="{{ assetUrlByCdn('/static/activity/thirdanniversary/images/two-gift.png') }}" />
			</div>
			@include('pc.activity.thirdAnniversary.template.lotteryRecord')
		    <!-- rule -->
		    <div class="anniversary-rule">
		    	<h3>活动规则：</h3>
		    	<p><span>1.</span>活动时间：{{date("Y年m月d日",$activityTime['start'])}}-{{date("d日",$activityTime['end'])}}</p>
		    	<p><span>2.</span>活动期间邀请人／被邀请人累计投资优选项目金额排名前5名，即可获得对应的实物奖励；<br>判定邀请人或被邀请的身份，以活动期间的第一份身份为主；</p>
		    	<p><span>3.</span>活动期间内用户每邀请一名新用户注册九斗鱼，即可获得3元现金红包奖励（60元现金红包封顶）；</p>
		    	<p><span>4.</span>所获的现金红包奖励将于7月5日之前以现金券的形式发放至账户；</p>
		    	<p><span>5.</span>活动期间活动期间内邀请人和被邀请人，如有一方提现金额≥10000元，则取消双方的领奖资格；</p>
		    	<p><span>6.</span>活动期间所得奖品均以实物发放，客服会在7月30日之前联系用户确定收货地址，如7月30日之前联系未果，则视为自动放弃奖品；</p>
		    	<p><span>7.</span>活动期间如有任何疑问请致电九斗鱼官方客服：400-6686-568，或登录九斗鱼官网咨询在线客服；</p>
		    	<p><span>8.</span>网贷有风险、投资需谨慎。</p>
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
	$(function(){
		$('.refresh').click(function(){
			window.location.reload();
		})
	})

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





