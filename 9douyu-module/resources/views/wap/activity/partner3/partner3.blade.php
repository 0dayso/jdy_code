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
					<span>我邀请的好友&nbsp;&nbsp;{{ $partner_info['invite_num'] or 0 }}人</span>
				</div>

			</div>

		</section>

		<section>
			<ul class="partner-list partner-friend-list">
				@if( !empty($invite_list) )
					@foreach($invite_list as $item)
						<li class="partner-flex-box partner-box-align">
							<div class="partner-friend-wrap">
								<div class="partner-friend-info"><span class="partner-font-color">{{ $item['real_name'] or null }}</span>
									<i class="partner-font-normal">（{{ $item['phone'] or null }}）</i></div>
								<div class="partner-flex-box partner-box-align partner-box-pack">
									<p class="partner-list-pfirst">在投本金<span>{{ number_format(($item['current_principal'] + $item['term_principal']), 2) }}</span></p>
									<p class="partner-list-plast"><strong class="partner-font-color">{{ $item['register_at'] }}</strong><span class="partner-font-color">注册</span></p>
								</div>
							</div>
						</li>
					@endforeach
				@endif

			</ul>
{{--
			<a href="javascript:;" class="partner-btn-more">下一页</a>
--}}
		</section>
		
    </article>
	@include('wap.common.sharejs')

@endsection


@section('jsScript')
    <script>
    </script>
@endsection



