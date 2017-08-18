@extends('pc.common.layout')

@section('title', '春风送礼 1月期1％加息')

@section('keywords', env('META_KEYWORD'))

@section('description', env('META_DESCRIPTION'))

@section('csspage')
    <meta name="format-detection" content="telephone=yes">
    <style>
        body {
            background:#ec4d5c;
        }

        .page-banner {
            width: 100%;
            height: 600px;
            background:url('{{assetUrlByCdn('/static/activity/bonus/images/page-banner3.jpg')}}') center top no-repeat;
        }

        .page-code {
            height:830px;
            text-align: center;
            color: #fff;
            font-size: 36px;
            line-height: 154px;
            background:url('{{assetUrlByCdn('/static/activity/bonus/images/page-bg-v2.jpg')}}') center top no-repeat;
        }

        .page-code img {
            margin-top: 146px;
        }
        .page-time{width:1230px;margin:0 auto;padding:30px 32px 0 0;text-align:right;color:#fff;font-size: 22px;line-height: 1;}

    </style>
@endsection
@section('content')
    <div class="page-banner">
        <p class="page-time">{{date("Y年m月d日",$start)}}-{{date("Y年m月d日",$end)}}</p>
    </div>
    <div class="page-code">
        <img src="{{assetUrlByCdn('/static/activity/bonus/images/scan-code.gif')}}" width="288" height="" alt="扫一扫">
        <p>扫描二维码领取加息券</p>
    </div>
@endsection

@section('jsScript')

@endsection


