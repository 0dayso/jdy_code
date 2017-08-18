@extends('wap.common.wapBase')

@section('title','我的资产')

@section('css')

    <link rel="stylesheet" type="text/css" href="{{assetUrlByCdn('/static/weixin/css/index-w3.css')}}">
    <style>
        body{overflow: auto;}
    </style>
@endsection

@section('content')

<article>
    <div class="wap2-mine clearfix">

        <!-- 资产切换-->
        <div class="swiper-container">
            <div class="swiper-wrapper">
                <div class="swiper-slide">
                    <p class="mine-txt1">总资产（元）<a href="javascript:;" class="js-eye wap2-asset-eye"></a></p>
                    <p class="mine-txt2 js-showtxt" >{{ $user['total_cash'] }}</p>
                    <p class="mine-txt2 js-showstar" style="display: none">****</p>
                </div>
                <div class="swiper-slide">
                    <p class="mine-txt1">在投本金（元）</p>
                    <p class="mine-txt2 js-showtxt">{{ $user['investing_cash'] }}</p>
                    <p class="mine-txt2 js-showstar" style="display: none">****</p>
                </div>
                <div class="swiper-slide">
                    <p class="mine-txt1">累计收益（元）</p>
                    <p class="mine-txt2 js-showtxt">{{ $user['total_interest'] }}</p>
                    <p class="mine-txt2 js-showstar" style="display: none">****</p>
                </div>
            </div>
            <!-- 分页器 -->
            <div class="swiper-pagination"></div>
        </div>


        <div class="wap2-asset-item clearfix">
            <a href="javascript:void(0)">
                <span>定期资产（元）</span><br>
                <em class="js-showtxt">{{ $user['doing_invest_amount'] }}</em>
                <em class="js-showstar" style="display: none">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;****</em>
                {{--<i>三角</i>--}}
            </a>
            <a href="/current">
                <span>零钱计划（元）</span><br>
                <em class="js-showtxt">{{ $user['current_cash'] }}</em>
                <em class="js-showstar" style="display: none">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;****</em>
                <i>三角</i>
            </a>
        </div>
    </div>

    <!-- 充值提现区域-->
    <div class="wap2-asset-wrap">
        <a style="font-size:0.55rem;color: #606060;width:50%;">余额 (元) :&nbsp;&nbsp;{{ $user['balance'] }}</a>
        <a href="/withdraw" style="width:25%;font-size: 0.65rem;border-right: 1px solid #eaeaea;">提现</a>
        <a href="/pay/index" style="width:25%;font-size: 0.65rem">充值</a>
    </div>

    <!--   九宫格菜单-->
    <div class="wap2-sudoku clearfix">
        @if(!empty($menuList))
        @foreach($menuList as $v)
            {{--@if($v['position_num'] != 2 && $v['position_num'] != 5)--}}
            @if($v['position_num'] != 5)
            <a href="{{ $v['location_url'] }}">
                <img src="{{ $v['picture'] }}" alt="icon" class="asset-img">
                <span>{{ $v['title'] }}</span>
            </a>
            @endif
        @endforeach
        @endif
    </div>

    <!-- 提示-->
    <div class="wap2-asset-tip" >
        <p class="w3-text2"> <i></i><span>账户资金享有银行级安全保障</span></p>
    </div>

</article>

@endsection

@section('jsScript')
<script src="{{assetUrlByCdn('/static/weixin//js/jquery-1.9.1.min.js')}}" type="text/javascript"></script>
<script src="{{assetUrlByCdn('/static/weixin/js/swiper3.1.0.jquery.min.js')}}" type="text/javascript"></script>


<script>
    var mySwiper = new Swiper ('.swiper-container', {
        direction: 'horizontal',
        loop: true,
        pagination: '.swiper-pagination',
        paginationClickable :true,

    })
    $(".js-eye").on("click touched",function(){
        var $this = $(this);
        if(!($this.hasClass("wap2-asset-eye-close"))){
            $this.addClass("wap2-asset-eye-close");
            $('.js-showtxt').hide();
            $('.js-showstar').show();
        }else{
            $this.removeClass("wap2-asset-eye-close");
            $('.js-showtxt').show();
            $('.js-showstar').hide();
        }
    });

</script>

@endsection

@section('footer')

    @include('wap.common.footer')
@endsection