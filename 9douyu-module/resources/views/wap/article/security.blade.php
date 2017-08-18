@extends('wap.common.wapBase')
@section('title', '安全保障')
@section('css')
<link rel="stylesheet" type="text/css" href="{{assetUrlByCdn('/static/weixin/css/reset12.css')}}"/>
<script>
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
            } else if (screen.height > 500) {
                _html.style.fontSize = view_width / 18 + 'px';
                if (screen.height == 1280 && screen.width == 800) {
                    _html.style.fontSize = view_width / 22 + 'px';
                }
            } else {

                _html.style.fontSize = 15 + 'px'
            }

        }
        ready(function () {
            ready_rem();
        });

    </script>
<link rel="stylesheet" type="text/css" href="{{assetUrlByCdn('/static/weixin/css/security.css')}}" />
<link rel="stylesheet" type="text/css" href="{{assetUrlByCdn('/static/weixin/css/animations.css')}}" />
<link rel="stylesheet" type="text/css" href="{{assetUrlByCdn('/static/weixin/css/animate.min.css')}}" />

@endsection

@section('content')
<div>
<div class="page page-1-1 page-current">
    <div class="wrap">
        <div class="title pt-page-scaleUp">集团实力</div>
        <div class="page1-main">
           <p class="animated fadeInRight">九斗鱼隶属于国内领先的综合性金融服务集团——耀盛投资管理集团（以下简称“耀盛中国”）。耀盛中国成立于2006年，拥有小额贷款、商业保理、融资租赁、企业征信、私募基金管理人、香港放债人、香港证券交易等多张中国内地、香港金融牌照，目前已建立起中国最大的中小企业金融服务体系。</p> 
           <p class="animated fadeInLeft">过去11年，耀盛中国完成了商业银行、投资银行、金融科技三大板块业务的全覆盖，金融服务横跨中港两地，累计帮助超过8万余家中小企业，年均创造经济总产值逾300亿元。</p>
           <div class="page1-txt animated fadeInRight"><span><img src="{{assetUrlByCdn('/static/weixin/images/security/pic1.jpg')}}"/></span>商业银行、投资银行、金融科技三大板块业务的全覆盖</div> 
           <div class="page1-txt animated fadeInRight"><span><img src="{{assetUrlByCdn('/static/weixin/images/security/pic2.jpg')}}"/></span>金融服务横跨中港两地，累计帮助超过8万余家中小企业</div> 
           <div class="page1-txt animated fadeInRight"><span><img src="{{assetUrlByCdn('/static/weixin/images/security/pic3.jpg')}}"/></span>年均创造经济总产值逾300亿元</div> 
        </div>
        <img class="news-arrowUp pt-page-moveIconUp" src="{{assetUrlByCdn('/static/weixin/images/security/arrow.png')}}"/>
    </div>
</div>
<!-- page2-->
<div class="page page-2-1 hide">
    <div class="wrap">
        <div class="title pt-page-scaleUp">业务合规</div>
        <div class="security-info animated fadeInLeft">
            <span><img src="{{assetUrlByCdn('/static/weixin/images/security/icon1.jpg')}}"/></span>
            <p><strong>拥抱监管</strong></p>
            <p>九斗鱼积极拥抱监管，严格遵守相关法律法规，坚持依法合规经营、持续健康发展，切实保障出借人利益。</p>
        </div>
        <div class="security-info animated fadeInRight">
            <span><img src="{{assetUrlByCdn('/static/weixin/images/security/icon2.jpg')}}"/></span>
            <p><strong>信息披露</strong></p>
            <p>对平台基本信息及业务运营必要信息进行真实、完整、准确、及时地披露，并定期发布经营报告。</p>
        </div>
        <div class="security-info animated fadeInLeft">
            <span><img src="{{assetUrlByCdn('/static/weixin/images/security/icon3.jpg')}}"/></span>
            <p><strong>小额分散</strong></p>
            <p>九斗鱼坚持小额分散原则，平均借贷金额5万-30万。</p>
        </div>
        <div class="security-info animated fadeInRight">
            <span><img src="{{assetUrlByCdn('/static/weixin/images/security/icon4.jpg')}}"/></span>
            <p><strong>电子合同</strong></p>
            <p>平台交易合同均为具有法律效力的电子合同。由第三方电子数据保全机构——易保全，提供电子签章及合同保全服务。</p>
        </div>
        <img class="news-arrowUp pt-page-moveIconUp" src="{{assetUrlByCdn('/static/weixin/images/security/arrow.png')}}"/>
    </div>
</div>

<!-- page3-->
<div class="page page-3-1 hide">
    <div class="wrap">
        <div class="title pt-page-scaleUp">专业风控</div>
        <div class="security-risk animated fadeInRight">
            <p><strong>RISKCALC风控系统科学评估逾期风险</strong></p>
            <p>九斗鱼采用母公司耀盛中国自主研发的RISKCALC中小企业信用风险评价技术，对借款方进行超过260项定性、定量指标的审核与评估，以科学的计算结果判断企业的还款能力，预估逾期风险。</p>
        </div>
        <div class="security-risk-icon animated fadeInLeft">
            <img class="img1" src="{{assetUrlByCdn('/static/weixin/images/security/img1.jpg')}}"/>
        </div>
        <div class="security-risk animated fadeInRight">
            <p><strong>接入行业黑名单数据甄别问题借款人</strong></p>
            <p>九斗鱼接入多家权威外部数据系统，有效排查欺诈、一人多贷等问题借款情况。利用大数据技术，对借贷方进行全方位数据分析。</p>
        </div>
        <div class="security-risk-icon animated fadeInLeft">
            <img class="img2" src="{{assetUrlByCdn('/static/weixin/images/security/img2.jpg')}}"/>
        </div>
        <img class="news-arrowUp pt-page-moveIconUp" src="{{assetUrlByCdn('/static/weixin/images/security/arrow.png')}}"/>
    </div>
</div>

<!-- page4-->
<div class="page page-4-1 hide">
    <div class="wrap">
        <div class="title pt-page-scaleUp">技术保障</div>
        <div class="security-info animated fadeInRight">
            <span><img src="{{assetUrlByCdn('/static/weixin/images/security/icon5.jpg')}}"/></span>
            <p><strong>数据安全</strong></p>
            <p>建立了完善的数据备份机制，可实现异地实时备份数据和定时恢复。对于核心交易数据进行实时签名检测、非法篡改秒级报警隔离，有效防范数据篡改。</p>
        </div>
        <div class="security-info animated fadeInLeft">
            <span><img src="{{assetUrlByCdn('/static/weixin/images/security/icon6.jpg')}}"/></span>
            <p><strong>架构安全</strong></p>
            <p>对核心系统与非核心系统进行物理隔离，避免单点漏洞导致关联系统受影响。</p>
        </div>
        <div class="security-info animated fadeInRight">
            <span><img src="{{assetUrlByCdn('/static/weixin/images/security/icon7.jpg')}}"/></span>
            <p><strong>安全检测</strong></p>
            <p>对整个IT系统进行渗透测试，杜绝安全漏洞。建立流量攻击防护措施，避免恶意攻击造成服务中断。</p>
        </div>
        <div class="security-info animated fadeInLeft">
            <span><img src="{{assetUrlByCdn('/static/weixin/images/security/icon8.jpg')}}"/></span>
            <p><strong>灾备应急</strong></p>
            <p>制定了多套灾难恢复预案，每日例行演练。确保系统出现严重故障时，自动化执行重建程序，快速恢复正常运行。</p>
        </div>
    </div>
</div>
</div>
<script src="{{assetUrlByCdn('/static/weixin/js/zepto.min.js')}}"></script>
<script src="{{assetUrlByCdn('/static/weixin/js/touch.js')}}"></script>
<script src="{{assetUrlByCdn('/static/weixin/js/security.js')}}"></script>
@endsection
