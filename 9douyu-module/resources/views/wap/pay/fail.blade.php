@extends('wap.common.wapBase')

@section('title', '九斗鱼')

@section('keywords', env('META_KEYWORD'))

@section('description', env('META_DESCRIPTION'))

@section('content') 
    <article>
        <section class="wap2-dd-box clearfix">
            <div class="wap2-dd-info">
                <p>
                    抱歉，充值失败~<br>
                </p>
                <i class="wap2-arrow-2"></i>
            </div>
            <div class="wap-dd-block">
                <img src="{{ assetUrlByCdn('/static/weixin/images/wap2/wap2-dd.png') }}" class="img">
            </div>
        </section>
        <section class="wap2-box box-pad">
            <table class="wap2-withdraw-info">
                <tr>
                    <td><p class="center font12 font85 mb20">建议到网站查看详情或拨打客服热线400-6686-568</p></td>
                </tr>
            </table>
        </section>

        <section class="wap2-btn-wrap">
            
            <a href="{{ env('APP_URL_WX') }}/pay/index" class="wap2-btn" style="width: 48%;float:left;"><重新充值</a>
              <a href="{{ env('APP_URL_WX') }}/user" class="wap2-btn" style="width: 48%;float: right;">去我的账户></a>
            
        </section>
    </article>

@endsection
