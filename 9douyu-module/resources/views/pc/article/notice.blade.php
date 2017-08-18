@extends('pc.common.layout')
@section('title',$info['title'])
@section('content')
    <div class="wrap">
        <div class="web-notice-detail-title">
            <a href="/">九斗鱼</a>><a href="{{ $url }}">{{ $info['category']['name'] }}</a>><span>正文</span>
        </div>
        <div class="web-notice-detail-main">
            <div class="web-notice-left-title">
                <h1>{{ $info['title'] }}</h1>
                <div class="web-notice-time">发布时间：<span>{{ $info['publish_time'] }}</span>浏览量：<span>{{ intval($info['hits']) }}</span></div>
                <div class="web-notice-share">@include('pc.common.index.sharemore')</div>
            </div>
            <div class="clear"></div>
            <!-- 正文开始 -->
            {!! htmlspecialchars_decode($info['content']) !!}
            <!-- 正文结束 -->
        </div>

        <div class="web-notice-sidebar">
            <h2>更多消息</h2>
            <ul>
                @if(!empty($articleList))
                    @foreach($articleList as $a)
                        <li><a href="{{ sprintf('/article/%s',$a['id']) }}" title="{{ $a['title'] }}">{{ str_limit($a['title'], $limit=30, $end='...' ) }}</a></li>
                    @endforeach
                @endif
            </ul>
            <p><a href="{{ $url }}">查看更多</a></p>
        </div>

        <div class="clear"></div>
    </div>
@endsection

