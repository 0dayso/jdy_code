@extends('wap.common.wapBase')
@section('title', '活加转入成功')

@section('keywords', env('META_KEYWORD'))

@section('description', env('META_DESCRIPTION'))

@section('content')
    <article>
        <section class="wap2-dd-box clearfix">
            <div class="wap2-dd-info">
                <p class="tc">
                    <img src="{{assetUrlByCdn('/static/weixin/images/wap2/wap2-txt-4.png')}}" width="136"></p>
                <p>
                    明天起就可以看见收益了哦～
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
                    <th colspan="3">零钱计划</th>
                </tr>
                <tr>
                    <td width="4%">•</td>
                    <td width="36%">借款利率</td>
                    <td>{{(float)$rate}}%
                        @if($add_rate > 0)
                            +<span class="t-current1-1">{{(float)$add_rate}}</span><span class="t-current1-4">%</span><em class="t-current1-2">（连续加息{{$period}}天）</em>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td>•</td>
                    <td>出借金额</td>
                    <td>{{$cash}}元</td>
                </tr>
                <tr>
                    <td>•</td>
                    <td colspan="2">买入当日计息</td>
                </tr>
            </table>

        </section>

        <section class="wap2-btn-wrap clearfix t-w-mt40px">
            <a href="/project/current/detail" class="wap2-btn wap2-btn-half fl wap2-btn-blue">继续投</a>
            <a href="/user" class="wap2-btn wap2-btn-half fr">去账户</a>
        </section>
    </article>
@endsection
