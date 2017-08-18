<!DOCTYPE html>
<html lang="zh-cn" class="no-js">
<head>
    <meta http-equiv="Content-Type">
    <meta content="text/html; charset=utf-8">
    <meta charset="utf-8">
    <title>内刊7月期</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1,target-densitydpi=medium-dpi">
    <meta name="format-detection" content="telephone=no">
    <meta name="format-detection" content="email=no">
    <link rel="stylesheet" type="text/css" href="{{assetUrlByCdn('/static/weixin/css/reset12.css')}}" />
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
                _html.style.fontSize = view_width / 18 + 'px';
                if(screen.height==1280&&screen.width==800){
                    _html.style.fontSize = view_width / 22 + 'px';
                }
            }else{

                _html.style.fontSize = 15 + 'px'
            }

        }
        ready(function () {
            ready_rem();
        });

    </script>
    <link rel="stylesheet" type="text/css" href="{{assetUrlByCdn('/static/weixin/paper/css/newspaper1707.css')}}"  id="sc" />
    <link rel="stylesheet" type="text/css" href="{{assetUrlByCdn('/static/weixin/css/animations.css')}}" />
    <link rel="stylesheet" type="text/css" href="{{assetUrlByCdn('/static/weixin/css/animate.min.css')}}" />
</head>
<body>

<div class="page page-1-1 page-current">
    <div class="wrap">
        <div class="page-bg1">
            <img class="page-bg-img" src="{{assetUrlByCdn('/static/weixin/paper/images/news1707/img01-3.jpg')}}" />
            <img class="page-img1 pa pt-page-scaleUp pt-page-delay300" src="{{assetUrlByCdn('/static/weixin/paper/images/news1707/img01-1.png')}}" />
            <img class="page-img2 pa animated fadeInLeft" src="{{assetUrlByCdn('/static/weixin/paper/images/news1707/img01-4.png')}}" />
            <img class="page-img3 pa animated fadeInRight" src="{{assetUrlByCdn('/static/weixin/paper/images/news1707/img01-2.png')}}" />
            <img class="page-img1-1" src="{{assetUrlByCdn('/static/weixin/paper/images/news1707/img01-5.png')}}" />
            <img class="page-img1-2" src="{{assetUrlByCdn('/static/weixin/paper/images/news1707/img01-6.png')}}" />
        </div>
    </div>
</div>
<!-- page2-->
<div class="page page-2-1 hide">
    <div class="wrap">
        <div class="page-bg2">
            <img class="page-bg-img" src="{{assetUrlByCdn('/static/weixin/paper/images/news1707/img02-bg.jpg')}}" />
            <img class="page-img4" src="{{assetUrlByCdn('/static/weixin/paper/images/news1707/img02-1.png')}}" />
            <img class="page-img5 pa pt-page-scaleUp pt-page-delay300" src="{{assetUrlByCdn('/static/weixin/paper/images/news1707/img02-2.png')}}" />
            <div class="page-div pa">
                <img class="page-img6-1 pa animated fadeInLeft" src="{{assetUrlByCdn('/static/weixin/paper/images/news1707/img02-3.png')}}" />
                <img class="page-img6-2 pa animated fadeInRight" src="{{assetUrlByCdn('/static/weixin/paper/images/news1707/img02-4.jpg')}}" />
                <img class="page-img6-3 pa animated fadeInLeft" src="{{assetUrlByCdn('/static/weixin/paper/images/news1707/img02-5.png')}}" />
                <img class="page-img6-4 pa animated fadeInRight" src="{{assetUrlByCdn('/static/weixin/paper/images/news1707/img02-6.png')}}" />
            </div>
            <img class="news-arrowUp pt-page-moveIconUp" src="{{assetUrlByCdn('/static/weixin/paper/images/news1707/img-up.png')}}" />
        </div>
    </div>
</div>

<!-- page3-->
<div class="page page-3-1 hide">
    <div class="wrap">
        <div class="page-bg3">
            <img class="page-bg-img" src="{{assetUrlByCdn('/static/weixin/paper/images/news1707/img03-bg.jpg')}}" />
            <img class="page-img4" src="{{assetUrlByCdn('/static/weixin/paper/images/news1707/img03-1.png')}}" />
            <img class="page-img8 pt-page-scaleUp pt-page-delay300" src="{{assetUrlByCdn('/static/weixin/paper/images/news1707/img03-6.png')}}" />
            <img class="page-img7 pa pt-page-scaleUp pt-page-delay300" src="{{assetUrlByCdn('/static/weixin/paper/images/news1707/img03-3.png')}}" />
            <img class="page-img7-1" src="{{assetUrlByCdn('/static/weixin/paper/images/news1707/img03-3-1.png')}}" />
            <div class="page-div pa">
                <img class="page-img7-2 pa animated fadeInRight" src="{{assetUrlByCdn('/static/weixin/paper/images/news1707/img03-2.png')}}" />
                <img class="page-img7-3 pa pt-page-scaleUp pt-page-delay300" src="{{assetUrlByCdn('/static/weixin/paper/images/news1707/img03-4.png')}}" />
            </div>
            <img class="page-img9" src="{{assetUrlByCdn('/static/weixin/paper/images/news1707/img03-5.png')}}" />
            <img class="news-arrowUp pt-page-moveIconUp" src="{{assetUrlByCdn('/static/weixin/paper/images/news1707/img-up.png')}}" />
        </div>
    </div>
</div>
<!-- page3-->
<div class="page page-4-1 hide">
    <div class="wrap">
        <div class="page-bg3">
            <img class="page-bg-img" src="{{assetUrlByCdn('/static/weixin/paper/images/news1707/img03-bg.jpg')}}" />
            <img class="page-img4" src="{{assetUrlByCdn('/static/weixin/paper/images/news1707/img03-1.png')}}" />
            <img class="page-img11 pa pa pt-page-scaleUp pt-page-delay300" src="{{assetUrlByCdn('/static/weixin/paper/images/news1707/img04-1.png')}}" />
            <img class="page-img12" src="{{assetUrlByCdn('/static/weixin/paper/images/news1707/img04-3.png')}}" />
            <img class="page-img13" src="{{assetUrlByCdn('/static/weixin/paper/images/news1707/img04-5.png')}}" />
            <div class="page-div pa">
                <img class="page-img11-1 pa animated fadeInLeft" src="{{assetUrlByCdn('/static/weixin/paper/images/news1707/img04-2.png')}}" />
                <img class="page-img11-2 pa pt-page-scaleUp pt-page-delay300" src="{{assetUrlByCdn('/static/weixin/paper/images/news1707/img04-4.jpg')}}" />
            </div>
            <img class="page-img14" src="{{assetUrlByCdn('/static/weixin/paper/images/news1707/img04-6.png')}}" />
            <img class="news-arrowUp pt-page-moveIconUp" src="{{assetUrlByCdn('/static/weixin/paper/images/news1707/img-up.png')}}" />
        </div>
    </div>
</div>

<!-- page5-->
<div class="page page-5-1 hide">
    <div class="wrap">
       <div class="page-bg3">
            <img class="page-bg-img" src="{{assetUrlByCdn('/static/weixin/paper/images/news1707/img03-bg.jpg')}}" />
            <img class="page-img4" src="{{assetUrlByCdn('/static/weixin/paper/images/news1707/img03-1.png')}}" />
            <img class="page-img15 pa pa pt-page-scaleUp pt-page-delay300" src="{{assetUrlByCdn('/static/weixin/paper/images/news1707/img05-1.png')}}" />
            <div class="page-div pa">
                <img class="page-img15-1 pa animated fadeInRight" src="{{assetUrlByCdn('/static/weixin/paper/images/news1707/img05-2.png')}}" />
                <img class="page-img15-2 pa pa pt-page-scaleUp pt-page-delay300" src="{{assetUrlByCdn('/static/weixin/paper/images/news1707/img05-3.jpg')}}" />
            </div>
            <img class="page-img16" src="{{assetUrlByCdn('/static/weixin/paper/images/news1707/img05-4.png')}}" />
            <img class="page-img17" src="{{assetUrlByCdn('/static/weixin/paper/images/news1707/img05-5.png')}}" />
            <img class="page-img18" src="{{assetUrlByCdn('/static/weixin/paper/images/news1707/img05-6.png')}}" />
            <img class="news-arrowUp pt-page-moveIconUp" src="{{assetUrlByCdn('/static/weixin/paper/images/news1707/img-up.png')}}" />

        </div>
    </div>
</div>

<!-- page6-->
<div class="page page-6-1 hide">
    <div class="wrap">
       <div class="page-bg3">
            <img class="page-bg-img" src="{{assetUrlByCdn('/static/weixin/paper/images/news1707/img03-bg.jpg')}}" />
            <img class="page-img4" src="{{assetUrlByCdn('/static/weixin/paper/images/news1707/img03-1.png')}}" />
            <img class="page-img19 pa pt-page-scaleUp pt-page-delay300" src="{{assetUrlByCdn('/static/weixin/paper/images/news1707/img06-1.png')}}" />
            <img class="page-img21" src="{{assetUrlByCdn('/static/weixin/paper/images/news1707/img06-4.png')}}" />
            <div class="page-div pa">
                <img class="page-img19-1 pa pt-page-scaleUp pt-page-delay300" src="{{assetUrlByCdn('/static/weixin/paper/images/news1707/img06-2.jpg')}}" />
            </div>
            <img class="page-img20" src="{{assetUrlByCdn('/static/weixin/paper/images/news1707/img06-3.png')}}" />
            <img class="news-arrowUp pt-page-moveIconUp" src="{{assetUrlByCdn('/static/weixin/paper/images/news1707/img-up.png')}}" />
        </div>
    </div>
</div>

<div class="page page-7-1 hide">
    <div class="wrap">
        <div class="page-bg3">
            <img class="page-bg-img" src="{{assetUrlByCdn('/static/weixin/paper/images/news1707/img03-bg.jpg')}}" />
            <img class="page-img4" src="{{assetUrlByCdn('/static/weixin/paper/images/news1707/img03-1.png')}}" />
            <img class="page-img23 pa animated fadeInLeft" src="{{assetUrlByCdn('/static/weixin/paper/images/news1707/img07-1.png')}}" />
            <img class="page-img12" src="{{assetUrlByCdn('/static/weixin/paper/images/news1707/img04-3.png')}}" />
            <img class="page-img13" src="{{assetUrlByCdn('/static/weixin/paper/images/news1707/img04-5.png')}}" />
            <div class="page-div pa">
                <img class="page-img24 pa pt-page-scaleUp pt-page-delay300" src="{{assetUrlByCdn('/static/weixin/paper/images/news1707/img07-2.png')}}" />
            </div>
            <img class="page-img14" src="{{assetUrlByCdn('/static/weixin/paper/images/news1707/img04-6.png')}}" />
            <img class="news-arrowUp pt-page-moveIconUp" src="{{assetUrlByCdn('/static/weixin/paper/images/news1707/img-up.png')}}" />
         </div>
    </div>
</div>
<div class="page page-8-1 hide">
    <div class="wrap">
        <div class="page-bg3">
            <img class="page-bg-img" src="{{assetUrlByCdn('/static/weixin/paper/images/news1707/img03-bg.jpg')}}" />
            <img class="page-img4" src="{{assetUrlByCdn('/static/weixin/paper/images/news1707/img03-1.png')}}" />
            <img class="page-img27 pa animated fadeInRight" src="{{assetUrlByCdn('/static/weixin/paper/images/news1707/img08-1.png')}}" />
            <img class="page-img29" src="{{assetUrlByCdn('/static/weixin/paper/images/news1707/img08-3.png')}}" />
             <div class="page-div pa">
                <img class="page-img28 pa pt-page-scaleUp pt-page-delay300" src="{{assetUrlByCdn('/static/weixin/paper/images/news1707/img08-2.png')}}" />
            </div>
            <img class="page-img30" src="{{assetUrlByCdn('/static/weixin/paper/images/news1707/img08-4.png')}}" />
            <img class="news-arrowUp pt-page-moveIconUp" src="{{assetUrlByCdn('/static/weixin/paper/images/news1707/img-up.png')}}" />
         </div>
    </div>
</div>

<div class="page page-9-1 hide">
    <div class="wrap">
       <div class="page-bg3">
            <img class="page-bg-img" src="{{assetUrlByCdn('/static/weixin/paper/images/news1707/img09-bg.jpg')}}" />
            <img class="page-img4" src="{{assetUrlByCdn('/static/weixin/paper/images/news1707/img09-1.png')}}" />
            <img class="page-img33 pa pt-page-scaleUp pt-page-delay300" src="{{assetUrlByCdn('/static/weixin/paper/images/news1707/img09-2.png')}}" />
            <div class="page-div pa">
                <img class="page-img31 pa animated fadeInRight" src="{{assetUrlByCdn('/static/weixin/paper/images/news1707/img09-3.png')}}" />
                <img class="page-img32 pa pt-page-scaleUp pt-page-delay300" src="{{assetUrlByCdn('/static/weixin/paper/images/news1707/img09-4.jpg')}}" /> 
            </div>
            <img class="page-img20" src="{{assetUrlByCdn('/static/weixin/paper/images/news1707/img06-3.png')}}" />
            <img class="page-img33-1 " src="{{assetUrlByCdn('/static/weixin/paper/images/news1707/img09-5.png')}}" />
            <img class="news-arrowUp pt-page-moveIconUp" src="{{assetUrlByCdn('/static/weixin/paper/images/news1707/img-up.png')}}" />
        </div>
    </div>
</div>

<div class="page page-10-1 hide">
    <div class="wrap">
        <div class="page-bg3">
            <img class="page-bg-img" src="{{assetUrlByCdn('/static/weixin/paper/images/news1707/img09-bg.jpg')}}" />
            <img class="page-img4" src="{{assetUrlByCdn('/static/weixin/paper/images/news1707/img09-1.png')}}" />
            <img class="page-img36-1 pa pt-page-rotateCubeBottomIn" src="{{assetUrlByCdn('/static/weixin/paper/images/news1707/img10-3.png')}}" />
            <img class="page-img33-1" src="{{assetUrlByCdn('/static/weixin/paper/images/news1707/img09-5.png')}}" />
            <div class="page-div pa">
                <img class="page-img35 pa pt-page-scaleUp pt-page-delay300" src="{{assetUrlByCdn('/static/weixin/paper/images/news1707/img10-1.jpg')}}" />
                <img class="page-img36 pa animated fadeInRight" src="{{assetUrlByCdn('/static/weixin/paper/images/news1707/img10-2.png')}}" />

            </div>
            <img class="page-img20" src="{{assetUrlByCdn('/static/weixin/paper/images/news1707/img06-3.png')}}" />
            <img class="news-arrowUp pt-page-moveIconUp" src="{{assetUrlByCdn('/static/weixin/paper/images/news1707/img-up.png')}}" />
         </div>
    </div>
</div>
<div class="page page-11-1 hide">
    <div class="wrap">
        <div class="page-bg3">
            <img class="page-bg-img" src="{{assetUrlByCdn('/static/weixin/paper/images/news1707/img02-bg.jpg')}}" />
            <img class="page-img4" src="{{assetUrlByCdn('/static/weixin/paper/images/news1707/img11-3.png')}}" />
            <img class="page-img37-1 pa pt-page-rotateCubeBottomIn" src="{{assetUrlByCdn('/static/weixin/paper/images/news1707/img11-4.png')}}" />
            <div class="page-div pa">
                <img class="page-img37 pa pt-page-scaleUp pt-page-delay300" src="{{assetUrlByCdn('/static/weixin/paper/images/news1707/img11-1.jpg')}}" />
                <img class="page-img38 pa animated fadeInLeft" src="{{assetUrlByCdn('/static/weixin/paper/images/news1707/img11-2.png')}}" />
            </div>
            <img class="news-arrowUp pt-page-moveIconUp" src="{{assetUrlByCdn('/static/weixin/paper/images/news1707/img-up.png')}}" />
         </div>
    </div>
</div>

<div class="page page-12-1 hide">
    <div class="wrap">
       <div class="page-bg3">
            <img class="page-bg-img" src="{{assetUrlByCdn('/static/weixin/paper/images/news1707/img12-bg.jpg')}}" />
            <img class="page-img4" src="{{assetUrlByCdn('/static/weixin/paper/images/news1707/img11-3.png')}}" />
            <img class="page-img37-1 pa pt-page-rotateCubeBottomIn" src="{{assetUrlByCdn('/static/weixin/paper/images/news1707/img12-1.png')}}" />
            <div class="page-div pa">
                <img class="page-img40 pa animated fadeInLeft" src="{{assetUrlByCdn('/static/weixin/paper/images/news1707/img12-2.png')}}" />
                <img class="page-img40-1 pa pt-page-scaleUp pt-page-delay300" src="{{assetUrlByCdn('/static/weixin/paper/images/news1707/img12-3.jpg')}}" />
            </div>
        </div>
        <img class="news-arrowUp pt-page-moveIconUp" src="{{assetUrlByCdn('/static/weixin/paper/images/news1707/img-up.png')}}" />
    </div>
</div>

<div class="page page-13-1 hide">
    <div class="wrap">
         <div class="page-bg3">
            <img class="page-bg-img" src="{{assetUrlByCdn('/static/weixin/paper/images/news1707/img13-bg.jpg')}}" />
            <img class="page-img4" src="{{assetUrlByCdn('/static/weixin/paper/images/news1707/img13-1.png')}}" />
            <img class="page-img41 pa pt-page-scaleUp pt-page-delay300" src="{{assetUrlByCdn('/static/weixin/paper/images/news1707/img13-2.png')}}" />
            <div class="page-div pa">
                <img class="page-img42 pa animated fadeInRight" src="{{assetUrlByCdn('/static/weixin/paper/images/news1707/img13-3.png')}}" />
                <img class="page-img42-1 pa pt-page-scaleUp pt-page-delay300" src="{{assetUrlByCdn('/static/weixin/paper/images/news1707/img13-4.jpg')}}" /> 
            </div>
            <img class="page-img20" src="{{assetUrlByCdn('/static/weixin/paper/images/news1707/img06-3.png')}}" />
            <img class="page-img33-1 " src="{{assetUrlByCdn('/static/weixin/paper/images/news1707/img09-5.png')}}" />
            <img class="news-arrowUp pt-page-moveIconUp" src="{{assetUrlByCdn('/static/weixin/paper/images/news1707/img-up.png')}}" />
        </div>
    </div>
</div>

<div class="page page-14-1 hide">
    <div class="wrap">
        <div class="page-bg3">
            <img class="page-bg-img" src="{{assetUrlByCdn('/static/weixin/paper/images/news1707/img14-bg.jpg')}}" />
            <img class="page-img43 pa pt-page-rotateCubeBottomIn" src="{{assetUrlByCdn('/static/weixin/paper/images/news1707/img14-1.png')}}" />
            <img class="page-img44 pa pt-page-scaleUp pt-page-delay300" src="{{assetUrlByCdn('/static/weixin/paper/images/news1707/img14-2.png')}}" />
            <img class="page-img46" src="{{assetUrlByCdn('/static/weixin/paper/images/news1707/img15-2.png')}}" />
            <img class="page-img47" src="{{assetUrlByCdn('/static/weixin/paper/images/news1707/img15-3.png')}}" />
            <img class="news-arrowUp pt-page-moveIconUp" src="{{assetUrlByCdn('/static/weixin/paper/images/news1707/img-up.png')}}" />
        </div>
    </div>
</div>

<div class="page page-15-1 hide">
    <div class="wrap">
       <div class="page-bg3 page-bg3-4">
            <img class="page-bg-img" src="{{assetUrlByCdn('/static/weixin/paper/images/news1707/img15-bg.jpg')}}" />
            <img class="page-img45 pa pt-page-scaleUp pt-page-delay300" src="{{assetUrlByCdn('/static/weixin/paper/images/news1707/img15-1.png')}}" />
            <img class="page-img46" src="{{assetUrlByCdn('/static/weixin/paper/images/news1707/img15-2.png')}}" />
            <img class="page-img47" src="{{assetUrlByCdn('/static/weixin/paper/images/news1707/img15-3.png')}}" />
            <img class="news-arrowUp pt-page-moveIconUp" src="{{assetUrlByCdn('/static/weixin/paper/images/news1707/img-up.png')}}" />
        </div>
    </div>
</div>

<div id="music" data-img="{{assetUrlByCdn('/static/weixin/paper/images/news1703/play.png')}}" data-music="{{assetUrlByCdn('/static/weixin/paper/voice/newspaper1610.mp3')}}" style="overflow: hidden;"></div>

<div id="audiocontainer"></div>

<script src="{{assetUrlByCdn('/static/weixin/js/zepto.min.js')}}"></script>
<script src="{{assetUrlByCdn('/static/weixin/js/touch.js')}}"></script>
<script src="{{assetUrlByCdn('/static/weixin/js/newspaper/newspaper.js')}}"></script>
<script type="text/javascript"> 
       var musicImg=$("#music").attr("data-img");
       var gSound = $("#music").attr("data-music");
        document.onreadystatechange = loading; 
        function loading(){
            if(document.readyState == "complete")
            { 

                // playbksound();
            }
        }

</script> 
@include('wap.common.sharejs')
</body>
</html>
