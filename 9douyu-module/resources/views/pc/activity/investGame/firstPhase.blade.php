@extends('pc.common.layout')

@section('title', '新春伊始 如鱼得水')

@section('keywords', env('META_KEYWORD'))

@section('description', env('META_DESCRIPTION'))

@section('css')
        <meta name="format-detection" content="telephone=yes">

@endsection
@section('content')
        <link rel="stylesheet" type="text/css" href="{{ assetUrlByCdn('/static/activity/investGame/css/firstphase.v1.css') }}">

        <div class="phase-banner">
            <span class="phase-time">{{date("m.d",$activityTime['start'])}}-{{date("m.d",$activityTime['end'])}}</span>
        </div>
        @if($activityStatus ==\App\Http\Logics\Activity\InvestGameLogic::ACTIVITY_PADDING)
        <div class="phase-project-area">
            <div class="phase-title">PK项目</div>
            <div class="phase-wrap clearfix">
               <!--  <div class="phase-chip1"></div> -->
                <!-- <div class="phase-chip2"></div> -->
                <div class="phase-project-wrap clearfix">
                        {{--3月期--}}
                    @if(!empty($projectList))
                            @foreach($projectList as $key=> $project)
                    <div class="phase-project">
                        <h4 class="phase-project-title">{{$project['product_line_note']}} • {{$project['invest_time_note']}}</h4>
                        <div class="phase-rate clearfix">
                            <div class="phase-text1 fl">
                                <p class="phase-text-margin"><span>{{(float)$project['profit_percentage']}}</span><em>%</em></p>
                                <p class="phase-textcolor">借款利率</p>
                            </div>
                            <div class="phase-text2 fr">
                                <p class="phase-text-margin"><span>{{$project['left_amount']}}元</span></p>
                                <p class="phase-textcolor">剩余可投</p>
                            </div>
                        </div>
                        <div class="phase-progress">
                            <span style="width: {{floor($project['invested_amount']/$project['total_amount']*100)}}%"></span>
                        </div>
                        <div class=" clearfix">
                            <div class="fl"><p class="phase-textcolor">投资进度</p></div>
                            <div class="fr"><p class="phase-textcolor">{{ floor($project['invested_amount']/$project['total_amount']*100)}}％</p></div>
                        </div>
                        @if($project['status'] == \App\Http\Dbs\Project\ProjectDb::STATUS_INVESTING && \App\Tools\ToolTime::getUnixTime($project['publish_at'],'default') >= time())
                            <a href="/project/detail/{{$project['id']}}" class="phase-btn">敬请期待</a>
                        @elseif($project['status'] == \App\Http\Dbs\Project\ProjectDb::STATUS_INVESTING)
                            <a href="/project/detail/{{$project['id']}}" class="phase-btn">立即出借</a>
                        @else
                            <a href="/project/detail/{{$project['id']}}" class="phase-btn phase-disable">{{$project['status_note']}}</a>
                        @endif
                    </div>
                    @endforeach
                    @endif
                </div>
            </div>
        </div>
        @endif
        <div class="phase-count-wrap">
            @if( $activityStatus ==\App\Http\Logics\Activity\InvestGameLogic::ACTIVITY_NOT_OPEN)
                <p class="phase-count-text"><span class="left"></span>活动未开始<span class="right"></span></p>
            @elseif($activityStatus ==\App\Http\Logics\Activity\InvestGameLogic::ACTIVITY_IS_OVER)
                <p class="phase-count-text"><span class="left"></span>活动已经结束<span class="right"></span></p>
            @else
                <p class="phase-count-text"><span class="left"></span>距离今日争夺战结束还有<span class="right"></span></p>
                <p class="phase-count-time clearfix server-spike-time" attr-spike-time="{{ date("Y/m/d H:i:s",time()) }}">
                    {!! $lastSecond !!}
                </p>
            @endif
        </div>
        <div class="phase-title phase-title2">PK排名</div>
        <div class="phase-wrap">
            <div class="phase-medal-wrap clearfix">
            @if($nowDay['statistics'] && $activityStatus!=\App\Http\Logics\Activity\InvestGameLogic::ACTIVITY_NOT_OPEN && !empty($nowDay['user']) )
                @foreach( $nowDay['statistics'] as $key => $statistic)
                    <div class="phase-medal-group fl">
                    @if($key =='three')
                    <p>3月期PK排名</p>
                    @elseif($key =='six')
                        <p>6月期PK排名</p>
                    @elseif($key =='one')
                        <p>1月期PK排名</p>
                    @elseif($key =='twelve')
                        <p>12月期PK排名</p>
                    @elseif($key =='jax')
                        <p>九安心PK排名</p>
                    @else
                        <p>九省心PK排名</p>
                    @endif
                    @if( empty($statistic))
                        <p>暂无投资排名数据</p>
                    @else
                        <ul>
                            @foreach($statistic as $id  => $info )
                            @if( $id < 3 )
                                    <li class="list{{$id}}">
                                        <span>{{\App\Tools\ToolStr::hidePhone($nowDay['user'][$info['user_id']]['phone'])}}</span>
                                        {{ \App\Tools\ToolMoney::moneyFormat(floor($info['total']))}}元
                                    </li>
                            @endif
                            {{--@if($id ==3)
                            <li class="list{{$id+1}}">
                                <em>第四名</em>
                                <span>{{\App\Tools\ToolStr::hidePhone($nowDay['user'][$info['user_id']]['phone'])}}</span>
                                {{ \App\Tools\ToolMoney::moneyFormat(floor($info['total']))}}元
                            </li>
                            @elseif($id ==4)
                            <li class="list{{$id+1}}">
                                <em>第五名</em>
                                <span>{{\App\Tools\ToolStr::hidePhone($nowDay['user'][$info['user_id']]['phone'])}}</span>
                                {{ \App\Tools\ToolMoney::moneyFormat(floor($info['total']))}}元
                            </li>
                            @elseif($id ==5)
                                <li class="list{{$id+1}}">
                                    <em>第六名</em>
                                    <span>{{\App\Tools\ToolStr::hidePhone($nowDay['user'][$info['user_id']]['phone'])}}</span>
                                    {{ \App\Tools\ToolMoney::moneyFormat(floor($info['total']))}}元
                                </li>
                            @else
                            <li class="list{{$id}}">
                                <span>{{\App\Tools\ToolStr::hidePhone($nowDay['user'][$info['user_id']]['phone'])}}</span>
                                {{ \App\Tools\ToolMoney::moneyFormat(floor($info['total']))}}元
                            </li>
                            @endif--}}
                            @endforeach
                        </ul>
                    @endif
                    </div>
                @endforeach
            @else
                <p class="phase-data" >暂无投资排名数据</p>
            @endif
            </div>

            <p class="phase-data" ><a href="javascript:window.location.reload()">点我更新最新投资数据</a></p>
        </div>
        <div class="phase-title phase-title1">礼品展示</div>

        <div class="phase-wrap clearfix">
                <div class="phase-gift fl">
                    <?php $i =1;?>
                    @foreach($projectList as $key=> $project)
                        <?php if($i==1){
                                $order = 3;
                            }else{
                                $order = 6;
                            } ?>
                        <h3 class="phase-gift-title{{$order}} ">
                            @if($key=='jax')
                                九安心
                            @else
                                {{$project['invest_time_note']}}
                            @endif
                                奖品
                        </h3>
                        <?php $i++;?>
                    @endforeach

                 {{--<h3 class="phase-gift-title6">6月期奖品</h3>--}}
                </div>
                <div class="phase-record fr">
                    <h4 class="phase-tag">中奖记录</h4>
                    <div class="phase-scroll">
            @if(!empty($everyDay))
                @foreach( $everyDay as $key=> $every)
                        <p class="phase-date">{{date("m月d日",strtotime($key))}}</p>
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
                        @if( empty($statistics) )
                        <p><span></span><em>暂无中奖数据</em><i></i></p>
                        @else
                        @foreach( $statistics as $num => $info)
                        @if(isset($every['user'][$info['user_id']]['phone']) && !empty($every['user'][$info['user_id']]['phone']))
                        <p>
                            <span>{{\App\Tools\ToolStr::hidePhone($every['user'][$info['user_id']]['phone'])}}</span>
                            <em>{{$info['total']}}元</em>
                            <i title="{{$lotteryList[$k][$num+1]}}">{{$lotteryList[$k][$num+1]}}</i>
                        </p>
                        @endif
                        @endforeach
                        @endif
                    @endforeach
                @endforeach
            @else
                    <p><span></span><em>暂无中奖数据</em><i></i></p>
            @endif

                </div>
            </div>
        </div>

        <div class="phase-wrap">
                <div class="phase-rule">
                        <h3 class="phase-tag">活动规则</h3>
                        <p><span>1、</span>本次投资pk活动仅限活动页面展示的项目参与。</p>
                        <p><span>2、</span>活动期间，每日各选取活动页面投资项目的前三名，获得对应奖品，以用户投资pk的累积金额进行排序；用户出借金额出现并列式，按照用户最后一笔投资时间，择先选取。</p>
                        <p><span>3、</span>参与领取奖品者，活动期间提现金额≥10000元，取消其领奖资格。</p>
                        <p><span>4、</span>活动所得奖品以实物形式发放，将在2017年3月15日之前，与您沟通联系确定发放奖品。</p>
                        <p><span>5、</span>活动期间如有任何疑问请致电九斗鱼官方客服：400-6686-568，或登录九斗鱼咨询在线客服。</p>
                </div>
        </div>
        <script type="text/javascript">
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

                        window.location.reload();
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
        <!-- 活动开始结束状态 -->
        @if( $activityTime['start'] > time())
            @include('pc.common.activityStart')
        @endif
        <!-- End 活动开始结束状态 -->
        @if($activityTime['end'] < time())
            @include('pc.common.activityEnd')
        @endif

@endsection


