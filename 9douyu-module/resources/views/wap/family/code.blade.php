@extends('wap.common.wapBase')

@section('title', '家庭账户')

@section('css')
    <meta name="format-detection" content="telephone=yes">
    <link rel="stylesheet" type="text/css" href="{{ assetUrlByCdn('/static/weixin/css/familyAccount.css') }}">
    <style>
	    body{background-color: #dcf5fa;}
	</style>
@endsection

@section('content')
	<div class="family-logo"></div>
	<section class="family-code">
		<form action="/family/register" method="post" id="registerForm">
			<div class="family-input1">
				<input type="text" class="family-input w50"  name="code" placeholder="请输入短信验证码" />
				<input type="hidden" name="phone" value="{{ $phone }}" id="phone"/>
				<input type="hidden" name="returnUrl" value="{{ $returnUrl }}" id="returnUrl"/>
				<input type="hidden" name="returnSuccessUrl" value="{{ $returnUrl }}?isbattle=on" id="returnSuccessUrl"/>
				@if($phoneStatus=='failure')
					<span><input type="button" class="family-sub"  disabled="true" default-value="获取验证码" value="获取验证码"/></span>
				@else
					<span><input type="button" class="family-sub"  id="code" default-value="获取验证码" value="获取验证码"/></span>
				@endif
			</div>
			<div class="family-input1">
				<input type="password" class="family-input"  name="password" placeholder="请设置登录密码" />
			</div>
			@if(Session::has('errors'))
			<p class="family-tip">{{ Session::get('errors') }}</p>
			@endif
			<input type="submit" class="family-btn yellow1" value="家庭账户投资加息4%，邀请人享加息2%" id="family-btn" onclick="_czc.push(['_trackEvent','{{ $channel }}家庭注册页','{{ $channel }}领取1W']);"/>
			<input type="hidden" name="_token" value="{{csrf_token()}}">
		</form>
	</section>
	<img src="{{ assetUrlByCdn('/static/weixin/images/topic/family-img12.png') }}" alt="" class="img" id="family-bm">
	@if($isLogin)
	<!-- 弹窗 -->
		<div class="family-alert" id="family-alert" style="display: block;">
			<div class="family-mask" id="family-mask"></div>
			<div class="family-box">
				<p class="family-content">您的50000元家庭理财金已到账！</p>
				<a href="{{ $downLink }}" class="family-down" onclick="_czc.push(['_trackEvent','{{ $channel }}家庭注册页','{{ $channel }}下载APP']);">下载app立即使用</a>
			</div>
		</div>
	@endif
@endsection

@section('jsScript')
	<script src="{{ assetUrlByCdn('/static/js/pc2/codeCheck.js') }}"></script>
	<script src="{{ assetUrlByCdn('/static/js/pc2/sendCode.js') }}"></script>
	<script type="text/javascript">
		(function($){

			var timeout={{ $leftTime }}, maxTimeout = {{ Config::get('phone.TIMEOUT') }};
			var ishttps = 'https:' == document.location.protocol ? true: false;
	        var webUrl = "{{ env('MODULE_URL') }}/common/sendCode";
	        if(ishttps){              
	        	webUrl = "{{ env('MODULE_URL') }}/common/sendCode";
	        }
	        $.bindSendCode({type: 'activate', autoPhone: false, timeout: timeout, maxTimeout: maxTimeout, url: webUrl });

			//输入或者失去焦点判断
			$("input[name=code]").on({
				keyup: function(){
					if(!$.trim($(this).val()) == '') {
						$(".register-new-btn").removeClass("phone-disabled");
					} else {
						$(".register-new-btn").addClass("phone-disabled");
					}
				},
				blur: function() {
					$(this).keyup();
				}
			});

			$("form").submit(function(){
				if($.trim($("input[name=code]").val()) == '') return false;
			});

		})(jQuery);


		if(screen.height>660){
			$("#family-bm").addClass('family-bm');
		}else if(screen.height>560){
			$("#family-bm").addClass('family-bm1');
		}
	</script>
@endsection



