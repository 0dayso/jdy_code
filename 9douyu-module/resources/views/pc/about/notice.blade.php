@extends('pc.common.layout')
@section('title', $title)
@section('keywords', $keywords)
@section('description', $description)
@section('content')
    @include('pc.about.common.menu')
    <div class="t-wrap t-ys">
        <h4>网站公告</h4>
        <h5>NOTICE</h5>
        <div class="t-ys-line"></div>
        <div class="clear"></div>
        <div class="web-notice-tab">
            <a href="/about/notice" @if($q == '')class="cur"@endif>网站公告</a>
            <a href="/about/notice?q=records"  @if($q == 'records')class="cur"@endif>还款公告</a>
        </div>
        <div class="web-notice-box">
            @if(!empty($list['list']))
            @foreach($list['list'] as $k => $article)
                <dl class="web-notice-main @if($countmax == $k) web-notice-last @endif" >
                    <dt>
                    <div>
                        <p><span>{{ date('Y',strtotime($article['publish_time'])) }}</span><br>{{ date('m-d',strtotime($article['publish_time'])) }}</p>
                    </div>
                    </dt>
                    <dd>
                        <i></i>
                        <a href="/article/{{$article['id']}}">{{ $article['title'] }}</a>
                    </dd>
                </dl>
            @endforeach
            @endif
            <div class="web-notice-page">
                <div class="web-page">
                    @include('scripts.paginate', ['paginate'=>$paginate])
                </div>
            </div>

        </div>
    </div>
@endsection