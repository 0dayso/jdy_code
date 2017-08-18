@extends('pc.common.layout')

@section('title', '耀盛中国旗下P2P网贷平台 为用户提供借款与出借服务')

@section('content')


    <!-- banner -->
    @include('pc.common/banner')

    <!-- 最新动态 -->
    @include('pc.home/news')

    <!-- 数据统计 -->
    {{--@include('pc.home/stat')--}}

<div class="index-activity-theme">

    <div class="wrap">

        <!-- 零钱计划 -->
        @include('pc.home/current')

        <!-- 闪电付息、变现宝 -->
        {{--@include('pc.home/sdf')--}}

        <!-- 九省心 -->
        @include('pc.home/jsx')


    </div>
    <!-- 媒体报道 -->
    @include('pc.home/mediaReport')

            <!-- 合作伙伴 -->
    @include('pc.home/cooper')

            <!-- 平台优势 -->
    @include('pc.home/chooseJdy')

</div>
{{--</div>--}}

    <!-- 关于我们 -->
    @include('pc.home/about')

<div class="web-footer-index">
    <!-- 底部信息 -->
{{--    @include('pc.common/footer')--}}
</div>
<div class="index-activity-layer">
    <!-- index 活动弹窗 -->
    @include('pc.home/pop')
</div>
@endsection



