@extends('wap.common.wapBase')
@section('title', '零钱计划转出成功')

@section('keywords', env('META_KEYWORD'))

@section('description', env('META_DESCRIPTION'))

@section('content')
    <article>
        <section class="wap2-dd-box clearfix">
            <div class="wap2-dd-info">
                <p class="tc wap2-success-text">
                    零钱计划转出{{$cash}}元成功!
                <p>
                <p>
                    欢迎你随时再次投资零钱计划
                </p>
                <i class="wap2-arrow-2"></i>
            </div>
            <div class="wap-dd-block">
                <img src="{{assetUrlByCdn('/static/weixin/images/wap2/wap2-dd.png')}}" class="img">
            </div>
        </section>
        <section class="wap2-box box-pad t-w1-mt15">
            <table class="wap2-withdraw-info">
                <tr>
                    <th colspan="3">账户余额</th>
                </tr>
                <tr>
                    <td>{{number_format($balance,2)}}</td>
                </tr>
            </table>

        </section>
        <section class="wap2-btn-wrap clearfix t-w-mt40px">
            <a href="/project/current/detail" class="wap2-btn wap2-btn-half fl wap2-btn-blue">继续投</a>
            <a href="/user" class="wap2-btn wap2-btn-half fr">去账户</a>
        </section>
    </article>


@endsection