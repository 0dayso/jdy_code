@extends('wap.common.wapBase')

@section('title', '加息2%准点限时秒杀')

@section('keywords', env('META_KEYWORD'))

@section('description', env('META_DESCRIPTION'))

@section('css')
    <meta name="format-detection" content="telephone=yes">
    <link rel="stylesheet" type="text/css" href="{{ assetUrlByCdn('/static/weixin/activity/spike/css/spike2.css') }}">
@endsection

@section('content')
<article>
    <div class="kill-banner">
        <p>{{ date("m月d日",$startTime) }}－{{ date("m月d日",$endTime) }}</p>
    </div>
    <section class="kill-1 server-spike-time" attr-spike-time="{{ date("Y/m/d H:i:s",time()) }}">
        <div class="kill-title">活动期间，每日 {{ $spikeTime }} 准时开启加息秒杀专场！</div>
        @foreach($spikeProject['high'] as $highKey=>$highProject)
            @if( !empty($highProject) )
            <a href="javascript:;" onclick="_czc.push(['_trackEvent','Wap秒杀活动','{{ $highProject['invest_time_note']}}']);" >
                <div class="kill-content kill-yellow">
                    <h4 class="kill-2">{{$highProject['name']}}</h4>
                    <table class="kill-data">
                        <tr>
                            <td>
                                <p>{{ (float)$highProject['base_rate'] }}<span>%</span>+{{ (float)$highProject['after_rate']}}<span>%</span></p>
                                <p>借款利率</p>
                            </td>
                            <td>
                                <div class="kill-line"></div>
                                <p>{{number_format($highProject['left_amount'])}}元</p>
                                <p>剩余可投</p>
                            </td>
                            <td>
                                @if($spikeStatus == \App\Http\Logics\Activity\SpikeLogic::ACTIVITY_ENDED || ($highProject['status'] != \App\Http\Dbs\Project\ProjectDb::STATUS_INVESTING && $isLastTime == true))
                                    <a href="javascript:;" class="kill-btn disable">已结束</a>
                                @elseif($highProject['left_amount'] == 0 && $highProject['status'] == \App\Http\Dbs\Project\ProjectDb::STATUS_INVESTING )
                                        <a href="javascript:" class="kill-btn disable">已售罄</a>
                                @elseif($highProject['left_amount'] == 0 || $highProject['status'] != \App\Http\Dbs\Project\ProjectDb::STATUS_INVESTING )
                                <!-- 项目其它状态 -->
                                    <a href="javascript:" class="kill-btn disable">{{ $project['status_note'] }}</a>
                                @elseif($highProject['publish_at'] > \App\Tools\ToolTime::dbNow() && $highProject['status'] == \App\Http\Dbs\Project\ProjectDb::STATUS_INVESTING)
                                    <a href="javascript:" class="kill-btn disable">敬请期待</a>
                                @elseif ( $highProject['status'] == \App\Http\Dbs\Project\ProjectDb::STATUS_INVESTING )
                                <!-- 可投资状态 -->
                                    <a href="javascript:;" class="kill-btn userInvestProject" attr-data="{{$highProject['id']}}">立即秒杀</a>
                                @else
                                <!-- 项目其它状态 -->
                                    <a href="javascript:" class="kill-btn disable">{{ $highProject['status_note'] }}</a>
                                @endif
                            </td>
                        </tr>
                    </table>

                    <!--蒙版层-->
                    @if($spikeStatus == \App\Http\Logics\Activity\SpikeLogic::ACTIVITY_NOSTART)
                        <div class="kill-data-mask">
                            <p>距离开始秒杀还有</p>
                            <p class="kill-time"></p>
                        </div>
                    @elseif($spikeStatus == \App\Http\Logics\Activity\SpikeLogic::ACTIVITY_ONGOING)

                        @if($highProject['publish_at'] > \App\Tools\ToolTime::dbNow() && $highProject['status'] == \App\Http\Dbs\Project\ProjectDb::STATUS_INVESTING )
                        <div class="kill-data-mask">
                            <p>距离开始秒杀还有</p>
                            <p class="kill-time"></p>
                        </div>
                         @elseif( $highProject['left_amount'] == 0 && $isLastTime == false)
                            <div class="kill-data-mask">
                                <p>距离下一场秒杀还有</p>
                                <p class="kill-time"></p>
                            </div>
                        @else

                        @endif

                    @else

                    @endif

                </div>
            </a>
            @else
                @include('wap.activity.spike.common.defaulthigh')
            @endif
        @endforeach

        <!--低息区的奖励-->
        @foreach($spikeProject['low'] as $key=>$lowProject)
            @if( !empty($lowProject) )
            <a href="javascript:;" onclick="_czc.push(['_trackEvent','Wap秒杀活动','{{ $lowProject['invest_time_note']}}']);" >
                <div class="kill-content ">
                    <h4 class="kill-2">{{$lowProject['name']}}</h4>
                    <table class="kill-data">
                        <tr>
                            <td>
                                <p>{{ (float)$lowProject['base_rate'] }}<span>%</span>+{{ (float)$lowProject['after_rate']}}<span>%</span></p>
                                <p>借款利率</p>
                            </td>
                            <td>
                                <div class="kill-line"></div>
                                <p>{{number_format($lowProject['left_amount'])}}元</p>
                                <p>剩余可投</p>
                            </td>
                            <td>
                                @if($spikeStatus == \App\Http\Logics\Activity\SpikeLogic::ACTIVITY_ENDED || ($lowProject['status'] != \App\Http\Dbs\Project\ProjectDb::STATUS_INVESTING && $isLastTime == true))

                                    <a href="javascript:;" class="kill-btn disable">已结束</a>
                                @elseif($lowProject['left_amount'] == 0 && $lowProject['status'] == \App\Http\Dbs\Project\ProjectDb::STATUS_INVESTING )

                                    <a href="javascript:" class="kill-btn disable">已售罄</a>
                                @elseif($lowProject['left_amount'] == 0 || $lowProject['status'] != \App\Http\Dbs\Project\ProjectDb::STATUS_INVESTING )
                                <!-- 项目其它状态 -->
                                    <a href="javascript:" class="kill-btn disable">{{ $project['status_note'] }}</a>
                                @elseif($lowProject['publish_at'] > \App\Tools\ToolTime::dbNow() && $lowProject['status'] == \App\Http\Dbs\Project\ProjectDb::STATUS_INVESTING)

                                    <a href="javascript:" class="kill-btn disable">敬请期待</a>
                                @elseif ( $lowProject['status'] == \App\Http\Dbs\Project\ProjectDb::STATUS_INVESTING )
                                <!-- 可投资状态 -->
                                    <a href="javascript:;" class="kill-btn userInvestProject" attr-data="{{$lowProject['id']}}">立即出借</a>
                                @else
                                <!-- 项目其它状态 -->
                                    <a href="javascript:" class="kill-btn disable">{{ $lowProject['status_note'] }}</a>
                                @endif
                            </td>
                        </tr>
                    </table>
                    @if($spikeStatus == \App\Http\Logics\Activity\SpikeLogic::ACTIVITY_NOSTART)
                        <div class="kill-data-mask">
                            <p>距离开始秒杀还有</p>
                            <p class="kill-time"></p>
                        </div>
                    @elseif($spikeStatus == \App\Http\Logics\Activity\SpikeLogic::ACTIVITY_ONGOING)

                        @if($lowProject['publish_at'] > \App\Tools\ToolTime::dbNow() && $lowProject['status'] == \App\Http\Dbs\Project\ProjectDb::STATUS_INVESTING )
                            <div class="kill-data-mask">
                                <p>距离开始秒杀还有</p>
                                <p class="kill-time"></p>
                            </div>
                        @elseif( $lowProject['left_amount'] == 0 && $isLastTime == false)
                            <div class="kill-data-mask">
                                <p>距离下一场秒杀还有</p>
                                <p class="kill-time"></p>
                            </div>
                        @else

                        @endif
                    @else

                    @endif

                </div>
            </a>
            @else
                @include('wap.activity.spike.common.defaultlow')
            @endif
            @endforeach

            <div class="kill-tip"><span></span><a href="javascript:;">查看温馨提示</a></div>
            <!-- 弹窗 -->
            <div class="kill-pop-wrap">
                <div class="mask3"></div>
                <div class="kill-pop" id="kill-pop">
                    <i></i>
                    <h3>温馨提示</h3>
                    <p class="yellow">1、活动时间：{{ date("Y年m月d日",$startTime) }}至{{ date("Y年m月d日",$endTime) }}</p>
                    <p>2、加息秒杀专区每天{{ $spikeTime }}准时开抢，100元起投，投资无上限，先到先得，投满为止；</p>
                    <p>3、投资加息秒杀专区项目不能使用加息券和红包；</p>
                    <p>4、活动期间内如有任何疑问请致电九斗鱼官方客服：400-6686-568，或登录九斗鱼咨询在线客服。</p>
                </div>
            </div>
        {{--<input type="hidden" id="serverTime" value="{{$nextSpikeTime}}">--}}
    </section>
</article>
@endsection

@section('footer')

@endsection

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
        $(function(){
            var status      =   '{{ $spikeStatus }}';
            //活动倒计时
            getRTime = function (){
                var serverTime  =   $(".server-spike-time").attr("attr-spike-time");
                var EndTime = new Date('{{ date("Y/m/d H:i:s",$nextSpikeTime) }}');
                var NowTime = new Date(serverTime);
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

                $(".kill-time").html(timeStr);
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
                var NowTime     =   new Date(serverTime);
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
                //$("#serverTime").val(y1+"/"+m1+"/"+d1+" "+h1+":"+i1+":"+s1);
            }
        })
    </script>
@endsection