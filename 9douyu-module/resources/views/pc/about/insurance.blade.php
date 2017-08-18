@extends('pc.common.layout')

@section('title', '安全保障')

@section('content')
    <div class="web-secure-banner"></div>
    {{--<div class="web-bg-white">--}}
        {{--<div class="wrap">--}}
            {{--<div class="web-secure-box1">--}}
                {{--<h2 class="web-secure-title">资金安全</h2>--}}
                {{--<ul class="web-secure-list">--}}
                    {{--<li style="margin:0 100px 0 225px;">--}}
                        {{--<a href="/content/article/reservefund?id=815" target="_blank" class="web-secure-block web-secure-block1"></a>--}}
                        {{--<span>千万风险准备金</span>--}}
                        {{--<p>专款专用，项目逾期第一时间代偿<br><a href="/content/article/reservefund?id=815" target="_blank">查看风险准备金证明</a></p>--}}
                    {{--</li>--}}
                    {{--<li>--}}
                        {{--<a href="/content/article/reservefund?id=815" class="web-secure-block web-secure-block2"></a>--}}
                        {{--<span>东亚银行资金监管</span>--}}
                        {{--<p>耀盛中国同香港东亚银行战略合作<br>全程监管资金流向<br/><a href="/content/article/reservefund?id=815">查看银行合作协议</a></p>--}}
                    {{--</li>--}}
                    {{--<!-- <li>--}}
                        {{--<a href="/static/resource/heightGuarantee.pdf" target="_blank" class="web-secure-block web-secure-block3"></a>--}}
                        {{--<span>第三方担保公司</span>--}}
                        {{--<p>唯达信用担保公司全额担保<br><a href="/static/resource/heightGuarantee.pdf" target="_blank">查看担保合同</a></p>--}}
                    {{--</li>--}}
                    {{--<li>--}}
                        {{--<a href="/static/resource/factorBuyback.pdf" target="_blank" class="web-secure-block web-secure-block4"></a>--}}
                        {{--<span>保理公司回购债权</span>--}}
                        {{--<p>耀盛商业保理对九斗鱼违约债权<br>当日无条件回购<br><a href="/static/resource/factorBuyback.pdf" target="_blank">查看保理合同</a></p>--}}
                    {{--</li> -->--}}
                {{--</ul>--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</div>--}}
    <div class="wrap web-secure-main">
        <h2 class="web-secure-title">风控保障</h2>
        <p>九斗鱼采用先进的RISKCALC®风控评级体系，对借款项目把关<br>专业的信审团队和风控团队，为每个借款项目做严格的尽职调查和风控审核<br>独创的共同借款人制度，目前平台无一笔逾期借款
            {{--<a href="/content/article/riskcontrol.html" target="_blank">点击查看</a>--}}
        </p>
        <p><img src="{{assetUrlByCdn('/static/images/new/web-secure-img1.png')}}"></p>
    </div>
    <div class="web-bg-white">
        <div class="wrap web-secure-main">
            <h2 class="web-secure-title">实力保障</h2>
            <p>耀盛中国旗下平台，实缴注册资本金3亿元<br>11年专注中小企业金融领域服务经验<br>集团业务遍布全国7省，涉及信贷、保理、租赁、企业征信、财富管理等多个领域</p>
            <p><img src="{{assetUrlByCdn('/static/images/new/web-secure-img2-0310.png')}}"></p>
        </div>
    </div>
    <div class="wrap web-secure-main">
        <h2 class="web-secure-title">技术保障</h2>
        <p>支持安全套接层协议和256位加密协议，7*24小时不间断主动备份技术<br>先进的存储机制确保影像和合同文件数据安全<br>技术团队均来自BAT、360等互联网公司，技术实力雄厚</p>
        <p>九斗鱼平台已进行信息系统安全等级（三级）测评&nbsp;&nbsp;<a  href="{{assetUrlByCdn('/static/images/new/insurance-quality.PDF')}}" target="_blank">查看测评结果</a></p>
        <p><img src="{{assetUrlByCdn('/static/images/new/web-secure-img3.png')}}"></p>
    </div>
    <div class="web-bg-white">
        <div class="wrap web-secure-main2">
            <h2 class="web-secure-title">法律保障</h2>
            <div class="web-secure-law">
                <h3>万商天勤律师事务所提供法律顾问</h3>
                <p class="web-secure-law-txt">九斗鱼同经验丰富的万商天勤律师事务所深度合作，保证平台服务流程及相关法律合同合规、合法、无漏洞。</p>
                <p><img src="{{assetUrlByCdn('/static/images/new/lvshi.png')}}"></p>
            </div>
            <div class="web-secure-law web-secure-law2">
                <h3>易保全</h3>
                <p class="web-secure-law-txt">易保全是一家拥有区块链技术并被司法机关认可的电子数据保全机构，易保全接轨国际先进电子数据保全技术， 结合信息法学理论，将法律与信息技术进行交叉融合。采用易保全研发的数据保全系统对电子数据进行加密固定， 能有效防止电子数据被人为篡改，确保电子数据的原始性、客观性。</p>
            </div>
        </div>
    </div>
    <div class="web-secure-btn">
        <a href="/register">立即注册领取大礼包</a>
    </div>
@endsection
