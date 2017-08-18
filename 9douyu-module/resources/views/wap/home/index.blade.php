@extends('wap.common.wapHome')

@section('title', '首页')
@section('keywords', "{{env('META_KEYWORD')}}")

@section('description', "{{env('META_DESCRIPTION')}}")
@section('css')
@endsection
@section('content')
<div>
    <div class="page page-1-1 page-current">
        <div class="wrap">
            <!--顶部广告-->
                @include('wap.home.adBanner');
            <!--零钱计划-->
            @include('wap.home.currentProject');
            <img  class="xiangxiatishi" src="{{ assetUrlByCdn('static/weixin/images/prompt.png')}}"/>
        </div>
    </div>
    <!--了解九斗鱼-->
    @include('wap.home.about9douyu');
</div>
@endsection
@section('footer')
    @include('wap.common.footer');
@endsection
@section('downloadApp')
    @include('wap.home.downloadapp');
@endsection
@section('jsScript')
<script type="text/javascript" src="{{assetUrlByCdn('static/weixin/js/zepto.min.js')}}"></script>
<script type="text/javascript" src="{{assetUrlByCdn('static/weixin/js/touch.js') }}"></script>
<script type="text/javascript" src="{{assetUrlByCdn('static/weixin/js/index.js') }}"></script>
<script type="text/javascript" src="{{assetUrlByCdn('static/weixin/js/jquery-1.9.1.min.js') }}"></script>
<script type="text/javascript" src="{{assetUrlByCdn('static/weixin/js/swiper3.1.0.jquery.min.js') }}"></script>
<script>
    var swiper = new Swiper('.swiper-container', {
        pagination: '.swiper-pagination',
        paginationClickable: true,
        autoplay: 2000,
        loop: true
    });
    document.onreadystatechange = loading;
    function loading(){
        if(document.readyState == "complete")
        {
            setTimeout(function(){
                $(".t-top").fadeOut();
            },6000);

        }
    }
</script>
@endsection
