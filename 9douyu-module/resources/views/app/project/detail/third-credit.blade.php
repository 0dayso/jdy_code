@include('wap.common.appBase')
@section('title')<title>借款人信息</title>@show
@section('content')
	<link rel="stylesheet" type="text/css" href="{{ assetUrlByCdn('/static/weixin/css/wap.css') }}">
	{{--借款人详情页要在新页面中打开--}}
	<article>
		<section class="w-box-show mt15px pd">
			<h3 class="w-title pb15px bb-1px mb20px pt15px"><img src="{{ assetUrlByCdn('/static/weixin/images/wap2/w-icon11.png')}}">借款人详情</h3>
			<table id="trade-records" class="wap2-table-1">
				<tr>
					<th width="">借款人姓名</th>
					<th width="">借款人身份证号</th>
					<th width="">借款金额（元）</th>
					<th width="">借款用途</th>
				</tr>
				@if( !empty($company['credit_list_info']) )
					@foreach($company['credit_list_info'] as $credit_item)
						<tr>
							<td>{{ $credit_item['realname'] }}</td>
							<td>{{ $credit_item['identity_card'] }}</td>
							<td>{{ number_format($credit_item['amount'],2) }}</td>
							<td>个人消费</td>
						</tr>
					@endforeach
				@endif
			</table>
		</section>
	</article>

@show
@section('jsPage')
	<script type="text/javascript">
		$(document.body).css("background","#f4f4f4");
	</script>

@show
