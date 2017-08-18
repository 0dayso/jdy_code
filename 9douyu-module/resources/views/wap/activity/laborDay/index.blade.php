@extends('wap.common.activity')

@section('title', '不劳而获开购吧')

@section('keywords', env('META_KEYWORD'))

@section('description', env('META_DESCRIPTION'))

@section('css')
<meta name="format-detection" content="telephone=yes">
<link rel="stylesheet" type="text/css" href="{{ assetUrlByCdn('/static/weixin/activity/laborDay/css/laborDay.css')}}">

@endsection

@section('content')
<div class="laborday-banner">
	<p>{{date('Y年m月d日',$activityTime['start'])}}--{{date('m月d日',$activityTime['end'])}}</p>
</div>
<div class="laborday-1">
	<a href="javascript:;" class="laborday-rule"><img src="{{ assetUrlByCdn('/static/weixin/activity/laborDay/images/rule.png')}}" class="img"></a>
	<p class="laborday-text1-1">签到时间：{{date('m月d日',$signTime['start'])}}--{{date('m月d日',$signTime['end'])}}</p>
	<p class="laborday-text1-2"><img src="{{ assetUrlByCdn('/static/weixin/activity/laborDay/images/sign-icon.png')}}"></p>
@if($signTime['end'] < time())
	<p class="laborday-text"><strong>（{{date('m月d日',$signTime['end'])}}）</strong>签到结束</p>
	<p class="laborday-text1">谢谢参与</p>
@else
	<p class="laborday-text"><strong>（{{date('m月d日',$signTime['start'])}}）</strong>开始签到</p>
	<p class="laborday-text1">领取红包奖励</p>
@endif
	<div class="laborday-2">
@if( $signStatus['status'] == true)
		<a href="javascript:;" class=""><img src="{{ assetUrlByCdn('/static/weixin/activity/laborDay/images/btn.png')}}" class="img laborday-btn laborday-btn-1 sign-btn-sure" sign-data-lock="start"></a>
@elseif($signStatus['data']['type'] =='notLogged' || $exchangeStatus['data']['type'] =='notLogged')
		<a href="javascript:;" class=""><img src="{{ assetUrlByCdn('/static/weixin/activity/laborDay/images/btn.png')}}" class="img laborday-btn laborday-btn-1 sign-btn-login"></a>
@else
		<a href="javascript:;" class=""><img src="{{ assetUrlByCdn('/static/weixin/activity/laborDay/images/btn.png')}}" class="img laborday-btn laborday-btn-1 sign-btn-error"></a>
@endif
@if($exchangeStatus['status'] == true)
		<a href="javascript:;" class=""><img src="{{ assetUrlByCdn('/static/weixin/activity/laborDay/images/btn-1.png')}}" class="img laborday-btn1 laborday-btn1-1 exchange-btn-sure" exchange-data-lock="start"></a>
@elseif( $exchangeStatus['data']['type'] =='notLogged')
		<a href="javascript:;" class=""><img src="{{ assetUrlByCdn('/static/weixin/activity/laborDay/images/btn-1.png')}}" class="img laborday-btn1 laborday-btn1-1 exchange-btn-login"></a>
@else
		<a href="javascript:;" class=""><img src="{{ assetUrlByCdn('/static/weixin/activity/laborDay/images/btn-1.png')}}" class="img laborday-btn1 laborday-btn1-1 exchange-btn-error"></a>
@endif
	</div>
</div>

<div class="laborday-lottery">
<div id="lottery" lottery_can_used="{{isset($lotteryStatus['data']['lotteryNumber']) ?$lotteryStatus['data']['lotteryNumber'] :0}}" data-lock="start">
	<table border="0" cellpadding="0" cellspacing="0">
		<tr>
			<td class="lottery-unit lottery-unit-0">
				<div class="laborday-bg"><img src="{{ assetUrlByCdn('/static/weixin/activity/laborDay/images/img1.png')}}"></div>
			</td>
			<td class="lottery-unit lottery-unit-1">
				<div class="laborday-bg"><img src="{{ assetUrlByCdn('/static/weixin/activity/laborDay/images/img2.png')}}" class="laborday-img1"></div>
			<td class="lottery-unit lottery-unit-2">
				<div class="laborday-bg"><img src="{{ assetUrlByCdn('/static/weixin/activity/laborDay/images/img3.png')}}" ></div>
			</td>
		</tr>
		<tr>
			<td class="lottery-unit lottery-unit-7">
				<div class="laborday-bg"><img src="{{ assetUrlByCdn('/static/weixin/activity/laborDay/images/img5.png')}}" class="laborday-img"></div>
			</td>
@if($lotteryStatus['status'] == false)
			<td><a href="javascript:;" class="lottery-error"></a></td>
@else
			<td><a href="javascript:;" class="lottery" data-lock="start"></a></td>
@endif
			<td class="lottery-unit lottery-unit-3">
				<div class="laborday-bg"><img src="{{ assetUrlByCdn('/static/weixin/activity/laborDay/images/img4.png')}}"></div>
			</td>
		</tr>
		<tr>
			<td class="lottery-unit lottery-unit-6">
				<div class="laborday-bg"><img src="{{ assetUrlByCdn('/static/weixin/activity/laborDay/images/img7.png')}}"></div>
			</td>
			<td class="lottery-unit lottery-unit-5">
				<div class="laborday-bg"><img src="{{ assetUrlByCdn('/static/weixin/activity/laborDay/images/img6.png')}}"></div>
			</td>
			<td class="lottery-unit lottery-unit-4">
				<div class="laborday-bg"><img src="{{ assetUrlByCdn('/static/weixin/activity/laborDay/images/img5.png')}}" class="laborday-img"></div>
			</td>
		</tr>
	</table>
</div>
<p class="laborday-text2" id="projectList">活动期间单笔投资优选项目≥{{$minInvest}}元，</p>
<p class="laborday-text2">即可获得1次抽奖机会</p>
</div>

@if(!empty($projectList))
<div class="laborday-box">
@foreach($projectList as $key => $project)
	<div class="laborday-box2">
		<h5>{{$project['name']}}</h5>
		<table class="first-table">
			<tr>
				<td width="27%"><span>{{(float)$project['profit_percentage']}}</span>％</td>
				<td width="30%"><span>{{$project['format_invest_time']}}</span>{{$project['invest_time_unit']}}</td>
				<td rowspan="2">
@if($project['status'] == \App\Http\Dbs\Project\ProjectDb::STATUS_INVESTING)
					<a href="javascript:;" class="first-btn investProject"  attr-project-id="{{$project['id']}}"></a>
@else
					<a href="javascript:;" class="first-btn disable investProject"  attr-project-id="{{$project['id']}}"></a>
@endif
				</td>
			</tr>
			<tr>
				<td>年化收益</td>
				<td>期限</td>
			</tr>
		</table>
	</div>
@endforeach
</div>
@endif
<div class="laborday-rule1">
	<h4 class="antwo-sum">活动规则</h4>
	<p>1、活动时间：{{date("Y年m月d日",$activityTime['start'])}}-{{date("Y年m月d日",$activityTime['end'])}}；</p>
	<p>2、活动期间内，{{date("Y年m月d日",$signTime['start'])}}-{{date("m月d日",$signTime['end'])}}坚持连续签到，每天都可领取2元红包。中途漏签，则无法继续签到，签到领取的红包活动期间内可以兑换。</p>
	<p>3、活动期间内，单笔投资优选项目（1月期除外）≥{{$minInvest}}元可获得一次抽奖机会，抽奖机会仅限活动期间有效；活动所得奖品以实物形式发放，客服将在2017年6月15日之前，与您沟通联系确定发放奖品。如在6月15日之前联系用户无回应，则视为自动放弃实物奖品</p>
	<p>4、活动期间内，获奖者提现金额≥10000元，取消其领奖资格；</p>
	<p>5、活动期间如有任何疑问请致电九斗鱼官方客服：400-6686-568，或登录九斗鱼咨询在线客服；</p>
</div>

<!-- 活动规则 -->
<div class="pop-layer" id="rule">
	<div class="pop-mask"></div>
	<div class="pop">
		<img src="{{ assetUrlByCdn('/static/weixin/activity/springFestival/images/text.png')}}" class="img pop-text">
		<div class="pop-wrap">
			<p class="laborday-rules"><span>1.</span>{{date("Y年m月d日",$signTime['start'])}}-{{date("m月d日",$signTime['end'])}}坚持连续签到，每天都可领取2元红包</p>
			<p class="laborday-rules"><span>2.</span>中途漏签，则无法继续签到</p>
			<p class="laborday-rules"><span>3.</span>签到领取的红包随时可以兑换</p>
			<p class="laborday-rules"><span>4.</span>红包兑换后则无法继续签到</p>
			<a href="javascript:;" class="pop-btn pop-btn-1" ><img src="{{ assetUrlByCdn('/static/weixin/activity/springFestival/images/btn4.png')}}" class="img"></a>
		</div>
	</div>
</div>
<!-- 抽奖的 -->
<div class="pop-layer" id="prize" style="display: none;">
    <div class="pop-mask"></div>
    <div class="pop">
@if($lotteryStatus['status'] == true )
    	<!--满足抽奖 -->
    	<img src="{{ assetUrlByCdn('/static/weixin/activity/laborDay/images/text6.png')}}" class="img pop-text7 lottery-success" >
    	<img src="{{ assetUrlByCdn('/static/weixin/activity/springFestival/images/sp-joined.png')}}" class="img pop-text7 lottery-error" style="display: none">
		<img src="{{ assetUrlByCdn('/static/weixin/activity/laborDay/images/img-5.png')}}" class="img pop-img" style="width:5.2rem; margin-top: 1.2rem;">
		<p class="pop-pr">{{$lotteryStatus['msg']}}</p>
		<p class="pop-text4">还有<span>2</span>次抽奖机会</p>
		<a href="javascript:;" class="pop-btn pop-btn-2" ><img src="{{ assetUrlByCdn('/static/weixin/activity/laborDay/images/btn7.png')}}" class="img"></a>
@elseif($lotteryStatus['data']['type'] =='notLogged')
	<!-- 登录提示 -->
		<img src="{{ assetUrlByCdn('/static/weixin/activity/laborDay/images/text4.png')}}" class="img pop-text">
		<img src="{{ assetUrlByCdn('/static/weixin/activity/laborDay/images/img-4.png')}}" class="img pop-img" style="width:4.65rem; margin-top: 1.8rem;">
		<p class="pop-text4">{{$lotteryStatus['msg']}}</p>
		<a href="javascript:;" class="pop-btn userLogin" ><img src="{{ assetUrlByCdn('/static/weixin/activity/laborDay/images/btn10.png')}}" class="img"></a>
@elseif($lotteryStatus['data']['type'] =='notLottery')
	<!-- 登录后不满足抽奖条件 -->
		<img src="{{ assetUrlByCdn('/static/weixin/activity/laborDay/images/text3.png')}}" class="img pop-text">
		<img src="{{ assetUrlByCdn('/static/weixin/activity/laborDay/images/img-3.png')}}" class="img pop-img" style="width:6.5rem; margin-top: 1.8rem;">
		<p class="pop-text4">{{$lotteryStatus['msg']}}</p>
		<a href="#projectList" class="pop-btn pop-btn-1" ><img src="{{ assetUrlByCdn('/static/weixin/activity/laborDay/images/btn9.png')}}" class="img"></a>
@elseif($lotteryStatus['data']['type'] =='notInTime' )
		<img src="{{ assetUrlByCdn('/static/weixin/activity/laborDay/images/sp-joined.png')}}" class="img pop-text7">
		<img src="{{ assetUrlByCdn('/static/weixin/activity/laborDay/images/img-5.png')}}" class="img pop-img" style="width:5.2rem; margin-top: 1.2rem;">
		<p class="pop-pr">{{$lotteryStatus['msg']}}</p>
		<p class="pop-text4"></p>
		<a href="javascript:;" class="pop-btn pop-btn-1" ><img src="{{ assetUrlByCdn('/static/weixin/activity/laborDay/images/btn7.png')}}" class="img"></a>
@endif
    </div>
</div>


<!-- 兑换红包 -->
<div class="pop-layer" id="exchange" style="display: none;">
    <div class="pop-mask"></div>
    <div class="pop">
@if($exchangeStatus['status']== true)
	<span class="exchange-title" style="display:none ">
          <!-- 兑换红包 -->
        <img src="{{ assetUrlByCdn('/static/weixin/activity/laborDay/images/text1.png')}}" class="img pop-text">
        <div class="pop-wrap">
        	<p class="pop-text4">我的奖金：<span>{{$signDayList['exchange']}}</span>元</p>
        	<p class="pop-text4">已连续签到{{isset($signDayList['recordList']['sign_num']) ? $signDayList['recordList']['sign_num'] : 0}}天</p>
        	<p class="pop-text5">兑换红包后则无法继续签到</p>
        	<p class="pop-text5">确认兑换红包？</p>
        </div>
        <div class="laborday-2" style="margin-top:1rem;">
			<a href="javascript:;" class=""><img src="{{ assetUrlByCdn('/static/weixin/activity/laborDay/images/btn5.png')}}" class="img laborday-btn pop-btn-redpacket" exchange-data-lock="start"></a>
			@if(date("Y-m-d",$signTime['end']) > date("Y-m-d",time()))
			<a href="javascript:;" class=""><img src="{{ assetUrlByCdn('/static/weixin/activity/laborDay/images/btn6.png')}}" class="img1 laborday-btn pop-btn-close"></a>
			@endif
		</div>
    </span>
	<span class="exchange-success" style="display: none">
        <!-- 成功兑换 -->
        <img src="{{ assetUrlByCdn('/static/weixin/activity/laborDay/images/text2.png')}}" class="img pop-text">
		<img src="{{ assetUrlByCdn('/static/weixin/activity/laborDay/images/img-1.png')}}" class="img pop-img">
		<p class="pop-text5">成功兑换{{$signDayList['exchange']}}元现金红包</p>
		<p class="pop-text4">【资产-我的优惠券】中查看使用</p>
		<a href="javascript:;" class="pop-btn pop-btn-1" ><img src="{{ assetUrlByCdn('/static/weixin/activity/laborDay/images/btn7.png')}}" class="img"></a>
    </span>
@elseif($exchangeStatus['data']['type'] =='notLogged')
	<span class="not-logged-status" style="display:block ">
        <!-- 成功兑换 -->
        <img src="{{ assetUrlByCdn('/static/weixin/activity/laborDay/images/text4.png')}}" class="img pop-text">
		<img src="{{ assetUrlByCdn('/static/weixin/activity/laborDay/images/img-4.png')}}" class="img pop-img">
		<p class="pop-text5">{{$exchangeStatus['msg']}}</p>
		<a href="javascript:;" class="pop-btn pop-btn-1 userLogin" ><img src="{{ assetUrlByCdn('/static/weixin/activity/laborDay/images/btn10.png')}}" class="img"></a>
    </span>
@else
	<span class="not-exchange-error" style="display:block ">
        <!-- 成功兑换 -->
        <img src="{{ assetUrlByCdn('/static/weixin/activity/laborDay/images/sp-joined.png')}}" class="img pop-text">
		<img src="{{ assetUrlByCdn('/static/weixin/activity/laborDay/images/img-1.png')}}" class="img pop-img">
		<p class="pop-text5">{{$exchangeStatus['msg']}}</p>
		<a href="javascript:;" class="pop-btn pop-btn-1" ><img src="{{ assetUrlByCdn('/static/weixin/activity/laborDay/images/btn7.png')}}" class="img"></a>
    </span>
@endif
	</div>
</div>

<div class="pop-layer" id="voucher" style="display: none;" >
	<div class="pop-mask"></div>
	<div class="pop">
@if($signStatus['status'] == true)
<!-- 签到领取弹窗 -->
		<span class="sign-success">
			<img src="{{ assetUrlByCdn('/static/weixin/activity/laborDay/images/text5.png')}}" class="img pop-text " >
			<p class="pop-text6">恭喜你签到成功!已连续签到<span>{{isset($signDayList['recordList']['sign_num']) ? $signDayList['recordList']['sign_num'] : 0}}</span>天</p>
			<ul class="pop-sign">
@if(!empty($signDayList['signDay']))
@foreach($signDayList['signDay'] as $dayKey => $signName)
@if(isset($signDayList['recordList']['sign_record']) && in_array($dayKey,$signDayList['recordList']['sign_record']))
			<li><i class="done" id="{{$dayKey}}"></i><span>{{$signName}}</span></li>
@else
			<li><i id="{{$dayKey}}"></i><span>{{$signName}}</span></li>
@endif
@endforeach
@else
			{{--<li><i class="disable" id=""></i><span>先锋奖章</span></li>--}}
			{{--<li><i class="done" id=""></i><span>先进奖章</span></li>--}}
			<li><i></i><span>先锋奖章</span></li>
			<li><i></i><span>先进奖章</span></li>
			<li><i></i><span>模范奖章</span></li>
			<li><i></i><span>敬业奖章</span></li>
			<li><i></i><span>劳模奖章</span></li>
			<li><i></i><span>爱心奖章</span></li>
			<li><i></i><span>团结奖章</span></li>
@endif
		<!-- 签到领取 -->
			</ul>
			<a href="javascript:;" class="pop-btn pop-btn-2">
				<img src="{{ assetUrlByCdn('/static/weixin/activity/laborDay/images/btn11.png')}}" class="img">
			</a>
		</span>
        <span class="sign-error">
            <img src="{{ assetUrlByCdn('/static/weixin/activity/laborDay/images/sp-joined.png')}}" class="img pop-text">
            <img src="{{ assetUrlByCdn('/static/weixin/activity/laborDay/images/img-2.png')}}" class="img pop-img" style="width:3.275rem;">
            <p class="pop-text4">{{$signStatus['msg']}}~</p>
            <a href="javascript:;" class="pop-btn pop-btn-1 " >
            <img src="{{ assetUrlByCdn('/static/weixin/activity/laborDay/images/btn7.png')}}" class="img">
            </a>
        </span>
@elseif($signStatus['data']['type'] =='notLogged')
		<span class="sign-error">
			<img src="{{ assetUrlByCdn('/static/weixin/activity/laborDay/images/text4.png')}}" class="img pop-text">
			<img src="{{ assetUrlByCdn('/static/weixin/activity/laborDay/images/img-2.png')}}" class="img pop-img" style="width:3.275rem;">
			<p class="pop-text4">{{$signStatus['msg']}}~</p>
			<a href="javascript:;" class="pop-btn pop-btn-1 userLogin" >
			<img src="{{ assetUrlByCdn('/static/weixin/activity/laborDay/images/btn10.png')}}" class="img">
			</a>
		</span>
@else
		<span class="sign-error">
			<img src="{{ assetUrlByCdn('/static/weixin/activity/laborDay/images/sp-joined.png')}}" class="img pop-text">
			<img src="{{ assetUrlByCdn('/static/weixin/activity/laborDay/images/img-2.png')}}" class="img pop-img" style="width:3.275rem;">
			<p class="pop-text4">{{$signStatus['msg']}}~</p>
			<a href="javascript:;" class="pop-btn pop-btn-1 " >
			<img src="{{ assetUrlByCdn('/static/weixin/activity/laborDay/images/btn7.png')}}" class="img">
			</a>
		</span>
@endif
	</div>
</div>
@endsection
@section('jsScript')
	<script type="text/javascript">
        var client = getCookie('JDY_CLIENT_COOKIES');
        if( client == '' || !client ){
        var client  =   '{{$client}}';
        }
		var lottery={
			index:-1,	//当前转动到哪个位置，起点位置
			count:0,	//总共有多少个位置
			timer:0,	//setTimeout的ID，用clearTimeout清除
			speed:10,	//初始转动速度
			times:0,	//转动次数
			cycle:10,	//转动基本次数：即至少需要转动多少次再进入抽奖环节
			prize:-1,	//中奖位置
			init:function(id){
				if ($("#"+id).find(".lottery-unit").length>0) {
					$lottery = $("#"+id);
					$units = $lottery.find(".lottery-unit");
					this.obj = $lottery;
					this.count = $units.length;
					$lottery.find(".lottery-unit-"+this.index).find(".laborday-bg").addClass("active");
				};
			},
			roll:function(){
				var index = this.index;
				var count = this.count;
				var lottery = this.obj;
				$(lottery).find(".lottery-unit-"+index).find(".laborday-bg").removeClass("active");
				index += 1;
				if (index>count-1) {
					index = 0;
				};
				$(lottery).find(".lottery-unit-"+index).find(".laborday-bg").addClass("active");
				this.index=index;
				return false;
			},
			stop:function(index){
				this.prize=index;
				return false;
			},
			lottery:function (prizeName,prizeType,activeIndex,prizeNumber) {
				//记录奖品的数据
				$("#lottery").attr("lottery_array_string",prizeName+"_"+prizeType+"_"+activeIndex).attr('lottery_can_used',prizeNumber-1)
			},
			lotteryShow:function (prizeName,prizeType) {

				var prizeNumber     =   $("#lottery").attr('lottery_can_used');
				$(".pop-text4 span").empty().html(prizeNumber);
				if(prizeType == 5){
					$(".lottery-success").hide();
					$(".lottery-error").show();
					$(".pop-pr").html(prizeName)

				}else{
					$(".lottery-success").show();
					$(".lottery-error").hide();
					$(".pop-pr").html("恭喜您获得了"+prizeName+"奖品");
				}
				$("#prize").show();
			}
		};

		function roll(){
			lottery.times += 1;
			lottery.roll();
			var lotteryInfo =   $("#lottery").attr('lottery_array_string');
			var prizeMsg    =   lotteryInfo.split("_");
			var prizeIndex  =   prizeMsg[2];

			if (lottery.times > lottery.cycle+10 && prizeIndex==lottery.index) {
				lottery.lotteryShow(prizeMsg[0],prizeMsg[1]);
				clearTimeout(lottery.timer);
				lottery.prize=-1;
				lottery.times=0;
				click=false;
			}else{
				if (lottery.times<lottery.cycle) {
					lottery.speed -= 10;
				}else if(lottery.times==lottery.cycle) {
					var index = Math.random()*(lottery.count)|0;
					lottery.prize = index;
				}else{
					if (lottery.times > lottery.cycle+10 && ((lottery.prize==0 && lottery.index==7) || lottery.prize==lottery.index+1)) {
						lottery.speed += 110;
					}else{
						lottery.speed += 20;
					}
				}
				if (lottery.speed<40) {
					lottery.speed=40;
				};
				//console.log(lottery.times+'^^^^^^'+lottery.speed+'^^^^^^^'+lottery.prize);
				lottery.timer = setTimeout(roll,lottery.speed);
			}
			return false;
		}

		var click=false;

		window.onload=function(){

			// 活动规则弹窗
			$(".laborday-rule").click(function(){
				$("#rule").show();
			});
			// 签到领取2元
			$(".sign-btn-login,.sign-btn-error").click(function(){
				$("#voucher").show();
			});
			// 兑换红包
			$(".exchange-btn-login,.exchange-btn-error").click(function(){
				$("#exchange").show();
			});
			// 抽奖的弹窗
			$(".lottery-error").click(function(){
				$("#prize").show();
			});
			$(".pop-btn-1,.pop-mask,.pop-btn-close,.pop-btn-redpacket").click(function(){
				$(".pop-layer").hide();
			});
			$(".pop-btn-2").click(function(){
				$(".pop-layer").hide();
				window.location.reload();
			});
			lottery.init('lottery');
			$(".lottery").click(function(){
				var lock    =   $(this).attr('data-lock');
				if( lock == 'stop'){
					return false;
				}
				$(this).attr('data-lock','stop');
				var token	=	'{{$token}}';
		//		var client	=	'{{$client}}';
				if (click) {
					return false;
				}else{
					lottery.speed=100;
					$.ajax({
						url      :"/activity/LabourDay/lottery",
						dataType :'json',
						type     :'post',
						data     : { _token:'{{csrf_token()}}'},
						success : function(json) {
							if(json.status == true){
								//添加奖品信息
								var prizeName   =   json.data.name;
								var prizeType   =   json.data.type;
								var activeIndex =   json.data.order_num;
								var prizeUseNum =   $('#lottery').attr('lottery_can_used');
								$(".pop-text4").show();
								lottery.lottery(prizeName,prizeType,activeIndex,prizeUseNum);
								roll();
								click=true;
								return false;
							}else{
								$("#prize").show();
								$(".lottery-success").hide();
								$(".lottery-error").show();
								$(".pop-text4").hide();
								$(".pop-pr").html(json.msg)
								click=true;
								return false;
							}
							$(this).attr('data-lock','start');
						},
						error : function(msg) {
							$("#prize").show();
							$(".lottery-success").hide();
		    				$(".lottery-error").show();
							$(".pop-text4").hide();
							$(".pop-pr").html('抽奖失败，请稍后重试！');
						}
					});

				}
			});
			@if( $lotteryStatus['data']['type'] =='notLogged')
                $("#user_not_login").click(function () {
				// 未登录状态
				$(".kill-pop-wrap-1").show();
			})
			$(".userLogin").click(function () {
		//		var  client     =   "{{ $client }}"
				if( client =='ios'){
					window.location.href = "objc:gotoLogin";;
					return false;
				}
				if (client =='android'){
					window.jiudouyu.login()
					return false;
				}
				window.location.href='/login';
			})
@endif

@if($signStatus['status'] == true)
			$(".sign-btn-sure").click(function () {
				var lock    =   $(this).attr('sign-data-lock');
				if( lock != 'start' ){
					return false
				}
				$(this).attr('sign-data-lock','stop');
				var token	=	'{{$token}}';
		//		var client	=	'{{$client}}';
				$(".sign-success").hide()
				$(".sign-error").hide()
				$.ajax({
					url      :"/activity/LabourDay/signIn",
					dataType :'json',
					type     :'post',
					data     : { _token:'{{csrf_token()}}'},
					success : function(json) {

						if(json.status == true){
							$("#"+json.data.new_sign_day).addClass('disable');
							$("#voucher .pop-text6").find('span').html(json.data.sign_num);
							$(".sign-success").show()
						}else{
							$("#voucher .pop-text4").html(json.msg);
							$(".sign-error").show()
						}
						$('.sign-btn-sure').attr('sign-data-lock','start');
						$("#voucher").show();

					},
					error : function(msg) {
                         $("#voucher").show();
                        $('.sign-btn-sure').attr('sign-data-lock','start');
                        $("#voucher .pop-text4").html('签到失败，请稍后重试!');
					}
				});
			})
			$(".page-layer-sign a").click(function () {
				window.location.reload();
			})
@endif

@if($exchangeStatus['status'] == true)
			//exchange-btn-sure
			$(".pop-btn-redpacket").click(function () {

				var lock    =   $(this).attr('exchange-data-lock');

				if( lock != 'start' ){
					return false
				}
				$(this).attr('exchange-data-lock','stop');

				var token	=	'{{$token}}';
		//		var client	=	'{{$client}}';
				$.ajax({
					url      :"/activity/LabourDay/exchange",
					dataType :'json',
					type     :'post',
					data     : { _token:'{{csrf_token()}}'},
					success : function(json) {

						if(json.status == true){
							$(".exchange-success").show();
							$(".exchange-title").hide();
							$("#exchange").show();
							$(".pop-btn-redpacket").attr('exchange-data-lock','start');
							return false
						}else{
							$(".sign-success").hide();
							$(".sign-error").show();
							$("#voucher").find(".pop-text4 ").html(json.msg);
							$("#voucher").show();
							$(".pop-btn-redpacket").attr('exchange-data-lock','start');
							return false
						}

					},
					error : function(msg) {
						$(".sign-success").hide();
    					$(".sign-error").show();
						$("#voucher").find(".pop-text4 ").html('签到失败,请稍后在尝试!');
						$("#voucher").show();
						$(".pop-btn-redpacket").attr('exchange-data-lock','start');
					}
				});

			})
			$(".page-layer-sign a").click(function () {
				window.location.reload();
			})
			$(".exchange-btn-sure").click(function(){
				$(".exchange-title").show();
				$(".exchange-success").hide();
				$('#exchange').show();
			})

@endif
			$('.investProject').click(function () {
		//		var  client     =   "{{ $client }}"
				var  projectId  =   $(this).attr("attr-project-id");

				if( !projectId ||projectId==0){
					return false;
				}
				if( client =='ios'){
					window.location.href="objc:toProjectDetail("+projectId+",1)";
					return false;
				}
				if (client =='android'){
					window.jiudouyu.fromNoviceActivity(projectId,1);
					return false;
				}
				window.location.href='/project/detail/'+projectId;

				return false

			})
		};
	</script>
@endsection

