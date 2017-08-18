{{--<extend name="Common:zt_base" />--}}


@extends('wap.common.wapBase')

@section('title', '微信扫一扫立即领钱')

@section('keywords', env('META_KEYWORD'))

@section('description', env('META_DESCRIPTION'))

@section('css')
    <meta name="format-detection" content="telephone=yes">
    <link rel="stylesheet" type="text/css" href="{{assetUrlByCdn('/static/weixin/css/weixin.css')}}">
@endsection

@section('content')
    <section>
        <img src="{{assetUrlByCdn('/static/weixin/images/guide-img1.png')}}" class="img" >
        <img src="{{assetUrlByCdn('/static/weixin/images/guide-img2.png')}}" class="img" >
    </section>

    @if(!empty($link))
        <a href="{{$link or ''}}" class="w-s-btn" id="downLoadButton">安装客户端</a>
    @else
        <a href="javascript:void(0);" class="w-s-btn w-s-gray" >{{$linkError or ''}}</a>
    @endif
@endsection

@section('jsScript')
    <script type="text/javascript" src="{{assetUrlByCdn('/static/weixin/js/WeixinApi.js')}}"></script>
    <script type="text/javascript">
        WeixinApi.ready(function(Api){
            // if(Api.openInWeixin() ) {
            // if(Api.isIphone()){
            document.getElementById("downLoadButton").addEventListener("click",function(){
                WeixinJSBridge.invoke("openUrlByExtBrowser",{
                            "url" : "{{$link or ''}}"
                        },
                        function(e){
                            //alert(e.err_msg)
                        })
            },!1)
            // }
            // }
        })
    </script>
@endsection



