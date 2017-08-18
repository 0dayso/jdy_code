@extends('pc.common.layout')
@section('title',$info['title'])
@section('content')
    <link rel="stylesheet" type="text/css" href="{{ assetUrlByCdn('/static/css/style.css') }}">
    <div class="lefttab">
        <div class="lefttab-left">
            <ul>
                @if(!empty($articleList))
                    @foreach($articleList as $a)
                        @if($a['id'] == $info['id'])
                        <li><a href="/article/{{$a['id']}}" title="{{ $a['title'] }}" class="on">{{ str_limit($a['title'], $limit=30, $end='...' ) }}</a></li>
                        @else
                        <li><a href="/article/{{$a['id']}}" title="{{ $a['title'] }}">{{ str_limit($a['title'], $limit=30, $end='...' ) }}</a></li>
                        @endif
                    @endforeach
                @endif
            </ul>
        </div>
        <div class="lefttab-right">
            {!! htmlspecialchars_decode($info['content']) !!}
        </div>
        <div class="clear"></div>
    </div>

@endsection
@section('jspage')
    <script src="{{assetUrlByCdn('/static/bootstrap/js/bootstrap.min.js')}}" type="text/javascript"></script>
    <script src="{{assetUrlByCdn('/static/js/jquery.scrollfix.js')}}" type="text/javascript"></script>
    <script type="text/javascript">
        (function($){
            $(document).ready(function(){
                $.scrollFix(".lefttab-right-sidebar", ".lefttab-right-main");
            });
        })(jQuery);
    </script>
@endsection


