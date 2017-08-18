<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <block name="title"><title>@yield('title')</title></block>
    <block name="keywords"><meta name="keywords" content="@yield('keywords')" /></block>
    <block name="description"><meta name="description" content="@yield('description')" /></block>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no" />
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="format-detection" content="telephone=no"/>
    <link href="{{ assetUrlByCdn('/static/images/favicon.ico') }}" rel="shortcut icon" type="image/vnd.microsoft.icon" />
    <link rel="stylesheet" href="{{ assetUrlByCdn('/static/weixin/css/wap2.css') }}" type="text/css"/>
    <script>
        //cnzz统计的api接口初始化
        var _czc = _czc || [];
        _czc.push(["_setAccount", "1259206554"]);
    </script>
</head>
<body>
<meta name="csrf-token" content="{{ csrf_token() }}">

<block name="header">
    @include('wap.common.familyFrame')
</block>
<block name="cssStyle">
    @yield('css')
</block>
<block name="content">
    @yield('content')
</block>
<block name="jsScript">
    <script src="{{ assetUrlByCdn('/static/js/jquery-1.9.1.min.js') }}"></script>
    <script src="{{ assetUrlByCdn('/static/weixin/js/wap2-common1.js') }}"></script>
    @yield('jsScript')
</block>
<block name="footer"></block>
<!--google网站跟踪-->
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>
<!--引入cnzz统计-->
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
</div>
</body>
</html>
