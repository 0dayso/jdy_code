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
    <div class="re-form" data-channel="{{ $channel or null }}">
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
    <div class="app11-con1">
        <p class="app11-con-title">借款利率</p>
        <div class="app11-product">
            <dl>
                <dt><span></span></dt>
                <dd>零钱计划</dd>
            </dl>
            <dl>
                <dt><span>10</span>%</dt>
                <dd>九安心</dd>
            </dl>
            <dl>
                <dt><span></span></dt>
                <dd>九省心</dd>
            </dl>
        </div>
    </div>
    <div class="ann2promote-work download">
        <p>客服时间：09:00-18:00</p>
        <p><span>400-6686-568</span></p>
        {{-- <p><i></i><small>理财有风险&nbsp;&nbsp;投资需谨慎</small><i></i></p> --}}
    </div>
    <div class="ann2promote-rule">
        <h4>活动规则</h4>
        <p><span>1.</span>活动时间:2017年4月1日 00:00起，本活动仅针对活动期间内注册的新用户;</p>
        <p><span>2.</span>现金券及加息券自用户注册九斗鱼账户后自动发放至账户，请在“资产-我的优惠券”处查看;</p>
        <p><span>3.</span>新手注册的888元现金券以组合形式发放，投资时即可使用，项目到期后可提现;</p>
        <p><span>4.</span>每个手机号码仅限参加一次，刷奖及冒用他人身份证、银行卡者一经核实，取消活动资格，所得奖励不予承兑;</p>
        <p><span>5.</span>本活动规则解释权归九斗鱼平台所有，如有任何疑问请致电九斗鱼官方客服：400-6686-568，或登录九斗鱼咨询在线客服;</p>
        <p><span>6.</span>网贷有风险 投资需谨慎。</p>
    </div>
    <div class="download-box">
        <div class="ann2promote-download">
            <span></span>
            <p>安享收益  华丽转身</p>
        <a href="{{$package}}" onclick="_czc.push(['_trackEvent','{{ $channel }}-新手推广页','{{ $channel }}-下载APP']);">立即下载</a>

        </div>
    </div>
    <div id="checkcode1" data-img="/captcha/wx_register"  style="overflow: hidden;"></div>

@endsection

@section('jsScript')
    <script>
        var registerWord = "{{ $registerWord or '注册领取888元现金券' }}";
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>
    <script src="{{assetUrlByCdn('/static/js/common.js')}}"></script>
    <script>
        $(document).ready(function(){
            $(".re-form form").find("input[name='aggreement']").before('<input name="_token" type="hidden" value="{{csrf_token()}}">')
         });
    </script>
@endsection

