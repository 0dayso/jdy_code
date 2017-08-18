@extends('pc.common.layout')

@section('title', '情到深处乐宵遥')

@section('keywords', env('META_KEYWORD'))

@section('description', env('META_DESCRIPTION'))

@section('csspage')
    <meta name="format-detection" content="telephone=yes">
    <link rel="stylesheet" type="text/css" href="{{ assetUrlByCdn('/static/activity/lantern/css/lantern.css') }}">
@endsection
@section('content')
	<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="lantern-banner">
	<div class="wrap">
		<p>{{$time_format['start_time']}}-{{$time_format['end_short_time']}}</p>
	</div>
</div>

<!-- 灯谜 -->
<div class="wrap">
	<div class="lantern-guess"><span></span></div>
	<ul class="lantern-lantern">
		@foreach($riddles_content as $key=>$val)
			<li @if($key==2 || $key ==5) class="middle" @endif data-key="{{$key}}" data-guess="{{$val['is_guess']}}"><img src="{{ assetUrlByCdn('/static/activity/lantern/images/lantern-'.$key.'.png') }}" width="304" height="330"></li>
		@endforeach
		{{--<li><img src="{{ assetUrlByCdn('/static/activity/lantern/images/lantern-1.png') }}" width="304" height="330"></li>
		<li class="middle"><img src="{{ assetUrlByCdn('/static/activity/lantern/images/lantern-2.png') }}" width="304" height="330"></li>
		<li><img src="{{ assetUrlByCdn('/static/activity/lantern/images/lantern-3.png') }}" width="304" height="330"></li>
		<li><img src="{{ assetUrlByCdn('/static/activity/lantern/images/lantern-4.png') }}" width="304" height="330"></li>
		<li class="middle"><img src="{{ assetUrlByCdn('/static/activity/lantern/images/lantern-5.png') }}" width="304" height="330"></li>
		<li><img src="{{ assetUrlByCdn('/static/activity/lantern/images/lantern-6.png') }}" width="304" height="330"></li>--}}
	</ul>

</div>
<!-- End 灯谜 -->

<!-- 项目 -->
	@foreach($project_list as $key=>$project)
		<?php if($key=='jax'){$image='jax';}else{$image='jsx';}?>
		<div class="lantern-project">
			<div class="wrap">
				<p class="pro-title"><img src="{{ assetUrlByCdn('/static/activity/lantern/images/'.$image.'.png') }}" width="233" height="76"></p>
				<a  target="_blank">
					<table>
						<tr>
							<th colspan="4">{{$project['invest_time_note']}} {{$project['id']}}</th>
						</tr>
						<tr>
							<td class="yellow" width="190"><big>{{(float)$project['profit_percentage']}}</big><em>%</em></td>
							<td width="90">{{$project['invest_time_note']}}</td>
							<td width="250">{{$project['refund_type_note']}}</td>
							<td width="210">{{\App\Tools\ToolMoney::moneyFormat($project['left_amount'])}}元</td>
							@if ( $project['status'] == \App\Http\Dbs\Project\ProjectDb::STATUS_FINISHED )
								<td rowspan="2"><span class="lantern-btn refund">还款中</span></td>
							@elseif ( $project['status'] == \App\Http\Dbs\Project\ProjectDb::STATUS_REFUNDING )
								<td rowspan="2"><span class="lantern-btn refund">还款中</span></td>
								{{--<td rowspan="2"><span class="lantern-btn repay"></span></td>--}}
							@elseif ( $project['status'] == \App\Http\Dbs\Project\ProjectDb::STATUS_INVESTING && $project['publish_at'] >= \App\Tools\ToolTime::dbNow() )
								<td rowspan="2"><span class="lantern-btn">敬请期待</span></td>
							@else
								<td rowspan="2"><span onclick="location='/project/detail/{{ $project['id'] }}';" class="lantern-btn">立即出借</span></td>
							@endif

						</tr>
						<tr>
							<td><small>借款利率</small></td>
							<td><small>期限</small></td>
							<td><small>还款方式</small></td>
							<td><small>剩余可投</small></td>
						</tr>
					</table>
				</a>
			</div>
		</div>
	@endforeach
	{{--
<div class="lantern-project">
	<div class="wrap">
		<p class="pro-title"><img src="{{ assetUrlByCdn('/static/activity/lantern/images/jsx.png') }}" width="233" height="76"></p>
		<a href="#" target="_blank">
			<table>
				<tr>
					<th colspan="4">29天3323</th>
				</tr>
				<tr>
					<td class="yellow" width="190"><big>9</big><em>%</em></td>
					<td width="76">29天</td>
					<td width="250">到期还本息</td>
					<td width="210">1，914，608元</td>
					<td rowspan="2"><span class="lantern-btn">立即出借</span></td>
				</tr>
				<tr>
					<td><small>借款利率</small></td>
					<td><small>期限</small></td>
					<td><small>还款方式</small></td>
					<td><small>剩余可投</small></td>
				</tr>
			</table>
		</a>
	</div>
</div>
<div class="lantern-project">
	<div class="wrap">
		<p class="pro-title"><img src="{{ assetUrlByCdn('/static/activity/lantern/images/jsx.png') }}" width="233" height="76"></p>
		<a href="#" target="_blank">
			<table>
				<tr>
					<th colspan="4">29天3323</th>
				</tr>
				<tr>
					<td class="yellow" width="190"><big>9</big><em>%</em></td>
					<td width="76">29天</td>
					<td width="250">到期还本息</td>
					<td width="210">1，914，608元</td>
					<td rowspan="2"><span class="lantern-btn refund">还款中</span></td>
				</tr>
				<tr>
					<td><small>借款利率</small></td>
					<td><small>期限</small></td>
					<td><small>还款方式</small></td>
					<td><small>剩余可投</small></td>
				</tr>
			</table>
		</a>
	</div>
</div>
<div class="lantern-project">
	<div class="wrap">
		<p class="pro-title"><img src="{{ assetUrlByCdn('/static/activity/lantern/images/jax.png') }}" width="233" height="76"></p>
		<a href="#" target="_blank">
			<table>
				<tr>
					<th colspan="4">29天3323</th>
				</tr>
				<tr>
					<td class="yellow" width="190"><big>9</big><em>%</em></td>
					<td width="76">29天</td>
					<td width="250">到期还本息</td>
					<td width="210">1，914，608元</td>
					<td rowspan="2"><span class="lantern-btn repay"></span></td>
				</tr>
				<tr>
					<td><small>借款利率</small></td>
					<td><small>期限</small></td>
					<td><small>还款方式</small></td>
					<td><small>剩余可投</small></td>
				</tr>
			</table>
		</a>
	</div>
</div>--}}
<!-- End 项目 -->

<!-- pk -->
<div class="wrap">
	<div class="lantern-pk">
		<img src="{{ assetUrlByCdn('/static/activity/lantern/images/prize-title.png') }}" width="388" height="156">
		<p>活动期间累计投资定期金额排名前5名可获得对应奖品</p>
		<img src="{{ assetUrlByCdn('/static/activity/lantern/images/prize.png') }}" width="1000" height="774">
	</div>
	<div class="lantern-rank">
		<table>
			<tr>
				<th width="210"><span class="title1">排名</span></th>
				<th width="460"><span class="title1">手机号</span></th>
				<th><span class="title2">累计出借金额</span></th>
			</tr>
			@foreach($invest_rank as $key=>$value)
				<tr>
					<td>第{{$key+1}}名</td>
					<td>{{$value['phone']}}</td>
					<td>{{$value['total']}}元 </td>
				</tr>
			@endforeach

		</table>
	</div>
	<div class="lantern-rank-tip">*投资数据实时更新</div class="lantern-rank-tip">
</div>
<!-- End pk -->


<div class="wrap">
	<div class="lantern-rule-title">活动规则</div>
	<div class="lantern-rule">
		<p>1.活动时间为：{{$time_format['start_time']}}-{{$time_format['end_middle_time']}}；</p>
		<p>2.活动期间内，参与猜灯谜活动，猜对灯谜即可获得奖励；</p>
		<p>3.活动期间内，累计投资定期金额排名前5名可获得对应奖品；如出现并列的累计出借金额，以最后一笔出借金额的先后顺序择先选取；</p>
		<p>4.参与领取奖品者，活动期间提现金额≥10000元，取消其领奖资格；</p>
		<p>5.活动所得奖品以实物形式发放，将在2017年3月15日之前，与您沟通联系确定发放奖品。如联系用户无回应，视为自动放弃活动奖励;</p>
		<p>6.活动期间如有任何疑问请致电九斗鱼官方客服：400-6686-568，或登录九斗鱼咨询在线客服。</p>
	</div>
</div>

<!-- 弹层 -->

<!-- 未登录 -->
<div class="pop-wrap" id="nologin">
	<div class="pop-mask"></div>
	<div class="lantern-pop nologin">
		<i class="pop-close"></i>
		<span class="nologin-txt"></span>
		<a href="/login" class="lantern-btn" target="_blank">登录</a>
	</div>
</div>
<!-- End 未登录 -->
<!-- 提示弹层 -->
<div class="pop-wrap" id="tip-notice">
	<div class="pop-mask"></div>
	<div class="lantern-pop nologin">
		<i class="pop-close"></i>
		<span class="nologin-txt tip-text" style="text-align:center;font-size:3rem;line-height:256px;background:none;color:#fff;"></span>
		<!--<a href="/login" class="lantern-btn" target="_blank">登录</a>-->
	</div>
</div>
<!-- End 提示弹层 -->

<!-- 灯谜已猜过 -->
<div class="pop-wrap" id="is_guess">
	<div class="pop-mask"></div>
	<div class="lantern-pop nologin">
		<i class="pop-close"></i>
		<span class="already-txt"></span>
		<a  class="lantern-btn" id="yes" target="_blank">知道啦</a>
	</div>
</div>
<!-- End 灯谜已猜过 -->

<!-- 输入灯谜答案 -->
<div class="pop-wrap" id="input">
	<div class="pop-mask"></div>
	<div class="lantern-pop input">
		<i class="pop-close"></i>
		<form method="post" action="/activity/lantern/doGuessRiddles" id="guess_riddles" onkeydown="if(event.keyCode==13){return false;}">
		<input type="hidden" name="_token" value="{{ csrf_token() }}">
		<input value="" type="hidden" name="user_id">
		<input value="" type="hidden" name="riddles_id">
		<input value="" type="hidden" name="type">
		<input value="" type="hidden" name="activity_key">
		<input type="text" class="lantern-pop-input" name="answer" autofocus="true">
		<input type="button" class="lantern-btn" id="answer-btn" value="提交">
		</form>
	</div>

</div>
<!-- End 输入灯谜答案 -->

<!-- 答对了 -->
<div class="pop-wrap" id="right">
	<div class="pop-mask"></div>
	<div class="lantern-pop right">
		<i class="pop-close"></i>
		<p>恭喜你获得2%定期加息券一张<br>「APP -资产-我的优惠券查看」</p>
		<a href="/project/index" class="lantern-btn" >立即出借</a>
	</div>
</div>
<!-- End 答对了 -->

<!-- 答错了 -->
<div class="pop-wrap" id="wrong">
	<div class="pop-mask"></div>
	<div class="lantern-pop wrong">
		<i class="pop-close"></i>
		<a class="lantern-btn" id="guess_again" target="_blank">再猜一次</a>
	</div>
</div>
<!-- End 答错了 -->
@endsection
<!-- 活动开始结束状态 -->
@if( $activityTime['start'] > time())
	@include('pc.common.activityStart')
@endif
<!-- End 活动开始结束状态 -->
@if($activityTime['end'] < time())
	@include('pc.common.activityEnd')
@endif
@section('jspage')
<script type="text/javascript">
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
(function($){
	$(function(){
		// 关闭按钮
		$(".pop-close,#yes").each(function(){
			$(this).click(function() {
				$(this).parent(".lantern-pop").parent(".pop-wrap").hide();
			});
		});

		$(".lantern-lantern>li").each(function() {
			var user_id = "{{$user_id}}";

			$(this).click(function() {

				var riddles_id = $(this).attr('data-key');
				var is_guess = $(this).attr('data-guess');
				var activity_key = "{{$activity_key}}";
				var type = "{{$type}}";

				$(".pop-wrap").hide();
				//未登录
				if(user_id == 0){
					$("#nologin").show();
					return false;
				}
				//用户已经猜过此谜语
				if(is_guess == 1){
					$("#is_guess").show();
					return false;
				}
                var activityStatus = "{{$activityStatus}}";
			    if(activityStatus == 1){
                    $(".tip-text").html('活动未开始');
                    $("#tip-notice").show();
                    return false;
			    }
                if(activityStatus == 3){

                    $(".tip-text").html('活动已结束');
                    $("#tip-notice").show();
                    return false;
                }

                $("input[name='answer']").val('');
				$("#input").show();
				$("input[name='user_id']").val(user_id);
				$("input[name='riddles_id']").val(riddles_id);
				$("input[name='activity_key']").val(activity_key);
				$("input[name='type']").val(type);
			});
		});
		$("#answer-btn").click(function(){
			//$("#guess_riddles").submit();
            riddlesAnswer();
		});

        function riddlesAnswer(){
            $.ajax({
				url: '/activity/lantern/doGuessRiddles',
				dataType:'json',
				//async: true,  //同步发送请求
				type: 'post',
				data: $("#guess_riddles").serialize(),
				success: function(result) {
					if(result.status == true){
						$(".pop-wrap").hide();
						$("#right").show();
					}else{
						//已经猜过了
						if(result.code == 501){
							$(".pop-wrap").hide();
							$("#is_guess").show();
						}
						//谜底猜错了
                        else if(result.code == 502){
							$(".pop-wrap").hide();
							$("#wrong").show();
						}else{
                            $(".tip-text").html(result.msg);
                            $("#tip-notice").show();
                            return false;
                        }
					}
				},
				error: function (result) {
					alert("连接服务器失败");

				}
			});

        }

       $(document).keyup(function(event){
            if(event.keyCode ==13){
                riddlesAnswer();
            }
       });

		$("#guess_again").click(function(){
            $(".pop-wrap").hide();
            $("input[name='answer']").val('');
			$("#input").show();
		});

	})
})(jQuery)

</script>

@endsection
<!-- 活动开始结束状态 -->





