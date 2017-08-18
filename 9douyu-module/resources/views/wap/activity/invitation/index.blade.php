@extends('wap.common.activity')

@section('title', '邀您一起畅春游')

@section('keywords', env('META_KEYWORD'))

@section('description', env('META_DESCRIPTION'))

@section('css')
<meta name="format-detection" content="telephone=yes">
<link rel="stylesheet" type="text/css" href="{{ assetUrlByCdn('/static/weixin/activity/invitation/css/index.css') }}">

@endsection

@section('content')
<div class="page-body page-auto">
    <time class="page-time">{{date('Y年m月d日',$activityTime['start'])}}--{{date('m月d日',$activityTime['end'])}}</time>

    <div class="page-center page-padding">
        <span class="page-title"><span></span>春意盎然 邀友踏春<span></span></span>
        <p class="page-txt font-small-size">活动期间邀请好友注册投资九斗鱼</p>
        <p class="page-txt font-small-size">根据好友累计投资定期金额排名前{{$ranking_total}}的邀请人可获得对应奖品</p>
    </div>

    <div class="page-center">
@if(empty($lottery_list))
        <div class="page-prize">
            <h4>美的高温消毒碗柜</h4>
            <img src="{{ assetUrlByCdn('/static/weixin/activity/invitation/images/page-prize1.png') }}" class="page-prize-img" alt="美的高温消毒碗柜">
            <span>第一名</span>
        </div>
        <div class="page-prize">
            <h4>小狗家用无线小型吸尘器</h4>
            <img src="{{ assetUrlByCdn('/static/weixin/activity/invitation/images/page-prize2.png') }}" class="page-prize-img" alt="小狗家用无线小型吸尘器">
            <span>第二名</span>
        </div>
        <div class="page-prize page-block page-auto">
            <h4>美的App智能微波炉</h4>
            <img src="{{ assetUrlByCdn('/static/weixin/activity/invitation/images/page-prize3.png') }}" class="page-prize-img" alt="美的App智能微波炉">
            <span>第三名</span>
        </div>
        <div class="page-prize">
            <h4>东菱面包机</h4>
            <img src="{{ assetUrlByCdn('/static/weixin/activity/invitation/images/page-prize4.png') }}" class="page-prize-img" alt="东菱面包机">
            <span>第四名</span>
        </div>
        <div class="page-prize">
            <h4>九阳多功能豆浆机</h4>
            <img src="{{ assetUrlByCdn('/static/weixin/activity/invitation/images/page-prize5.png') }}" class="page-prize-img" alt="九阳多功能豆浆机">
            <span>第五名</span>
        </div>
@else
@foreach($lottery_list as $key => $lottery)
@if($lottery['order_num'] ==3)
        <div class="page-prize page-block page-auto">
@else
        <div class="page-prize">
@endif
            <h4>{{$lottery['name']}}</h4>
            <img src="{{ assetUrlByCdn('/static/weixin/activity/invitation/images/page-prize'.$lottery["order_num"].'.png') }}" class="page-prize-img">
             <span>第{{$ranking_word[$lottery['order_num']]}}名</span>
        </div>
@endforeach
@endif
    </div>
    <img src="{{ assetUrlByCdn('/static/weixin/activity/invitation/images/page-small-banner.png') }}" class="page-small-banner page-block" alt="">

    <div class="page-center page-padding"><span class="page-title"><span></span>最新排名<span></span></span></div>

    <div class="page-table">
        <table class="page-auto">
@if(empty($ranking_list))
            <tr>
                <td class="first">{{date('m月d日')}} </td>
                <td>暂无邀请数据</td>
                <td class="last">暂无投资数据</td>
            </tr>
@else
@foreach($ranking_list as $key => $ranking)
@if($key < $ranking_total)
            <tr class="mark">
@else
            <tr>
@endif
                <td class="first">{{isset($ranking['phone'])?\App\Tools\ToolStr::hidePhone($ranking['phone']):'********'}}</td>
                <td>{{isset($ranking['total'])?$ranking['total']:'**'}}</td>
                <td class="last">{{isset($ranking['invest_cash'])?$ranking['invest_cash']:'****'}}</td>
            </tr>
@endforeach
@endif
        </table>
        <a href="javascript:;" onclick='window.location.reload();' class="page-btn-refresh">刷新数据</a>
    </div>
    <div class="page-center page-padding">
        <span class="page-title"><span></span>邀请好友有什么好处？<span></span></span>
        <p class="page-txt">好友人数越多，佣金收益越高</p>
        <p class="page-txt">最高可达 <mark>３%</mark> 佣金收益率</p>
    </div>

    <img src="{{ assetUrlByCdn('/static/weixin/activity/invitation/images/page-img4.png') }}" class="page-img4 page-block" alt="">
    <div class="page-center page-padding">
        <span class="page-title"><span></span>如何参与<span></span></span>
        <p class="page-txt">攻略：好友下载九斗鱼APP</p>
        <p class="page-txt">注册后填写邀请人手机号</p>
    </div>

    <div class="page-radius">
         <img src="{{ assetUrlByCdn('/static/weixin/activity/invitation/images/page-img1.png') }}" class="page-img1 page-block" alt="">
         <img src="{{ assetUrlByCdn('/static/weixin/activity/invitation/images/page-img2.png') }}" class="page-img2 page-block" alt="">
         <img src="{{ assetUrlByCdn('/static/weixin/activity/invitation/images/page-img3.png') }}" class="page-img3 page-block" alt="">
         <p class="page-scan">扫一扫</p>
    </div>

    <div class="page-rule page-margin">
        <h4>【活动规则】</h4>
        <p>1.{{date('m月d日',$activityTime['start'])}}--{{date('m月d日',$activityTime['end'])}}，活动期间新邀请的好友注册且投资九斗鱼，根据好友累计投资定期金额排名前{{$ranking_total}}的邀请人可获得对应奖品。</p>
        <p>2.活动期间，邀请人和被邀请的人的净充值金额为负，则取消邀请人领奖资格；</p>
        <p>3.活动所得奖品以实物形式发放，将在2017年5月31日之前，与您沟通联系确定发放奖品。如联系用户无回应，视为自动放弃活动奖励;</p>
        <p>4.活动期间如有任何疑问请致电九斗鱼官方客服：400-6686-568，或登录九斗鱼咨询在线客服。</p>
    </div>

</div>

@endsection

@section('footer')

@endsection

@section('jsScript')



@endsection
