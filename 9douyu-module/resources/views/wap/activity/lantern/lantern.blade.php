@extends('wap.common.wapBase')

@section('title', '情到深处乐宵遥')

@section('keywords', env('META_KEYWORD'))

@section('description', env('META_DESCRIPTION'))

@section('css')
<meta name="format-detection" content="telephone=yes">
<link rel="stylesheet" type="text/css" href="{{ assetUrlByCdn('/static/weixin/activity/Lantern/css/index.css') }}">

@endsection

@section('content')
	<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="la-banner">
	<p>{{$time_format['start_time']}}-{{$time_format['end_short_time']}}</p>
</div>
<ul class="la-deng">
	@foreach($riddles_content as $key=>$val)
		<li data-key="{{$key}}" data-guess="{{$val['is_guess']}}"><img src="{{ assetUrlByCdn('/static/weixin/activity/Lantern/images/deng'.$key.'.png') }}" class="img la-deng1"></li>
	@endforeach
	{{--<li><img src="{{ assetUrlByCdn('/static/weixin/activity/Lantern/images/deng1.png') }}" class="img la-deng1"></li>
	<li><img src="{{ assetUrlByCdn('/static/weixin/activity/Lantern/images/deng2.png') }}" class="img la-deng1"></li>
	<li><img src="{{ assetUrlByCdn('/static/weixin/activity/Lantern/images/deng3.png') }}" class="img la-deng1"></li>
	<li><img src="{{ assetUrlByCdn('/static/weixin/activity/Lantern/images/deng4.png') }}" class="img la-deng1"></li>
	<li><img src="{{ assetUrlByCdn('/static/weixin/activity/Lantern/images/deng5.png') }}" class="img la-deng1"></li>
	<li><img src="{{ assetUrlByCdn('/static/weixin/activity/Lantern/images/deng6.png') }}" class="img la-deng1"></li>--}}
</ul>
	@foreach($project_list as $key=>$project)
		<?php if($key=='jax'){$image='title1';}else{$image='title';}?>
		<div class="la-box">
			<img src="{{ assetUrlByCdn('/static/weixin/activity/Lantern/images/'.$image.'.png') }}" class="img la-title">
			<table>
				<tr>
					<td colspan="3">{{$project['invest_time_note']}} {{$project['id']}}</td>
				</tr>
				<tr>
					<td width="33%">{{(float)$project['profit_percentage']}}%</td>
					<td width="30%">{{$project['invest_time_note']}}</td>
					<td>{{\App\Tools\ToolMoney::moneyFormat($project['left_amount'])}}元</td>
				</tr>
				<tr>
					<td>借款利率</td>
					<td>期限</td>
					<td>剩余可投</td>
				</tr>
			</table>
			@if ( $project['status'] == \App\Http\Dbs\Project\ProjectDb::STATUS_FINISHED )
				<a href="javascript:;" class="la-btn end">还款中</a>
			@elseif ( $project['status'] == \App\Http\Dbs\Project\ProjectDb::STATUS_REFUNDING )
				<a href="javascript:;" class="la-btn disable">已售罄</a>
				<td rowspan="2"><span class="lantern-btn repay"></span></td>
			@elseif ( $project['status'] == \App\Http\Dbs\Project\ProjectDb::STATUS_INVESTING && $project['publish_at'] >= \App\Tools\ToolTime::dbNow() )
				<a href="javascript:;" class="la-btn">敬请期待</a>
			@else
				<a href="javascript:;" class="la-btn investProject" attr-data-id="{{$project['id']}}">立即出借</a>
			@endif
		</div>

	@endforeach
{{--<div class="la-box">
    <img src="{{ assetUrlByCdn('/static/weixin/activity/Lantern/images/title.png') }}" class="img la-title">
	<table>
		<tr>
			<td colspan="3">29天3323</td>
		</tr>
		<tr>
			<td width="33%">9%</td>
			<td width="30%">29天</td>
			<td>1，914，608元</td>
		</tr>
		<tr>
			<td>借款利率</td>
			<td>期限</td>
			<td>剩余可投</td>
		</tr>
	</table>
	<a href="#" class="la-btn">立即出借</a>
</div>

<div class="la-box">
    <img src="{{ assetUrlByCdn('/static/weixin/activity/Lantern/images/title1.png') }}" class="img la-title">
	<table>
		<tr>
			<td colspan="3">29天3323</td>
		</tr>
		<tr>
			<td width="33%">9%</td>
			<td width="30%">29天</td>
			<td>1，914，608元</td>
		</tr>
		<tr>
			<td>借款利率</td>
			<td>期限</td>
			<td>剩余可投</td>
		</tr>
	</table>
	<a href="#" class="la-btn end">还款中</a>
</div>

<div class="la-box">
	<img src="{{ assetUrlByCdn('/static/weixin/activity/Lantern/images/title.png') }}" class="img la-title">
	<table>
		<tr>
			<td colspan="3">29天3323</td>
		</tr>
		<tr>
			<td width="33%">9%</td>
			<td width="30%">29天</td>
			<td>1，914，608元</td>
		</tr>
		<tr>
			<td>借款利率</td>
			<td>期限</td>
			<td>剩余可投</td>
		</tr>
	</table>
	<a href="#" class="la-btn disable">已售罄</a>
</div>--}}
<img src="{{ assetUrlByCdn('/static/weixin/activity/Lantern/images/la-title.png') }}" class="img la-title1">
<p class="la-title2">活动期间累计投资定期金额排名前5名可获得对应奖品</p>
<img src="{{ assetUrlByCdn('/static/weixin/activity/Lantern/images/prize.png') }}" class="img la-prize">

<div class="la-mg">
	<div class="la-list">
		<table>
			<thead>
				<tr>
					<td>排名</td>
					<td>手机号</td>
					<td>累计出借金额</td>
				</tr>
			</thead>
			<tbody>
			@foreach($invest_rank as $key=>$value)
				<tr>
					<td width="32%">第{{$key+1}}名</td>
					<td width="33%">{{$value['phone']}}</td>
					<td >{{$value['total']}}元</td>
				</tr>
			@endforeach
			</tbody>
		</table>

	</div>
	<p class="la-text2">*投资数据实时更新</p>
</div>


<div class="la-rules">
	<h5 class="antwo-sum">活动规则</h5>
	<p>1.活动时间为：{{$time_format['start_time']}}-{{$time_format['end_short_time']}}</p>
	<p>2.活动期间内，参与猜灯谜活动，猜对灯谜即可获得奖励；</p>
	<p>3.活动期间内，累计投资定期金额排名前5名可获得对应奖品；如出现并列的累计出借金额，以最后一笔出借金额的先后顺序择先选取；</p>
	<p>4.参与领取奖品者，活动期间提现金额≥10000元，取消其领奖资格；</p>
	<p>5.活动所得奖品以实物形式发放，将在2017年3月15日之前，与您沟通联系确定发放奖品。如联系用户无回应，视为自动放弃活动奖励;</p>
	<p>6.活动期间如有任何疑问请致电九斗鱼官方客服：400-6686-568，或登录九斗鱼咨询在线客服；</p>

</div>

<!--登录弹窗 -->
<div class="pop-layer" id="pop-layer">
    <div class="pop-mask"></div>
    <div class="pop">
        <div class="pop-title">
	        <img src="{{ assetUrlByCdn('/static/weixin/activity/Lantern/images/text.png') }}" class="img la-text">
            <a href="javascript:;" class="pop-close"><img src="{{ assetUrlByCdn('/static/weixin/activity/Lantern/images/close.png') }}" class="img"></a>
        </div>
        <div class="pop-content">
            <a  href="/login" class="la-btn1" id="userLogin">登录</a>
        </div>
    </div>
</div>
<!--提示弹窗 -->
<div class="pop-layer" id="pop-tip">
    <div class="pop-mask"></div>
    <div class="pop">
        <div class="pop-title">
            <div class="img la-text tip-text" style="height:156px;font-size:0.9rem;text-align:center;line-height:156px;color:#fff;"></div>
            <a href="javascript:;" class="pop-close"><img src="{{ assetUrlByCdn('/static/weixin/activity/Lantern/images/close.png') }}" class="img"></a>
        </div>
    </div>
</div>


<!-- 换一个试试 -->

<div class="pop-layer"  id="pop-layer1">
    <div class="pop-mask"></div>
    <div class="pop">
        <div class="pop-title">
	        <img src="{{ assetUrlByCdn('/static/weixin/activity/Lantern/images/text1.png') }}" class="img la-text1">
            <a href="javascript:;" class="pop-close"><img src="{{ assetUrlByCdn('/static/weixin/activity/Lantern/images/close.png') }}" class="img"></a>
        </div>
        <div class="pop-content">
            <a href="javascript:;" class="la-btn1 la-btn-1">知道啦</a>
        </div>
    </div>
</div>

<!-- 输入灯谜弹窗 -->
<div class="pop-layer" id="pop-layer2" >
    <div class="pop-mask"></div>
    <div class="pop pop-1">
        <div class="pop-title">
            <a href="javascript:;" class="pop-close"><img src="{{ assetUrlByCdn('/static/weixin/activity/Lantern/images/close.png') }}" class="img"></a>
        </div>
        <div class="pop-content">
			<form method="post" action="/activity/lantern/doGuessRiddles" id="guess_riddles" onkeydown="if(event.keyCode==13){return false;}">
			<input type="hidden" name="_token" value="{{ csrf_token() }}">
			<input value="" type="hidden" name="user_id">
			<input value="" type="hidden" name="riddles_id">
			<input value="" type="hidden" name="type">
			<input value="" type="hidden" name="activity_key">
        	<p class="pop-2"><input type="text" name="answer" /></p>
<!--             <a href="#" class="la-btn1">提交</a>
 -->        <input value="提交" id="answer-btn" type="button" class="la-btn1">			</form>
        </div>
    </div>
</div>


<!-- 答错了 -->
<div class="pop-layer"  id="pop-layer3">
    <div class="pop-mask"></div>
    <div class="pop pop-3">
        <div class="pop-title">
            <a href="javascript:;" class="pop-close"><img src="{{ assetUrlByCdn('/static/weixin/activity/Lantern/images/close.png') }}" class="img"></a>
        </div>
        <div class="pop-content">
            <a href="javascript:;" class="la-btn1" id="guess_again" style="margin-top:10.375rem;" >再猜一次</a>

        </div>
    </div>
</div>

<!-- 回答正确 -->
<div class="pop-layer"  id="pop-layer4">
    <div class="pop-mask"></div>
    <div class="pop pop-4">
        <div class="pop-title">
            <a href="javascript:;" class="pop-close"><img src="{{ assetUrlByCdn('/static/weixin/activity/Lantern/images/close.png') }}" class="img"></a>
        </div>
        <div class="pop-content">
			<p class="pop-c">恭喜你获得2%定期加息券一张</p>
			<p class="pop-c1">「APP -资产-我的优惠券查看」</p>
            <a href="javascript:;" class="la-btn1" id="invest">立即出借</a>

        </div>
    </div>
</div>


@endsection

@section('footer')

@endsection
<!-- 活动开始结束状态 -->
@if( $activityTime['start'] > time())
	@include('wap.common.activityStart')
@endif
<!-- End 活动开始结束状态 -->
@if($activityTime['end'] < time())
	@include('wap.common.activityEnd')
@endif

@section('jsScript')
<script type="text/javascript">
    var client = getCookie('JDY_CLIENT_COOKIES');
    if( client == '' || !client ){
         var client  =   '{{$client}}';
     }
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
  $(".la-deng li").click(function(){
	  var user_id = "{{$user_id}}";
	  var riddles_id = $(this).attr('data-key');
	  var is_guess = $(this).attr('data-guess');
	  var activity_key = "{{$activity_key}}";
	  var type = "{{$type}}";
	  $(".pop-layer").hide();

	  //未登录
	  if(user_id == 0){
		  $("#pop-layer").show();
		  return false;
	  }
	  //用户已经猜过此谜语
	  if(is_guess == 1){
		  $("#pop-layer1").show();
		  return false;
      }

      var activityStatus = "{{$activityStatus}}";
      if(activityStatus == 1){
          $(".tip-text").html('活动未开始');
          $("#pop-tip").show();
          return false;
		  $(this).addClass('disable').val('活动未开始');
	  }
	  if(activityStatus == 3){
          $(".tip-text").html('活动已结束');
          $("#pop-tip").show();
          return false;
		  $(this).addClass('disable').val('活动已结束');
	  }

      $("input[name='answer']").val('');
   	  $("#pop-layer2").show();
	  $("input[name='user_id']").val(user_id);
	  $("input[name='riddles_id']").val(riddles_id);
	  $("input[name='activity_key']").val(activity_key);
	  $("input[name='type']").val(type);
  });
  $("#answer-btn").click(function(){
      riddlesAnswer();
  });
  function riddlesAnswer(){
    $.ajax({
		  url: '/activity/lantern/doGuessRiddles',
		  dataType:'json',
		  type: 'post',
		  data: $("#guess_riddles").serialize(),
		  success: function(result) {
			  if(result.status == true){
				  $(".pop-layer").hide();
				  $("#pop-layer4").show();
			  }else{
				  //已经猜过了
				  if(result.code == 501){
					  $(".pop-layer").hide();
					  $("#pop-layer1").show();
				  }
				  //谜底猜错了
				  else if(result.code == 502){
					  $(".pop-layer").hide();
					  $("#pop-layer3").show();
                  }else{

                  $(".tip-text").html(result.msg);
                  $("#pop-tip").show();
                  return false;
                 }
			  }
		  },
		  error: function (result) {
			  alert("服务器连接失败");

		  }
	  });

  }
  $(document).keyup(function(event){
            if(event.keyCode ==13){
                riddlesAnswer();
            }
   });


	$("#guess_again").click(function(){
		$(".pop-layer").hide();
        $("input[name='answer']").val('');
		$("#pop-layer2").show();
	});
  $(".pop-close,.la-btn-1").click(function(){
  	$(".pop-layer").hide();
  })


	//用户登录
	$("#userLogin").click(function () {
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
    //定期投资
	$('.investProject').click(function () {

		var projectId = $(this).attr("attr-data-id");
		if (!projectId || projectId == 0) {
			return false;
		}
		if (client == 'ios') {
			window.location.href = "objc:certificationOrInvestment(" + projectId + ",1)";
			return false;
		}
		if (client == 'android') {
			window.jiudouyu.fromNoviceActivity(projectId, 1);
			return false;
		}
		window.location.href = '/project/detail/' + projectId;
	});
    //立即出借
    $("#invest").click(function(){
        if(client == 'ios'){
             window.location.href='objc:gotoInvest';
        }
        if (client == 'android') {
			window.jiudouyu.gotoInvest();
		}
		window.location.href = '/project/lists';

    })


	// document.body.addEventListener('touchstart', function (){});

</script>

@endsection
