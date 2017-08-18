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
		<section class="partner-list-head">
			<div class="partner-head-wrap">
				<div class="partner-head-img">
					<img src="{{assetUrlByCdn('/static/weixin/activity/partner3/images/partner-head-img.png')}}">
				</div>
				@if( !empty($user_info) )
					<div class="partner-user-info">{{ \App\Tools\ToolStr::hidePhone($user_info['phone']) }}&nbsp;&nbsp;{{ $user_info['real_name'] }}</div>
				@endif

				<div class="partner-user-detail">
					<span>佣金收益&nbsp;&nbsp;{{ isset($partner_info['interest']) ? number_format($partner_info['interest'], 2) : '0.00' }}</span>
					<span class="partner-line"></span>
					<span>我的排行&nbsp;&nbsp;
						@if( isset($partner_info['interest_sort']) && $partner_info['interest_sort'] >0 )
							{{ $partner_info['interest_sort'] }}
						@else
							--
						@endif
					</span>
				</div>

			</div>

		</section>

		<section>
			<ul class="partner-list">
				<!-- 前三名 数字颜色一致 -->
				@if( !empty($list) )
					@foreach( $list as $key => $item)
						<li class="partner-flex-box partner-box-align partner-box-pack">
							<p class="partner-list-pfirst">
								<i class="partner-list-num partner-font-color">{{ $key + 1 }}</i>
								累计佣金收益
								<span class="partner-font-color partner-font-26px">{{ number_format($item['interest'], 2) }}</span>
							</p>
							<p class="partner-list-plast">
								<i class="partner-font-color partner-font-normal">{{ $item['phone'] }}</i>
{{--
								<span class="partner-font-20px">(舒**)</span>
--}}
							</p>
						</li>
					@endforeach
				@endif
			</ul>
{{--
			<a href="javascript:;" class="partner-btn-more">加载更多</a>
--}}
		</section>

    </article>
	@include('wap.common.sharejs')

@endsection

@section('jsScript')
    <script>
    </script>
@endsection



