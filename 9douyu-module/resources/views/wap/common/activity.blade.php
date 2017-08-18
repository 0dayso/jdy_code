<!DOCTYPE HTML>
<html ng-app='activityApp'>
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

    function getCookie(c_name){
        if (document.cookie.length>0){
            c_start=document.cookie.indexOf(c_name + "=")
            if (c_start!=-1){
                c_start=c_start + c_name.length+1
                c_end=document.cookie.indexOf(";",c_start)
                if (c_end==-1) c_end=document.cookie.length
                return unescape(document.cookie.substring(c_start,c_end))
            }
        }
        return ""
    }

    var client = getCookie('JDY_CLIENT_COOKIES');
     if( client == '' || !client ){
        var client  =   '{{$client or "wap"}}';
    }
    var version =   getCookie('version');
    if( version=='' || !version ) {
        var version = '{{$version or ""}}'
    }
    ready(function () {
        ready_rem();
    });
</script>
    @include('wap.common.familyFrame')

    @yield('content')

    @include('wap.common.activityPop')

    @yield('footer')

    <script src="{{ assetUrlByCdn('static/weixin/js/jquery-1.9.1.min.js')}}"></script>
    <script src="{{ assetUrlByCdn('static/weixin/js/wap2-common1.js')}}"></script>
    <script src="{{assetUrlByCdn('/static/js/common.js')}}"></script>
    @yield('jsScript')

<!--google网站跟踪-->
<script type='text/javascript'>
    (function($){
        if($(".t-hengfu")){
            $(".w-alert1 img").css("top","2.25rem");
            $(".t-hengfu-close").click(function(){
                $(".t-hengfu").fadeOut();
                $(".w-alert1 img").css("top","0.5rem");
            })
        }
        if( client =='ios' || client =='android' ) {
            $('#none-user').hide();
        }
    })(jQuery);
</script>
<script type="text/Javascript">
    var registerWord = "注册领取现金券";
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $(document).ready(function(){
        @if( isset($jrttChanleValue) &&  !empty($jrttChanleValue) )

            $("form").submit(function(){
                var captcha =   $.trim($("input[name=captcha]").val());
                if( captcha =='' || captcha.length !=4 ) return false;
                var code    =   $.trim($("input[name=code]").val());
                if(code =='' || code.length !=6 ) return false;
                var phone   =   $.trim($("input[name=phone]").val()) ;
                if( phone == ''|| phone.length !=11) return false;
                var pwd =   $.trim($("input[name=password]").val());
                if(pwd ==''|| pwd.length <6 ) return false;
                var JtrrId = "{{$jrttChanleValue}}";
                _taq.push({convert_id:JtrrId, event_type:"form"})
            });
        @endif
        $(".re-form form").find("input[name='aggreement']").before('<input name="_token" type="hidden" value="{{csrf_token()}}">')

        // expand
        var evclick = "ontouchend" in window ? "touchend" : "click";
        $("#expand").on(evclick,function(){

            if($(this).hasClass("active")){
                $(this).removeClass("active");
                 $("#registerForm").show();

            }else{
                $(this).addClass("active");
                $("#registerForm").hide();

            }
        })
     });
$(document).delegate(".doInvest","click",function(){var projectId=$(this).attr("attr-data-id");var act_token=$(this).attr("attr-act-token");if(!projectId||projectId==0){return false}if(!act_token){act_token="{{$actToken}}_"+projectId}if(client=="ios"){if(version&&version<"4.1.0"){window.location.href="objc:certificationOrInvestment("+projectId+",1)";return false}if(version=="4.1.2"){setActToken(act_token,projectId);return false}if(!version||version>="4.1.0"){window.location.href="objc:toProjectDetail("+projectId+",1,"+act_token+")";return false}}if(client=="android"){if(version<"4.1.0"){window.jiudouyu.fromNoviceActivity(projectId,1);return false}if(version>="4.1.0"){window.jiudouyu.fromNoviceActivity(projectId,1,act_token);return false}}setActToken(act_token,projectId)});function setActToken(act_token,projectId){if(act_token){var _token=$("input[name='_token']").val();$.ajax({url:"/activity/setActToken",data:{act_token:act_token,_token:_token},dataType:"json",type:"post",success:function(){window.location.href="/project/detail/"+projectId},error:function(){window.location.href="/project/detail/"+projectId}})}window.location.href="/project/detail/"+projectId;return false};

 $(document).delegate(".userDoLogin,.introduce-btn","click",function () {
    if( client =='ios'){
        if(version =='4.1.2') {
            window.location.href='/login';
            return false
        } else {
            window.location.href = "objc:gotoLogin";
            return false;
        }
    }
    if (client =='android'){
        window.jiudouyu.login();
        return false;
    }
    window.location.href='/login';
})

</script>
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
