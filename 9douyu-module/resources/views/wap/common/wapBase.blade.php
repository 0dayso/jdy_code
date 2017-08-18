<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>@yield('title')</title>
    <meta name="keywords" content="@yield('keywords')" />
    <meta name="description" content="@yield('description')" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no" />
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="format-detection" content="telephone=no"/>
    <link href="{{ assetUrlByCdn('/static/images/favicon.ico') }}" rel="shortcut icon" type="image/vnd.microsoft.icon" />
    <link rel="stylesheet" type="text/css" href="{{assetUrlByCdn('static/weixin/css/wap.css')}}">
    @yield('css')
    <script>
        //cnzz统计的api接口初始化
        var _czc = _czc || [];
        _czc.push(["_setAccount", "1259206554"]);
    </script>
</head>
<body>
<meta name="csrf-token" content="{{ csrf_token() }}">

<script type="text/javascript">
    //ready 函数
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
        } else {
            _html.style.fontSize = view_width / 16 + 'px';
        }
    }
    ready(function () {
        ready_rem();
    });
</script>

    @include('wap.common.familyFrame')

    @yield('content')

    @yield('footer')

    <script src="{{ assetUrlByCdn('static/weixin/js/jquery-1.9.1.min.js')}}"></script>
    <script src="{{ assetUrlByCdn('static/weixin/js/wap2-common1.js')}}"></script>
    <script>
        function getCookie(c_name)
        {
            if (document.cookie.length>0)
            {
                c_start=document.cookie.indexOf(c_name + "=")
                if (c_start!=-1)
                {
                    c_start=c_start + c_name.length+1
                    c_end=document.cookie.indexOf(";",c_start)
                    if (c_end==-1) c_end=document.cookie.length
                    return unescape(document.cookie.substring(c_start,c_end))
                }
            }
            return ""
        }
    </script>
    @yield('jsScript')

<!--google网站跟踪-->
<script>
    (function($){
        if($(".t-hengfu")){
            $(".w-alert1 img").css("top","2.25rem");
            $(".t-hengfu-close").click(function(){
                $(".t-hengfu").fadeOut();
                $(".w-alert1 img").css("top","0.5rem");
            })
        }
    })(jQuery);
</script>
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>
<!--引入cnzz统计-->
@if( formalEnvironment() == true)
<div style="display: none;">
<script type="text/JavaScript">
    var cnzz_tag = document.createElement('script');
    cnzz_tag.type = 'text/javascript';
    cnzz_tag.async = true;
    cnzz_tag.charset = 'utf-8';
    cnzz_tag.src = 'https://s4.cnzz.com/z_stat.php?id=1259206554&async=1';
    var cnzz_root = document.getElementsByTagName('script')[0];
    cnzz_root.parentNode.insertBefore(cnzz_tag, cnzz_root);
</script>
    <script>
        var _hmt = _hmt || [];
        (function() {
            var hm = document.createElement("script");
            hm.src = "https://hm.baidu.com/hm.js?bc62ca5d897247faea9a91bbc9f4e046";
            var s = document.getElementsByTagName("script")[0];
            s.parentNode.insertBefore(hm, s);
        })();
    </script>
</div>
@endif
</body>
</html>
