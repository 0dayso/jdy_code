@extends('pc.common.layout')

@section('title', '315中国消费市场影响力品牌')

@section('keywords', env('META_KEYWORD'))

@section('description', env('META_DESCRIPTION'))

@section('csspage')
    <meta name="format-detection" content="telephone=yes">
    <link rel="stylesheet" type="text/css" href="{{assetUrlByCdn('/static/activity/zt315/css/zt315.css')}}">
@endsection
@section('content')
<div class="zt315-top">
    <div class="wrap">
        <div class="zt315-bnner por">
            <a href="https://v.qq.com/x/page/f0385wx2qxh.html" class="btn-video">点击观看视频</a>
        </div>
        <div class="zt315-title">评选背景</div>
        <div class="zt315-background">
            <p>随着社会经济的不断发展，企业产品（服务）销售模式发生了重大变革，如何跟上形势、把握先机、获得更多的市场占有率，实现产品（服务）的销售是当前企业亟待解决的问题，本次会议将对此展开讨论，并寻求解决之道。为此，举办由中国消费经济高层论坛组委会、商务部研究院消费经济研究部支持，消费日报社主办，以“打造精品树立名牌”为研讨主题的2017年“中国消费市场影响力牌（产品）”推荐研讨活动暨颁奖盛典。</p>
            <p>本次活动将在全国选择重点行业，评选出、“2017年度中国消费市场行业影响力品牌（产品）”2017年度“中国互联网金融行业最具创新价值品牌（产品）”等奖项，鼓励更多的企业打造自己的品牌，强化品牌意识，让企业品牌成为消费者喜爱，并值得信赖的品牌。</p>
        </div>
    </div>
</div>
<div class="zt315-main">
    <div class="wrap">
        <div class="zt315-title">媒体报道</div>
        <div class="zt315-img">
            <div class="index_tabs" id="index_tabs">
                <ul>
                    <li><img src="{{assetUrlByCdn('/static/activity/zt315/images/img1.png')}}"></li>
                    <li><img src="{{assetUrlByCdn('/static/activity/zt315/images/img2.png')}}"></li>
                    <li><img src="{{assetUrlByCdn('/static/activity/zt315/images/img3.png')}}"></li>
                    <li><img src="{{assetUrlByCdn('/static/activity/zt315/images/img4.png')}}"></li>
                </ul>
                
            </div>
            <div id="index_tabs_prev"></div>
            <div id="index_tabs_next"></div>
        </div>
        <div class="zt315-link">
            <p><a href="http://e.xfrb.com.cn/news-20170315-A10-2795.html" target="_blank">九斗鱼CEO郭鹏：筛好资产、做好自律是互金企业最大的社会责任</a></p>
            <p><a href="http://finance.ce.cn/rolling/201703/15/t20170315_21025584.shtml" target="_blank">坚守金融诚信 九斗鱼荣获最具创新价值品牌</a></p>
            <p><a href="http://www.zgswcn.com/2017/0315/768763.shtml" target="_blank">九斗鱼CEO郭鹏：金融诚信应长期坚守
</a></p>
            <p><a href="http://industry.caijing.com.cn/20170315/4247801.shtml" target="_blank">快金TimeCash获“3·15中国消费市场影响力品牌”大奖
            </a></p>
            
        </div>
        <div class="clear"></div>
        <div class="zt315-title">参会嘉宾</div>
        <ul class="zt315-guest">
            <li><em>高延敏</em><span>工信部消费品司司长</span></li>
            <li><em>李同喜</em><span>中国轻工业管理协会副理事长</span></li>
            <li><em>武高汉</em><span>中国消费者权益保护法研究会发言人、常务理事</span></li>
            <li><em>赵   萍</em><span>消费经济专家、商务部研究院消费经济研究部副主任</span></li>
            <li><em>洪   涛</em><span>商业经济研究所所长、北京工商大学经济学博士</span></li>
        </ul>
        <div class="zt315-title">评选标准</div>
        <div class="zt315-standard">
            <p><span>◆</span>产品具有较高美誉度和影响力，守法经营，无负面事件,注重产品质量。</p>
            <p><span>◆</span>具有良好的企业发展战略和文化。</p>
            <p><span>◆</span>具有较高的管理水平和良好的财务状况。</p>
            <p><span>◆</span>具有行业示范作用,在行业发展中具有较强的带动性或带动潜力。</p>
            <p><span>◆</span>整体发展水平在同行业居于领先地位，或具有领先地位的潜力。</p>
            <p><span>◆</span>具有较大发展潜力和较高的成长性。</p>
        </div>
        <div class="zt315-title title2">部分获奖品牌</div>
        <div class="zt315-brand"></div>
        <div class="zt315-title">媒体支持</div>
        <div class="zt315-media"></div>
    </div>
</div>
@endsection

@section('jspage')
<script type="text/javascript">
    
//海报
jQuery("#index_tabs").jCarouselLite({
    auto:5000,
    speed:300,
    visible:1,
    vertical:false,
    stop:$("#index_tabs"),
    btnGo:$("#index_tabs_li li"),
    btnGoOver:true,
    btnPrev:"#index_tabs_prev",
    btnNext:"#index_tabs_next"
});
</script>
@endsection