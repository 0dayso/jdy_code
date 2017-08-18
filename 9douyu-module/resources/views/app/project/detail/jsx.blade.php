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
	      				<p>到账后的本金和利息（整数部分）自动转入零钱计划，可转出后进行再次投资或提现。</p>
				    </td>
      			</tr>

      		</table>
      	</div>
    </div>

	<div class="t-coupon t-mb20px">
		<h3 class="t-coupon-1"><span class="t-icon1"></span>项目描述</h3>
		<div class="t-detail-3">
			<h3 class="t-detail-title">九省心寓意“安全无忧”</h3>
			<img src="{{assetUrlByCdn('/static/app/images/t-app-img7-new.png')}}" class="t-app-img7" />
			<p class="t-detail-4"><span>●</span>一笔资金购买一个债权组合。</p>
			<p class="t-detail-4"><span>●</span>包含多个项目，风险分散。</p>
			<p class="t-detail-4"><span>●</span>比一笔资金一次只能购买一个项目更安全。</p>
			<p class="t-detail-4"><span>●</span>自动分散投资，到期自动赎回，省心省力。</p>
		</div>
		<div class="t-detail-3">
			<h3 class="t-detail-title t-alignr">九省心意味“短期高收益"</h3>
			<img src="{{assetUrlByCdn('/static/app/images/t-app-img8.png')}}" class="t-app-img8" />
			<p class="t-detail-5">借款利率<span> <?php echo (float)$project["percentage_float_one"]?>% </span>，是余额宝的 <span> 2 </span>倍</p>
			<p class="t-detail-5">活期存款的<span> 20 </span>倍</p>
			<p class="t-detail-5">投资1万元，20天收益对比</p>
			<p class="t-detail-6">“20天出借计划能赚51元，享受浪漫双人电影余额宝只能有</p>
			<p class="t-detail-7">24元，勉强够一份KFC工作日午餐”</p>
		</div>
		<div class="t-detail-3">
			<h3 class="t-detail-title">
				<p>九省心资金安全保驾护航</p>
			</h3>
			<img src="{{assetUrlByCdn('/static/app/images/t-app-img9.png')}}" class="t-app-img9" />
			<dl class="t-detail-9">
				<dt><span>●</span>第一重：</dt>
				<dd>精选优质债权，最佳风险收益比构建债权组合，投资风险更低。</dd>
				<dt><span>●</span>第二重：</dt>
				<dd>东亚银行《资金管理协议》，千万风险准备金保障，查看《风险准备金账户》</dd>
			</dl>
		</div>
	</div>

</article>

@show
@section('jsPage')
<script type="text/javascript">
$(document.body).css("background","#f4f4f4");
</script> 

@show
