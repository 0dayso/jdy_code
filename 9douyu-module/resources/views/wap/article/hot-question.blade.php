@extends('wap.common.appBase')

@section('title', '热门问题')

@section('css')
<style type="text/css">
	body{background-color: #f5f5f5; color: #333;}
	.hot-wrap{padding: 0 0.512rem 0.512rem; }
	.hot-wrap h2{line-height: 1.83rem;font-size: 0.55rem; text-indent: 0.64rem;}
	.hot-list{width:100%;}
	.hot-list>dt{background-color: #fff; font-size: 0.6rem; padding:0.64rem 1.49rem 0.64rem 0.64rem;position: relative; border-bottom: 1px solid #ebebeb; }
	.hot-list>dt:nth-last-child(2){border:none;}
	.hot-list>dt:after{content: ''; position: absolute; width: 15px; height: 15px;background: url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAA+1pVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuNS1jMDIxIDc5LjE1NTc3MiwgMjAxNC8wMS8xMy0xOTo0NDowMCAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczpkYz0iaHR0cDovL3B1cmwub3JnL2RjL2VsZW1lbnRzLzEuMS8iIHhtbG5zOnhtcE1NPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvbW0vIiB4bWxuczpzdFJlZj0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL3NUeXBlL1Jlc291cmNlUmVmIyIgeG1wOkNyZWF0b3JUb29sPSJBZG9iZSBQaG90b3Nob3AgQ0MgMjAxNCAoTWFjaW50b3NoKSIgeG1wOkNyZWF0ZURhdGU9IjIwMTctMDQtMTJUMTU6MDM6NTQrMDg6MDAiIHhtcDpNb2RpZnlEYXRlPSIyMDE3LTA0LTEyVDA3OjA0OjMyKzA4OjAwIiB4bXA6TWV0YWRhdGFEYXRlPSIyMDE3LTA0LTEyVDA3OjA0OjMyKzA4OjAwIiBkYzpmb3JtYXQ9ImltYWdlL3BuZyIgeG1wTU06SW5zdGFuY2VJRD0ieG1wLmlpZDpCMzJFNEU2NTE2QjExMUU3OTczRkZBOTAxNEQyMzI1MyIgeG1wTU06RG9jdW1lbnRJRD0ieG1wLmRpZDpCMzJFNEU2NjE2QjExMUU3OTczRkZBOTAxNEQyMzI1MyI+IDx4bXBNTTpEZXJpdmVkRnJvbSBzdFJlZjppbnN0YW5jZUlEPSJ4bXAuaWlkOkIzMkU0RTYzMTZCMTExRTc5NzNGRkE5MDE0RDIzMjUzIiBzdFJlZjpkb2N1bWVudElEPSJ4bXAuZGlkOkIzMkU0RTY0MTZCMTExRTc5NzNGRkE5MDE0RDIzMjUzIi8+IDwvcmRmOkRlc2NyaXB0aW9uPiA8L3JkZjpSREY+IDwveDp4bXBtZXRhPiA8P3hwYWNrZXQgZW5kPSJyIj8+vWq2dgAAAt1JREFUeNq8l1uIjGEYx7/5Vi60inVaSyvbSmuzsi3K+eyCdSEhSaKmlMSFVU7RrnLcSGjGhlyICy7cLKW2tkgOGzmFhLRLWWySZR3G769HO83ON/PN7Mw+9eudZr55f9/zvu/3vO8XiEQijt8Ih8OzaGZCGYyEHGiBZ9AIl+GPn74CycTIRtCshzkm+glf4BO0Qx4MVF+gzp7DRahP1G+vJNK9NFXwGPYFg8ELUb/F+8sE2AxXoAEq4YdvMZ3m0zRZVjMQ3vQ5G3dgJWyEQ/Ad1sKZpGKkFTS3NFQIK530ohXWwEM4DYNhf/QFbow0z+66thvS6DgMkzRNsMxTbHd6FGmVk7m4DRW24MZ2EZPtMZrPSDc5mY97UG2LrlOMtMgemQone7ELhsHu6IyPwH2yfeVkNzSa66LFqkI7nOzHCU0nLHQZ5vmqRGR71emZeAoblPESeOdRSMpgRRqdL4dSj99U10skLrL0vQpBNfLjKUh17Tarel4rvF3i4fAx3hUMf4sVgHLktT6kuqYEpnmNIvFa9VviPvDbqyfk/+q1tkPkexJItaFMhHm2e3nFL+1irg1n30RpIO+Acj6uth0rXqZaK1MTJWHRT9urxG+hv8/5K4bpMXNeB+NsiP3EGMh1rZYO8fMPslY2C2A07LQyWGDf+Y3x8C0QCoVUxq5pmOi4LYVjUJOdQKak+KhdhxsusmY7ttSk2EF5GlIdowrh4P+SeUoLpweqVp09Zl9dmzttEq0M3/YsSrWO5sKW2IOASmMN8sIsid/rkGGLuVNM1vpiK7yxI1AmQyfOl7Ytdj36ID9AcwmakZdmSFpvx97iRGcuyZea/BHyRd0QFthC0nObG/uj61EoVtFIek7PK8xOQTgUzoNOM2chP+VXGISq4Sdtb9Vm8QTuwgN7Z1IlGwSjYLJVNH3+YFWtIe13J7uBHHs1WQwDoAPaTKyF2NtGr9HO0i+S9flXgAEAE0Tg1HwRqpkAAAAASUVORK5CYII='); background-size: 100%; right: 0.64rem; top: 50%; margin-top: -7.5px;-webkit-transition: all 0.5s;-moz-transition: all 0.5s;-o-transition: all 0.5s;transition: all 0.5s;}
	.hot-list>dt.down:after{transform: rotate(180deg);-ms-transform:rotate(180deg);-moz-transform:rotate(180deg);-webkit-transform:rotate(180deg);-o-transform:rotate(180deg);}
	.hot-list dd{/*padding: 0.64rem;*/color: #666; line-height: 0.81rem; display: none; border-bottom: 1px solid #ebebeb; background-color: #f7f7f7;}
	.hot-list dd>a,.hot-list dd>a:visited{display: block; color: #666; padding: 0.64rem 0.64rem 0.64rem 1rem; position: relative;border-bottom: 1px solid #ebebeb;}
	.hot-list dd>a:hover,.hot-list dd>a:active{color: #999;}
	.hot-list dd>a:nth-last-child(1){border:none;}
	.hot-list dd>a:after{content: ''; position: absolute;right: 0.64rem; top: 50%; margin-top: -3px; width: 6px; height: 6px; border:2px solid #ccc;  border-width: 1px 1px 0 0; transform: rotate(45deg);-ms-transform:rotate(45deg);-moz-transform:rotate(45deg);-webkit-transform:rotate(45deg);-o-transform:rotate(45deg);}
</style>
@endsection

@section('content')
    <article class="hot-wrap">
        <h2>热门问题</h2>
		@if(!empty($hotQuestion))
        <dl class="hot-list">
			@foreach($hotQuestion as $key=>$value)
        	<dt>{{$key}}</dt>
        	<dd style="display: none;">
				@foreach ($value as $k=>$v)
        		<a href="{{$v['url']}}">{{$v['title']}}</a>
				@endforeach
        	</dd>
        	@endforeach
        </dl>
		@endif
        <h2>所有问题</h2>
		@if(!empty($allQuestion))
			<dl class="hot-list">
				@foreach($allQuestion as $key=>$value)
					<dt>{{$key}}</dt>
					<dd style="display: none;">
						@foreach ($value as $k=>$v)
							<a href="{{$v['url']}}">{{$v['title']}}</a>
						@endforeach
					</dd>
				@endforeach
			</dl>
		@endif
    </article>
@endsection

@section('jsScript')
<script type="text/javascript">
	(function($){
		$(function(){
			$(".hot-list>dt").each(function(){
				$(this).on('click',function(){
					if($(this).next("dd").is(':visible')){
						$(this).removeClass('down');
						$(this).next("dd").slideUp();
					}else{
						$(this).addClass('down');
						$(this).next("dd").slideDown();
					}
				})
			})
			
		})
	})(jQuery)
</script>
@endsection
