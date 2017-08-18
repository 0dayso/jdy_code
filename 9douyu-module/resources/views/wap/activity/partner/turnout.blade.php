@extends('wap.common.wapBase')

@section('title', '转出佣金收益')

@section('keywords', env('META_KEYWORD'))

@section('description', env('META_DESCRIPTION'))

@section('content')
    <article>
        <section class="wap2-dd-box clearfix">
            <div class="wap2-dd-info">
                <p class="wap2-success-txt f16">恭喜您，转出成功！</p>
                <i class="wap2-arrow-2"></i>
            </div>
            <div class="wap-dd-block">
                <img src="{{ assetUrlByCdn('/static/weixin/images/wap2/wap2-dd.png') }}" class="img">
            </div>
        </section>
        <section class="wap2-box box-pad">
            <table class="wap2-withdraw-info">

                <tr>
                    <td width="4%">•</td>
                    <td width="48%">转出金额</td>
                    <td align="right">{{number_format($turnCash,2)}}元</td>
                </tr>
                <tr>
                    <td>•</td>
                    <td>转出至</td>
                    <td align="right">账户余额</td>
                </tr>

            </table>

        </section>
        <section class="wap2-btn-wrap mb2">
            <a href="/project/lists" class="wap2-btn wap2-btn-half fl wap2-btn-blue">去投资</a>
            <a href="/ActivityPartner/" class="wap2-btn wap2-btn-half fr">返回</a>

        </section>

    </article>
@endsection

@section('jsScript')
    <script>
    </script>
@endsection