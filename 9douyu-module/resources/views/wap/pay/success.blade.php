@extends('wap.common.wapBase')

@section('title', '九斗鱼')

@section('keywords', env('META_KEYWORD'))

@section('description', env('META_DESCRIPTION'))

@section('content') 
    <article>
        <section class="wap2-dd-box clearfix">
            <div class="wap2-dd-info">
                <p>
                    充值成功啦~<br>
                    请查询账户余额
                </p>
                <i class="wap2-arrow-2"></i>
            </div>
            <div class="wap-dd-block">
                <img src="{{ assetUrlByCdn('/static/weixin/images/wap2/wap2-dd.png') }}" class="img">
            </div>
        </section>
        <section class="wap2-box box-pad t-w1-mt15">
            <table class="wap2-withdraw-info">
                <tr>
                    <th colspan="3">充值成功</th>
                </tr>
                <tr>
                    <td width="4%">•</td>
                    <td width="48%">充值金额</td>
                    <td>{{ $cash }}&nbsp;元</td>
                </tr>
                {{--<tr>--}}
                    {{--<td width="4%">•</td>--}}
                    {{--<td width="48%">目前账户可用余额</td>--}}
                    {{--<td>{{ $balance }}</td>--}}
                {{--</tr>--}}
            </table>

        </section>

        <section class="wap2-btn-wrap t-w-mt40px">
            
            <a href="{{ env('APP_URL_WX') }}/project/lists" class="wap2-btn" style="width: 48%;float:left;"><继续投资</a>
              <a href="{{ env('APP_URL_WX') }}/user" class="wap2-btn" style="width: 48%;float: right;">去我的账户></a>
            
        </section>
    </article>

@endsection
