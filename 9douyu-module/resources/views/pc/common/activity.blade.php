<!DOCTYPE html>
<html ng-app='activityApp'>
    <head>
        <title>九斗鱼 - @yield('title')</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="keywords" content="{{ env('META_KEYWORD') }}" />
        <meta name="description" content="{{ env('META_DESCRIPTION') }}" />
        <meta name="renderer" content="webkit" />
        <meta http-equiv = "X-UA-Compatible" content = "IE=edge,chrome=1" />
        <link href="{{ assetUrlByCdn('/static/images/favicon.ico') }}" rel="shortcut icon" type="image/vnd.microsoft.icon" />
        <link rel="stylesheet" href="{{ assetUrlByCdn('/static/css/pc2.css') }}" type="text/css" />
        {{--<link rel="stylesheet" href="{{ assetUrlByCdn('/static/theme/spring/css/theme.css') }}" />--}}
        @if( \App\Http\Logics\SystemConfig\SystemConfigLogic::getConfig('SKIN_CSS') )
            <link rel="stylesheet" type="text/css" href="{{ assetUrlByCdn('/static/theme/'.\App\Http\Logics\SystemConfig\SystemConfigLogic::getConfig('SKIN_CSS').'/css/theme.css') }}">
        @endif
        @yield('csspage')


    </head>
<body>

<!-- 注册流程优化弹层 -->
<script type="text/javascript" src="{{assetUrlByCdn('static/js/jquery-1.9.1.min.js')}}"></script>


    @section('header')
        @include('pc.common/header')
    @show

    @yield('content')

    @include('pc.common/activityPop')

    @section('footer')
        @include('pc.common/footer')
    @show

    @include('pc.common/qqService')

    @yield('jspage')

</body>
<script type="text/javascript">
    $(document).delegate(".clickInvest",'click',function () {
        var  projectId  =   $(this).attr("attr-data-id");
        var  act_token  =   $(this).attr('attr-act-token');
        if( !projectId ){
            return false;
        }
        if( !act_token ){
            act_token   =   '__' + projectId;
        }
        var _token      =   $("input[name='_token']").val();
        $.ajax({
            url      :"/activity/setActToken",
            data     :{act_token:act_token,_token:_token},
            dataType :'json',
            type     :'post',
            success : function() {
                window.location.href='/project/detail/' + projectId;
            }, error : function() {
                window.location.href='/project/detail/' + projectId;
            }
        });

    })
</script>

</html>
