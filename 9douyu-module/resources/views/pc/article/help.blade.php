@extends('pc.common.layout')
@section('title',$current['title'])
@section('content')
    <div class="t-wrap t-help">
        @include('pc.article.helpnav')
        <div class="t-help-right">
            <div class="t-help-login">
                <h3>{{ $current['title'] }}</h3>
                <h4>{!! $current['intro'] !!}</h4>
            </div>
            <div class="clear"></div>
            {!! $current['content'] !!}
        </div>

    </div>
    <div class="clear"></div>
@endsection