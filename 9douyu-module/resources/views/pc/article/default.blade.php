@extends('pc.common.layout')
@section('title',$info['title'])
@section('content')
    <div class="wrap">
        <div class="web-notice-detail-title">
            <a href="/">九斗鱼</a>><a href="{{ $url }}">{{ $info['category']['name'] }}</a>><span>正文</span>
        </div>

        <!-- 正文开始 -->
        {!! htmlspecialchars_decode($info['content']) !!}
        <!-- 正文结束 -->

    </div>
@endsection