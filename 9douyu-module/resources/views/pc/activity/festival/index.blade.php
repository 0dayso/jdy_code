@extends('pc.common.layout')

@section('title', '双蛋嘉年华')

@section('keywords', env('META_KEYWORD'))

@section('description', env('META_DESCRIPTION'))

@section('css')
    <meta name="format-detection" content="telephone=yes">

@endsection
@section('content')
    <link rel="stylesheet" type="text/css" href="{{assetUrlByCdn('／static/activity/festival/css/index.css')}}">
    <div class="page-time">{{date("Y.m.d",$activityTime['start'])}}-{{date("Y.m.d",$activityTime['end'])}}</div>
    <div class="page-section1"><p class="page-section1-txt">登录九斗鱼APP每天猛戳红包雨</p></div>

    <div class="page-section2-top page-auto">
        @if( $projectInfo['product_line'] == 200)
        <h6>九<span class="text1">安</span><span class="text2">心</span></h6>
        @elseif($projectInfo['product_line']+ $projectInfo['type'] ==101)
        <h6>1<span class="text1">月</span><span class="text2">期</span></h6>
        @elseif($projectInfo['product_line']+ $projectInfo['type'] ==112)
        <h6>12<span class="text1">月</span><span class="text2">期</span></h6>
        @elseif($projectInfo['product_line']+ $projectInfo['type'] ==306)
        <h6>6<span class="text1">月</span><span class="text2">期</span></h6>
        @elseif($projectInfo['product_line']+ $projectInfo['type'] ==312)
        <h6>12<span class="text1">月</span><span class="text2">期</span></h6>
        @else
            <h6>{{substr($projectInfo['invest_time_note'],0,1)}}<span class="text1">月</span><span class="text2">期</span></h6>
        @endif
    </div>
    <!-- section2 content -->
    <div class="page-section2-main page-auto">
    <!-- iphone 7 -->
        <div class="page-wrap clearfix">
            <div class="page-gift-iphone fl">
                <ul class="fl">
                    <li><img src="{{assetUrlByCdn('/static/activity/festival/images/page-img-iphone7.png')}}" class="page-img-iphone7"alt="投资即送icon"></li>
                </ul>
                <ul class="fr iphone-detail">
                    <li><a href="javascript:;" class="page-btn page-btn-yellow {{$projectStatus['css']}}">{{$projectStatus['note']}}<img src="{{assetUrlByCdn('/static/activity/festival/images/page-icon-red.png')}}" alt="投资即送icon"></a></li>
                    <li class="txt">仅投资<span>{{$projectInfo['base_profit'][1]['base']}}万</span>免费领取<br>还获收益<span>{{$projectInfo['base_profit'][1]['profit']}}</span>元</li>
                    <li class="price">原价：6188元</li>
                </ul>
            </div>
            <div class="page-gift-iphone fr">
                <ul class="fl iphone-bg">
                    <li><img src="{{assetUrlByCdn('/static/activity/festival/images/page-img-iphone7-plus.png')}}" class="page-img-iphone7-plus"alt="投资即送icon"></li>
                </ul>
                <ul class="fr iphone-detail">
                    <li><a href="javascript:;" class="page-btn page-btn-yellow {{$projectStatus['css']}}">{{$projectStatus['note']}}<img src="{{assetUrlByCdn('/static/activity/festival/images/page-icon-red.png')}}" alt="投资即送icon"></a></li>
                    <li class="txt">仅投资<span>{{$projectInfo['base_profit'][2]['base']}}万</span>免费领取<br>还获收益<span>{{$projectInfo['base_profit'][2]['profit']}}</span>元</li>
                    <li class="price">原价：7188元</li>
                </ul>
            </div>
        </div>

        <div class="page-wrap clearfix">
           <!-- 2万 -->
            <div class="page-gift-item fl">
                <div class="page-gift-name clearfix">
                    <span>BRITA  滤水壶</span>
                    <div class="hr-dashed"></div>
                </div>
                <ul class="fl">
                    <li><img src="{{assetUrlByCdn('/static/activity/festival/images/page-gift-img1.png')}}" class="page-gift-img" alt="BRITA  滤水壶"></li>
                </ul>
                <ul class="fr page-padding-right">
                    <li><a href="javascript:;" class="page-btn {{$projectStatus['css']}}">{{$projectStatus['note']}}<img src="{{assetUrlByCdn('/static/activity/festival/images/page-icon-white.png')}}" alt="投资即送icon"></a></li>
                    <li class="txt">仅投资<span>{{$projectInfo['base_profit'][3]['base']}}万</span>免费领取<br>还获收益<span>{{$projectInfo['base_profit'][3]['profit']}}</span>元</li>
                    <li class="price">原价：199元</li>
                </ul>
            </div>
            <!-- 3万 -->
            <div class="page-gift-item fr">
                <div class="page-gift-name clearfix">
                    <span>GMP 万向旅行箱20寸</span>
                    <div class="hr-dashed"></div>
                </div>
                <ul class="fl">
                    <li><img src="{{assetUrlByCdn('/static/activity/festival/images/page-gift-img2.png')}}" class="page-gift-img" alt="GMP 万向旅行箱20寸"></li>
                </ul>
                <ul class="fr page-padding-right">
                    <li><a href="javascript:;" class="page-btn {{$projectStatus['css']}}">{{$projectStatus['note']}}<img src="{{assetUrlByCdn('/static/activity/festival/images/page-icon-white.png')}}" alt="投资即送icon"></a></li>
                    <li class="txt">仅投资<span>{{$projectInfo['base_profit'][4]['base']}}万</span>免费领取<br>还获收益<span>{{$projectInfo['base_profit'][4]['profit']}}</span>元</li>
                    <li class="price">原价：299元</li>
                </ul>
            </div>
            <!-- 4万 -->
            <div class="page-gift-item fr">
                <div class="page-gift-name clearfix">
                    <span>MOBICOOL  15L车载冰箱</span>
                    <div class="hr-dashed"></div>
                </div>
                <ul class="fl">
                    <li><img src="{{assetUrlByCdn('/static/activity/festival/images/page-gift-img4.png')}}" class="page-gift-img" alt="MAOKING 便携蓝牙音箱"></li>
                </ul>
                <ul class="fr page-padding-right">
                    <li><a href="javascript:;" class="page-btn {{$projectStatus['css']}}">{{$projectStatus['note']}}<img src="{{assetUrlByCdn('/static/activity/festival/images/page-icon-white.png')}}" alt="投资即送icon"></a></li>
                    <li class="txt">仅投资<span>{{$projectInfo['base_profit'][6]['base']}}万</span>免费领取<br>还获收益<span>{{$projectInfo['base_profit'][6]['profit']}}</span>元</li>
                    <li class="price">原价：499元</li>
                </ul>
            </div>
            <!-- 5万 -->
            <div class="page-gift-item fl">
                <div class="page-gift-name clearfix">
                    <span>MAOKING 便携蓝牙音箱 </span>
                    <div class="hr-dashed"></div>
                </div>
                <ul class="fl">
                    <li><img src="{{assetUrlByCdn('/static/activity/festival/images/page-gift-img3.png')}}" class="page-gift-img" alt="MOBICOOL  15L车载冰箱"></li>
                </ul>
                <ul class="fr page-padding-right">
                    <li><a href="javascript:;" class="page-btn {{$projectStatus['css']}}">{{$projectStatus['note']}}<img src="{{assetUrlByCdn('/static/activity/festival/images/page-icon-white.png')}}" alt="投资即送icon"></a></li>
                    <li class="txt">仅投资<span>{{$projectInfo['base_profit'][5]['base']}}万</span>免费领取<br>还获收益<span>{{$projectInfo['base_profit'][5]['profit']}}</span>元</li>
                    <li class="price">原价：398元</li>
                </ul>
            </div>
            <!-- 6万 -->
            <div class="page-gift-item fl">
                <div class="page-gift-name clearfix">
                    <span>苏泊尔 球釜电压力锅高压锅</span>
                    <div class="hr-dashed"></div>
                </div>
                <ul class="fl">
                    <li><img src="{{assetUrlByCdn('/static/activity/festival/images/page-gift-img5.png')}}" class="page-gift-img" alt="苏泊尔 球釜电压力锅高压锅"></li>
                </ul>
                <ul class="fr page-padding-right">
                    <li><a href="javascript:;" class="page-btn {{$projectStatus['css']}}">{{$projectStatus['note']}}<img src="{{assetUrlByCdn('/static/activity/festival/images/page-icon-white.png')}}" alt="投资即送icon"></a></li>
                    <li class="txt">仅投资<span>{{$projectInfo['base_profit'][7]['base']}}万</span>免费领取<br>还获收益<span>{{$projectInfo['base_profit'][7]['profit']}}</span>元</li>
                    <li class="price">原价：599元</li>
                </ul>
            </div>
            <!-- 9万 -->
            <div class="page-gift-item fr">
                <div class="page-gift-name clearfix">
                    <span>PHILIPS  除螨仪</span>
                    <div class="hr-dashed"></div>
                </div>
                <ul class="fl">
                    <li><img src="{{assetUrlByCdn('/static/activity/festival/images/page-gift-img6.png')}}" class="page-gift-img" alt="苏泊尔 球釜电压力锅高压锅"></li>
                </ul>
                <ul class="fr page-padding-right">
                    <li><a href="javascript:;" class="page-btn {{$projectStatus['css']}}">{{$projectStatus['note']}}<img src="{{assetUrlByCdn('/static/activity/festival/images/page-icon-white.png')}}" alt="投资即送icon"></a></li>
                    <li class="txt">仅投资<span>{{$projectInfo['base_profit'][8]['base']}}万</span>免费领取<br>还获收益<span>{{$projectInfo['base_profit'][8]['profit']}}</span>元</li>
                    <li class="price">原价：899元</li>
                </ul>
            </div>
       
        </div>
    </div>
     <!-- section2 content -->
    <div class="page-section2-bottom page-auto">
        <img src="{{assetUrlByCdn('/static/activity/festival/images/page-fish.png')}}" class="page-fish" alt="定位的小鱼">
    </div>
    <!-- 活动规则 -->
    <div class="page-rule page-auto">
        <h4>活动规则</h4>
        <p><span>1、</span>活动期间内，投资定期项目累计达到一定金额，可以免费获得对应的双诞奖品，投资时使用加息券的额度不计算在内；</p>
        <p><span>2、</span>参与领取奖品者，活动期间提现金额≥10000元，取消其领奖资格；</p>
        <p><span>3、</span>活动所得奖品以实物形式发放，将在2017年1月30日之前，与您沟通联系确定发放奖品；</p>
        <p><span>4、</span>活动期间如有任何疑问请致电九斗鱼官方客服：400-6686-568，或登录九斗鱼咨询在线客服；</p>
    </div>
    <script>
        $(document).ready(function(){
            $(".page-btn").click(function () {

                var projectId   =   '{{$projectInfo['id']}}';

                if( projectId ==''  || !projectId){
                    return false
                }
                window.location.href="/project/detail/"+projectId;
            })
        })
    </script>
@endsection
<!-- 活动开始结束状态 -->
@if( $activityTime['start'] > time())
    @include('pc.common.activityStart')
@endif
<!-- End 活动开始结束状态 -->
@if($activityTime['end'] < time())
    @include('pc.common.activityEnd')
@endif

@section('jsScript')
    <script type="text/javascript">
        $(document).ready(function(){
            $(".page-btn").click(function () {

                var projectId   =   '{{$projectInfo['id']}}';

                if( projectId ==''  || !projectId){
                    return false
                }
                window.location.href="/project/detail/"+projectId;
            })
        })
    </script>
@endsection


