@extends('wap.common.wapBase')

@section('title', '微信扫一扫立即领钱')

@section('keywords', env('META_KEYWORD'))

@section('description', env('META_DESCRIPTION'))

@section('css')
    <meta name="format-detection" content="telephone=yes">
    <link rel="stylesheet" type="text/css" href="{{assetUrlByCdn('/static/weixin/css/partner.css')}}">
@endsection

@section('content')
    <article class="partner-repeat">
        <div class="partner-weixin">
            <p><img src="/{{ $qrCodePath }}"></p>
            <strong>微信扫一扫立即加入</strong>
        </div>
    </article>
@endsection

@section('jsScript')
    <script>
    </script>
@endsection