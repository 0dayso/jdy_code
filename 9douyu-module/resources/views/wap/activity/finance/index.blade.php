@extends('wap.common.wapBase')

@section('title', '普付宝荣获⋯⋯')

@section('keywords', env('META_KEYWORD'))

@section('description', env('META_DESCRIPTION'))

@section('css')
<meta name="format-detection" content="telephone=yes">
<link rel="stylesheet" type="text/css" href="{{ assetUrlByCdn('/static/weixin/activity/finance/css/index.css') }}">
@endsection

@section('content')
<div class="page-bg">
<div class="page-visit">
       <div class="outer">
           <div class="inner">
               <span class="mark"></span>
            
                <a href="http://mp.weixin.qq.com/s/u_cfWFTYwOgsbKgvetQlmg" class="textarea">
                   <h4>原旭霖2017移动金融大会谈耀盛中国大数据战略：</h4>
                   <p>打通从B到C的完整金融服务链条</p>
                   <span></span>
                </a>
              
           </div>
       </div>
    </div>
    <!-- news -->

    <div class="page-title title1">
        <h3>新闻动态</h3>
        <p>JOURNALISM</p>
    </div>
   
    <div class="swiper-container">
        <div class="swiper-wrapper">
            <div class="swiper-slide">
                <a href="http://www.mpaypass.com.cn/news/201703/28094034.html "><img src="{{assetUrlByCdn('/static/weixin/activity/finance/images/page-swiper-img1.jpg')}}" alt=""></a>
                <a href="http://www.iyiou.com/p/43824 "><img src="{{assetUrlByCdn('/static/weixin/activity/finance/images/page-swiper-img2.jpg')}}" alt=""></a>
            </div>
            <div class="swiper-slide">
                <a href="http://www.investorchina.com.cn/article-27030-1.html?winzoom=1 "><img src="{{assetUrlByCdn('/static/weixin/activity/finance/images/page-swiper-img3.jpg')}}" alt=""></a>
                <a href="http://sczg.chinareports.org.cn/news-3554-3313.html"><img src="{{assetUrlByCdn('/static/weixin/activity/finance/images/page-swiper-img4.jpg')}}" alt=""></a>
            </div>
           
        </div>
        <!-- Add Pagination -->
        <!-- <div class="swiper-pagination"></div> -->
        <!-- Add Arrows -->
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
    </div>



    <div class="page-introduction">
        <div class="page-title title1">
            <h3>大会简介</h3>
            <p>GENERAL INTRODUCTION</p>
        </div>

        <div class="inner clearfix">
            <p>在2016年，移动金融成为了人们日常生活不可分割的一部分，伴随移动金融大爆发的还有电信诈骗、支付安全、商户服务等一系列的迫切需要解决的问题和服务，2016年也是移动金融政策收紧的一年，96费改、网联、 261号文、账户分类管理等规定的执行，将整个移动金融推向了变革的前沿。到2017年，后政策时代，整个移动金融市场该如何发展呢？在此，2017中国移动金融发展大会，从政策、金融支付、金融风控、消费金融等4个方面，邀请产业链各方全面探讨移动金融发展。</p>
        </div>

    </div>

    <div class="page-title title1">
        <h3>参会嘉宾</h3>
        <p>PARTICIPANTS</p>
    </div>



    <div class="page-participator clearfix">
        <ul>
            <li class="pepole1">
                <a href="javascript:;">
                    <span></span>
                    <p>人民银行科技司<br>原副司长</p>
                </a>
                <div class="name">李晓枫</div>
            </li>
            <li class="pepole2">
                <a href="javascript:;">
                    <span></span>
                    <p>中国工商银行总行<br>信息科技部副总经理</p>
                </a>
                <div class="name">张颖</div>
            </li>
            <li class="pepole3">
                <a href="javascript:;">
                    <span></span>
                    <p>耀盛中国总裁<br>普付宝执行总裁</p>
                </a>
                <div class="name">原旭霖</div>
            </li>
            <li class="pepole4">
                <a href="javascript:;">
                    <span></span>
                    <p>北京移动金融产业<br>联盟秘书长</p>
                </a>
                <div class="name">班廷伦</div>
            </li>
            <li class="pepole5">
                <a href="javascript:;">
                    <span></span>
                    <p>中国电子技术标准化<br>研究院技术总监</p>
                </a>
                <div class="name">王立建</div>
            </li>
            <li class="pepole6">
                <a href="javascript:;">
                    <span></span>
                    <p>小米支付创新事业部<br>运营负责人</p>
                </a>
                <div class="name">赵武汉</div>
            </li>
        </ul>
    </div>

    <div class="page-title title1">
        <h3>议题回顾</h3>
        <p>ISSUE REVIEW</p>
    </div>
    <div class="page-issue1"></div>
    <div class="page-issue2"></div>

    <div class="page-title title1">
        <h3>部分获奖企业</h3>
        <p>SOME WINNERS</p>
    </div>
    <div class="page-winner"></div>


    <div class="page-title title1">
        <h3>媒体支持</h3>
        <p>MEDIA</p>
    </div>
    <div class="page-media"></div>
</div>
    







@endsection

@section('footer')

@endsection

@section('jsScript')
<script src="{{ assetUrlByCdn('/static/weixin/activity/zt315/js/swiper3.1.0.jquery.min.js') }}" type="text/javascript"></script>

<script type="text/javascript">

    var swiper = new Swiper('.swiper-container', {
        pagination: '.swiper-pagination',
        paginationClickable: true,
        autoplay: 3000,
        loop: true,
        nextButton: '.swiper-button-next',
        prevButton: '.swiper-button-prev',
    });






</script>
@endsection