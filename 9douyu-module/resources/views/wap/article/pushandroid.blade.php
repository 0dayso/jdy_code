@extends('wap.common.wapBase')

@section('title', '消息推送教程')

@section('keywords', env('META_KEYWORD'))

@section('description', env('META_DESCRIPTION'))

@section('css')
    <meta name="format-detection" content="telephone=yes">
    <style>
    body{font-weight: 100; background-color: #fff; -webkit-font-smoothing: antialiased;-moz-osx-font-smoothing: grayscale;}
    .push-tc{text-align: center;}
    .push-tl{text-align: left;}
    .push-blue-box{position: relative; padding:0.75rem 1rem; color: #fff; line-height: 1.2rem; background-color: #60b7fc;}
    .push-blue-box.top{font-size: 0.65rem; margin-bottom: 1.5rem;}
    .push-blue-box.bottom{font-size: 0.65rem;}
    .push-blue-box.bottom span{font-weight: 400;}
    .push-blue{color: #60b7fc;}
    .push-main{text-align: center; line-height: 1.2rem; font-size: 0.7rem; padding:0 1rem 2rem;}
    .push-img{width: 13rem; height:17.65rem ; margin-bottom: 0.5rem;}
    .push-mb1{margin-bottom: 0.75rem;}
    .push-mb2{margin-bottom: 1.5rem;}
    .push-line{width: 100%; height: 1px; overflow: hidden; border-bottom: 1px dashed #ccc; margin-bottom: 0.75rem; transform: scaleY(0.5);-webkit-transform: scaleY(0.5);}
    .push-light{width: 0.85rem; height: 0.85rem; margin-right: 0.3rem; vertical-align: middle;}
    .push-arrow{position: absolute; left: 50%; bottom:-0.5rem;width:0;
    height:0;border-width:0.5rem 0.6rem 0;border-style:solid;border-color:#60b7fc transparent transparent; margin-left: -0.6rem}
    .push-f13{font-size: 0.65rem;}
    .push-title{position: relative; margin-bottom: 1.25rem;}
    
</style>
@endsection

@section('content')
    <section class="push-blue-box top">
        <p><img src="{{assetUrlByCdn('/static/weixin/activity/push/images/icon-light.png')}}" class="push-light">开启九斗鱼推送通知方便接收账户资金变动、项目回款等一系列重要通知。</p>
        <p>现在，小鱼儿就拿4种品牌的安卓手机举例（小米、华为、三星、OPPO），告诉你怎么样打开推送通知。</p>
        <i class="push-arrow"></i>
    </section>
    <!-- 小米 -->
    <section class="push-main">
        <p class="push-title">首先我们来看看小米手机怎样打开通知</p>
        <p class="push-mb1">第一步：点击手机中的<span class="push-blue">【设置】</span></p>
        <p><img src="{{assetUrlByCdn('/static/weixin/activity/push/images/android-img1.png')}}" class="push-img"></p>
        <div class="push-line"></div>
        <p class="push-mb1">第二步：点击设置中的<span class="push-blue">【通知和状态栏】</span></p>
        <p><img src="{{assetUrlByCdn('/static/weixin/activity/push/images/android-img2.png')}}" class="push-img"></p>
        <div class="push-line"></div>
        <p class="push-mb1">第三步：找到通知中的<span class="push-blue">【九斗鱼】</span></p>
        <p><img src="{{assetUrlByCdn('/static/weixin/activity/push/images/android-img3.png')}}" class="push-img"></p>
        <div class="push-line"></div>
        <p class="push-mb1">第四步：找到以下关键按钮并打开</p>
        <p class="push-f13 push-tl">点击【允许通知】，以及【悬浮通知】，【锁屏通知】、等有关按钮。</p>
    </section>
    <section class="push-blue-box bottom push-mb2">
        <p><span>结束：</span></p>
        <p>这样操作下来，九斗鱼在小米手机中的通知开关就打开了。</p>
    </section>
    <!-- end 小米 -->

    <!-- 华为 -->
    <section class="push-main">
        <p class="push-title">接下来我们来看看华为手机怎样打开通知</p>
        <p class="push-mb1">第一步：点击手机中的<span class="push-blue">【设置】</span></p>
        <p><img src="{{assetUrlByCdn('/static/weixin/activity/push/images/android-img4.png')}}" class="push-img"></p>
        <div class="push-line"></div>
        <p class="push-mb1">第二步：点击设置中的<span class="push-blue">【通知中心】</span></p>
        <p><img src="{{assetUrlByCdn('/static/weixin/activity/push/images/android-img5.png')}}" class="push-img"></p>
        <div class="push-line"></div>
        <p class="push-mb1">第三步：找到通知中的<span class="push-blue">【九斗鱼】</span></p>
        <p><img src="{{assetUrlByCdn('/static/weixin/activity/push/images/android-img6.png')}}" class="push-img"></p>
        <div class="push-line"></div>
        <p class="push-mb1">第四步：找到以下关键按钮并打开</p>
        <p class="push-f13 push-tl">点击【允许通知】，以及【优先显示】，【锁屏】等有关按钮。</p>
    </section>
    <section class="push-blue-box bottom push-mb2">
        <p><span>结束：</span></p>
        <p>这样操作下来，九斗鱼在华为手机中的通知开关就打开了。</p>
    </section>
    <!-- end 华为 -->

    <!-- 三星 -->
    <section class="push-main">
        <p class="push-title">现在我们来看看三星手机怎样打开通知</p>
        <p class="push-mb1">第一步：点击手机中的<span class="push-blue">【设置】</span></p>
        <p><img src="{{assetUrlByCdn('/static/weixin/activity/push/images/android-img7.png')}}" class="push-img"></p>
        <div class="push-line"></div>
        <p class="push-mb1">第二步：点击设置中的<span class="push-blue">【应用程序】</span></p>
        <p><img src="{{assetUrlByCdn('/static/weixin/activity/push/images/android-img8.png')}}" class="push-img"></p>
        <div class="push-line"></div>
        <p class="push-mb1">第三步：找到应用程序通知中的<span class="push-blue">【九斗鱼】</span></p>
        <p><img src="{{assetUrlByCdn('/static/weixin/activity/push/images/android-img9.png')}}" class="push-img"></p>
        <div class="push-line"></div>
        <p class="push-mb1">第四步：找到以下关键按钮</p>
        <p class="push-f13 push-tl">点击打开【在弹出窗口中预览】，点击关闭【阻止通知】【在状态上隐藏通知】</p>
    </section>
    <section class="push-blue-box bottom push-mb2">
        <p><span>结束：</span></p>
        <p>这样操作下来，九斗鱼在三星手机中的通知开关就打开了。</p>
    </section>
    <!-- end 三星 -->

    <!-- OPPO -->
    <section class="push-main">
        <p class="push-title">最后我们来看看OPPO手机怎样打开通知</p>
        <p class="push-mb1">第一步：点击手机中的<span class="push-blue">【设置】</span></p>
        <p><img src="{{assetUrlByCdn('/static/weixin/activity/push/images/android-img10.png')}}" class="push-img"></p>
        <div class="push-line"></div>
        <p class="push-mb1">第二步：点击设置中的<span class="push-blue">【通知中心】</span></p>
        <p><img src="{{assetUrlByCdn('/static/weixin/activity/push/images/android-img11.png')}}" class="push-img"></p>
        <div class="push-line"></div>
        <p class="push-mb1">第三步：找到通知中心里的<span class="push-blue">【九斗鱼】</span></p>
        <p><img src="{{assetUrlByCdn('/static/weixin/activity/push/images/android-img12.png')}}" class="push-img"></p>
        <div class="push-line"></div>
        <p class="push-mb1">第四步：找到以下关键按钮并打开</p>
        <p class="push-f13 push-tl">点击【允许通知】、以及【在状态栏显示】【在锁屏上显示】等有关按钮。</p>
    </section>
    <section class="push-blue-box bottom">
        <p><span>结束：</span></p>
        <p>这样操作下来，九斗鱼在OPPO手机中的通知开关就打开了，很简单吧？</p>
        <p>快去九斗鱼投资吧！</p>
    </section>
    <!-- end OPPO -->
@endsection




