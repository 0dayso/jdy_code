@extends('wap.common.wapBase')

@section('title', 'App新手活动')

@section('keywords', env('META_KEYWORD'))

@section('description', env('META_DESCRIPTION'))

@section('css')
    <meta name="format-detection" content="telephone=yes">
    <link rel="stylesheet" type="text/css" href="{{assetUrlByCdn('/static/weixin/css/novice.css')}}">
@endsection

@section('content')
    <div class="ann2promote-success">
        <div class="novice-success">
            @if(!empty($awardWord))
                @foreach($awardWord as $item)
                    <p>{{ $item }}</p>
                @endforeach
            @endif
        </div>
        <p>恭喜您成功领取<br>新手专享现金券</p>
        <p>已放入<span class="red">{{ \App\Tools\ToolStr::hidePhone($phone) }}</span>帐号</p>
        <a href="{{ $package }}" class="novice-btn">立即下载</a>
    </div>
@endsection






