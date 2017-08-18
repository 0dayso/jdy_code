<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>认证页面跳转中 - {{env('TITLE_SUFFIX')}}</title>
    <meta name="keywords" content="{{env('META_KEYWORD')}}" />
    <meta name="description" content="env('META_DESCRIPTION')" />
    <meta http-equiv = "X-UA-Compatible" content = "IE=edge,chrome=1" />
    <link href="{{assetUrlByCdn('/static/images/favicon.ico')}}" rel="shortcut icon" type="image/vnd.microsoft.icon" />
</head>
<body>

<div style="display:none" id="knet">
    {{--<script src="http://ss.knet.cn/verifyseal.dll?sn=e14012111010045547kgan000000&ct=df&a=1&pa=0.2789653257532144"></script>--}}
    <script src="https://kxlogo.knet.cn/seallogo.dll?sn=e14012111010045547kgan000000&size=3"></script>
</div>


    <script type="text/javascript" src="{{assetUrlByCdn('/static/js/jquery-1.9.1.min.js')}}"></script>
        <script type="text/javascript">
        (function($){
            $(document).ready(function(){
                var href = $("#knet a").attr("href");
                if(href == undefined || href == ''){
                }else{
                    window.location.href = $("#knet a").attr("href");
                }
            });
        })(jQuery);
    </script>
</body>
</html>