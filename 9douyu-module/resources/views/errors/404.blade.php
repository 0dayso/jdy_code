
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no" />
    <title>404错误页面 - 九斗鱼，安全便捷的互联网金融平台</title>
    <link href="{{assetUrlByCdn('/static/images/favicon.ico')}}" rel="shortcut icon" type="image/vnd.microsoft.icon"/>
    <link href="{{assetUrlByCdn('/static/css/pc2.css')}}" rel="stylesheet">
    <style>

        /* 兼容问题*/
        @media screen and (max-width: 640px){
            body{min-width:320px;}
        }
        @media screen and (device-width: 320px) and (device-height: 480px) and (-webkit-device-pixel-ratio: 2){
            .x-404-txt-2x{margin-top:68%;}/* iphone4*/
        }
    </style>

</head>
<body>
<div class="x-404-bg" id="box404">
    <p class="x-404-txt">您查看的页面不存在</p>
    <p><a href="/" class="x-404-btn">返回首页</a></p>
</div>
<script type="text/javascript" src="{{assetUrlByCdn('/static/js/jquery-1.9.1.min.js')}}"></script>
<script>
    function browserRedirect() {

        var sUserAgent = navigator.userAgent.toLowerCase();
        var bIsIpad = sUserAgent.match(/ipad/i) == "ipad";
        var bIsIphoneOs = sUserAgent.match(/iphone os/i) == "iphone os";
        var bIsMidp = sUserAgent.match(/midp/i) == "midp";
        var bIsUc7 = sUserAgent.match(/rv:1.2.3.4/i) == "rv:1.2.3.4";
        var bIsUc = sUserAgent.match(/ucweb/i) == "ucweb";
        var bIsAndroid = sUserAgent.match(/android/i) == "android";
        var bIsCE = sUserAgent.match(/windows ce/i) == "windows ce";
        var bIsWM = sUserAgent.match(/windows mobile/i) == "windows mobile";
        $("#box404").height($(window).height()).width($(window).width());
        if (bIsIpad || bIsIphoneOs || bIsMidp || bIsUc7 || bIsUc || bIsAndroid || bIsCE || bIsWM) {
            $(".x-404-bg").addClass("x-404-bg-2x");
            $(".x-404-txt").addClass("x-404-txt-2x");
            $(".x-404-btn").addClass("x-404-btn-2x");


        } else {
            $(".x-404-bg").removeClass("x-404-bg-2x");
            $(".x-404-txt").removeClass("x-404-txt-2x");
            $(".x-404-btn").removeClass("x-404-btn-2x");
        }

    }

    browserRedirect();
</script>
</body>
</html>
