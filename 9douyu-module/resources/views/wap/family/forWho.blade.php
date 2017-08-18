@extends('wap.common.wapBase')

@section('title', '为谁开通')

@section('keywords', env('META_KEYWORD'))

@section('description', env('META_DESCRIPTION'))

@section('css')
    <meta name="format-detection" content="telephone=yes">
    <link rel="stylesheet" type="text/css" href="{{ assetUrlByCdn('/static/weixin/css/familyAccount.css') }}">
@endsection

@section('content')
    <p class="family-open">你为谁开通家庭账户</p>
    <section class="family-open1">
        <a href="#" class="family-open-1">
            <span class="family-open-icon">
                <img src="{{ assetUrlByCdn('/static/weixin/images/topic/family-icon1.png') }}"/>
            </span>
            <p>请在下方选择对方角色</p>
        </a>

        <ul class="family-open2">

            @foreach($hotAccount as $vo)
            <li>
                <a href="{{ URL('/family/phone') }}/{{ urlencode($vo) }}" class="ml"> <span>+</span> {{ $vo }}</a>
            </li>
            @endforeach
            <li>
                <a href="/family/more" class="active mr"> <span>+</span> 更多</a>
            </li>
        </ul>
    </section>

    <p class="family-tel2-21">
        <a href="/family/intro" class="family-contact">什么是家庭账户？</a>
    </p>
@endsection