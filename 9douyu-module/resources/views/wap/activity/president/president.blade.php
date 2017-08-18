@extends('wap.common.wapBase')

@section('title', '晋升中关村互联网金融行业协会副会长单位')

@section('keywords', env('META_KEYWORD'))

@section('description', env('META_DESCRIPTION'))

@section('css')
<meta name="format-detection" content="telephone=yes">
<style type="text/css">
	body{background-color: #f2f2f3; }
	.pre-wrap{ position: relative;}
	.pre-btn{ display: block; width: 4.35rem; height: 1.2rem; border: 1px solid #45668a; background:#ffffff; color: #45668a; font-size: 0.65rem; text-align: center; line-height: 1.2rem; border-radius: 1rem; position:absolute; left: 50%; margin-left: -2.175rem; top:32rem;}
	.mt-1px{ margin-top:-1px;}
</style>
@endsection

@section('content')
<div class="pre-wrap">
 <img src="{{assetUrlByCdn('/static/weixin/activity/president/images/banner.jpg')}}" class="img">
 <img src="{{assetUrlByCdn('/static/weixin/activity/president/images/img1.png')}}" class="img">
 <img src="{{assetUrlByCdn('/static/weixin/activity/president/images/img2.png')}}" class="img">
 <img src="{{assetUrlByCdn('/static/weixin/activity/president/images/img3.png')}}" class="img mt-1px">
 <img src="{{assetUrlByCdn('/static/weixin/activity/president/images/img4.png')}}" class="img mt-1px">
 <img src="{{assetUrlByCdn('/static/weixin/activity/president/images/img5.png')}}" class="img mt-1px">
 <a href="http://stock.jrj.com.cn/2016/12/27122621892512.shtml" class="pre-btn">媒体报道</a>
</div>
@endsection

@section('footer')

@endsection

@section('jsScript')


@endsection