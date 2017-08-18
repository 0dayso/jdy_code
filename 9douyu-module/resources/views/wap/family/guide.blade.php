@extends('wap.common.wapBase')

@section('title', '家庭账户')

@section('keywords', env('META_KEYWORD'))

@section('description', env('META_DESCRIPTION'))

@section('css')
    <meta name="format-detection" content="telephone=yes">
    <link rel="stylesheet" type="text/css" href="{{ assetUrlByCdn('/static/weixin/css/familyAccount.css') }}">
    <style>
       body{background-color: #f8f7fc;}
    </style>
@endsection

@section('content')
    <img src="{{ assetUrlByCdn('/static/weixin/images/topic/family-img07.png') }}" alt="" class="img">
    <img src="{{ assetUrlByCdn('/static/weixin/images/topic/family-img08.png') }}" alt="" class="img">
    <img src="{{ assetUrlByCdn('/static/weixin/images/topic/family-img09.png') }}" alt="" class="img">
    <img src="{{ assetUrlByCdn('/static/weixin/images/topic/family-img10.pn') }}g" alt="" class="img">
    <img src="{{ assetUrlByCdn('/static/weixin/images/topic/family-img11.png') }}" alt="" class="img">
    <section class="family-btn-ye family-mb">
        <a href="{{ $downLink }}" class="family-btn yellow" onclick="_czc.push(['_trackEvent','{{ $channel }}家庭首页','{{ $channel }}下载APP']);"><span>点击下载</span>家庭账户加息4%</a>
        <!-- <p class="family-company">星果时代信息技术有限公司</p> -->
    </section>
    @if($isLogin===false)
    <div class="family-bootm" id="family-input-phone-box1">
        <form action="/family/checkUniquePhone" method="post" id="registerForm">
            <span>
                <input type="text" placeholder="@if(Session::has('errors')){{ Session::get('errors') }}@else 输入手机号领取5万元@endif" class="family-input lh" name="phone" id='family-phone-text' value=""/>
            </span>
            <input type="submit" class="family-btn btn-small"  value="领 取" onclick="_czc.push(['_trackEvent','{{ $channel }}家庭首页','{{ $channel }}领取']);">
            <input type="hidden" name="_token" value="{{csrf_token()}}">
        </form>
    </div>
    @endif
    @if($channel=='dspfamily')
        <script type="text/javascript">
            var _py = _py || [];
            _py.push(['a', '6ws.6-.RpeYWJuUKsS4IKWwdXcKa0']);
            _py.push(['domain','stats.ipinyou.com']);
            _py.push(['e','']);
            -function(d) {
                var s = d.createElement('script'),
                        e = d.body.getElementsByTagName('script')[0]; e.parentNode.insertBefore(s, e),
                        f = 'https:' == location.protocol;
                s.src = (f ? 'https' : 'http') + '://'+(f?'fm.ipinyou.com':'fm.p0y.cn')+'/j/adv.js';
            }(document);
        </script>
        <noscript><img src="//stats.ipinyou.com/adv.gif?a=6ws.6-.RpeYWJuUKsS4IKWwdXcKa0&e=" style="display:none;"/></noscript>
    @endif
@endsection


