<!DOCTYPE html>
<html lang="zh-cn" class="no-js">
<head>
    <meta http-equiv="Content-Type">
    <meta content="text/html; charset=utf-8">
    <meta charset="utf-8">
    <block name="title"><title>@yield('title') - {{env('TITLE_SUFFIX')}}</title></block>
    <block name="keywords"><meta name="keywords" content="@yield('keywords')" /></block>
    <block name="description"><meta name="description" content="@yield('description')" /></block>
    <meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="format-detection" content="telephone=no">
    <link href="{{ assetUrlByCdn('/static/images/favicon.ico') }}" rel="shortcut icon" type="image/vnd.microsoft.icon" />
    <meta name="format-detection" content="email=no">
    <link rel="stylesheet" type="text/css" href="{{ assetUrlByCdn('/static/weixin/css/animations.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ assetUrlByCdn('/static/weixin/css/index-w3.css') }}">
    <script>
        //cnzz统计的api接口初始化
        var _czc = _czc || [];
        _czc.push(["_setAccount", "1259206554"]);
    </script>
</head>
<body>
<block name="cssStyle">
    @yield('css')
</block>
<block name="content">@yield('content')</block>

<block name="footer">@yield('footer')</block>

<block name="downloadApp">@yield('downloadApp')</block>

<jscssminify-js></jscssminify-js>
<block name="jsPage"></block>
<block name="jsScript">@yield('jsScript')</block>
</body>
</html>