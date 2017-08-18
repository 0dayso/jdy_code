@extends('wap.common.wapBase')

@section('title', '九斗鱼')

@section('keywords', env('META_KEYWORD'))

@section('description', env('META_DESCRIPTION'))

@section('css')
<link href="{{ assetUrlByCdn('/static/weixin/css/weixin.css') }}" rel="stylesheet">
<style>
html,body{background-color: #e5e5e5;}
.app-bg{padding: 0;}
.app-box{background-color: #fff; padding: 13px 0 30px; text-align: center; margin-bottom: 30px; margin-top: 15px;}
.app-txt1{font-size: 24px; color: #cb3b33; line-height: 50px;}
.app-txt2{font-size: 15px;}
.app-color1{color: #636363;}
.app-color2{color: #b2b2b2;}
a.app-btn{display: block; margin: 0 30px 30px; background-color: #cb3b33; color: #fff; border-radius: 18px; height: 36px; line-height: 36px; text-align: center; font-size: 18px;}
</style>
@endsection

@section('content') 
<article class="app-bg">

    <section class="app-box">
        <p><img src="{{ assetUrlByCdn('/static/weixin/images/apprecharge.png') }}" width="123" ></p>
        <p class="app-txt1">充值成功</p>
        <p class="app-txt2"><span class="app-color2">支付金额：</span><span class="app-color1"> {{ $cash }}元</span></p>
    </section>
    <a href="javascript:void(0);" onClick="gotoInvest()" class="app-btn">完成</a>
</article>

@endsection

@section('jsScript')
<script>
var client = "{{ $client }}";
client = (client=='') ? getCookie('JDY_CLIENT_COOKIES') : client;
var version = "{{ $version }}";
var compare = "{{ version_compare('"+version+"', '2.1.0', '>=') }}";

    function gotoInvest() {
        if(client == "android") {

            window.jiudouyu.gotoInvest();
            /*
            if (compare) {
                window.jiudouyu.Complete();
            } else {
                window.jiudouyu.gotoInvest();
            }
            */
        } else {
            if (compare) {
                window.location.href = 'objc:gotoOrige';
            } else {
                window.location.href='objc:gotoInvest';
            }
        }
    }

    function gotoAccount() {

        if(client == "android") {
            window.jiudouyu.gotoAccount();
        } else {
            window.location.href='objc:gotoAccount';
        }
    }
    
</script>
@endsection
