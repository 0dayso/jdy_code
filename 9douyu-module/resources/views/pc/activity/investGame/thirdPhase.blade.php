@extends('pc.common.layout')

@section('title', '看得见的安心，才能fun肆投')

@section('keywords', env('META_KEYWORD'))

@section('description', env('META_DESCRIPTION'))

@section('csspage')
        <meta name="format-detection" content="telephone=yes">
        <link rel="stylesheet" type="text/css" href="{{ assetUrlByCdn('/static/activity/investGame/css/pk0706.css') }}">

@endsection
@section('content')

        <div class="phase-banner">
            <input type="hidden" name="_token"  value="{{csrf_token()}}">
            <div class="wrap"><span class="phase-time">{{date('m.d',$activityTime['start'])}}-{{date('m.d',$activityTime['end'])}}</span></div>
        </div>

        <div class="wrap">
            <div class="phase-title">PK项目</div>
            <div class="phase-wrap clearfix">
               <!--  <div class="phase-chip1"></div> -->
                <!-- <div class="phase-chip2"></div> -->
                <div class="phase-project-wrap clearfix">
@if( $activityStatus ==\App\Http\Logics\Activity\InvestGameLogic::ACTIVITY_PADDING )
@if(!empty($projectList))
            @foreach($projectList as $key=> $project)
                    <div class="phase-project">
                        <h4 class="phase-project-title">{{$project['product_line_note']}} • {{$project['invest_time_note']}}</h4>
                        <div class="phase-rate clearfix">
                            <div class="phase-text1 fl">
                                <p class="phase-text-margin red"><span>{{(float)$project['profit_percentage']}}</span><em>%</em></p>
                                <p class="phase-textcolor">借款利率</p>
                            </div>
                            <div class="phase-text2 fr">
                                <p class="phase-text-margin"><span>{{$project['left_amount']}}元</span></p>
                                <p class="phase-textcolor">剩余可投</p>
                            </div>
                        </div>
                        <div class="phase-progress">
                            <span style="width: {{number_format($project['invested_amount']/$project['total_amount']*100,2)}}%"></span>
                        </div>
                        <div class=" clearfix">
                            <div class="fl"><p class="phase-textcolor">投资进度</p></div>
                            <div class="fr"><p class="phase-textcolor">{{number_format($project['invested_amount']/$project['total_amount']*100,2)}}％</p></div>
                        </div>
@if($project['status'] == \App\Http\Dbs\Project\ProjectDb::STATUS_INVESTING && \App\Tools\ToolTime::getUnixTime($project['publish_at'],'default') >= time())
                            <a href="javascript:;" class="phase-btn investClick" attr-data-id='{{$project['id']}}'>敬请期待</a>
@elseif($project['status'] == \App\Http\Dbs\Project\ProjectDb::STATUS_INVESTING)
                            <a href="javascript:;"  class="phase-btn investClick" attr-data-id='{{$project['id']}}'>立即出借</a>
@else
                            <a href="javascript:;" class="phase-btn phase-disable investClick" attr-data-id='{{$project['id']}}'>{{$project['status_note']}}</a>
@endif
               </div>
                    @endforeach
                            @endif
@endif
                </div>
            </div>
        </div>
        <div class="phase-count-wrap">
@if( $activityStatus ==\App\Http\Logics\Activity\InvestGameLogic::ACTIVITY_NOT_OPEN)
                 <p class="phase-count-text"><span class="left"></span>活动未开始<span class="right"></span></p>
@elseif($activityStatus ==\App\Http\Logics\Activity\InvestGameLogic::ACTIVITY_IS_OVER)
                <p class="phase-count-text"><span class="left"></span>活动已经结束<span class="right"></span></p>
@else
                <p class="phase-count-text"><span class="left"></span>距离今日争夺战结束还有<span class="right"></span></p>
                <p class="phase-count-time clearfix server-spike-time" attr-spike-time="{{ date("Y/m/d H:i:s",time()) }}">
                    <!--span>1</span><span>2</span><em>时</em>
                    <span>0</span><span>0</span><em>分</em>
                    <span>0</span><span>0</span><em>秒</em-->
                {!! $lastSecond !!}
                </p>
@endif
   </div>
        <div class="phase-wrap">
        <div class="phase-title phase-title2">PK排名</div>
            <div class="phase-medal-wrap clearfix">
@if($nowDay['statistics'] && $activityStatus!=\App\Http\Logics\Activity\InvestGameLogic::ACTIVITY_NOT_OPEN && !empty($nowDay['user']) )
@foreach( $nowDay['statistics'] as $key => $statistic)
                    <div class="phase-medal-group ">
@if( empty($statistic))
<p>暂无投资排名数据</p>
@else
                        <ul>
 @foreach($statistic as $id  => $info )
                            @if( $id < 5 )
                            <li class="list{{$id}}">
                            <span>{{\App\Tools\ToolStr::hidePhone($nowDay['user'][$info['user_id']]['phone'])}}</span><span> {{ \App\Tools\ToolMoney::moneyFormat(floor($info['total']))}}元</span>
                            </li>
                            @endif
 @endforeach
                        </ul>
@endif
                    </div>
@endforeach
@else
                <p class="phase-data" >暂无投资排名数据</p>
@endif
       </div>
            {{--<p class="phase-data" ><a href="javascript:window.location.reload()">点我更新最新投资数据</a></p>--}}

        </div>


        <div class="phase-wrap clearfix">
                <div class="phase-gift">
                    <ul>
                    <li>
                        <p><img src="{{ assetUrlByCdn('/static/activity/investGame/pk0706/img1.png') }}" width="180" height="180"></p>
                        <p><big>第一名</big></p>
                        <p>小米平板3 WIFI版 7.9英寸</p>
                    </li>
                    <li>
                        <p><img src="{{ assetUrlByCdn('/static/activity/investGame/pk0706/img2.png') }}" width="180" height="180"></p>
                        <p><big>第二名</big></p>
                        <p>小米净化器2</p>
                    </li>
                    <li>
                        <p><img src="{{ assetUrlByCdn('/static/activity/investGame/pk0706/img3.png') }}" width="180" height="180"></p>
                        <p><big>第三名</big></p>
                        <p>水星家纺 四件</p>
                    </li>
                    </ul>
                </div>
                <div class="phase-record">
                    <div class="phase-scroll">


                        @if(!empty($everyDay))
                            @foreach( $everyDay as $key=> $every)
                                <p class="phase-date">{{date("m月d日",strtotime($key))}}</p>
                                @foreach( $every['statistics'] as $k => $statistics )
                                    @if( empty($statistics) )
                                        <p class="phase-data-main"><span></span><em>暂无中奖数据</em><i></i></p>
                                        <div class="clear"></div>
                                    @else
                                    <div class="phase-data-main">
                                        @foreach( $statistics as $num => $info)
                                            @if(isset($every['user'][$info['user_id']]['phone']) && !empty($every['user'][$info['user_id']]['phone']))
                                                <p>
                                                    <span>{{\App\Tools\ToolStr::hidePhone($every['user'][$info['user_id']]['phone'])}}</span>
                                                    <em>{{$info['total']}}元</em>
                                                    <i title="{{$lotteryList['lottery'][$num+1]['name']}}">{{$lotteryList['lottery'][$num+1]['name']}}</i>
                                                </p>

                                            @endif
                                        @endforeach
                                        </div>
                                        <div class="clear"></div>
                                    @endif
                                @endforeach
                            @endforeach
                        @else
                            <div class="phase-data-main"><span></span><em>暂无中奖数据</em><i></i></div>
                            <div class="clear"></div>
                        @endif

                </div>
            </div>
        </div>

        <div class="phase-rule">
                <div class="wrap">
                        <h3>活动规则</h3>
                        <p><span>1、</span>本次投资PK活动仅限投资六月期项目。</p>
                        <p><span>2、</span>活动期间内，每日选取六月期累计投资金额前三名，获得对应奖品；以用户累计投资六月期项目金额进行排序，用户投资金额出现并列时，则按照用户投资时间的先后进行名次排序。</p>
                        <p><span>3、</span>参与领取奖品者，活动期间提现金额≥10000元、投资项目进行债转，取消其领奖资格。</p>
                        <p><span>4、</span>活动所得奖品以实物形式发放，将在2017年8月30日之前，与您沟通联系确定发放奖品。</p>
                        <p><span>5、</span>活动期间如有任何疑问请致电九斗鱼官方客服：400-6686-568，或登录九斗鱼咨询在线客服。</p>
                        <p><span>6、</span>本活动最终解释权归九斗鱼所有。</p>
                </div>
        </div>
        <script type="text/javascript" src="{{ assetUrlByCdn('/static/activity/investGame/js/countDown.js') }}"></script>
        <script type="text/javascript">
            $(function(){
                var status      =   '{{ $activityStatus }}';
                //活动倒计时
                if(status == 3){
                    setInterval(function(){countDown.getRTime('server-spike-time',"{{ date("Y/m/d H:i:s",$lastTime) }}")},1000);
                }
            })
            $(document).delegate(".investClick",'click',function () {
                var  projectId  =   $(this).attr("attr-data-id");
                if( !projectId ||projectId==0){
                    return false;
                }
                var act_token   =   '{{$actToken}}_' + projectId;
                var _token      =   $("input[name='_token']").val();
                $.ajax({
                    url      :"/activity/setActToken",
                    data     :{act_token:act_token,_token:_token},
                    dataType :'json',
                    type     :'post',
                    success : function() {

                        window.location.href='/project/detail/' + projectId;
                    }, error : function() {
                        window.location.href='/project/detail/' + projectId;
                    }
                });

            })
        </script>
        <!-- 活动开始结束状态 -->
        @if( $activityTime['start'] > time())
            @include('pc.common.activityStart')
        @endif
        <!-- End 活动开始结束状态 -->
        @if($activityTime['end'] < time())
            @include('pc.common.activityEnd')
        @endif

@endsection


