@extends('wap.common.activity')

@section('title', '让火热的小金库燥起来吧')

@section('keywords', env('META_KEYWORD'))

@section('description', env('META_DESCRIPTION'))

@section('css')
<meta name="format-detection" content="telephone=yes">
<link rel="stylesheet" type="text/css" href="{{assetUrlByCdn('/static/weixin/activity/canadian/css/canadian.css')}}">
@endsection

@section('content')
    <article>
    	<!-- banner -->
    	<section class="banner">
    		<p>{{date("Y年m月d日",$activityTime['start'])}}-{{date("d",$activityTime['end'])}}</p>
            <input name="_token" type="hidden" value="{{csrf_token()}}">
    	</section>
    	<!-- End banner -->

    	<!-- reward -->
    	<section class="reward">
    		<ul>
			@if( !empty($awardConfig) )
				@foreach($awardConfig as $key => $value )
				@if( $key =='three')
    			<li class="current"><span>{{$lineNote[$key]}}</span></li>
				@else
				<li><span>{{$lineNote[$key]}}</span></li>
				@endif
				@endforeach

			@else
				<li class="current"><span>3月期</span></li>
				<li><span>6月期</span></li>
				<!-- <li><span>12月期</span></li> -->
			@endif
    		</ul>
            @if( !empty($awardConfig) )
            @foreach($awardConfig as $line => $awardValue)
                @if( $line =='three')
                <div class="reward-main {{$line}}" style="display: block;">
                @else
                <div class="reward-main {{$line}}">
                @endif
                @if( !empty($awardValue))
                    <div>
                    @foreach($awardValue as $award)
                        <p>{{number_format($award['base'])}}</p>
                    @endforeach
                    </div>
                    <div>
                    @foreach($awardValue as  $value)
                        <p>{{$value['award']}}</p>
                    @endforeach
                    </div>
                @endif
                </div>
            @endforeach
        @endif
    	</section>
    	<!-- End reward -->
@if( !empty($projectList))
	@foreach($projectList as $key => $project)
    	<section class="project">
			@if( $userStatus == false)
			<a href="javascript:;" class="userLogin" attr-data-id="{{$project['id']}}">
			@else
			<a href="javascript:;" class="doInvest" attr-data-id="{{$project['id']}}" attr-act-token="{{$project['act_token'] or null}}">
			@endif
    		<div class="project-title"></div>
    		<table>
    			<tr>
    				<th colspan="3">● {{$project['invest_time_note']}}{{$project['id']}}</th>
    			</tr>
    			<tr>
    				<td class="yellow" width="32%"><strong>{{(float)$project['profit_percentage']}}</strong><em>％</em></td>
    				<td width="30%"><big>{{$project['format_invest_time']}}{{$project['invest_time_unit']}}</big></td>
    				<td><big>{{number_format($project['left_amount'])}}元</big></td>
    			</tr>
    			<tr>
    				<td>借款利率</td>
    				<td>期限</td>
    				<td>剩余可投</td>
    			</tr>
    		</table>
			@if($project['status'] == \App\Http\Dbs\Project\ProjectDb::STATUS_INVESTING && \App\Tools\ToolTime::getUnixTime($project['publish_at'],'default') >= time())
    		<span class="btn btn-refund">敬请期待</span>
			@elseif($project['status'] == \App\Http\Dbs\Project\ProjectDb::STATUS_INVESTING)
			<span class="btn">立即出借</span>
			@else
			<span class="btn btn-soldout">{{$project['status_note']}}</span>
			@endif
		</a>
    	</section>
	@endforeach
@endif
    	<!-- End project -->

    	<!-- rule -->
    	<section class="rule">
    		<h3>活动规则</h3>
    		<p>1.活动时间：{{date("Y年m月d日",$activityTime['start'])}}-{{date("Y年m月d日",$activityTime['end'])}}；</p>
            <p style="color:#ffee01">2.仅限在活动页面进行项目投资，才可获得对应的活动奖励；</p>
    		<p>3.活动期间内累计净充值金额（即充值减提现金额）投资3月期或6月期项目，可获得对应等级的现金奖励；</p>
    		<p>4.使用红包或加息券投资的该笔项目金额不参与返现奖励；</p>
    		<p>5.所投资的项目，项目周期内如进行债转，则视为自动放弃现金奖励；</p>
    		<p>6.活动期间内获得的现金奖励发放时间为2017年11月30日，奖励将以现金形式直接发放至您的账户；</p>
    		<p>7.活动期间内如有任何疑问请致电九斗鱼官方客服：400-6686-568，或登录九斗鱼咨询在线客服；</p>
            <p>8.本活动最终解释权归九斗鱼所有。</p>
    	</section>
    	<!-- End rule -->
    </article>

    <!-- pop -->
    <section class="pop-wrap">
    	<div class="pop-mask"></div>
    	<div class="pop">
    		<span class="pop-close"></span>
    		<a href="javascript:;" class="pop-btn userDoLogin"></a>
    	</div>
    </section>
    <!-- End pop -->
@endsection

@section('jsScript')
    <script>
    $(function(){
    	// tab数据切换
        var client = getCookie('JDY_CLIENT_COOKIES');
        if( client == '' || !client ){
            var client  =   '{{$client}}';
        }
    	$('.reward li').on("touchend",function(){
    		var index = $(this).index();
    		$(this).addClass('current').siblings('.reward li').removeClass('current');
    		$('.reward-main').eq(index).show().siblings('.reward-main').hide();
    	});

    	// 点击投资未登录弹层
    	$('.userLogin').on('click',function(){
    		$('.pop-wrap').show();
    	});

    	// 弹层关闭按钮
    	$('.pop-close').click(function(){
    		$('.pop-wrap').hide();
    	})
    })
    </script>
@endsection
