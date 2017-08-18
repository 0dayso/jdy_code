@extends('pc.common.layout')

@section('title', '加息2%，金秋加息火速开抢')

@section('keywords', env('META_KEYWORD'))

@section('description', env('META_DESCRIPTION'))

@section('css')
    <meta name="format-detection" content="telephone=yes">

@endsection

@section('content')
    <link rel="stylesheet" type="text/css" href="{{ assetUrlByCdn('/static/activity/spike/css/spike2.css') }}">
    <!-- banner -->
    <div class="seckill-banner"><p>{{ date("m月d日",$startTime) }}－{{ date("m月d日",$endTime) }}</p> </div>
    <div class="wrap server-spike-time"  attr-spike-time="{{ date("Y/m/d H:i:s",time()) }}">
        <!-- 高息区 -->
    @foreach($spikeProject['high'] as $highKey=>$highProject)
        @if( !empty($highProject) )
        <div class="seckill-4">
            <a href="javascript:;" onclick="_czc.push(['_trackEvent','Wap秒杀活动','{{ $highProject['invest_time_note']}}']);window.location.href='{:U(sprintf(\'/project/%s\',$highProject[\'id\']))}'">
                <div class="seckill-title seckill-title1">九安心</div>
                <div class="seckill-info">活动期间，每日 {{ $spikeTime }}准时开启加息秒杀专场！</div>
                <div class="seckill-4-data">
                    <table>
                        <tr>
                            <td width="200">
                                <p>
                                    <big>{{ (float)$highProject['base_rate'] }}</big><span class="per">%</span><span class="add">+</span><big>{{ (float)$highProject['after_rate'] }}</big><span class="per">%</span>
                                </p>
                                <p class="nhs"><small>借款利率</small></p>
                                <i></i>
                            </td>
                            <td width="160">
                                <p>{{$highProject['format_invest_time']}} {{$highProject['invest_time_unit']}}</p>
                                <p><small>项目期限</small></p>
                                <i></i>
                            </td>
                            <td width="170">
                                <p>{{number_format($highProject['left_amount'])}}元</p>
                                <p><small>剩余可投</small></p>
                                <i></i>
                            </td>
                            <td>

                                @if($spikeStatus == \App\Http\Logics\Activity\SpikeLogic::ACTIVITY_ENDED || ($highProject['status'] != \App\Http\Dbs\Project\ProjectDb::STATUS_INVESTING && $isLastTime == true))
                                    <span class="seckill-btn btn1 disable">已结束</span>
                                @elseif($highProject['left_amount'] == 0 && $highProject['status'] == \App\Http\Dbs\Project\ProjectDb::STATUS_INVESTING )
                                    <span class="seckill-btn btn1 disable">已售罄</span>
                                @elseif($highProject['left_amount'] == 0 || $highProject['status'] != \App\Http\Dbs\Project\ProjectDb::STATUS_INVESTING )
                                <!-- 项目其它状态 -->
                                    <span class="seckill-btn btn1 disable">{{ $project['status_note'] }}</span>
                                @elseif($highProject['publish_at'] > \App\Tools\ToolTime::dbNow() && $highProject['status'] == \App\Http\Dbs\Project\ProjectDb::STATUS_INVESTING)
                                    <span class="seckill-btn btn1 disable">敬请期待</span>
                                @elseif ( $highProject['status'] == \App\Http\Dbs\Project\ProjectDb::STATUS_INVESTING )
                                <!-- 可投资状态 -->
                                    <span onclick="window.location.href='/project/detail/{{ $highProject['id'] }}'" class="seckill-btn btn1">立即秒杀</span>
                                @else
                                <!-- 项目其它状态 -->
                                    <span class="seckill-btn btn1 disable" >{{ $highProject['status_note'] }}</span>
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
            </a>
            <!-- 倒计时遮罩 -->

            @if($spikeStatus == \App\Http\Logics\Activity\SpikeLogic::ACTIVITY_NOSTART)
                <div class="seckill-4-mask">
                    <i></i>
                    <p>距离开始秒杀还有<br><big class="timelast"></big></p>
                </div>
            @elseif($spikeStatus == \App\Http\Logics\Activity\SpikeLogic::ACTIVITY_ONGOING)

                @if($highProject['publish_at'] > \App\Tools\ToolTime::dbNow() && $highProject['status'] == \App\Http\Dbs\Project\ProjectDb::STATUS_INVESTING )
                    <div class="seckill-4-mask">
                        <i></i>
                        <p>距离开始秒杀还有<br><big class="timelast"></big></p>
                    </div>
                @elseif( $highProject['left_amount'] == 0 && $isLastTime == false)
                    <div class="seckill-4-mask">
                        <i></i>
                        <p>距离下一场秒杀还有<br><big class="timelast"></big></p>
                    </div>
                @else

                @endif

            @else

            @endif

        </div>

        @else
            @include('pc.common.activity.spikehigh')
        @endif
        @endforeach
        @foreach($spikeProject['low'] as $lowKey=>$lowProject)
            @if( !empty($lowProject) )
                <div class="seckill-2">
                    <a href="javascript:;" onclick="_czc.push(['_trackEvent','Wap秒杀活动','{{ $lowProject['invest_time_note']}}']);window.location.href='/project/detail/{{ $lowProject['id'] }}'">
                        <div class="seckill-title seckill-title1">{{$lowProject['name']}}</div>
                        <div class="seckill-info">活动期间，每日 {{ $spikeTime }}准时开启加息秒杀专场！</div>
                        <div class="seckill-4-data">
                            <table>
                                <tr>
                                    <td width="200">
                                        <p>
                                            <big>{{ (float)$lowProject['base_rate'] }}</big><span class="per">%</span><span class="add">+</span><big>{{ (float)$lowProject['after_rate'] }}</big><span class="per">%</span>
                                        </p>
                                        <p class="nhs"><small>借款利率</small></p>
                                        <i></i>
                                    </td>
                                    <td width="160">
                                        <p>{{$lowProject['format_invest_time']}} {{$lowProject['invest_time_unit']}}</p>
                                        <p><small>项目期限</small></p>
                                        <i></i>
                                    </td>
                                    <td width="170">
                                        <p>{{number_format($lowProject['left_amount'])}}元</p>
                                        <p><small>剩余可投</small></p>
                                        <i></i>
                                    </td>
                                    <td>

                                        @if($spikeStatus == \App\Http\Logics\Activity\SpikeLogic::ACTIVITY_ENDED || ($lowProject['status'] != \App\Http\Dbs\Project\ProjectDb::STATUS_INVESTING && $isLastTime == true))
                                            <span class="seckill-btn btn1 disable">已结束</span>
                                        @elseif($lowProject['left_amount'] == 0 && $lowProject['status'] == \App\Http\Dbs\Project\ProjectDb::STATUS_INVESTING )
                                            <span class="seckill-btn btn1 disable">已售罄</span>
                                        @elseif($lowProject['left_amount'] == 0 || $lowProject['status'] != \App\Http\Dbs\Project\ProjectDb::STATUS_INVESTING )
                                        <!-- 项目其它状态 -->
                                            <span class="seckill-btn btn1 disable">{{ $lowProject['status_note'] }}</span>
                                        @elseif($lowProject['publish_at'] > \App\Tools\ToolTime::dbNow() && $lowProject['status'] == \App\Http\Dbs\Project\ProjectDb::STATUS_INVESTING)
                                            <span class="seckill-btn btn1 disable">敬请期待</span>
                                        @elseif ( $lowProject['status'] == \App\Http\Dbs\Project\ProjectDb::STATUS_INVESTING )
                                        <!-- 可投资状态 -->
                                            <span class="seckill-btn btn1" onclick="window.location.href='/project/detail/{{ $lowProject['id'] }}'">立即秒杀</span>
                                        @else
                                        <!-- 项目其它状态 -->
                                            <span class="seckill-btn btn1 disable" >{{ $lowProject['status_note'] }}</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </a>
                    <!-- 倒计时遮罩 -->

                    @if($spikeStatus == \App\Http\Logics\Activity\SpikeLogic::ACTIVITY_NOSTART)
                        <div class="seckill-4-mask">
                            <i></i>
                            <p>距离开始秒杀还有<br><big class="timelast"></big></p>
                        </div>
                    @elseif($spikeStatus == \App\Http\Logics\Activity\SpikeLogic::ACTIVITY_ONGOING)

                        @if($lowProject['publish_at'] > \App\Tools\ToolTime::dbNow() && $lowProject['status'] == \App\Http\Dbs\Project\ProjectDb::STATUS_INVESTING )
                            <div class="seckill-4-mask">
                                <i></i>
                                <p>距离开始秒杀还有<br><big class="timelast"></big></p>
                            </div>
                        @elseif( $lowProject['left_amount'] == 0 && $isLastTime == false)
                            <div class="seckill-4-mask">
                                <i></i>
                                <p>距离下一场秒杀还有<br><big class="timelast"></big></p>
                            </div>
                        @else

                        @endif

                    @else

                    @endif

                </div>

            @else
                @include('pc.common.activity.spikelow')
            @endif
        @endforeach


        <div class="clear"></div>
        <div class="seckill-rule">
            <div class="seckill-title seckill-title4">温馨提示</div>
            <p><span>1、</span>活动时间：{{ date("Y年m月d日",$startTime) }}至{{ date("Y年m月d日",$endTime) }}</p>
            <p><span>2、</span>加息秒杀专区每天{{ $spikeTime }}准时开抢，100元起投，投资无上限，先到先得，投满为止；</p>
            <p><span>3、</span>投资加息秒杀专区项目不能使用加息券和红包；</p>
            <p><span>4、</span>活动期间内如有任何疑问请致电九斗鱼官方客服：400-6686-568，或登录九斗鱼咨询在线客服。</p>
        </div>
        </div>

    <script type="text/javascript">
        $(function(){
            var status      =   '{{ $spikeStatus }}';
            //活动倒计时
            getRTime = function (){
                var serverTime  =   $(".server-spike-time").attr("attr-spike-time");
                var EndTime     =   new Date('{{ date("Y/m/d H:i:s",$nextSpikeTime) }}');
                var NowTime     =   new Date(serverTime);
                var t =EndTime.getTime() - NowTime.getTime();
                var d=0;
                var h=0;
                var m=0;
                var s=0;
                var timeStr = '';
                if(t>=0){
                    d=Math.floor(t/1000/60/60/24);
                    h=Math.floor(t/1000/60/60%24);
                    m=Math.floor(t/1000/60%60);
                    s=Math.floor(t/1000%60);
                }else{
                    if(status != 3) {
                        location.reload();
                    }
                }
                if( d > 0 ){
                    timeStr = d + ": ";
                }
                if( (d > 0) || (h > 0) ){
                    timeStr += pad(h, 2) + ": ";
                }
                if( (d > 0 || h > 0) || (m > 0) ){
                    timeStr += pad(m, 2) + ": ";
                }
                timeStr += pad(s, 2) + "";

                $(".timelast").html(timeStr);
                getNextTime();
            }
            if(status != 3){
                setInterval(getRTime,1000);
            }

            function pad(num, n) {
                var numLength = num.toString().length;
                return Array(n>numLength?(n-(''+num).length+1):0).join(0)+num;
            }

            function getNextTime(){
                var serverTime  =   $(".server-spike-time").attr("attr-spike-time");
                var NowTime = new Date(serverTime);
                var t1 = NowTime.getTime();
                t1 +=1000;
                var NowTime1 = new Date(t1);
                var y1 = NowTime1.getFullYear();
                var m1 = NowTime1.getMonth()+1;
                var d1 = NowTime1.getDate();
                var h1 = NowTime1.getHours();
                var i1 = NowTime1.getMinutes();
                var s1 = NowTime1.getSeconds();
                $(".server-spike-time").attr("attr-spike-time",y1+"/"+m1+"/"+d1+" "+h1+":"+i1+":"+s1);
            }
        })
    </script>
@endsection
