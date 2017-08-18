@extends('wap.common.activity')

@section('title', '投资PK')

@section('keywords', env('META_KEYWORD'))

@section('description', env('META_DESCRIPTION'))

@section('css')
<meta name="format-detection" content="telephone=yes">
<link rel="stylesheet" type="text/css" href="{{ assetUrlByCdn('/static/weixin/activity/investGame4/css/index.css') }}">
@endsection

@section('content')
<div class="page-bg">

    <div class="page-time">
        <time>{{date('m月d日',$activityTime['start'])}}－{{date('m月d日',$activityTime['end'])}}</time>
    </div>
@if(!empty($projectList))
    <div class="page-module module1 project-content">
        <div class="page-module-title">
            <div class="dot"></div>
            <div class="module-title"></div>
        </div>
    <!-- 三月期 -->
@foreach($projectList as $key => $project)
        <div class="page-project">
            <div class="page-sub-title">
@if($key == 'jax')
                <span>九</span><span>安</span><span>心</span>
                <span class="none">•</span>
                <span>{{$project['format_invest_time']}}</span><span>天</span>
@else
                <span>九</span><span>省</span><span>心</span>
                <span class="none">•</span>
                <span>{{isset($lineToNumber[$key]) ?$lineToNumber[$key] :12}}</span><span>月</span><span>期</span>
@endif
            </div>

            <div class="text">
                <p>出借利率<big>{{(float)$project['profit_percentage']}}</big><span>％</span></p>
                <p>剩余可投<span>{{$project['left_amount']}}元</span></p>
            </div>

            <div class="progress">
                <div class="bar" style="width: {{ number_format($project['invested_amount']/$project['total_amount'],2)*100 }}%;">
                    <span class="dot"><span>
                </div>
                <span class="per">{{ number_format($project['invested_amount']/$project['total_amount'],2)*100 }}%</span>
            </div>
@if($project['status'] == \App\Http\Dbs\Project\ProjectDb::STATUS_INVESTING && \App\Tools\ToolTime::getUnixTime($project['publish_at'],'default') >= time())
            <a href="javascript:;" attr-data-id="{{$project['id']}}" class="btn investProject">敬请期待</a>
@elseif($project['status'] == \App\Http\Dbs\Project\ProjectDb::STATUS_INVESTING)
            <a href="javascript:;" attr-data-id="{{$project['id']}}" class="btn investProject">立即出借</a>
@else
            <a href="javascript:;" attr-data-id="{{$project['id']}}" class="btn investProject">{{$project['status_note']}}</a>
@endif
        </div>
        <div class="page-module-line page-module-line-footer"></div>
@endforeach
    </div>
@endif

    <!-- 倒计时 -->
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
@if(!empty($nowDay['statistics']))
    <!-- pk排名 -->
    <div class="page-module module2 invest-now-day-list">
        <!-- 三月期 -->
        <div class="page-module-title">
            <div class="dot"></div>
            <div class="module-title"></div>
        </div>
@foreach($nowDay['statistics'] as $k => $statistic )
@if($k =='jax')
        <div class="page-sub-title">
            <span>九</span><span>安</span><span>心</span>
        </div>
@else
        <div class="page-sub-title">
            <span>{{isset($lineToNumber[$k]) ?$lineToNumber[$k] :12}}</span><span>月</span><span>期</span>
        </div>
@endif
        <ul class="page-pk-list">
        @if(empty($statistic))
            <li><span class="heart1">1</span><em>{{date('m月d日',time())}}</em>暂无排名数据</li>
        @else
        @foreach($statistic as $n => $item )
        @if($n ==0)
            <li><span class="heart1">1</span><em>{{\App\Tools\ToolStr::hidePhone($nowDay['user'][$item['user_id']]['phone'])}}</em>{{$item['total']}}元</li>
        @else
            <li><span></span><em>{{\App\Tools\ToolStr::hidePhone($nowDay['user'][$item['user_id']]['phone'])}}</em>{{$item['total']}}元</li>
        @endif
        @endforeach
        @endif
        </ul>
        <p class="page-refesh-txt" onclick="window.location.reload();">*点我实时刷新数据 </p>
        <div class="page-module-line page-module-line-footer"></div>
        <!-- 六月期 -->
  @endforeach
    </div>
@endif
@if(!empty($lotteryList['lottery']))
    <!-- 投资奖品 -->
    <div class="page-module module3 lottery-record-list">
        <!-- 三月期 模块标题-->
        <div class="page-module-title">
            <div class="dot"></div>
            <div class="module-title"></div>
        </div>
@foreach($lotteryList['lottery'] as $nu =>$lottery)
        <div class="page-pk-prize">
            <div class="page-prize-show">
                {{-- <span class="show{{$nu}}">九<br>省心</span> --}}

                <img src="{{ assetUrlByCdn('/static/weixin/activity/investGame4/images/page-gift'.$nu.'-1.png') }}" class="page-prize-img" alt="PK奖品">
            </div>
            {{-- <p class="page-prize-name">{{$lottery['name']}}</p> --}}
            <div class="page-winner-title"><span>中奖纪录</span></div>
            <div class="page-winner-list">
        @if(!empty($lotteryList['record'][$nu]))
            @foreach($lotteryList['record'][$nu] as $record)
                <p><span>{{date('m月d日',strtotime($record['created_at']))}}<span><span>{{\App\Tools\ToolStr::hidePhone($record['phone'])}}</span>{{$record['award_name']}}</p>
            @endforeach
        @else
            <p>九省心</p>
            <p>{{date('m月d日',time())}}<span></span>暂无中奖数据公布!</p>
        @endif
            </div>
        </div>
            <div class="page-module-line page-module-line-footer"></div>
@endforeach
    </div>
@endif
    <div class="page-rule">
        <h4>活动规则</h4>
        <p>1、本次活动，仅限活动页面展示的优选项目参加；</p>
        <p>2、活动期间内，使用加息券、红包的投资额度不计算在pk数据统计中</p>
        <p>3、活动期间内，每日各选取活动页面展示的优选项目，累计投资金额的前三名，获得对应的实物奖品；以用户投资优选项目的累积金额进行排序，当用户累积投资金额出现并列时，则按照用户投资最后一笔时间的先后，择先选取；</p>
        <p>4、参与领取奖品者，活动期间提现金额≥10000元，取消其领奖资格；</p>
        <p>5、活动所得奖品以实物形式发放，将在2017年5月30日之前，与您沟通联系确定发放奖品；</p>
    </div>
</div>
<script  type="text/javascript" src="{{ assetUrlByCdn('/static/weixin/activity/investGame/js/countDown.js') }}"></script>
@endsection

@section('footer')

@endsection

@section('jsScript')


    <script type="text/javascript">
        $('.project-content').find('.page-module-line:eq(0)').removeClass('page-module-line-footer');
        $('.invest-now-day-list').find('.page-refesh-txt:eq(0)').remove();
        $('.invest-now-day-list').find('.page-module-line:eq(0)').removeClass('page-module-line-footer');
        $('.lottery-record-list').find('.page-module-line:eq(0)').removeClass('page-module-line-footer');
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

            var status      =   '{{ $activityStatus }}';
            //活动倒计时
            if(status == 3){
                setInterval(function(){
                    countDown.getRTime('server-spike-time','{{ date("Y/m/d H:i:s",$lastTime) }}')
                },1000);
            }
    </script>
@endsection
