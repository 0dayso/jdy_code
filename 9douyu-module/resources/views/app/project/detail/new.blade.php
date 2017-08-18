@include('wap.common.appBase')
@section('title')<title>九省心一月期详情</title>@show
@section('content')
	<link rel="stylesheet" type="text/css" href="{{ assetUrlByCdn('/static/weixin/css/wap.css') }}">
	<article>
		<div class="t-coupon">
			<h3 class="t-detail"><span class="t-icon1"></span>{{ $project["default_title"] }}</h3>
			<div class="t-detail-2">
				<table class="t-detail-1">
					<tr>
						<td width="36%">借款利率</td>
						<td>{{ $project["percentage_float_one"] }}%</td>
					</tr>
					<tr>
						<td>期限</td>
						<td>{{ $project["format_invest_time"] }}{{ $project["invest_time_unit"] }}</td>
					</tr>
					<tr>
						<td>预计到期日</td>
						<td>{{ $project["refund_end_time"] }}</td>
					</tr>
					<tr>
						<td>起购金额</td>
						<td>{{ $project["invest_min_cash"] }}元起投</td>
					</tr>
					<tr>
						<td>还款方式</td>
						<td>{{ $project["refund_type_text"] }}</td>
					</tr>
					<tr>
						<td>赎回</td>
						<td>
							<p>到期后本金和利息自动返还至账户余额，申请提现即可转入绑定的银行卡中</p>
						</td>
					</tr>

				</table>
			</div>
		</div>

		<div class="t-coupon">
			<h3 class="t-coupon-1"><span class="t-icon1"></span>项目描述</h3>
			<div class="t-coupon-2">
                @if( !empty($company['credit_list_info']) )
                    @foreach($company['credit_list_info'] as $credit_item)
				<table class="t-detail-1">
					<tr>
						<td width="36%">借款人姓名</td>
						<td> {{ \App\Tools\ToolStr::hidePhone( $credit_item['loan_username'], 3 ,3) }}</td>
					</tr>
					<tr>
						<td>借款人身份证</td>
						<td>{{ \App\Tools\ToolStr::hidePhone(  $credit_item['loan_user_identity'], 8, 4 ) }}</td>
					</tr>
					<tr>
						<td>借款金额</td>
						<td>{{ $credit_item['loan_amounts'] }} 元</td>
					</tr>
				</table>
                   @endforeach
               @endif
			</div>

		</div>
            @if ( !empty( $creditDetail['companyView']['risk_control'] ) )
		<div class="t-coupon">
			<h3 class="t-coupon-1"><span class="t-icon1"></span>风险控制</h3>
			<div class="t-coupon-2">
				<p class="t-detail-14">
					{!! $company['risk_control'] or '' !!}
				</p>
			</div>

		</div>
        @endif
	</article>

@show
@section('jsPage')
	<script type="text/javascript">
		$(document.body).css("background","#f4f4f4");
	</script>

@show
