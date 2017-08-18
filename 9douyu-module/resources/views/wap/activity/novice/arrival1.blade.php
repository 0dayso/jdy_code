@extends('wap.common.wapBase')

@section('title', '做个新懒人  收益不缺席')

@section('keywords', env('META_KEYWORD'))

@section('description', env('META_DESCRIPTION'))

@section('css')
    <meta name="format-detection" content="telephone=yes">
    <link rel="stylesheet" type="text/css" href="{{assetUrlByCdn('/static/weixin/css/novice3.1.css')}}">
   
@endsection

@section('content')
    <div class="app11-success">
    	<div class="app11-inner">
	        <p>恭喜你成功领取</p>
	        <p class="pop-red">888元现金券+3张加息券</p>
	        <p class="text1">已放入{{ \App\Tools\ToolStr::hidePhone($phone) }}账号</p>
        </div>
        <a href="{{ $package }}" class="app11-btn2">立即下载九斗鱼APP</a>
    </div>
     <p class="app11-bom">九斗鱼值得托付的互联网金融平台</p>
@endsection





