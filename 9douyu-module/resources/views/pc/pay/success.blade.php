@extends('pc.common.layout')

@section('title', '充值支付－九斗鱼')

@section('csspage')
<link rel="stylesheet" href="{{assetUrlByCdn('/static/css/style.css')}}" type="text/css" />
@endsection

@section('content')

	<div class="wrap">
	  <div class="jump-message">
	    
	    <present name="message">
	    	<h1 style="color:#00A9FD">充值订单提交成功</h1>
		    <p style="margin-top: 5px; color:#414141;">现在就去：<a href="/user" style="text-decoration: underline;">我的账户</a>，<a href="/project/index" style="text-decoration: underline;">我要出借</a> </p>
			<p style="color:#999; font-size: 12px; line-height: 16px; margin-top: 5px;">注意:充值结果由第三方支付公司返回，如遇繁忙情况，请待定5~15分钟返回结果。</p>
		    <span class="jump-success-icon"></span>
	    </present>
	    
	  </div>
   </div>

@endsection