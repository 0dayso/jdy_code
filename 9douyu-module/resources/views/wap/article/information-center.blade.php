@extends('wap.common.appBase')

@section('title', '资讯中心')

@section('content')
    <article id="new_list">
        @foreach($articleList as $key => $article)
            <div class="t-info-center">
                <div class="t-info">
                    <h3 class="t-info-title"><a href="/Article/index/{{$article['id']}}" title="{{$article['title']}}">{{$article['title']}}</a></h3>
                    <p class="t-info-2">
                        <span>
                            @if($article['type_id'] == 1)
                                媒体资讯
                            @elseif($article['type_id'] == 2)
                                文章资讯
                            @endif
                        </span>｜<span>{{$article['publish_time']}}</span></p>
                </div>
            </div>
        @endforeach
    </article>
@endsection
