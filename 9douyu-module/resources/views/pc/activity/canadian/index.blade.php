@extends('pc.common.activity')

@section('title', '让火热的小金库燥起来吧')

@section('keywords', env('META_KEYWORD'))

@section('description', env('META_DESCRIPTION'))

@section('css')
    <meta name="format-detection" content="telephone=yes">

@endsection
@section('content')
    <link rel="stylesheet" type="text/css" href="{{ assetUrlByCdn('/static/activity/canadian/css/canadian.css')}}">
    <div class="page-banner">
    	<div class="page-time">{{date('Y年m月d日',$activityTime['start'])}}-{{date('m月d日',$activityTime['end'])}}</div>
    </div>

	<!-- 返现 -->
	<div class="Js_tab_box page-cash-return">
        <ul class="Js_tab page-cash-tab clearfix">
            @if( !empty($awardConfig) )
                @foreach($awardConfig as $key => $value )
                    @if( $key =='three')
                        <li class="cur" style="margin-left:0;">{{$lineNote[$key]}}</li>
                    @else
                        <li>{{$lineNote[$key]}}</li>
                    @endif
                @endforeach

            @else
            <li class="cur" style="margin-left:0;">3月期</li>
            <li>6月期</li>
            @endif
        </ul>
        <div class="js_tab_content page-cash-content">
            @if( !empty($awardConfig) )
@foreach($awardConfig as $line => $awardValue)
        @if( $line =='three')
                    <div class="Js_tab_main">
        @else
                    <div class="Js_tab_main" style="display: none;">
        @endif
                        <ul class="color_{{$line}}">
        @if( !empty($awardValue))
            @foreach($awardValue as $num=> $item)
               @if( $num =='0')
                        <li class="clearfix">
                            <p><span></span>{{number_format($item['base'])}}</p>
                            <p><span class="withdrawal"></span>{{$item['award']}}</p>
                        </li>
               @else
                        <li class="clearfix">
                            <p>{{number_format($item['base'])}}</p>
                            <p>{{$item['award']}}</p>
                        </li>
               @endif
            @endforeach
        @endif
                        </ul>
                    </div>
@endforeach
            @endif
        </div>
	</div>

    <!-- 项目 -->
@if( !empty($projectList) )
    @foreach($projectList as $key => $project )
    <div class="page-project-main page-auto">
        <div class="page-project-item page-auto">
            <h4 class="title"><span>•</span>{{$project['invest_time_note']}}{{$project['id']}}</h4>
            <div class="page-project-inner clearfix">
                <p class="p1"><strong>{{(float)$project['profit_percentage']}}</strong><em>%</em><span>借款利率</span></p>
                <p class="p2"><em>{{$project['format_invest_time']}}{{$project['invest_time_unit']}}</em><span>期限</span></p>
                <p class="p2"><em>{{$project['refund_type_note']}}</em><span>还款方式</span></p>
                <p class="p2"><em>{{$project['left_amount']}}元</em><span>剩余可投</span></p>
            </div>
@if( $userStatus == true)
    @if($project['status'] == \App\Http\Dbs\Project\ProjectDb::STATUS_INVESTING && \App\Tools\ToolTime::getUnixTime($project['publish_at'],'default') >= time())
            <a href="javascript:;" attr-data-id="{{$project['id']}}"  class="page-project-disabled clickInvest page-auto" attr-act-token="{{$project['act_token'] or null}}">敬请期待</a>
    @elseif($project['status'] == \App\Http\Dbs\Project\ProjectDb::STATUS_INVESTING)
            <a href="javascript:;" attr-data-id="{{$project['id']}}"  class="page-project-btn page-auto clickInvest" attr-act-token="{{$project['act_token'] or null}}" >立即出借</a>
    @else
            <a href="javascript:;" attr-data-id="{{$project['id']}}" class="page-project-btn page-auto clickInvest" attr-act-token="{{$project['act_token'] or null}}" >{{$project['status_note']}}</a>
    @endif
@else
            <a href="javascript:;" class="page-project-btn page-auto active userLogin">立即出借</a>
@endif
        </div>
    </div>
    @endforeach
@endif
    <!-- 活动规则 -->
    <div class="page-bottom-wrap">
    	<div class="page-bottom page-auto">
	    	<div class="page-rule">
	    		<h6>活动规则</h6>
	    		<p>1.活动时间：{{date('Y年m月d日',$activityTime['start'])}}-{{date('Y年m月d日',$activityTime['end'])}}；</p>
	    		<p style="color:#ffee01">2.仅限在活动页面进行项目投资，才可获得对应的活动奖励；</p>
                <p>3.活动期间内累计净充值金额（即充值减提现金额）投资3月期或6月期项目，可获得对应等级的现金奖励；</p>
                <p>4.使用红包或加息券投资的该笔项目金额不参与返现奖励；</p>
                <p>5.所投资的项目，项目周期内如进行债转，则视为自动放弃现金奖励；</p>
                <p>6.活动期间内获得的现金奖励发放时间为2017年11月30日，奖励将以现金形式直接发放至您的账户；</p>
                <p>7.活动期间内如有任何疑问请致电九斗鱼官方客服：400-6686-568，或登录九斗鱼咨询在线客服；</p>
                <p>8.本活动最终解释权归九斗鱼所有。</p>
	    	</div>
    	</div>
    </div>
    <!-- 没登录提示框 -->
    <div class="page-layer layer" style="display: none;">
    	<div class="page-mask"></div>
    	<div class="page-pop">
    		<a href="javascript:;" class="page-pop-close" data-toggle="mask" data-target="layer">关闭</a>
    		<a href="/login" class="page-pop-btn">登录</a>
    	</div>
    </div>
<input type="hidden" name="_token"  value="{{csrf_token()}}">
<script>
    $(function(){
        // 点击投资未登录弹层
        $('.userLogin').on('click',function(){
            $('.page-layer').show();
        });
    })
</script>
@endsection


@section('jsScript')
@endsection


