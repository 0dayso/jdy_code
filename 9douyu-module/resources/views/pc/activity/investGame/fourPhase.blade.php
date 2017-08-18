@extends('pc.common.activity')

@section('title', '投资PK')

@section('keywords', env('META_KEYWORD'))

@section('description', env('META_DESCRIPTION'))

@section('css')
        <meta name="format-detection" content="telephone=yes">

@endsection
@section('content')
        <link rel="stylesheet" type="text/css" href="{{ assetUrlByCdn('/static/activity/investGame/css/fourphase.css') }}">
        <div class="phase-banner">
            <span class="phase-time">{{date('m月d日',$activityTime['start'])}}－－{{date('m月d日',$activityTime['end'])}}</span>
        </div>
        <div class="phase-bg">
@if(!empty($projectList))
            <div class="phase-box">
@foreach($projectList as $key => $project)
                <div class="phase-title phase-title1">
@if($key == 'jax')
                    <span>九</span><span>安</span><span>心</span><i>•</i><span>{{$project['format_invest_time']}}</span><span>天</span>
@else
                    <span>九</span><span>省</span><span>心</span><i>•</i><span>{{isset($lineToNumber[$key]) ?$lineToNumber[$key] :12}}</span><span>月</span><span>期</span>
@endif
           </div>
                <div class="phase-project">
                    <div class="phase-rate clearfix">
                        <div class="phase-text1 fl">
                            <p class="phase-text-margin"><i>借款利率</i><span>{{(float)$project['profit_percentage']}}</span><em>%</em></p>
                        </div>
                        <div class="phase-text1 phase-text2 fr">
                            <p class="phase-text-margin"><i>剩余可投</i><em>{{$project['left_amount']}}元</em></p>
                        </div>
                    </div>
                    <div class="phase-progress">
                        <span style="width: {{round($project['invested_amount']/$project['total_amount']*100)}}%"></span>
                    </div>
                    <div class=" clearfix">
                        <div class="fl"><p class="phase-textcolor">出借进度</p></div>
                        <div class="fr"><p class="phase-textcolor">{{round($project['invested_amount']/$project['total_amount']*100)}}％</p></div>
                    </div>
@if($project['status'] == \App\Http\Dbs\Project\ProjectDb::STATUS_INVESTING && \App\Tools\ToolTime::getUnixTime($project['publish_at'],'default') >= time())
                    <a href="/project/detail/{{$project['id']}}" class="phase-btn">敬请期待</a>
@elseif($project['status'] == \App\Http\Dbs\Project\ProjectDb::STATUS_INVESTING)
                    <a href="/project/detail/{{$project['id']}}" class="phase-btn">立即投资</a>
@else
                    <a href="/project/detail/{{$project['id']}}" class="phase-btn phase-disable">已售罄</a>
@endif
            </div>
@endforeach
            </div>
@endif

            <div class="phase-count-wrap">
@if($activityTime['start'] > time())
                <p class="phase-count-text"><span class="left"></span>活动未开始<span class="right"></span></p>
@elseif($activityTime['end'] < time())
                <p class="phase-count-text"><span class="left"></span>活动已经结束<span class="right"></span></p>
@else
                <p class="phase-count-text"><span class="left"></span>距离今日争夺战结束还有<span class="right"></span></p>
                <p class="phase-count-time clearfix server-spike-time" attr-spike-time="{{ date("Y/m/d H:i:s",time()) }}">
                {!! $lastSecond !!}
                </p>
@endif
            </div>
@if(!empty($nowDay['statistics']))
        <div class="phase-box1">
            @foreach($nowDay['statistics'] as $k => $statistic )
                @if($k =='jax')
                    <div class="phase-title phase-title3 phase-title2">
                        <span>九</span><span>安</span><span>心</span>
                    </div>
                @else
                    <div class="phase-title phase-title3 phase-title2">
                        <span>{{isset($lineToNumber[$k]) ?$lineToNumber[$k] :12}}</span><span>月</span><span>期</span>
                    </div>
                @endif
                <div class="phase-medal-group ">
                    <ul>
                        @if(empty($statistic))
                            <li><i class="icon1">1</i><span>{{date('m月d日',time())}}</span>暂无排名数据</li>
                        @else
                            @foreach($statistic as $n => $item )
                                @if($n ==0)
                                    <li><i class="icon1">1</i><span>{{\App\Tools\ToolStr::hidePhone($nowDay['user'][$item['user_id']]['phone'])}}</span>{{$item['total']}}元</li>
                                @else
                                    <li><i></i><span>{{\App\Tools\ToolStr::hidePhone($nowDay['user'][$item['user_id']]['phone'])}}</span>{{$item['total']}}元</li>
                                @endif
                            @endforeach
                        @endif
                    </ul>

                </div>
            @endforeach
            <p class="phase-data" ><a href="javascript:window.location.reload()">*点我实时刷新数据</a></p>
        </div>
@endif
@if(!empty($lotteryList['lottery']))
            <div class="phase-box2 ">
                <!--三月期中奖-->
                @foreach($lotteryList['lottery'] as $nu =>$lottery)
                    <div class="phase-prize">
                        <img src="{{ assetUrlByCdn('/static/activity/investGame/images/four-prize'.$nu.'-1.png') }}">
                    </div>
                    {{-- <p class="phase-prize-text">{{$lottery['name']}}</p> --}}
                    <div class="phase-record">中奖纪录</div>
                    <div class="phase-scroll">
                @if(!empty($lotteryList['record'][$nu]))
                @foreach($lotteryList['record'][$nu] as $record)
                    <p class="phase-date"><span>{{date('m月d日',strtotime($record['created_at']))}}</span><span>{{\App\Tools\ToolStr::hidePhone($record['phone'])}}</span><i>{{$record['award_name']}}</i></p>
                @endforeach
                @else
                    <p>九省心</p>
                    <p><span>{{date('m月d日',time())}}</span><em></em><i>暂无中奖数据公布!</i></p>
                @endif
                    </div>
                @endforeach
            </div>
@endif
            <!-- 活动规则 -->
            <div class="phase-wrap">
                <div class="phase-rule">
                    <h3 class="phase-tag">活动规则</h3>
                    <p><span>1、</span>本次活动，仅限活动页面展示的优选项目参加；</p>
                    <p><span>2、</span>活动期间内，使用加息券、红包的投资额度不计算在pk数据统计中；</p>
                    <p><span>3、</span>活动期间内，每日各选取活动页面展示的优选项目，累计投资金额的前三名，获得对应的实物奖品；以用户投资优选项目的累积金额进行排序，当用户累积投资金额出现并列时，则按照用户投资最后一笔时间的先后，择先选取；</p>
                    <p><span>4、</span>参与领取奖品者，活动期间提现金额≥10000元，取消其领奖资格；</p>
                    <p><span>5、</span>活动所得奖品以实物形式发放，将在2017年5月30日之前，与您沟通联系确定发放奖品；</p>
                </div>
            </div>
        </div>
        <script  type="text/javascript" src="{{ assetUrlByCdn('/static/activity/investGame/js/countDown.js') }}"></script>
        <script type="text/javascript">
            $('.phase-box').find('.phase-title:eq(0)').removeClass('phase-title1');
            $('.phase-box2').find('.phase-prize:eq(0)').removeClass('phase-prize1');
            var status      =   '{{ $activityStatus }}';
            //活动倒计时
            if(status == 3){
                setInterval(function(){
                    countDown.getRTime('server-spike-time','{{ date("Y/m/d H:i:s",$lastTime) }}')
                },1000);
            }
        </script>

@endsection



