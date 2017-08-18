<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <block name="title"><title>@yield('title')</title></block>
<block name="keywords"><meta name="keywords" content="" /></block>
<block name="description"><meta name="description" content="" /></block>
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no" />
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="format-detection" content="telephone=no"/>
<link href="{{ assetUrlByCdn('/static/images/favicon.ico') }}" rel="shortcut icon" type="image/vnd.microsoft.icon" />
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
<link rel="stylesheet" type="text/css" href="{{ assetUrlByCdn('/static/app/css/public.css') }}">
    <block name="cssStyle">
        @yield('css')
    </block>
</head>
<body>
<block name="content">
    @yield('content')
</block>

<block name="jsScript">
    <script src="{{ assetUrlByCdn('/static/weixin/js/jquery-1.9.1.min.js') }}"></script>
    @yield('jsScript')
</block>

</body>
</html>
