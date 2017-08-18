@extends('wap.common.wapBase')

@section('title', '做个新懒人  收益不缺席')

@section('keywords', env('META_KEYWORD'))

@section('description', env('META_DESCRIPTION'))

@section('css')

<meta name="format-detection" content="telephone=yes">

<link rel="stylesheet" type="text/css" href="{{assetUrlByCdn('/static/weixin/css/novice-public.css')}}">
<link rel="stylesheet" type="text/css" href="{{assetUrlByCdn('/static/weixin/css/novice3.1.css')}}">


@endsection

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="re-instro">
    @if($userStatus == false)
        <a href="javascript:;" class="app11-btn btn1 introduce-btn">点击领取</a>
@endif
        <div class="app11-coupon"></div>
    </div>
    <div class="app11-con">
        <p class="app11-con-title"><!--九斗鱼风控获得央行企业征信牌照--></p>
        <ul class="app11-data">
            <li>
                <span>九斗鱼携手江西银行达成资金存管合作</span>
            </li>
            <li>
                <span>《九斗鱼获国家信息系统安全保护等级三级认证》</span>
            </li>
            <li>
                <span>平台注册用户：<strong>1,449,506</strong> 人</span>
            </li>
            <li>
                <span>累计出借金额：<strong>4,789,791,095</strong> 元</span>
            </li>
            <li>
                <span>帮助投资者赚取收益：<strong>84,197,903</strong> 元</span>
            </li>
        </ul>
    </div>
    <div class="app11-con2">
        <p class="app11-con-title">借款利率</p>
        <div class="app11-product intro">
            <dl>
                <dt><span>7</span>%</dt>
                <dd>零钱计划</dd>
            </dl>
            <dl>
                <dt><span>10</span>%</dt>
                <dd>九安心</dd>
            </dl>
            <dl>
                <dt><span>12</span>%</dt>
                <dd>九省心</dd>
            </dl>
        </div>
        <a href="javascript:;" class="app11-btn1 invest-btn">立即出借</a>
    </div>
    <div class="app11-rule">
        <p class="antwo-sum">活动规则</p>
        <ul>
            <li>1.活动时间：2017年4月1日 00:00起，本活动仅针对活动期间内注册的新用户；</li>
            <li>2.现金券及加息券自用户注册九斗鱼账户后自动发放至账户，请在“资产-我的优惠券”处查看;</li>
            <li>3.每个手机号码仅限参加一次，刷奖及冒用他人身份证、银行卡者一经核实，取消活动资格，所得奖励不予承兑；</li>
            <li>4.本活动规则解释权归九斗鱼平台所有，如有任何疑问请致电九斗鱼官方客服：400-6686-568，或登录九斗鱼咨询在线客服。</li>
        </ul>
    </div>

@endsection

@section('jsScript')
<script>
    $(function(){
        var client = getCookie('JDY_CLIENT_COOKIES');
        if( client == '' || !client ){
            var client  =   '{{$client}}';
        }
        $(".introduce-btn").click(function(){
            if(client == 'ios'){
                window.location.href = "objc:gotoLogin";
                return false;
            }
            if(client =='android'){
                window.jiudouyu.login();
                return false;
            }

            window.location.href='/Novice/extension?from=app&client='+client
        })
        $(".invest-btn").click(function(){
            if(client =='ios'){
                window.location.href = "objc:JumpToSecondPage";
                return false;
            }
            if(client =='android'){
                window.jiudouyu.gotoInvest()
                return false;
            }
            window.location.href='/project/lists';
         })
    })
</script>
@endsection

