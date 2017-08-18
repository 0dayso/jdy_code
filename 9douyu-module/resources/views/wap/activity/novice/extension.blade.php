@extends('wap.common.wapBase')

@section('title', '新手活动s10')

@section('keywords', env('META_KEYWORD'))

@section('description', env('META_DESCRIPTION'))

@section('css')

<meta name="format-detection" content="telephone=yes">

<link rel="stylesheet" type="text/css" href="{{assetUrlByCdn('/static/weixin/css/novice-public.css')}}">
<link rel="stylesheet" type="text/css" href="{{assetUrlByCdn('/static/weixin/css/novice.css')}}">

@endsection

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="re-form" data-channel="{{ $channel or null }}">
    </div>

    <div class="app9-con">
        <div class="app9-con-title">
            <p>九斗鱼风控获得 <big>央行企业</big> 征信牌照</p>
        </div>
        <ul class="app9-data">
            <li>
                <span><strong>1,297,909</strong> 人</span>平台注册用户
            </li>
            <li>
                <span><strong>2,522,599,900</strong> 元</span>累计出借金额
            </li>
            <li>
                <span><strong>{{ number_format(48555049) }}</strong> 元</span>帮助投资者赚取收益
            </li>
        </ul>
        <div class="app9-con-title2">
            <i></i><span>明星产品</span><i></i><small>（借款利率）</small>
        </div>
        <div class="app9-product">
            <dl>
                <dt><span>7</span>%</dt>
                <dd>零钱计划</dd>
            </dl>
            <dl>
                <dt><span>9</span>%</dt>
                <dd>九安心</dd>
            </dl>
            <dl>
                <dt><span>12</span>%</dt>
                <dd>九省心</dd>
            </dl>
        </div>
    </div>

    <div class="download-box">
    <div class="ann2promote-download">
        <span></span>
        <p><strong>九斗鱼app</strong></p>
        <p>心安财有余</p>
        <a href="{{$package}}" onclick="_czc.push(['_trackEvent','{{ $channel }}-新手推广页','{{ $channel }}-下载APP']);">立即下载</a>

    </div>
    </div>
    <div class="ann2promote-work download">
        <p>客服时间：09:00-18:00</p>
        <p><span>400-6686-568</span></p>
        <p><i></i><small>投资有风险，理财需谨慎</small><i></i></p>
    </div>
@endsection

@section('jsScript')
    <script>
        var registerWord = "{{ $registerWord or '提交注册' }}";
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

