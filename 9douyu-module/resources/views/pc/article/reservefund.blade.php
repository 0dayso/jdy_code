@extends('pc.common.layout')
@section('title',$title)
@section('keywords', $keywords)
@section('description', $description)
@section('content')
    {!! html_entity_decode($currentArticle['content']) !!}
@endsection
@section('jspage')
    <script src="{{assetUrlByCdn('/static/js/jquery.nyroModal.custom.js')}}" type="text/javascript"></script>
    <script src="{{assetUrlByCdn('/static/js/pc2/slide.js')}}" type="text/javascript"></script>
    <script type="text/javascript">
        (function($){
            $(document).ready(function(){
                jQuery.nmProxy(".web-reverse-main img");
                $("a[rel=group]").fancybox();
                //step切换
                jQuery(".web-reverse-main").jCarouselLite({
                    speed:1200,
                    visible:3,
                    stop:$(".web-reverse-main"),
                    btnGo:$("#newertopic-step-li li"),
                    btnGoOver:true,
                    btnPrev:$(".web-reverse-next"),
                    btnNext:$(".web-reverse-prev"),
                    scroll:1,
                    //circular:false
                });
            });
        })(jQuery);
    </script>
@endsection
