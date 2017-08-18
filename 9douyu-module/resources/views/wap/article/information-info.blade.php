@extends('wap.common.appBase')

@section('title', $currentArticle['title'])

@section('content')
    <article>
        <div class="t-info-article">
            <h3 class="t-article-title">{{ $currentArticle['title'] }}</h3>
            <h4 class="t-article-date">{{ date("Y-m-d", strtotime($currentArticle['publish_time'])) }}<span>|</span> @if($currentArticle['type_id'] == 1)文章资讯 @else 媒体资讯 @endif </h4>
            <p class="t-info-art">{!! htmlspecialchars_decode($currentArticle['content']) !!}</p>
        </div>
    </article>
@endsection