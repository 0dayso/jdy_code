@extends('pc.common.activity')

@section('title', '鱼你前行，耀我新生')

@section('keywords', env('META_KEYWORD'))

@section('description', env('META_DESCRIPTION'))

@section('csspage')
    <meta name="format-detection" content="telephone=yes">
    <link rel="stylesheet" type="text/css" href="{{ assetUrlByCdn('/static/activity/thirdanniversary/css/one.css') }}">
    <script type="text/javascript" src="{{ assetUrlByCdn('/static/angular/angular.min.js')}}"></script>
    <script type="text/javascript" src="{{ assetUrlByCdn('/static/angular/angular-route.js')}}"></script>
    <script type="text/javascript" src="{{ assetUrlByCdn('/static/angular/angular-animate.min.js')}}"></script>
    <script type="text/javascript" src="{{ assetUrlByCdn('/static/activity/thirdanniversary/js/anniversary.one.js')}}"></script>
    <script type="text/javascript" src="{{ assetUrlByCdn('/static/activity/thirdanniversary/js/lottery.one.js')}}"></script>
@endsection
@section('content')
    <!--顶部总数统计- -->
    @include('pc.activity.thirdAnniversary.template.summation')
	<div class="anniversary-banner">
		<p>好机会不常来呦，100%中奖，放肆投！<br>{{date("Y年m月d日",$activityTime['start'])}}-{{date("m月d日",$activityTime['end'])}}</p>
	</div>
	<div class="anniversary-main">
		<div class="anniversary-wrap"  ng-controller="userCtrl" >
			<div class="anniversary-progress"></div>
			<div class="anniversary-login">
                <!-- 未登录状态 -->
				<div class="anniversary-login-status1"  ng-if="userStatus == false" >
                    <p class="anniversary-login-account">还没账号？<a href="/register" class="anniversary-btn-register">马上注册</a></p>
                    <a href="/login" class="anniversary-btn-login">立即登录</a>
                    <p class="anniversary-login-tip">请登录查看您的投资等级，不同等级对应的奖池是不一样哒！</p>
                </div>
                <!-- End 未登录状态 -->
                <!-- 登录状态 -->
                <div class="anniversary-login-status2"  ng-if="userStatus == true && account >= min_invest" ng-style="setBlock(userStatus)">
                    <p class="anniversary-login-tip">截止{{date('Y年m月d日',min($activityTime['end'],max($activityTime['start'],time())))}}您在活动期间累计充值金额</p>
                    <p class="anniversary-invest-total">共计<span ng-bind="account |number:2"></span>元</p>
                    <p class="anniversary-invest-result">开启了"<span ng-bind="levelNote"></span>"奖池</p>
                    <p class="anniversary-invest-txt">单笔投资满足对应奖池的起投金额，即可获得一次抽奖机会</p>
                    <a href="#float-three" class="anniversary-btn-invest" >马上投资</a>
                </div>
                <!-- End 登录状态 -->
                <div class="anniversary-login-status2"  ng-if="userStatus == true && account < min_invest" ng-style="setBlock(userStatus)">
                    <p class="anniversary-login-tip">截止{{date('Y年m月d日',min($activityTime['end'],max($activityTime['start'],time())))}}您在活动期间累计充值金额</p>
                    <p class="anniversary-invest-total">共计<span ng-bind="account |number:2"></span>元</p>
                    <p class="anniversary-invest-result">活动期间累计充值到<span ng-bind='grade_money'></span>可升级到L1</p>
                    <p class="anniversary-invest-txt">单笔投资 <span class="anniversary-label-color2" ng-bind="min_invest"></span>元，好礼升级，可参与 <span class="anniversary-label-color2" ng-bind="grade.grade_name"></span>奖池投资抽奖呦</p>
                    <a href="#float-three" class="anniversary-btn-invest" >马上投资</a>
                </div>
                <!-- End 登录状态 -->
			</div>
			<!--抽奖区-->
		    <div id="anniversaryLottery"><a name="float-two"></a>
			@include("pc.activity.thirdAnniversary.template.lotteryPlate")
		    </div>

			<a name="float-three"></a>
			<div class="anniversary-title title2"></div>
			<!-- 活动项目-->
		    @include('pc.activity.thirdAnniversary.template.project')

			<a name="float-four"></a>
		    <div class="anniversary-title title3"></div>
            <div class="anniversary-prize">
                <img src="{{ assetUrlByCdn('/static/activity/thirdanniversary/images/one-gift.png') }}" />
            </div>
		    <!-- 每天随机抽奖伴手礼 -->
		    @include('pc.activity.thirdAnniversary.template.lotteryRecord')

		    <!-- rule -->
		    <div class="anniversary-rule">
		    	<h3>活动规则：</h3>
		    	<p><span>1.</span>活动时间：{{date("Y年m月d日",$activityTime['start'])}}-{{date("m月d日",$activityTime['end'])}}</p>
		    	<p><span>2.</span>活动期间内根据用户新充值金额累计投资金额开启对应的活动奖池；活动期间内单笔投资优选项目满足对应奖池的起投金额，即可在对应奖池抽奖一次，100%中奖<br>
                    L1奖池:20000≤新充值金额﹤50000且单笔投资金额≥2万元,<br>
                    L2奖池:50000≤新充值金额﹤100000且单笔投资金额≥5万元，<br>
                    L3奖池:100000≤新充值金额﹤150000且单笔投资金额≥8万元，<br>
                    L4奖池:150000≤新充值金额﹤200000且单笔投资金额≥10万元，
                </p>
		    	<p><span>3.</span>活动期间内，每个用户参与每个奖池的抽奖机会最高为5次；</p>
		    	<p><span>4.</span>参与抽奖的有效金额不包含使用红包和加息券的额度；</p>
		    	<p><span>5.</span>参与领取奖品者，活动期间提现金额≥10000元，则取消其领奖资格；</p>
		    	<p><span>6.</span>活动期间所得奖品均以实物发放，客服会在7月30日之前联系用户确定收货地址，如7月30日之前联系未果，则视为自动放弃奖品；</p>
		    	<p><span>7.</span>每日在优选项目投资用户中，抽取3名用户，获得周年庆伴手礼，获奖名单将于第二个工作日12点公布；</p>
		    	<p><span>8.</span>优选项目即为：九省心1月期、3月期、6月期、12月期及九安心项目；</p>
                <p><span>9.</span>活动期间如有任何疑问请致电九斗鱼官方客服：400-6686-568，或登录九斗鱼官网咨询在线客服；</p>
                 <p><span>10.</span>网贷有风险、投资需谨慎。</p>
		    </div>

		</div>
	</div>

    <div class="anniversary-float">
        <a href="#float-one">活动数据</a>
        <a href="#float-two">任性抽奖</a>
        <a href="#float-three">投资狂嗨</a>
        <a href="#float-four">惊喜不断</a>
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
    //选项卡切换
    $(".anniversary-level li").click(function(){
        $(this).addClass("active").siblings(".anniversary-level li").removeClass('active');
        $("#anniversary-plate-tab .anniversary-plate").hide().eq($(this).index()).show();
        $('#lottery1,#lottery2,#lottery3,#lottery4').removeClass('active');
        // 清除定时器 及初始抽奖参数
        clearTimeout(lottery.timer);
        lottery.prize=-1;
        lottery.times=0;
        click=false;
        // 初始化奖池
        vipIndex = $(this).index()+1;
        lottery.init('lottery'+vipIndex);
        $("#btn-lottery-vip").removeClass("btn-plate-active").attr('lottery-level-value',vipIndex);
    });
})

var click=false;

window.onload=function(){
    lottery.init('lottery1');
    $("#btn-lottery-vip").click(function(event){
        if (click) {//click控制一次抽奖过程中不能重复点击抽奖按钮，后面的点击不响应
            return false;
        }
        lottery.speed=100;
        lotteryEvent.doLottery(event);
        roll();    //转圈过程不响应click事件，会将click置为false
        click=true; //一次抽奖完成后，设置click为true，可继续抽奖
        return false;

    });
};

 //list滚动
    function AutoScroll1(obj) {
        $(obj).find("ul").animate({
            marginTop: "-40px"
        }, 500, function() {
            $(this).css({ marginTop: "0px" }).find("li:first").appendTo(this);
        });
    }
    var myar1 = setInterval('AutoScroll1("#scrollDiv1")', 2000);
    $("#scrollDiv1").hover(function() { clearInterval(myar1); }, function() { myar1 = setInterval('AutoScroll1("#scrollDiv1")', 2000) }); //当鼠标放上去的时候，滚动停止，鼠标离开的时候滚动开始
</script>
@endsection
