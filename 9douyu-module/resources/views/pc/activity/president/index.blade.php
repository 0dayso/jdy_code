@extends('pc.common.layout')

@section('title', '晋升中关村互联网金融行业协会副会长单位')

@section('keywords', env('META_KEYWORD'))

@section('description', env('META_DESCRIPTION'))

@section('csspage')
    <meta name="format-detection" content="telephone=yes">
<style type="text/css">
    body{background-color: #f2f2f3;}
    .p-banner{width: 100%; height: 495px; background: url("{{ assetUrlByCdn('/static/activity/president/images/banner.png') }}") top center no-repeat;}
    .p-summary{padding:65px 0 20px; }
    .p-center{text-align: center;}
    .p-txt{line-height: 38px; font-size: 16px; width: 800px; margin: 38px auto 20px;}
    a.p-link,a.p-link:visited{display: inline-block; width:84px; height: 24px; line-height: 24px; border-radius: 12px; border:1px solid #68839e; color: #466789 }
    a.p-link:hover,a.p-link:active{color:#466789;font-weight: bold;}
    .p-history{background-color: #6eb3e2;}
    .wrap{position: relative;}
    .p-social{padding-top: 60px;}
    a.p-btn,a.p-btn:visited{position: absolute; right: 0; text-align: center; width:84px; height: 24px; line-height: 24px; border-radius: 12px; border:1px solid #fff; color: #fff;}
    a.p-btn:hover,a.p-btn:active{color: #fff; font-weight: bold;}
    .p-btn.p-btn1{top: 450px;}
    .p-btn.p-btn2{top: 552px;}
    .p-btn.p-btn3{top: 858px;}
    .p-btn.p-btn4{top: 1166px;}
</style>
@endsection
@section('content')
    <div class="p-banner"></div>
    <div class="wrap">
        <div class="p-summary">
            <div class="p-center"><img src="{{ assetUrlByCdn('/static/activity/president/images/img01.jpg') }}" height="374" width="496"  alt=""></div>
            <div class="p-txt">中关村互联网金融行业协会，是中国首家互联网金融行业协会，由中关村管委会和北京市民政局进行指导。九斗鱼于2015年3月沟通入会以来认真践行协会宗旨，坚持合法合规、严守风险控制，自觉开展信息披露和出借人教育、积极推进资金存管等工作，努力为互联网金融行业的规范自律、 健康发展和创新发展做出表率，积极推动互联网金融行业健康规范，于2016年3月被授予中关村金融行业协会副会长单位。</div>
            <div class="p-center"><a href="http://stock.jrj.com.cn/2016/12/27122621892512.shtml" class="p-link" target="_blank">媒体报道</a></div>
        </div>
    </div>
    <div class="p-history">
        <div class="wrap">
            <img src="{{ assetUrlByCdn('/static/activity/president/images/img02.png') }}" width="1002" height="1258"  alt="">
            <a rel="example_group" href="{{assetUrlByCdn('/static/activity/president/images/1.png')}}" class="p-btn p-btn1">点击查看</a>
            <a rel="example_group" href="{{assetUrlByCdn('/static/activity/president/images/3.png')}}" class="p-btn p-btn3">点击查看</a>
            <a rel="example_group" href="{{assetUrlByCdn('/static/activity/president/images/4.png')}}" class="p-btn p-btn4">点击查看</a>
        </div>
    </div>
    <div class="wrap">
        <div class="p-social">
            <div class="p-center"><img src="{{ assetUrlByCdn('/static/activity/president/images/img03.jpg') }}" height="530" width="496"  alt=""></div>
            <div class="p-txt">九斗鱼的运营方及核心股东广泛参与互联网金融行业共建发展，并获得了社会各界的广泛关注和赞誉。除了当选中关互联网金融行业协会副会长单位，还拥有中国支付清算协会小微金融风险信息共享平台首批接入单位、中国中小企业协会常务理事单位、广东省商业保理协会常务副会长单位、中国服务贸易协会商业保理专业委员会会员单位等社会荣誉。</div>
        </div>
    </div>
@endsection

@section('jspage')
    <script type="text/javascript">

        $(function(){

                $("a[rel=example_group]").fancybox({
                    'transitionIn'      : 'none',
                    'transitionOut'     : 'none',
                    'titlePosition'     : 'over',
                });

        })

    </script>
@endsection
