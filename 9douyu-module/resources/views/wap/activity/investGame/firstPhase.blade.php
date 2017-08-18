@extends('wap.common.wapBase')

@section('title', '新春伊始 如鱼得水')

@section('keywords', env('META_KEYWORD'))

@section('description', env('META_DESCRIPTION'))

@section('css')
<meta name="format-detection" content="telephone=yes">
<link rel="stylesheet" type="text/css" href="{{ assetUrlByCdn('/static/weixin/activity/investGame1/css/firstPhase.css') }}">
@endsection

@section('content')
<div class="first-bg">
	<p>{{date("m.d",$activityTime['start'])}}-{{date("m.d",$activityTime['end'])}}</p>
</div>
@if( $activityStatus ==\App\Http\Logics\Activity\InvestGameLogic::ACTIVITY_PADDING )
<div class="first-bg1">
	<div class="first-title"></div>
	<section class="first-mg">
        @if(!empty($projectList))
            @foreach($projectList as $key=> $project)
		<div class="first-box">
			<div class="first-title1">{{$project['product_line_note']}} • {{$project['invest_time_note']}}</div>
			<table class="first-table">
				<tr>
					<td width="35%"><span>{{(float)$project['profit_percentage']}}</span>％</td>
					<td width="32%">{{$project['left_amount']}}元</td>
					<td rowspan="2">
                    @if($project['status'] == \App\Http\Dbs\Project\ProjectDb::STATUS_INVESTING && \App\Tools\ToolTime::getUnixTime($project['publish_at'],'default') >= time())
                        <a href="javascript:;"  attr-data-id="{{$project['id']}}" class="first-btn ">待售</a>
                    @elseif($project['status'] == \App\Http\Dbs\Project\ProjectDb::STATUS_INVESTING)
                        <a href="javascript:;"  attr-data-id="{{$project['id']}}" class="first-btn investProject">投资</a>
                    @else
                        <a href="javascript:;"  attr-data-id="{{$project['id']}}" class="first-btn disable investProject">售罄</a>
                    @endif
					</td>
				</tr>
				<tr>
					<td>借款利率</td>
					<td>剩余可投</td>
				</tr>
			</table>
		</div>
        @endforeach
        @endif
	</section>
</div>
@endif
<div class="first-time">
@if( $activityStatus ==\App\Http\Logics\Activity\InvestGameLogic::ACTIVITY_NOT_OPEN)
    <p class="antwo-sum">活动未开始</p>
@elseif($activityStatus ==\App\Http\Logics\Activity\InvestGameLogic::ACTIVITY_IS_OVER)
    <p class="antwo-sum">活动已经结束</p>
@else
    <p class="antwo-sum">距离今日争夺战结束还有</p>
    <p class="antwo-num server-spike-time" attr-spike-time="{{ date("Y/m/d H:i:s",time()) }}">{!! $lastSecond !!}</p>
@endif
</div>
<div class="first-title-2"></div>
@if($nowDay['statistics'] && $activityStatus!=\App\Http\Logics\Activity\InvestGameLogic::ACTIVITY_NOT_OPEN && !empty($nowDay['user']))
    @foreach( $nowDay['statistics'] as $key => $statistic)
    @if($key =='one')
    <p class="first-title2">1月期PK排名</p>
    @elseif($key =='three')
    <p class="first-title2">3月期PK排名</p>
    @elseif($key =='six')
    <p class="first-title2">6月期PK排名</p>
    @elseif($key =='twelve')
    <p class="first-title2">12月期PK排名</p>
    @elseif($key =='jax')
    <p class="first-title2">九安心PK排名</p>
    @else
    <p class="first-title2">九省心PK排名</p>
    @endif
    @if( empty($statistic))
        <p class="first-title2">暂无排名数据</p>
    @else
    <div class="first-ranking">
        @foreach($statistic as $id  => $info )
            @if( $id < 3 )
                <p><span>{{\App\Tools\ToolStr::hidePhone($nowDay['user'][$info['user_id']]['phone'])}}</span><span>{{ \App\Tools\ToolMoney::moneyFormat($info['total'])}}元</span></p>
            @endif
            {{--@if( $id==3)
        <p><span>第四名</span><span>{{\App\Tools\ToolStr::hidePhone($nowDay['user'][$info['user_id']]['phone'])}} </span><span>{{ \App\Tools\ToolMoney::moneyFormat($info['total'])}}元</span></p>
            @elseif($id==4)
        <p><span>第五名</span><span>{{\App\Tools\ToolStr::hidePhone($nowDay['user'][$info['user_id']]['phone'])}}</span><span>{{ \App\Tools\ToolMoney::moneyFormat($info['total'])}}元</span></p>
            @else
        <p><span>{{\App\Tools\ToolStr::hidePhone($nowDay['user'][$info['user_id']]['phone'])}}</span><span>{{ \App\Tools\ToolMoney::moneyFormat($info['total'])}}元</span></p>
            @endif--}}
            @endforeach
    </div>
    @endif
@endforeach
@else
<p class="first-title2">暂无投资排名数据</p>
@endif

{{--<p class="first-title2">6月期PK排名</p>--}}
{{--<div class="first-ranking">--}}
	{{--<p><span>马＊＊</span><span>0,000,000元</span></p>--}}
	{{--<p><span>马＊＊</span><span>0,000,000元</span></p>--}}
	{{--<p><span>马＊＊</span><span>0,000,000元</span></p>--}}
{{--</div>--}}
<p class="first-up" ><a href="javascript:window.location.reload()">点我更新最新投资数据</a></p>
<div class="first-title-1"></div>
<div class="first-gift">
    <?php $i =1;?>
    @foreach($projectList as $key=> $project)
        <?php if($i==1){
            $order = '';
        }else{
            $order = 1;
        } ?>
        <p class="gift-title{{$order}}">
            @if($key=='jax')九安心@else{{$project['invest_time_note']}}@endif 奖品</p>
        <?php $i++;?>
    @endforeach
    {{--<p class="gift-title">3月期奖品</p>
    <p class="gift-title1">6月期奖品</p>--}}
</div>
<section class="first-mg first-j">
	<p class="antwo-sum antwo1" style="margin-top: 1.5rem;">中奖记录</p>
    {{--<div class="first-record">--}}
        {{----}}
        {{--<div class="first-date1">--}}
            {{--<p class="first-date">10月17日</p>--}}
            {{--<p>九省心&nbsp; 3月期</p>--}}
            {{--<ul>--}}
                {{--<li><p><span>135xxxx8899</span><span>888888元</span><span>获得小米电视一台</span></p></li>--}}
                {{--<li><p><span>135xxxx8899</span><span>888888元</span><span>获得小米电视一台</span></p></li>--}}
                {{--<li><p><span>135xxxx8899</span><span>888888元</span><span>获得小米电视一台</span></p></li>--}}
            {{--</ul>--}}
            {{--<p>九省心&nbsp; 6月期</p>--}}
            {{--<ul>--}}
                {{--<li><p><span>135xxxx8899</span><span>888888元</span><span>获得小米电视一台</span></p></li>--}}
                {{--<li><p><span>135xxxx8899</span><span>888888元</span><span>获得小米电视一台</span></p></li>--}}
                {{--<li><p><span>135xxxx8899</span><span>888888元</span><span>获得小米电视一台</span></p></li>--}}
            {{--</ul>--}}
        {{--</div>--}}
    {{--</div>--}}
	<div class="first-record">
        <div class="first-date1">
        @if(!empty($everyDay))
            @foreach( $everyDay as $key=> $every)
        <p class="first-date">{{date("m月d日",strtotime($key))}}</p>
            @foreach( $every['statistics'] as $k => $statistics )
        @if($k=='three')
            <p>九省心  3月期</p>
        @elseif($k=='six')
            <p>九省心  6月期</p>
        @elseif($k=='one')
            <p>九省心  1月期</p>
        @elseif($k=='twelve')
            <p>九省心  12月期</p>
        @elseif($k=='jax')
            <p>九安心</p>
        @else
            <p>九省心</p>
        @endif
        <ul>
        @if( empty($statistics) )
            <li>
                <p><span></span><span>暂无中奖数据</span><span></span></p>
            </li>
        @else
            @foreach( $statistics as $num => $info)
            @if(isset($every['user'][$info['user_id']]['phone']) && !empty($every['user'][$info['user_id']]['phone']))
            <li>
                <p><span>{{\App\Tools\ToolStr::hidePhone($every['user'][$info['user_id']]['phone'])}}</span><span>{{$info['total']}}元</span><span>{{$lotteryList[$k][$num+1]}}</span></p>
            </li>
            @endif
            @endforeach
        @endif

		</ul>
                @endforeach
        @endforeach
        @else
        <ul>
            <li>
                <p><span></span><span>暂无中奖数据</span><span></span></p>
            </li>
        </ul>
        @endif
	</div>
	</div>
</section>
<div class="kill-tip"><span></span><a href="javascript:;">查看活动规则</a></div>
<!-- 弹窗 -->
    <div class="kill-pop-wrap">
        <div class="mask3"></div>
        <div class="kill-pop" id="kill-pop">
            <i></i>
            <h3>活动规则</h3>
            <p>1、本次投资pk活动仅限活动页面展示的项目参与。</p>
            <p>2、活动期间，每日各选取活动页面投资项目的前三名，获得对应奖品，以用户投资pk的累积金额进行排序；用户出借金额出现并列式，按照用户最后一笔投资时间，择先选取。</p>
            <p>3、参与领取奖品者，活动期间提现金额≥10000元，取消其领奖资格。</p>
            <p>4、活动所得奖品以实物形式发放，将在2017年3月15日之前，与您沟通联系确定发放奖品。</p>
            <p>5、活动期间如有任何疑问请致电九斗鱼官方客服：400-6686-568，或登录九斗鱼咨询在线客服。</p>
        </div>
    </div>

@endsection

@section('footer')

@endsection
<!-- 活动开始结束状态 -->
@if( $activityTime['start'] > time())
    @include('wap.common.activityStart')
@endif
<!-- End 活动开始结束状态 -->
@if($activityTime['end'] < time())
    @include('wap.common.activityEnd')
@endif

    <!-- End prize pop -->
@section('jsScript')
    <script type="text/javascript">
    	$(".kill-tip a").click(function(){
        $(".kill-pop-wrap").show();
        var h = $("#kill-pop").outerHeight();
        var mt = parseInt(-h/2) + 'px';
        $("#kill-pop").css("margin-top",mt);
    });
    $(".kill-pop i,.mask3").click(function(){
        $(".kill-pop-wrap").hide();
    });

        $('.investProject').click(function () {

        var client = getCookie('JDY_CLIENT_COOKIES');
        if( client == '' || !client ){
            var client  =   '{{$client}}';
        }
            var  projectId  =   $(this).attr("attr-data-id");
            if( !projectId ||projectId==0){
                return false;
            }
            if( client =='ios'){
                window.location.href="objc:certificationOrInvestment("+projectId+",1)";
                return false;
            }
            if (client =='android'){
                window.jiudouyu.fromNoviceActivity(projectId,1);
                return false;
            }
            window.location.href='/project/detail/'+projectId;

        })

        $(function(){
            var status      =   '{{ $activityStatus }}';
            //活动倒计时
            getRTime = function (){

                var serverTime  =   $(".server-spike-time").attr("attr-spike-time");
                var endTime     =   new Date('{{ date("Y/m/d H:i:s",$lastTime) }}');
                var nowTime     =   new Date(serverTime);

                var t =endTime.getTime() - nowTime.getTime();
                var d=0;
                var h=0;
                var m=0;
                var s=0;
                if(t>0){

                    h=Math.floor(t/1000/60/60%24);
                    if(h < 10){
                        oh = '0';
                        sh = h;
                    }else{
                        oh = Math.floor(h/10);
                        sh = Math.floor(h%10);
                    }
                    m=Math.floor(t/1000/60%60);

                    if(m < 10 ){
                        om = '0';
                        sm = m;
                    }else{
                        om= Math.floor(m/10);
                        sm= Math.floor(m%10);
                    }

                    s=Math.floor(t/1000%60);
                    if( s < 10 ){
                        os = '0';
                        ss = s;
                    }else{
                        os = Math.floor(s/10);
                        ss = Math.floor(s%10);
                    }

                    document.getElementById("t_h1").innerHTML   = oh;
                    document.getElementById("t_h").innerHTML    = sh;
                    document.getElementById("t_m1").innerHTML   = om;
                    document.getElementById("t_m").innerHTML    = sm;
                    document.getElementById("t_s1").innerHTML   = os;
                    document.getElementById("t_s").innerHTML    = ss;
                }else {

                    window.location.reload()
                }
                getNextTime();
            }
            if(status == 3){
                setInterval(getRTime,1000);
            }

            function getNextTime(){
                var serverTime  =   $(".server-spike-time").attr("attr-spike-time");
                var nowTime     =   new Date(serverTime);
                var t1 = nowTime.getTime();
                t1 +=1000;
                var nowTime1 = new Date(t1);
                var y1 = nowTime1.getFullYear();
                var m1 = nowTime1.getMonth()+1;
                var d1 = nowTime1.getDate();
                var h1 = nowTime1.getHours();
                var i1 = nowTime1.getMinutes();
                var s1 = nowTime1.getSeconds();
                $(".server-spike-time").attr("attr-spike-time",y1+"/"+m1+"/"+d1+" "+h1+":"+i1+":"+s1);
            }
        })
    </script>
@endsection

