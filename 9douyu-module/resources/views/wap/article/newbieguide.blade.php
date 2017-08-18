@extends('wap.common.wapBase')

@section('title', '新手指引')

@section('keywords', "{{env('META_KEYWORD')}}")

@section('description', "{{env('META_DESCRIPTION')}}")
@section('css')
    <link rel="stylesheet" href="{{ assetUrlByCdn('/static/app/css/app.css') }}" type="text/css" />
    <style type="text/css">
        body{background-color: #fff;}
        .newbieguide-nav{padding:0.25rem 0.75rem 0; width: 100%;  overflow: hidden; background-color: #fff; box-sizing: border-box; margin-bottom: 1.35rem;}
        .newbieguide-nav li{float: left; width: 33.333%; text-align: center;height:1.6rem;-webkit-tap-highlight-color: rgba(0,0,0,0);}
        .newbieguide-nav li:visited,.newbieguide-nav li:active,.newbieguide-nav li:hover,.newbieguide-nav li:focus{background-color: #fff !important;}
        .newbieguide-nav.second span{width: 3.3rem;}
        .newbieguide-nav span{display: inline-block; width: 2.6rem; height:1.55rem; line-height:1.55rem; font-size: 0.6rem;}
        .newbieguide-nav li.active span{border-bottom: 1px solid #1151b5; color: #1151b5;}
        .newbieguide-main{ display: none; padding-bottom: 1rem;}
        .newbieguide-title{text-align: center; background: url('/static/app/images/newbieguide/title-bg.png') top center no-repeat; background-size: 4.35rem 3.6rem;padding-right: 0.25rem; height: 3.6rem; margin-bottom: 1rem; }
        .newbieguide-title p{font-size: 0.7rem;line-height: 1.175rem;  color: #1152b5; font-size: 0.7rem; margin-bottom: 0.5rem;}
        .title-num{ color: #fff;font-size: 0.6rem;}
        
        .mb30{margin-bottom: 0.75rem;}
        .mb50{margin-bottom: 1.25rem;}

        .blue{color: #569eea;}
        .mb40{margin-bottom: 1rem;}
        .newbie-main{padding: 1.25rem 0.75rem 0;}
        .newbie-txt{text-align: center;line-height: 2; font-size: 0.55rem;}
        .newbie-txt img{width: 14.55rem;}
        .newbie-line{margin: 1rem 0 1.25rem;}
        .newbieguide-nav2{padding-bottom: 1.5rem; padding-top: 0.5rem; width: 100%; box-sizing: border-box; padding-left: 0.15rem;}
        .newbieguide-nav2 li{float: left; box-sizing: border-box; position: relative; width: 3.8rem; text-align: center; height: 1.3rem; line-height: 1.3rem;  background-color: #fff; background-clip: content-box; color: #77cdff; border:1px solid #def8fc; font-size: 0.6rem; border-radius: 2px;-webkit-tap-highlight-color: rgba(0,0,0,0); }
        .newbieguide-nav2 li.active{color: #fff;background-color: #77cdff;border:1px solid #77cdff;}
        .newbieguide-nav2 li span{color: #def8fc; margin-right: 0.35rem;}
        .newbieguide-nav2 li:before{display: block; content: '';position: absolute; left: -3px; top:-3px; right: -3px; bottom: -3px; border:1px solid #def8fc;border-radius: 2px;}
        .newbieguide-nav2 li.active:before{border-color:#77cdff }
        .newbieguide-nav2 li:nth-child(2){margin:0 1.2rem;}
        .newbieguide-nav2 li.active span{color: #fff;}
        .clearfix:before,.clearfix:after{display: table;  content: " ";clear: both; }
    </style>
@endsection

@section('content')
    <article>
        <section>
            <img src="{{ assetUrlByCdn('/static/app/images/newbieguide/banner.png') }}" class="img" >
            <div class="newbie-main">
                <div class="newbieguide-title">
                    <p><span class="title-num">1</span></p>
                    <p>来九斗鱼都有什么福利？</p>
                </div>
                <div class="newbie-txt">
                    <p class="blue">小鱼儿为新用户们带来了呛到辣眼睛的新手福利</p>
                    <p>简！单！粗！暴！</p>
                    <p>注册即送 288元+2张加息券（加息高达4%）</p>
                    <img src="{{ assetUrlByCdn('/static/app/images/newbieguide/img01.png') }}" class="mb40" >
                </div>
                <div class="newbieguide-title">
                    <p><span class="title-num">2</span></p>
                    <p>我可以投资哪些产品呢？</p>
                </div>
                <div id="tab-main1">
                    <ul class="newbieguide-nav">
                        <li><span>零钱计划</span></li>
                        <li class="active"><span>九省心</span></li>
                        <li><span>闪电付息</span></li>
                    </ul>
                    <div class="newbieguide-main">
                        <img src="{{ assetUrlByCdn('/static/app/images/newbieguide/banner02.png') }}" class="img" >
                        <img src="{{ assetUrlByCdn('/static/app/images/newbieguide/img02_1.png') }}" class="img" >
                    </div>
                    <div class="newbieguide-main" style="display: block;">
                        <img src="{{ assetUrlByCdn('/static/app/images/newbieguide/banner03_1.png') }}" class="img" >
                        <img src="{{ assetUrlByCdn('/static/app/images/newbieguide/img03_1.png') }}" class="img" >
                    </div>
                    <div class="newbieguide-main">
                        <img src="{{ assetUrlByCdn('/static/app/images/newbieguide/banner04_1.png') }}" class="img" >
                        <img src="{{ assetUrlByCdn('/static/app/images/newbieguide/img04_1.png') }}" class="img" >
                    </div>
                </div>
                <div class="newbieguide-title">
                    <p><span class="title-num">3</span></p>
                    <p>九斗鱼有什么特色？</p>
                </div>
                <div id="tab-main2">
                    <ul class="newbieguide-nav second">
                        <li class="active"><span>邀请好友</span></li>
                        <li><span>闪电付息</span></li>
                        <li><span>家庭账户</span></li>
                    </ul>
                    <div class="newbieguide-main" style="display: block;">
                        <img src="{{ assetUrlByCdn('/static/app/images/newbieguide/img05_1.png') }}" class="img" >
                    </div>
                    <div class="newbieguide-main">
                        <img src="{{ assetUrlByCdn('/static/app/images/newbieguide/img06_1.png') }}" class="img" >
                        <img src="{{ assetUrlByCdn('/static/app/images/newbieguide/sdf01.png') }}" class="img mb30" >
                        <img src="{{ assetUrlByCdn('/static/app/images/newbieguide/sdf02.png') }}" class="img mb30" >
                        <img src="{{ assetUrlByCdn('/static/app/images/newbieguide/sdf03.png') }}" class="img mb30" >
                        <img src="{{ assetUrlByCdn('/static/app/images/newbieguide/sdf04.png') }}" class="img mb50" >
                        <img src="{{ assetUrlByCdn('/static/app/images/newbieguide/line.png') }}" class="img" >
                    </div>
                    <div class="newbieguide-main">
                        <img src="{{ assetUrlByCdn('/static/app/images/newbieguide/img07.png') }}" class="img mb30" >
                        <img src="{{ assetUrlByCdn('/static/app/images/newbieguide/family01.png') }}" class="img mb30" >
                        <img src="{{ assetUrlByCdn('/static/app/images/newbieguide/family02.png') }}" class="img mb30" >
                        <img src="{{ assetUrlByCdn('/static/app/images/newbieguide/family03.png') }}" class="img mb40" >
                        <img src="{{ assetUrlByCdn('/static/app/images/newbieguide/line.png') }}" class="img" >
                    </div>
                </div>
                <div class="newbieguide-title">
                    <p><span class="title-num">4</span></p>
                    <p>如何投资？</p>
                </div>
                <div id="tab-main3">
                    <ul class="newbieguide-nav2 clearfix">
                        <li class="active"><span>①</span>注册</li>
                        <li><span>②</span>充值</li>
                        <li><span>③</span>投资</li>
                    </ul>
                    <div class="newbieguide-main" style="display: block;">
                        <img src="{{ assetUrlByCdn('/static/app/images/newbieguide/register_1.png') }}" class="img mb30" >
                        <img src="{{ assetUrlByCdn('/static/app/images/newbieguide/login_1.png') }}" class="img mb30" >
                    </div>
                    <div class="newbieguide-main">
                        <img src="{{ assetUrlByCdn('/static/app/images/newbieguide/recharge.png') }}" class="img mb30" >
                    </div>
                    <div class="newbieguide-main">
                        <img src="{{ assetUrlByCdn('/static/app/images/newbieguide/project.png') }}" class="img mb30" >
                        <img src="{{ assetUrlByCdn('/static/app/images/newbieguide/project2.png') }}" class="img mb30" >
                    </div>
                </div>
            </div>
            
        </section>
    </article>
@endsection

@section('jsScript')
<script type="text/javascript">
document.body.addEventListener('touchstart', function () { });
 $(function(){

    
    // 导航切换
    $("#tab-main1 .newbieguide-nav li").each(function() {
       $(this).click(function(){
            var index = $(this).index() ;
            $(this).addClass("active").siblings(".newbieguide-nav li").removeClass("active");
            $("#tab-main1 .newbieguide-main").eq(index).show().siblings(".newbieguide-main").hide();
       })
    });

    $("#tab-main2 .newbieguide-nav li").each(function() {
       $(this).click(function(){
            var index = $(this).index() ;
            $(this).addClass("active").siblings(".newbieguide-nav li").removeClass("active");
            $("#tab-main2 .newbieguide-main").eq(index).show().siblings(".newbieguide-main").hide();
       })
    });

    $("#tab-main3 .newbieguide-nav2 li").each(function() {
       $(this).click(function(){
            var index = $(this).index() ;
            $(this).addClass("active").siblings(".newbieguide-nav2 li").removeClass("active");
            $("#tab-main3 .newbieguide-main").eq(index).show().siblings(".newbieguide-main").hide();
       })
    });

});
 </script>
@endsection