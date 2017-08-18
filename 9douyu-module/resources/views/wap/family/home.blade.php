@extends('wap.common.wapBase')

@section('title', '填写手机号')

@section('keywords', env('META_KEYWORD'))

@section('description', env('META_DESCRIPTION'))

@section('css')
    <meta name="format-detection" content="telephone=yes">
    <link rel="stylesheet" type="text/css" href="{{ assetUrlByCdn('/static/weixin/css/familyAccount.css') }}">
    <style>
       body{background-color: #fff;}
    </style>
@endsection

@section('content')
    <div class="family-home">
        <img src="{{ assetUrlByCdn('/static/weixin/images/topic/family-img02.png') }}" alt="" class="img">
        <h1>为你的家人管理财富</h1>
        <p><i></i>对方可授权你投资并管理对方在九斗鱼中的资金</p>
        <p><i class="two"></i>你可为对方将资金直接转入九斗鱼平台，并投资零钱计划或定期产品，获取收益后可为对方直接提现至对方银行卡中</p>
        @if ($isLogin)
            <a href="{{ URL('/family/forWho') }}" onclick="_czc.push(['_trackEvent','家庭账户','开始体验']);" class="home-btn">开始体验</a>
        @else
            <a href="javascript:;" onclick="_czc.push(['_trackEvent','家庭账户','开始体验']);start();" class="home-btn">开始体验</a>
        @endif
    </div>
@endsection

@section('jsScript')
    <script>
        var client = '{{ $client }}';
        function start(){
            if(client=='ios'){
                window.location.href="objc:gotoLogin";
            }else{
                window.jiudouyu.login();
            }
        }
    </script>
</block>
@endsection