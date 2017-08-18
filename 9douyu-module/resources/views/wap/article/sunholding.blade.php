@extends('wap.common.wapBase')

@section('title', '耀盛中国')

@section('keywords', "{{env('META_KEYWORD')}}")

@section('description', "{{env('META_DESCRIPTION')}}")
@section('css')
    <link rel="stylesheet" href="{{ assetUrlByCdn('/static/app/css/app.css') }}" type="text/css" />
    <style type="text/css">
        body{background-color: #fff;}
        .sunholding-nav{padding:0.25rem 0.75rem 0; width: 100%;  overflow: hidden; background-color: #fff; box-sizing: border-box;}
        .nav-fixed{position: fixed;top: 0; left: 0; z-index: 10;}
        .sunholding-nav li{float: left; width: 33.333%; text-align: center;height:1.6rem;-webkit-tap-highlight-color: rgba(0,0,0,0);}
        .sunholding-nav li:visited,.sunholding-nav li:active,.sunholding-nav li:hover,.sunholding-nav li:focus{background-color: #fff !important;}
        .sunholding-nav span{display: inline-block; width: 2.6rem; height:1.55rem; line-height:1.55rem; font-size: 0.6rem;}
        .sunholding-nav li.active span{border-bottom: 1px solid #1151b5; color: #1151b5;}
        .sunholding-main{padding:1.8rem 0.75rem; display: none;}
        .sunholding-title{text-align: center; background-position: top center; background-repeat: no-repeat; background-size: 4.35rem 3.6rem;padding-right: 0.25rem; height: 3.6rem; margin-bottom: 1rem; }
        .sunholding-title p{font-size: 0.7rem;line-height: 1.175rem;  color: #1152b5; font-size: 0.7rem; margin-bottom: 0.5rem;}
        .title-num{ color: #fff;font-size: 0.6rem;}
        .sunholding-txt{padding-bottom: 1rem;}
        .sunholding-txt p{line-height:1.1rem; font-size: 0.55rem;   }
        .cer01{width: 9.6rem;}
        .cer02{width: 10.35rem;}
        .cer03{width: 11.4rem;}
        .cer04{width: 11.35rem;}
        .sunholding-box{text-align: center; border:1px dashed #2880e4; padding:0.5rem 0; margin-bottom: 0.65rem; border-radius: 0.25rem;}
        .sunholding-box p{text-align: center; font-size: 0.55rem;line-height: 1.2rem;}
        .sunholding-box p strong{font-size: 0.65rem;}
        .sunholding-box p.lh1{line-height: 1rem;}
        .mb10{margin-bottom: 0.25rem;}
        .mb30{margin-bottom: 0.75rem;}
        .mb50{margin-bottom: 1.25rem;}
        .mb74{margin-bottom: 1.85rem;}
    </style>
@endsection

@section('content')
    <article>
        <section>
            <img src="{{ assetUrlByCdn('/static/app/images/sunholding/banner.png') }}" class="img" id="sunholding-banner">
            <ul class="sunholding-nav">
                <li class="active"><span>九斗鱼</span></li>
                <li><span>耀盛小贷</span></li>
                <li><span>瑞思科雷</span></li>
            </ul>
            <div class="sunholding-main" style="display: block;">
                <div class="sunholding-title" data-img="{{ assetUrlByCdn('/static/app/images/sunholding/title-bg.png') }}">
                    <p><span class="title-num">1</span></p>
                    <p>九斗鱼大股东 — 耀盛中国</p>
                </div>
                <div class="sunholding-txt">
                    <p class="t2"><strong>11年，1项核心技术，8万家小微，300亿价值。</strong></p>
                    <p class="t2">耀盛中国，一家成立于2006年的综合化现代金融服务集团，为九斗鱼大股东。
十一年来，我们深耕“中小企业金融生态圈”，已建立起扎实、完整的中小企业金融服务体系，全心全意为中小企业提供系统化、定制化的金融服务解决方案，用心服务中小企业从初创、成长、成熟的全生命周期。</p>
                    <p class="t2">我们自主掌握着中国中小企业信用评价的核心系统——瑞思科雷RISKCALC中小企业信用评级专利技术(专利号：2014sr137684)，并运用十一年积累的移动互联网、大数据、云计算、机器学习、人工智能等多项金融科技成果，向中小微企业提供了多点接触、一站满足的完整金融服务平台，切实解决着中国中小企业融资难、 融资贵的大问题。</p>
                    <p class="t2">我们服务的重点行业涉及大消费、大健康、大文化、互联网四个领域,累计服务超过8万余家中小企业, 年均创造经济总产值超 300 亿元。</p>
                    <p class="t2 mb30">耀盛中国业务涵盖企业征信、小额信贷、商业保理、融资租赁、股权投资、互联网金融、消费金融、电影金融、海外投资、智能支付管理等多业务板块。集团旗下包括耀盛商业保理有限公司、耀江融资租赁有限公司、北京耀盛小额贷款有限公司、北京汉泰基金管理中心、耀盛影业有限公司、瑞思科雷征信有限公司、星果时代信息技术有限公司、星果科技有限公司、北京耀汉网络科技有限公司、耀盛财富管理有限公司等定位清晰、运营良好的商业主体。</p>
                    <img src="{{ assetUrlByCdn('/static/app/images/sunholding/structure.png') }}" class="img mb10">
                </div>
                <div class="sunholding-title">
                    <p><span class="title-num">2</span></p>
                    <p>关于九斗鱼</p>
                </div>
                <div class="sunholding-txt">
                    <p class="t2">九斗鱼，正式上线于2014年6月，实缴注册资本金5880万元人民币，是耀盛中国旗下互联网金融平台，倡导共享金融，专注于为普通投资者提供安全、便捷、丰富的互联网理财与投资产品，让老百姓也能分享优质中小企业的发展红利。</p>
                </div>
                <div class="sunholding-box">
                    <p class="lh1">感谢各位鱼客的支持和信赖</p>
                    <p class="lh1">截至到2016年10月10日</p>
                    <p>我们已经服务了 <strong class="blue">1,298,394</strong> 位鱼客</p>
<!--                     <p>累计出借金额 <strong class="blue">2,587,709,875</strong> 元</p>
 -->                </div>
                <div class="sunholding-box">
                    <img src="{{ assetUrlByCdn('/static/app/images/sunholding/cer01.png') }}" class="cer01">
                </div>
            </div>
            <div class="sunholding-main">
                <div class="sunholding-txt">
                    <p class="t2">耀盛小贷于2016年4月经北京市金融工作局批准成立，注册资本金1亿元，专注小额信贷，以金融科技的作业方式、细分市场的创新服务场景见长，正成为小贷行业的领跑者。</p>
                    <p class="t2">耀盛小贷是一家“创新驱动、科技引领、特色鲜明、惠民利民”的小额贷款公司。我们面向传统商业银行不能覆盖、也无法有效服务的中小微企业客户，定制化地提供创新的小贷产品与服务，有效解决了中小微企业小额、分散、快速的资金需求，更“破例”成为一家民营机构全资控股的小额贷款公司。</p>
                </div>
                <div class="sunholding-box">
                    <img src="{{ assetUrlByCdn('/static/app/images/sunholding/cer02.png') }}" class="cer02">
                </div>
            </div>
            <div class="sunholding-main">
                <div class="sunholding-txt">
                    <p class="t2">瑞思科雷成立于2014年9月，注册资本金6000万元，2015年11月获得中国人民银行颁发的《中华人民共和国企业征信业务经营备案证》。</p>
                    <p class="t2">瑞思科雷自主掌握着一项核心专利技术——瑞思科雷RISKCALC中小企业信用评级技术。该技术的主要功能在于判断中小企业真实的资产负债比，清晰描绘出了一张张中小企业“信用身份证”。</p>
                    <p class="t2">瑞思科雷RISKCALC中小企业信用评级技术是在耀盛中国在金融风险管理理论基础上，根据数学计量方法和金融风险管理模型对中小企业信用风险进行建模分析，最终形成的一套中小企业信用评价体系。2010年该技术问世以来，确立了耀盛中国在中小企业信用风险评级领域的行业领先地位。</p>
                </div>
                <div class="sunholding-box">
                    <img src="{{ assetUrlByCdn('/static/app/images/sunholding/cer03.png') }}" class="cer03 mb30">
                    <img src="{{ assetUrlByCdn('/static/app/images/sunholding/cer04.png') }}" class="cer04">
                </div>
            </div>
        </section>
    </article>
@endsection

@section('jsScript')
<script type="text/javascript">
document.body.addEventListener('touchstart', function () { });
 $(function(){


    var titlebg = $('.sunholding-title').data('img')
    $('.sunholding-title').css('background-image','url('+titlebg+')')

    // 导航固定
    function setFixed(){
        var $window = $(window);
        var $body = $('body');
        var $header = $('.sunholding-nav');
        var $banner = $('#sunholding-banner');
        var height = $banner.height();
        var MARGIN = 0;

        $window.on('scroll', function(e) {
            var scrollTop = $(this).scrollTop();

            if (scrollTop > height + MARGIN) {
                $header.addClass('nav-fixed');
                $banner.addClass('mb74');
            } else {
                $header.removeClass('nav-fixed')
                $banner.removeClass('mb74');
            };
        }).trigger('scroll');
    }

    setFixed();

    // 导航切换
    $(".sunholding-nav li").each(function() {
       $(this).click(function(){
            var index = $(this).index() ;
            $(this).addClass("active").siblings(".sunholding-nav li").removeClass("active");
            $(".sunholding-main").eq(index).show().siblings(".sunholding-main").hide();
       })
    });

});
 </script>
@endsection
