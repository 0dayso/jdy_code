@extends('wap.common.wapBase')

@section('title', '315中国消费市场影响力品牌')

@section('keywords', env('META_KEYWORD'))

@section('description', env('META_DESCRIPTION'))

@section('css')
<meta name="format-detection" content="telephone=yes">
<link rel="stylesheet" type="text/css" href="{{ assetUrlByCdn('/static/weixin/activity/zt315/css/index.css') }}">
@endsection

@section('content')
<script>
    
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
        if (view_width > 750) {
            _html.style.fontSize = 750 / 16 + 'px'
        } else {
            _html.style.fontSize = view_width / 16 + 'px';
        }
        
    }
    ready(function () {
        ready_rem();
    });

</script>
    
    <img src="{{ assetUrlByCdn('/static/weixin/activity/zt315/images/page-banner2.png') }}" class="page-banner" alt="打造精品 树立品牌">
    <div class="por">
         <img src="{{ assetUrlByCdn('/static/weixin/activity/zt315/images/page-banner4.png') }}" class="page-banner" alt="打造精品 树立品牌">
        <a href="https://v.qq.com/x/page/f0385wx2qxh.html" class="btn-video">点击观看视频</a>
    </div>
    <br>
    <img src="{{ assetUrlByCdn('/static/weixin/activity/zt315/images/page-title1.png') }}" class="page-title page-title1" alt="评选背景">
    <div class="page-border">
        <p>
            随着社会经济的不断发展，企业产品（服务）销售模式发生了重大变革，如何跟上形势、把握先机、获得更多的市场占有率，实现产品（服务）的销售是当前企业亟待解决的问题，本次会议将对此展开讨论，并寻求解决之道。为此，举办由中国消费经济高层论坛组委会、商务部研究院消费经济研究部支持，消费日报社主办，以“打造精品树立名牌”为研讨主题的2017年“中国消费市场影响力牌（产品）”推荐研讨活动暨颁奖盛典。
        </p>
    </div>

<img src="{{ assetUrlByCdn('/static/weixin/activity/zt315/images/page-title2.png') }}" class="page-title" alt="媒体报道">
<div>
    <div class="swiper-container">
        <div class="swiper-wrapper">
            <div class="swiper-slide">
                <a href="http://industry.caijing.com.cn/20170315/4247801.shtml"><img src="{{ assetUrlByCdn('/static/weixin/activity/zt315/images/page-swiper1.png') }}" alt=""></a>
               	<a href="http://finance.ce.cn/rolling/201703/15/t20170315_21025584.shtml"><img src="{{ assetUrlByCdn('/static/weixin/activity/zt315/images/page-swiper2.png') }}" alt=""></a>
            </div>
            <div class="swiper-slide">
                <a href="http://www.zgswcn.com/2017/0315/768763.shtml"><img src="{{ assetUrlByCdn('/static/weixin/activity/zt315/images/page-swiper3.png') }}" alt=""></a>
               	<a href="http://e.xfrb.com.cn/news-20170315-A10-2795.html"><img src="{{ assetUrlByCdn('/static/weixin/activity/zt315/images/page-swiper4.png') }}" alt=""></a>
            </div>
           
        </div>
		<!-- Add Pagination -->
        <!-- <div class="swiper-pagination"></div> -->
        <!-- Add Arrows -->
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
    </div>
</div>


<img src="{{ assetUrlByCdn('/static/weixin/activity/zt315/images/page-title3.png') }}" class="page-title" alt="参会嘉宾">
<div class="page-border page-txt2">
    <p><span>高延敏</span>工信部消费品司司长</p>
    <p><span>李同喜</span>中国轻工业管理协会副理事长</p>
    <p><span>武高汉</span>中国消费者权益保护法研究会发言人、常务理事</p>
    <p><span>赵&nbsp;&nbsp;&nbsp;&nbsp;萍</span>消费经济专家、商务部研究院消费经济研究部副主任</p>
    <p><span>洪&nbsp;&nbsp;&nbsp;&nbsp;涛</span>商业经济研究所所长、北京工商大学经济学博士</p>
</div>
<img src="{{ assetUrlByCdn('/static/weixin/activity/zt315/images/page-title4.png') }}" class="page-title" alt="评选标准">
<div class="page-border page-txt3">
    <p><em></em>产品具有较高美誉度和影响力，守法经营，无负面事件，注重产品质量。</p>
    <p><em></em>具有良好的企业发展战略和文化。</p>
    <p><em></em>具有较高的管理水平和良好的财务状况。</p>
    <p><em></em>具有行业示范作用，在行业发展中具有较强的带动性或带动潜力。</p>
    <p><em></em>整体发展水平在同行业居于领先地位，或具有领先地位的潜力。</p>
    <p><em></em>具有较大发展潜力和较高的成长性。</p>
</div>
<img src="{{ assetUrlByCdn('/static/weixin/activity/zt315/images/page-title7.png') }}" class="page-title" style="width: 12rem;" alt="主要参选品牌">
<img src="{{ assetUrlByCdn('/static/weixin/activity/zt315/images/page-logo4.png') }}" class="page-logo" alt="品牌logo">
<img src="{{ assetUrlByCdn('/static/weixin/activity/zt315/images/page-title6.png') }}" class="page-title" alt="媒体支持">
<img src="{{ assetUrlByCdn('/static/weixin/activity/zt315/images/page-logo2.png') }}" class="page-logo" alt="媒体logo">



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