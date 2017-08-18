@extends('wap.common.wapBase')

@section('title', 'App新手活动')

@section('keywords', env('META_KEYWORD'))

@section('description', env('META_DESCRIPTION'))

@section('css')
<meta name="format-detection" content="telephone=yes">
<link rel="stylesheet" type="text/css" href="{{ assetUrlByCdn('/static/weixin/css/novice.css') }}">
@endsection

@section('content')
	<div class="novice-list-title"></div>
	<div class="novice-list-title2">定期项目&nbsp;&nbsp;<small>•&nbsp;&nbsp;省心理财&nbsp;&nbsp;月月返息</small></div>

	@foreach($creditProject as $k => $project)
		<div class="novice-box">
			<a href="javascript:;" class="doInvestProject" attr-project="{{$project['id']}}">
				<table>
					<tr>
						<th colspan="3">{{$project['product_line_note']}} • {{$project['invest_time_note']}}</th>
					</tr>
					<tr class="mt">
						<td class="red left"><strong>{{(float)$project['profit_percentage']}}</strong><span>％</span></td>
						<td>{{ number_format($project['left_amount'])}}</td>
						@if($project['status'] == \App\Http\Dbs\Project\ProjectDb::STATUS_INVESTING && \App\Tools\ToolTime::getUnixTime($project['publish_at'],'default') >= time())
						<td rowspan="2"><i class="novice-btn2">敬请期待</i></td>
						@elseif($project['status'] == \App\Http\Dbs\Project\ProjectDb::STATUS_INVESTING )
						<td rowspan="2"><i class="novice-btn2">立即出借</i></td>
						@else
						<td rowspan="2"><i class="novice-btn2">已售罄</i></td>
						@endif
					</tr>
					<tr class="mb">
						<td class="left"><small>借款利率</small></td>
						<td><small>剩余可投</small></td>
					</tr>
				</table>
			</a>
		</div>
	@endforeach
	<div class="novice-list">

		@foreach($moreProject as $k => $item)
			<div class="novice-box">
				<a href="javascript:;" class="doInvestProject" attr-project="{{$item['id']}}">
					<table>
						<tr>
							<th colspan="3">{{$item['product_line_note']}} • {{$item['invest_time_note']}}</th>
						</tr>
						<tr class="mt">
							<td class="red left"><strong>{{(float)$item['profit_percentage']}}</strong><span>％</span></td>
							<td>{{ number_format($item['left_amount'])}}</td>
							@if($item['status'] == \App\Http\Dbs\Project\ProjectDb::STATUS_INVESTING && \App\Tools\ToolTime::getUnixTime($project['publish_at'],'default') >= time())
								<td rowspan="2"><i class="novice-btn2">敬请期待</i></td>
							@elseif($item['status'] == \App\Http\Dbs\Project\ProjectDb::STATUS_INVESTING )
								<td rowspan="2"><i class="novice-btn2">立即出借</i></td>
							@else
								<td rowspan="2"><i class="novice-btn2">已售罄</i></td>
							@endif
						</tr>
						<tr class="mb">
							<td class="left"><small>借款利率</small></td>
							<td><small>剩余可投</small></td>
						</tr>
					</table>
				</a>
			</div>
		@endforeach
	</div>
	<div class="novice-more"><span></span>更多定期出借项目</div>
	<div class="novice-box">
	<a href="javascript:;" class="doCurrentProject">
		<table>
			<tr>
				<th colspan="3">{{$currentProject['name']}}<ins>一元起投  {{$currentProject['note']}}</ins></th>
			</tr>
			<tr class="mt">
			
				<td class="red left"><strong>{{(float)$currentProject['rate']}}</strong><span>％</span></td>
				<td>{{number_format($currentProject['invested_amount'])}}</td>
				<td rowspan="2"><i class="novice-btn2">{{$currentProject['project_type_note']}}</i></td>
			</tr>
			<tr class="mb">
				<td class="left"><small>借款利率</small></td>
				<td><small>剩余可投</small></td>
			</tr>
		</table>
		</a>
	</div>
	<div class="novice-title3">新手红包</div>	
	<div class="novice-coupon-box">
		<span>8<br><big>元</big></span>
		<span>20<br><big>元</big></span>
		<span>60<br><big>元</big></span>
		<span>200<br><big>元</big></span>
		<span>2％<br><small>加息券</small></span>
		<span>4％<br><small>加息券</small></span>
	</div>
@endsection

@section('jsScript')
<script type="text/javascript">
    (function($){
        $(document).ready(function(){
        	var tag = 1;
        	$(".novice-more").click(function() {
        		if(tag){
	        		$(this).addClass('up');
	        		$(".novice-list").show();
	        		tag = 0;
        		}else{
        			$(this).removeClass('up');
	        		$(".novice-list").hide();
	        		tag = 1;
        		}
        	});

			client = "{{$client}}";

			currentId= "{{$currentProject['id']}}";

			if(client == 'ios'){
				$("body").delegate(".doCurrentProject",'click',function () {
					window.location.href="objc:certificationOrInvestment("+currentId+",2)";
				})
				$("body").delegate(".doInvestProject",'click',function () {
					projectId = $(this).attr('attr-project');
					//跳转至定期
					window.location.href="objc:certificationOrInvestment("+projectId+",1)";
				})
			} else if(client == 'android'){

				$("body").delegate(".doCurrentProject",'click',function () {
					//零钱计划
					window.jiudouyu.fromNoviceActivity(currentId,2);
				});
				$("body").delegate(".doInvestProject",'click',function () {
					projectId = $(this).attr('attr-project');
					//跳转至定期
					window.jiudouyu.fromNoviceActivity(projectId,1);
				});
			} else{
				$("body").delegate(".doCurrentProject",'click',function () {
					//调整到登录
					window.location.href='/project/current/detail';
				});
				$("body").delegate(".doInvestProject",'click',function () {
					projectId = $(this).attr('attr-project');
					//跳转至定期
					window.location.href='/project/detail/'+projectId;
				});

			}
    	})
    })(jQuery);
</script>
@endsection


