@extends('pc.common.layout')

@section('title', '盛世华诞，一路向钱')

@section('keywords', env('META_KEYWORD'))

@section('description', env('META_DESCRIPTION'))

@section('css')
    <meta name="format-detection" content="telephone=yes">

@endsection
@section('content')
    <link rel="stylesheet" type="text/css" href="{{assetUrlByCdn('static/activity/national/css/national.css')}}">
    <div class="nat-wrap">
        <div class="wrap">
            <div class="nat-date">9.30-10.8</div>
            <div class="nat-rule">
                <p>连续每日签到<br>赚取对应<span>现金奖励</span></p>
                <p class="middle">活动期间<br>抢限时加息<span>2%</span></p>
                <p>满足要求即可抽奖<br><span>100%中奖！</span></p>
            </div>
            <div class="nat-img">
                <img src="{{assetUrlByCdn('/static/activity/national/images/erweima.png')}}" alt="盛世华诞，一路向钱" width="164" height="164">
                <p>扫一扫二维码<br>进入活动页面<br><small>（本活动仅在手机端进行）</small></p>

            </div>
        </div>
    </div>
@endsection


