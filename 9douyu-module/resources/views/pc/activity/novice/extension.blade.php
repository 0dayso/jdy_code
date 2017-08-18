@extends('pc.common.layout')

@section('title', '做个新懒人  收益不缺席')

@section('keywords', env('META_KEYWORD'))

@section('description', env('META_DESCRIPTION'))

@section('csspage')
    <meta name="format-detection" content="telephone=yes">
    <link rel="stylesheet" type="text/css" href="{{assetUrlByCdn('/static/activity/novice/css/extension0717.css')}}">
@endsection
@section('content')

<div class="novice-banner"></div>
<div class="novice-coupon">
    <img src="{{assetUrlByCdn('/static/activity/novice/0717/coupon.png')}}">
</div>
<div  class="novice-btn"><a href="{{$registerUrl}}" >点击领取</a></div>
<div class="novice-data">
    <h2>为什么九斗鱼更值得信赖</h2>
    <h3>九斗鱼携手江西银行达成资金存管合作</h3>
    <div class="novice-data-main">
        <p>平台注册用户：<strong>1,449,506</strong>  人</p>
        <p>累计出借金额：<strong>4,789,791,095</strong>  元</p>
        <p>帮助出借人赚取收益：<strong>84,197,903</strong>  元</p>
    </div>
    <ul class="novice-intro">
        <li>
            <p><span class="novice-intro-icon icon1"></span></p>
            <p><big>专注</big></p>
            <p>11年专注中小企业<br>金融领域服务经验</p>
        </li>
        <li>
            <p><span class="novice-intro-icon icon2"></span></p>
            <p><big>权威</big></p>
            <p>国家专利技术认可<br>RISKCALC®<br>风控评级体系</p>
        </li>
        <li>
            <p><span class="novice-intro-icon icon3"></span></p>
            <p><big>安全</big></p>
            <p>获国家信息系统<br>安全保护等级三级认证</p>
        </li>
    </ul>
</div>
<div class="novice-data">
    <h2>明星产品</h2>
    <h3>借款利率</h3>
    <ul class="novice-prod-block">
        <li>
            <p><span class="novice-rate rate1"></span></p>
            <p>零钱计划</p>
        </li>
        <li>
            <p><span class="novice-rate rate2"></span></p>
            <p>九安心</p>
        </li>
        <li>
            <p><span class="novice-rate rate3"></span></p>
            <p>九省心</p>
        </li>
    </ul>
    <a href="/project/index" class="novice-btn2">立即出借</a>
</div>
<div class="novice-rule">
    <p class="novice-rule-title">活动规则</p>
    <p>1.活动时间: 2017年4月1日 00:00 起，本活动仅针对活动期间内注册的新用户；</p>
    <p>2.现金券及加息券自用户注册九斗鱼账户后自动发放至账户，请在“资产-我的优惠券”处查看;</p>
    <p>3.每个手机号码仅限参加一次，刷奖及冒用他人身份证、银行卡者一经核实，取消活动资格，所得奖励不予承兑；</p>
    <p>4.本活动规则解释权归九斗鱼平台所有，如有任何疑问请致电九斗鱼官方客服：400-6686-568，或登录九斗鱼咨询在线客服。</p>
</div>

@endsection

@section('jspage')
<script type="text/javascript">


</script>
@endsection

