@extends('wap.common.wapBase')

@section('title', '了解九斗鱼')

@section('keywords', "{{env('META_KEYWORD')}}")

@section('description', "{{env('META_DESCRIPTION')}}")

@section('css')

@endsection
@section('content')
    <script>
        var readyRE = /complete|loaded|interactive/;
        var ready = window.ready = function (callback) {
            if (readyRE.test(document.readyState) && document.body) callback()
            else document.addEventListener('DOMContentLoaded', function () {
                callback()
            }, false)
        }
        //rem方法
        function ready_rem() {
            var view_width = document.getElementsByTagName('html')[0].getBoundingClientRect().width;
            var _html = document.getElementsByTagName('html')[0];


            if (view_width > 640) {
                _html.style.fontSize = 640 / 16 + 'px'
            } else if(screen.height>500){
                _html.style.fontSize = view_width / 16 + 'px';
                if(screen.height==1280&&screen.width==800){
                    _html.style.fontSize = view_width / 21 + 'px';
                }
            }else{

                _html.style.fontSize = 16 + 'px'
            }

        }
        ready(function () {
            ready_rem();
        });

    </script>
    <link rel="stylesheet" type="text/css" href="{{assetUrlByCdn('/static/weixin/css/intro.css')}}"  id="sc" />
    <link rel="stylesheet" type="text/css" href="{{assetUrlByCdn('/static/weixin/css/animations.css')}}" />
    <link rel="stylesheet" type="text/css" href="{{assetUrlByCdn('/static/weixin/css/animate.min.css')}}" />
    <div>
        <div class="page page-1-1 page-current">
            <div class="wrap dd" style="width:1100%; height: 100%; background: #e5f6fd;">
                <img class="intro-1-1 pt-page-moveCircle" src="{{assetUrlByCdn('/static/weixin/images/topic/intro-p1-4-new1.png')}}"/>
                <img class="intro-1-2 pt-page-scaleUp pt-page-delay300" src="{{assetUrlByCdn('/static/weixin/images/topic/intro-p1-2.png')}}"/>
                <img class="intro-1-3 animated fadeInLeft pt-page-delay700" src="{{assetUrlByCdn('/static/weixin/images/topic/intro-p1-3.png')}}" />
                <img class="img_6 pt-page-moveIconUp" src="{{assetUrlByCdn('/static/weixin/images/topic/intro-up.png')}}" />

            </div>
        </div>
        <div class="page page-2-1 hide">
            <div class="wrap">
                <img class="intro-2-1 pt-page-scaleUp" src="{{assetUrlByCdn('/static/weixin/images/topic/intro-p2-1.png')}}"/>
                <img class="intro-2-2 animated fadeInLeft" src="{{assetUrlByCdn('/static/weixin/images/topic/intro-p2-6-new-1.png')}}"/>
                <img class="intro-2-3 animated fadeInRight" src="{{assetUrlByCdn('/static/weixin/images/topic/intro-p2-3-1.png')}}"/>
                <img class="img_6 pt-page-moveIconUp" src="{{assetUrlByCdn('/static/weixin/images/topic/intro-up.png')}}" />
            </div>
        </div>

        <div class="page page-3-1 hide">
            <div class="wrap">
                <img class="intro-3-1 pt-page-moveCircle " src="{{assetUrlByCdn('/static/weixin/images/topic/intro-p3-9.png')}}"/>
                <img class="intro-3-2 pt-page-scaleUp" src="{{assetUrlByCdn('/static/weixin/images/topic/intro-p3-10.png')}}"/>
                <img class="intro-3-3 animated fadeInRight" src="{{assetUrlByCdn('/static/weixin/images/topic/intro-p3-2.png')}}"/>
                <img class="intro-3-4 animated fadeInLeft" src="{{assetUrlByCdn('/static/weixin/images/topic/intro-p3-7.png')}}"/>
                <img class="intro-3-5 animated fadeInRight" src="{{assetUrlByCdn('/static/weixin/images/topic/intro-p3-5.png')}}"/>
                <img class="img_6 pt-page-moveIconUp" src="{{assetUrlByCdn('/static/weixin/images/topic/intro-up.png')}}" />
            </div>
        </div>

        <div class="page page-4-1 hide">
            <div class="wrap">
                <img class="intro-3-1 pt-page-scaleUp" src="{{assetUrlByCdn('/static/weixin/images/topic/intro-p4-1.png')}}"/>
                <img class="intro-4-2 animated fadeInLeft" src="{{assetUrlByCdn('/static/weixin/images/topic/intro-p4-8.png')}}"/>
                <img class="intro-4-3 animated fadeInRight" src="{{assetUrlByCdn('/static/weixin/images/topic/intro-p4-9.png')}}"/>
                <img class="img_6 pt-page-moveIconUp" src="{{assetUrlByCdn('/static/weixin/images/topic/intro-up.png')}}" />
            </div>
        </div>


        <div class="page page-5-1 hide">
            <div class="wrap">
                <img class="intro-3-1 pt-page-moveCircle " src="{{assetUrlByCdn('/static/weixin/images/topic/intro-p5-1.png')}}"/>
                <img class="intro-5-2 animated fadeInLeft" src="{{assetUrlByCdn('/static/weixin/images/topic/intro-p5-2.png')}}"/>
                <img class="intro-5-3 animated fadeInRight" src="{{assetUrlByCdn('/static/weixin/images/topic/intro-p5-3.png')}}"/>
                <img class="intro-5-4 pt-page-scaleUp" src="{{assetUrlByCdn('/static/weixin/images/topic/intro-p5-4.png')}}"/>
                <img class="intro-5-5 pt-page-scaleUp pt-page-delay500" src="{{assetUrlByCdn('/static/weixin/images/topic/intro-p5-5.png')}}"/>
                <img class="img_6 pt-page-moveIconUp" src="{{assetUrlByCdn('/static/weixin/images/topic/intro-up.png')}}" />
            </div>
        </div>

        <div class="page page-6-1 hide">
            <div class="wrap">
                <img class="intro-3-1 pt-page-moveCircle" src="{{assetUrlByCdn('/static/weixin/images/topic/intro-p7-1.png')}}"/>
                <img class="intro-7-2 pt-page-scaleUp pt-page-delay300" src="{{assetUrlByCdn('/static/weixin/images/topic/intro-p7-2.png')}}"/>
                <img class="img_6 pt-page-moveIconUp" src="{{assetUrlByCdn('/static/weixin/images/topic/intro-up.png')}}" />

            </div>
        </div>

        <div class="page page-7-1 hide">
            <div class="wrap">
                <img class="intro-3-1 pt-page-scaleUp" src="{{assetUrlByCdn('/static/weixin/images/topic/intro-p8-1.png')}}"/>
                <img class="intro-8-2 animated flipInY" src="{{assetUrlByCdn('/static/weixin/images/topic/intro-p8-2.png')}}"/>
                <img class="intro-8-3 animated fadeInLeft" src="{{assetUrlByCdn('/static/weixin/images/topic/intro-p8-3.png')}}"/>
                <img class="intro-8-4 animated fadeInRight" src="{{assetUrlByCdn('/static/weixin/images/topic/intro-p8-4.png')}}"/>
                <img class="intro-8-5 animated fadeInLeft" src="{{assetUrlByCdn('/static/weixin/images/topic/intro-p8-5.png')}}"/>
                <img class="img_6 pt-page-moveIconUp" src="{{assetUrlByCdn('/static/weixin/images/topic/intro-up.png')}}" />

            </div>
        </div>


        <div class="page page-8-1 hide">
            <div class="wrap">
                <img class="intro-3-1 pt-page-scaleUp" src="{{assetUrlByCdn('/static/weixin/images/topic/intro-p9-1.png')}}"/>
                <img class="intro-9-2 animated fadeInLeft" src="{{assetUrlByCdn('/static/weixin/images/topic/intro-p9-2-new.png')}}"/>
                <img class="intro-9-4 animated bounceInUp pt-page-delay300" src="{{assetUrlByCdn('/static/weixin/images/topic/intro-p9-5.png')}}"/>
                <img class="intro-9-5" src="{{assetUrlByCdn('/static/weixin/images/topic/intro-p9-4.png')}}"/>
                <img class="img_6 pt-page-moveIconUp" src="{{assetUrlByCdn('/static/weixin/images/topic/intro-up.png')}}" />
            </div>
        </div>

    </div>
    <script src="{{ assetUrlByCdn('/static/weixin/js/zepto.min.js')}}"></script>
    <script src="{{ assetUrlByCdn('/static/weixin/js/touch.js')}}"></script>
    <script src="{{ assetUrlByCdn('/static/weixin/js/intro.js')}}"></script>
@endsection