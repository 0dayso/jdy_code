@extends('wap.common.wapBase')

@section('title', '九斗鱼合伙人计划')

@section('keywords', env('META_KEYWORD'))

@section('description', env('META_DESCRIPTION'))

@section('css')
    <meta name="format-detection" content="telephone=yes">
    <link rel="stylesheet" type="text/css" href="{{assetUrlByCdn ('/static/weixin/activity/partner3/css/partner.css') }}">
@endsection

@section('content')
    <article class="page-share-auto">
		<section class="partner-share-banner">
			<a href="/activity/rule" class="rule">活动规则</a>
			<time>活动时间：{{ date('Y.m.d', strtotime($shareConfig['START_DATE'])) }}~{{ date('Y.m.d', strtotime($shareConfig['END_DATE'])) }}</time>
		</section>
		<section class="partner-section-wrap">
			<h6 class="partner-tag">赚钱攻略</h6>
			<div class="partner-step">
				<ul class="partner-flex-box partner-box-align">
					<li><span>1.</span>
						分享<br>
						邀请链接
					</li>
					<li><span>2.</span>
						好友<br>
						注册投资
					</li>
					<li><span>3.</span>
						每天<br>
						收到佣金
					</li>
				</ul>
			</div>
			<a href="javascript:;" class="partner-btn-money" data-target="modul3">分享赚钱</a>
			<p class="partner-rate">每日佣金收益=全部好友在投本金×佣金收益率÷365<br>如有疑问？点击右上角 <a href="/activity/rule">活动规则</a></p>
		</section>
		<section class="partner-section-wrap">
			<h6 class="partner-tag partner-tag-length">邀请排行榜</h6>
			<table class="partner-inviation">
                @if(!empty($list))
                    @foreach($list as $k=>$v)
			    	<tr>
			    		<td width="10%">
			    		<img src="{{assetUrlByCdn('/static/weixin/activity/partner3/images/partner-pk-icon'.($k+1).'.png')}}"></td>
			    		<td width="15%">NO.{{ $k+1 }} </td>
			    		<td width="30%">{{ $v['phone'] }}</td>
			    		<td>已赚  ¥{{ number_format($v['interest'], 2) }}</td>
			    	</tr>
                    @endforeach
                @endif
			</table>
		</section>
		<section class="partner-totaly">
			<a href="/activity/partner1?from=app" style="color:#fff">累计佣金收益(元)：{{ isset($partner_info['interest']) ? number_format($partner_info['interest'], 2) : '0.00' }}<img src="{{assetUrlByCdn('/static/weixin/activity/partner3/images/partner-arrow-icon.png')}}" style="width: 0.325rem;"></a>
		</section>

		<!--邀请好友 浏览器打开提示弹窗 -->
		<div class="partner-share-layer" data-modul="modul3">
			<div class="partner-mask" data-toggle="mask" data-target="modul3"></div>
			<div class="partner-pop"><img src="{{assetUrlByCdn('/static/weixin/activity/partner3/images/partner-share-img.png')}}" class="partner-share-img"  data-toggle="mask" data-target="modul3"></div>
		</div>
    </article>




{{--@include('wap.common.sharejs')--}}
@include('wap.common.sharejs')
@endsection
@section('jsScript')
<script>
var evclick = "ontouchend" in window ? "touchend" : "click";
    // 显示弹窗
    $(document).on(evclick, '[data-target]',function(event){
        event.stopPropagation();
		var $this = $(this);
        var target = $this.attr("data-target");
		var $target = $("div[data-modul="+target+"]");
		$target.show();
    })

    // 关闭弹窗
	$(document).on(evclick, '[data-toggle="mask"]', function (event) {
        event.stopPropagation();
        var target = $(this).attr("data-target");
        $("div[data-modul="+target+"]").hide();
	 })
</script>
@endsection



