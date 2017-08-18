@extends('wap.common.wapBase')
@section('title', '实名成功')

@section('keywords', env('META_KEYWORD'))

@section('description', env('META_DESCRIPTION'))

@section('content')

    <article>
        <section class="wap2-dd-box clearfix">
            <div class="wap2-dd-info">
                <p class="tc">
                    恭喜您，实名认证成功
                </p>

                <i class="wap2-arrow-2"></i>
            </div>
            <div class="wap-dd-block">
                <img src="{{assetUrlByCdn('/static/weixin/images/wap2/wap2-dd.png')}}" class="img">
            </div>
        </section>

        <section class="wap2-btn-wrap">
            <a href="/user" class="wap2-btn  wap2-btn-blue2">去我的账户</a>
        </section>
    </article>
@endsection