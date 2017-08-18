@extends('wap.common.wapBase')

@section('title', '用户提现预览')

@section('keywords', "{{env('META_KEYWORD')}}")

@section('description', "{{env('META_DESCRIPTION')}}")

@section('css')

@endsection
@section('content')
    <article>
        <section class="wap2-dd-box clearfix">
            <div class="wap2-dd-info">
                <p class="tc mt1 mb1"><img src="{{ assetUrlByCdn('/static/weixin/images/wap2/wap2-txt-2.png') }}" width="136"></p>
                <i class="wap2-arrow-2"></i>
            </div>
            <div class="wap-dd-block">
                <img src="{{ assetUrlByCdn('/static/weixin/images/wap2/wap2-dd.png') }}" class="img">
            </div>
        </section>
        <section class="wap2-box box-pad">
            <table class="wap2-withdraw-info">
                <tr>
                    <th colspan="3">提现申请</th>
                </tr>
                <tr>
                    <td width="4%">•</td>
                    <td width="48%">姓名</td>
                    <td>@if(!empty($orderInfo['real_name'])) {{$orderInfo['real_name']}}@endif</td>
                </tr>
                <tr>
                    <td>•</td>
                    <td>银行卡号</td>
                    <td>@if(!empty($orderInfo['card_number'])){{$orderInfo['card_number']}}@endif</td>
                </tr>
                <tr>
                    <td>•</td>
                    <td>提现金额</td>
                    <td>@if(!empty($orderInfo['cash'])){{$orderInfo['handling_fee']+$orderInfo['cash']}}元@else 0.00元@endif</td>
                </tr>
                <tr>
                    <td>•</td>
                    <td>提现手续费</td>
                    <td>@if(!empty($orderInfo['cash'])){{$orderInfo['handling_fee']}}元@else 0.00元@endif</td>
                </tr>
                <tr>
                    <td>•</td>
                    <td>实际到账金额</td>
                    <td>@if(!empty($orderInfo['cash'])){{$orderInfo['cash']}}元@else 0.00元@endif</td>
                </tr>
                <tr>
                    <td>•</td>
                    <td>预计到账时间</td>
                    <td>次日到账（节假日顺延）</td>
                </tr>

            </table>

        </section>

        <section class="wap2-btn-wrap">
            <a href="/user" class="wap2-btn">查看账户余额</a>
        </section>
    </article>
@endsection