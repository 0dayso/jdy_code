@extends('pc.common.activity')

@section('title', '邀您一起畅春游')

@section('keywords', env('META_KEYWORD'))

@section('description', env('META_DESCRIPTION'))

@section('css')
    <meta name="format-detection" content="telephone=yes">

@endsection
@section('content')
    <link rel="stylesheet" type="text/css" href="{{assetUrlByCdn('static/activity/invitation/css/invitation.css')}}">
    <div class="s-banner">
        <p>{{date('Y年m月d日',$activityTime['start'])}}-{{date('m月d日',$activityTime['end'])}}</p>
    </div>
    <div class="bg">
        <p class="antwo-sum title"><span class="left"></span>春意盎然 邀友踏春<span class="right"></span></p>
        <p class="text1">活动期间邀请好友注册投资九斗鱼</p>
        <p class="text1">根据好友累计投资定期金额排名前{{$ranking_total}}的邀请人可获得对应奖品</p>
        <div class="prize"></div>
        <div class="little-banner"></div>
    </div>
    <div class="bg3">
       <p class="antwo-sum"><span class="left"></span>最新排名<span class="right"></span></p>
       <div class="ranking">
           <ul>
@if( empty($ranking_list))
<li><span class="num1">{{date('Y年m月d日',time())}}</span> <span  class="num2">暂无邀请数据</span> <span class="num3">暂无投资数据</span></li>
@else
@foreach($ranking_list as $key => $rank)
@if($key < $ranking_total)
<li class="red"><span class="num1">{{isset($rank['phone'])?\App\Tools\ToolStr::hidePhone($rank['phone']):'********'}}</span> <span  class="num2">{{isset($rank['total'])?$rank['total']:'**'}}</span> <span class="num3">{{isset($rank['invest_cash'])?$rank['invest_cash']:'****'}}</span></li>
@else
<li><span class="num1">{{isset($rank['phone'])?\App\Tools\ToolStr::hidePhone($rank['phone']):'********'}}</span> <span  class="num2">{{isset($rank['total'])?$rank['total']:'**'}}</span> <span class="num3">{{isset($rank['invest_cash'])?$rank['invest_cash']:'****'}}</span></li>
@endif
@endforeach
@endif
           </ul>
           <a href="javascript:;" onclick="window.location.reload();" class="s-btn">刷新数据</a>
           <!-- <a href="#" class="s-btn disable">刷新数据</a> -->

       </div>
       <div class="line-bottom"></div>

       <p class="antwo-sum title1"><span class="left"></span>邀请好友有什么好处?<span class="right"></span></p>
       <p class="text1"> 好友人数越多，佣金收益越高</p>
       <p class="text1">最高可达<span>３%</span>佣金收益率</p>
       <div class="little-banner1">
           <p class="text2">邀请的好友，<br/>在九斗鱼注册并投资，最高获得3%佣金年化收益</p>
           <p class="text3">自己邀请的好友在九斗鱼一年投资100万元，自己最高可额外获得3万元佣金，躺着也能赚钱。</p>
       </div>
       <p class="antwo-sum title2"><span class="left"></span>如何参与<span class="right"></span></p>
       <p class="text1">  攻略：好友下载九斗鱼APP——注册后填写邀请人手机号</p>
       <div class="little-banner2"></div>
       <div class="s-rule">
           <h4>【活动规则】</h4>
           <p>1.{{date('m月d日',$activityTime['start'])}}-{{date('m月d日',$activityTime['end'])}},活动期间新邀请的好友注册且投资九斗鱼，根据好友累计投资定期金额排名前{{$ranking_total}}的邀请人可获得对应奖品。</p>
           <p>2.活动期间，邀请人和被邀请的人的净充值金额为负，则取消邀请人领奖资格；</p>
           <p>3.活动所得奖品以实物形式发放，将在2017年5月31日之前，与您沟通联系确定发放奖品。如联系用户无回应，视为自动放弃活动奖励;</p>
           <p>4.活动期间如有任何疑问请致电九斗鱼官方客服：400-6686-568，或登录九斗鱼咨询在线客服。</p>
       </div>

    </div>
@endsection


