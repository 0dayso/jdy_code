@extends('pc.common.layout')

@section('title', '充值支付－九斗鱼')

@section('csspage')
<link rel="stylesheet" href="{{assetUrlByCdn('/static/css/style.css')}}" type="text/css" />
@endsection

@section('content')

	<div class="wrap">
	  <div class="jump-message">
	    
	    <present name="message">
	      <h1>充值失败，请重试。</h1>
			@if($msg)
			<p>失败原因：{{$msg}}</p>
			@endif
			<p>现在就去：<a href="/recharge/index">重新充值</a>，<a href="/user">我的账户</a> </p>
	      <span class="jump-error-icon"></span>
	    </present>
	    
	  </div>
   </div>

@endsection