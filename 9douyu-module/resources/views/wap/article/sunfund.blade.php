@extends('wap.common.wapBase')

@section('title', '一分钟了解九斗鱼')

@section('keywords', "{{env('META_KEYWORD')}}")

@section('description', "{{env('META_DESCRIPTION')}}")
@section('css')
    <link rel="stylesheet" href="{{ assetUrlByCdn('/static/app/css/app.css') }}" type="text/css" />
@endsection

@section('content')
    <article>
        <section>
            <div class="mb10"><img src="{{ assetUrlByCdn('/static/app/images/topic/sunfund-banner.gif') }}" class="img" /></div>
            <div class="intro-wrap">
                <p class="sunfund-info">耀盛中国直接控股及参股的公司有耀盛汇融、耀盛保理、耀江租赁、耀盛银行（筹）、瑞思科雷征信公司等。</p>
                <img src="{{ assetUrlByCdn('/static/app/images/topic/intro-img3-new.gif') }}" class="img mb40" />
                <img src="{{ assetUrlByCdn('/static/app/images/topic/intro-img4-2.png') }}" class="img" />
                <img src="{{ assetUrlByCdn('/static/app/images/topic/intro-img5.gif') }}" class="img" />
                <img src="{{ assetUrlByCdn('/static/app/images/topic/intro-img6.gif') }}" class="img" />
                <img src="{{ assetUrlByCdn('/static/app/images/topic/intro-img7.gif') }}" class="img" />
                <img src="{{ assetUrlByCdn('/static/app/images/topic/intro-img8.gif') }}" class="img mb40" />
            </div>
        </section>
    </article>
@endsection