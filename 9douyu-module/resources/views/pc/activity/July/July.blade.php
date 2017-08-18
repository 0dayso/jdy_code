@extends('pc.common.activity')

@section('title', '夏不为利 畅享七月')

@section('keywords', env('META_KEYWORD'))

@section('description', env('META_DESCRIPTION'))

@section('csspage')
    <meta name="format-detection" content="telephone=yes">
    <link rel="stylesheet" type="text/css" href="{{assetUrlByCdn('/static/activity/July/css/index.css')}}">
@endsection
@section('content')
<div class="page-banner">
    <input type="hidden" name="_token"  value="{{csrf_token()}}">
    <p>{{date('Y-m-d',$activityTime['start'])}} --{{date('m-d',$activityTime['end'])}}</p>
</div>

<div class="wrap page-top">
     <div class="Js_tab_box page-tab-box">
        <ul class="Js_tab_click page-main-tab" id="tab1">
            <li class="cur">3月期项目</li>
            <li >6月期项目</li>
        </ul>
        <div class="Js_tab_main_click clearfix" style="display:block;" >
            @include('pc.activity.July.lotteryOne')
        </div>
        <div class="Js_tab_main_click clearfix" >
            @include('pc.activity.July.lotteryTwo')
        </div>
    </div>
    <!--project-->
    <span id="investModule"></span>
    @include('pc.activity.July.project')

    <div class="rule">
        <div class="page-title1"> -活动规则- </div>
        <div class="rule-content">
            <p>1.活动期间内，累计投资优选项目3月期或6月期达到奖品对应金额，即可获得对应的实物奖品。投资金额不可使用加息券或红包；</p>
            <p>2.参与领取奖品者，活动期间提现金额≥10000元，取消其领奖资格；</p>
            <p>3.活动所得奖品以实物形式发放，将在2017年8月30日之前，与您沟通联系确定发放奖品。如在2017年8月30日之前联系未果，则视为自动放弃奖励；</p>
            <p>4.活动期间如有任何疑问请致电九斗鱼官方客服：400-6686-568，或登录九斗鱼咨询在线客服；</p>
            <p>5.活动最终解释权归九斗鱼所有；</p>
        </div>
    </div>
</div>
@endsection


@section('jspage')
<script type="text/javascript">
     function tabclick(tab,tabmain,cur,cur1){
        $(tab).click(function(){
            var index = $(tab).index(this);
            $(this).addClass(cur).siblings(tab).removeClass(cur);
            if($(this).index()==1){
                  $(this).parent().addClass(cur1);
             }else{
                 $(this).parent().removeClass(cur1);
             }

            $(tabmain).eq(index).show().siblings(tabmain).hide();
        })
    };
    tabclick('.Js_tab_click li','.Js_tab_main_click','cur',"cur1");
</script>
@endsection
