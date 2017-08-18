@extends('wap.common.wapBase')

@section('title', '九斗鱼合伙人计划')

@section('keywords', env('META_KEYWORD'))

@section('description', env('META_DESCRIPTION'))

@section('css')
    <meta name="format-detection" content="telephone=yes">
    <link rel="stylesheet" type="text/css" href="{{assetUrlByCdn('/static/weixin/activity/partner3/css/partner.css')}}">
@endsection

@section('content')
    <article class="partner-wrap">

		<section class="partner-nav partner-flex-box partner-box-align">
			<div class="partner-nav-content">
				<p>昨日佣金收益(元)</p>
				<h6>{{ isset($yesterday_interest) ? number_format($yesterday_interest, 2) : '0.00' }}</h6>
			</div>
		</section>

		<section class="partner-box-shadow partner-middle-margin clearfix bgcolor-white">
			<a href="/activity/partner?from=wap" class="partner-invest" data-touch="false">
				<i class="partner-icon-friend"></i>
				邀请好友说明
				<span class="partner-icon-arrow"></span>
			</a>
		</section>
		<section class="partner-box-shadow partner-middle-margin partner-pad-bot clearfix bgcolor-white">
			<div class="partner-code-wrap"><i class="partner-icon-vcode"></i>我的邀请码<span class="partner-my-phone">{{ $invite_code or null }}</span></div>
			<div class="partner-code-box">
				<img src="{{ $qr_code }}">
			</div>
		</section>
		<section class="partner-middle-margin clearfix bgcolor-white partner-main-nav">

			<a>
				<span>累计佣金收益(元)</span>
				<strong>{{ isset($interest) ? number_format($interest, 2) : '0.00' }}</strong>
			</a>
			<a href="/activity/partner2" data-touch="false">
				<span>佣金收益排名</span>
				<strong>
					@if(isset($interest_sort) && $interest_sort > 0)
						{{ $interest_sort }}名
					@else
						暂无排名
					@endif
						</strong>
				<img src="{{assetUrlByCdn('/static/weixin/activity/partner3/images/partner-icon-nav.png')}}">
			</a>
			<a>
				<span>好友在投本金(元)</span>
				<strong>{{ isset($yesterday_cash) ? number_format($yesterday_cash, 2) : '0.00' }}</strong>
			</a>

			@if(isset($invite_num) && $invite_num>0)
				<a href="/activity/partner3" data-touch="false">
					<span>我邀请的好友</span>
					<strong>{{ number_format($invite_num) }}</strong>
					<img src="{{assetUrlByCdn('/static/weixin/activity/partner3/images/partner-icon-nav.png')}}">
				</a>
			@else
				<a href="javascript:;">
					<span>我邀请的好友</span>
					<strong>0</strong>
				</a>
			@endif

			<a>
				<span>佣金余额(元)</span>
				<strong>{{ isset($cash) ? number_format($cash, 2) : '0.00' }}</strong>

			</a>

			@if( !empty($rate_list) )
				<a href="javascript:;" data-target="modul2" data-touch="false">
					<span>佣金收益率</span>
					<strong>{{ $rate or '1.0' }}%
						{{--
                                            @if( $rate_interest )<i>+{{ number_format($rate_interest, 1) }}%</i>@endif
                        --}}
						<i>+{{ count($rate_list) }}张加息券</i>
					</strong>
					<img src="{{assetUrlByCdn('/static/weixin/activity/partner3/images/partner-icon-nav.png')}}">
				</a>
			@else
				<a href="javascript:;"><span>佣金收益率</span><strong>{{ $rate or '1.0' }}%</strong></a>
			@endif

		</section>
		<div class="partner-btn-wrap partner-flex-box partner-box-align partner-box-pack">
			@if( !isset($cash) || $cash < 1 )
				<a href="javascript:;" class="partner-btn partner-btn-disabled">转出佣金</a>
			@else
				<a href="javascript:;" class="partner-btn" data-target="modul1">转出佣金</a>
			@endif
			<a href="javascript:;" class="partner-btn" data-target="modul3">邀请好友</a>
		</div>
    </article>

     <!-- 加息券弹窗 -->
	 @if( isset($rate_interest) && empty($rate_interest) && isset($rate_list) && !empty($rate_list) )
		 <div class="partner-coupon-layer"  data-modul="modul2" style="display: none;">
			 <div class="partner-mask"></div>
			 <div class="partner-pop">
				 <div data-target="modul2" data-toggle="mask" class="partner-pop-head partner-flex-box partner-box-align partner-box-pack">
					 <h6>我的加息券</h6>
					 <a href="javascript:;">X</a>
				 </div>
				 <div class="partner-pop-body">
					 @foreach( $rate_list as $item )
						 <div class="partner-coupon clearfix">
							 <div class="partner-coupon-name"><p>加息券</p></div>
							 <div class="partner-coupon-details">
								 <form name="form" method="post" action="/activity/partner1/doUseRate">
								 	 <div class="details-date partner-flex-box partner-box-align partner-box-pack">
										 <time>有效期至{{ \App\Tools\ToolTime::getDate($item['use_expire_time']) }}</time>
										 <input class="button" type="submit" name="submit" value="使用">
									 </div>
								 	 <div class="details-day"><span>{{ $item['rate'] }}%</span><i>连续加息{{ $item['days'] }}天</i></div>
									 <input type="hidden" name="id" value="{{ $item['id'] }}">
									 <input type="hidden" name="_token" value="{{csrf_token()}}">
								 </form>
							 </div>
						 </div>
					 @endforeach
				 </div>
			 </div>
		 </div>
	 @endif

     <!-- 转出佣金弹窗 -->
	@if(isset($cash) && $cash >= 1)
		<div class="partner-comm-layer" data-modul="modul1" style="display: none;">
				<div class="partner-mask"></div>
				<div class="partner-pop">
					<div class="partner-pop-head" data-toggle="mask" data-target="modul1">
						<h6>转出佣金</h6>
						<a href="javascript:;" >X</a>
					</div>
					<form method="post" action="/ActivityPartner/doWithdraw" id="formDownload">
						<div class="partner-pop-body">
							<p>当前佣金余额<span class="partner-font-color partner-font-30px">{{ number_format($cash, 2) }}</span></p>
							<p>确认要转出{{ (int)$cash }}元到零钱计划吗？</p>
							<input type="hidden" name="cash" value="{{ (int)$cash }}">
							<input type="submit" name="submit" class="partner-pop-btn" data-target="modul-load" value="确定">
							<input type="hidden" name="csrf_token" value="{{ md5(rand().time()) }}">
							<input type="hidden" name="_token" value="{{csrf_token()}}">
						</div>
					</form>
				</div>
		 </div>
	@endif


	<!-- 提示弹窗 -->
	@if(Session::has('message'))
		<div class="partner-comm-layer" data-modul="modul4" style="display: block;">
			<div class="partner-mask"></div>
			<div class="partner-pop">
				<div class="partner-pop-head">
					<h6>提示信息</h6>
					<a href="javascript:;" data-toggle="mask" data-target="modul4">X</a>
				</div>
				<div class="partner-pop-body">
					<p class="partner-font-33px">{{ Session::get('message') }}</p>
					<a href="javascript:;" class="partner-pop-btn" data-toggle="mask" data-target="modul4">确定</a>
				</div>
			</div>
		</div>
	@endif

	<!--邀请好友 浏览器打开提示弹窗 -->
	<div class="partner-share-layer" data-modul="modul3">
		<div class="partner-mask" data-toggle="mask" data-target="modul3"></div>
		<div class="partner-pop"><img src="{{assetUrlByCdn('/static/weixin/activity/partner3/images/partner-share-img.png')}}" class="partner-share-img"  data-toggle="mask" data-target="modul3"></div>
	</div>


	<!--成功提示弹窗 -->
	{{--<div class="partner-comm-layer partner-success-layer" data-modul="modul5" style="display: block;">
		<div class="partner-mask"></div>
		<div class="partner-pop">
			<div class="partner-pop-head">
				<a href="javascript:;" data-toggle="mask" data-target="modul5">X</a>
			</div>
			<div class="partner-pop-body">
				<p class="partner-font-33px partner-font-color partner-success-txt">佣金转出成功</p>
				<a href="javascript:;" class="partner-pop-btn" data-toggle="mask" data-target="modul5">知道了</a>
			</div>
		</div>
	</div>--}}

	<!--转出中提示弹窗 -->
	<div class="partner-wait-layer" data-modul="modul-load" style="display: none;">
		<div class="partner-mask"></div>
		<div class="partner-pop partner-pop-fixed">
			<img src="{{assetUrlByCdn('/static/weixin/activity/partner3/images/partner-wait-img.png')}}" class="partner-wait-img">
			<p class="partner-tip-txt">转出中</p>
			<div class="loaded-wrap">
				<div class="loaded">
					<ul class="ball-pulse clearfix">
						<li></li>
						<li></li>
						<li></li>
					</ul>
				</div>
			</div>
		</div>
	</div>


@endsection

@include('wap.common.sharejs')

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
		//禁止鼠标穿透底层
		$target.css('pointer-events', 'none');
		setTimeout(function(){
			$target.css('pointer-events', 'auto');
		}, 400);


    })

    // 关闭弹窗
	$(document).on(evclick, '[data-toggle="mask"]', function (event) {
        event.stopPropagation();
        var target = $(this).attr("data-target");
        $("div[data-modul="+target+"]").hide();

		//禁止鼠标穿透底层
		$('[data-touch="false"]').css('pointer-events', 'none');
		setTimeout(function(){
			$('[data-touch="false"]').css('pointer-events', 'auto');
		}, 400);
	 })

	//自动关闭提示窗
	@if(Session::has('message'))

		clearTimeout(timer);
		var timer = window.setTimeout(function(){
				$("div[data-modul='modul4']").hide();
			},3000);
	@endif

    </script>
@endsection



