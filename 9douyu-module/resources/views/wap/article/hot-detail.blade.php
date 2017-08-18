@extends('wap.common.appBase')

@section('title', '热门问题')

@section('css')
<style type="text/css">
	body{background-color: #f5f5f5; color: #333;}
	.hot-wrap{padding:0.512rem; }
	.hot-wrap h2{line-height: 1.83rem;font-size: 0.55rem; text-indent: 0.64rem;}
	.hot-list{width:100%;}
	.hot-list>dt{background-color: #fff; font-size: 0.6rem; padding:0.64rem 1.49rem 0.64rem 0.64rem;position: relative; }
	.hot-list dd{padding: 0.64rem;color: #666; line-height: 0.81rem;  background-color: #f7f7f7;}
	
</style>
@endsection

@section('content')
    <article class="hot-wrap">
		@if(!empty($questionArticle))
        <dl class="hot-list">
        	<dt class="down">{{$questionArticle['title']}}</dt>
        	<dd>
				{!! htmlspecialchars_decode($questionArticle['content']) !!}
        	</dd>
        </dl>
		@endif
    </article>
@endsection
