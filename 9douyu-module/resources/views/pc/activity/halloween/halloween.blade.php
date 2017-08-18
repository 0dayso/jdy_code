@extends('pc.common.layout')

@section('title', '不给糖•就捣蛋，天天惊喜送给你')

@section('keywords', env('META_KEYWORD'))

@section('description', env('META_DESCRIPTION'))

@section('css')
    <meta name="format-detection" content="telephone=yes">

@endsection
@section('content')
    <link rel="stylesheet" type="text/css" href="{{ assetUrlByCdn('/static/activity/halloween/css/halloween.css') }}">
    <div class="ha-banner">
        <div class="wrap">
            <p>{{date("m.d",$activityTime['start'])}}-{{date("m.d",$activityTime['end'])}}</p>
        </div>
    </div>
    <div class="ha-bg">
        <div class="ha-bg1">
            <p class="ha-title">天天砸南瓜</p>
            <ul class="ha-melon" lock-status="opened">
                <li></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
            </ul>
           <p class="ha-record">获奖记录</p> 
           <div id="scrollDiv" class="hidden an-scroll">
                <ul class="an-activity-list center" id="an">
                @if( !empty($lotteryList))
                    @foreach($lotteryList as $key => $record )
                    <li>
                        <span class="w1">恭喜</span>
                        <span class="w2">{{\App\Tools\ToolStr::hidePhone($record['phone'],3,3)}}</span>
                        <span>获得了{{$record['award_name']}}</span>
                    </li>
                    @endforeach
                @else
                    <li><span>暂无抽奖数据!</span></li>
                @endif
                </ul>
            </div>
        </div>
        <div class="ha-bg2">
            <p class="ha-title3">天天幸运榜</p>
            @if( !empty($projectList))
                @foreach($projectList as $key => $project)
                <p class="ha-title4">{{$project['product_line_note']}} • {{$project['format_invest_time']}}{{$project['invest_time_unit']}}</p>
                <a href="/project/detail/{{$project['id']}}" class="ha-table">
                    <table>
                        <tr>
                            <td width="30%"><span class="ha-1">{{(float)$project['profit_percentage']}}</span><span class="ha-2">%</span></td>
                            <td width="30%"><span class="ha-4">{{$project['left_amount']}}元</span></td>
                            <td rowspan="2">
                            @if($project['status'] == \App\Http\Dbs\Project\ProjectDb::STATUS_INVESTING && \App\Tools\ToolTime::getUnixTime($project['publish_at'],'default') >= time())
                                <a href="/project/detail/{{$project['id']}}" class="ha-btn">敬请期待</a>
                            @elseif($project['status'] == \App\Http\Dbs\Project\ProjectDb::STATUS_INVESTING)
                                <a href="/project/detail/{{$project['id']}}" class="ha-btn">立即出借</a>
                            @else
                                <a href="/project/detail/{{$project['id']}}" class="ha-btn diasble">{{$project['status_note']}}</a>
                            @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="ha-3">借款利率</td>
                            <td class="ha-3">剩余可投</td>
                        </tr>

                    </table>
                </a>
                @endforeach
            @endif
            <dl class="ha-prize">
                <p class="ha-title8">今日奖品</p>
                <dt>
                    <img src="{{ assetUrlByCdn('/static/activity/halloween/images/prize-'.$everyDayPrize['number'].'.png') }}" />
                </dt>
                <dd>
                    <h4>{{$everyDayPrize['name']}}</h4>
                    <!-- <h4>九阳豆浆机</h4>
                    <h4>飞利浦电动牙刷</h4>
                    <h4>美的吸尘器</h4>
                    <h4>Brita家用滤水壶</h4>
                    <h4>小米电视3S</h4>
                    <h4>小米净化器2</h4> -->
                </dd>
            </dl>
            <p class="ha-pro">投资定期项目，每日随机抽选一名惊喜奖</p>
        </div>
        <div class="ha-title6"><p>获奖名单</p></div>
        <div id="scrollDiv1" class="hidden an-scroll an-scroll1">
            <ul class="an-activity-list an1">
                @if( !empty($timeList))
                @foreach($timeList as $key => $list )
                    <li>
                        <span class="w1">{{date("m月d日",strtotime($list['time']))}} </span>
                        <span class="w2">{{$list['lottery']}} </span>
                        <span>{{$list['user']}} </span>
                    </li>
                @endforeach
                @endif
            </ul>
        </div>
        <div class="ha-rule">
            <h3>活动规则</h3>
            <ul>
                <li>1、活动时间：{{date("Y年m月d日",$activityTime['start'])}}-{{date("m月d日",$activityTime['end'])}}；</li>
                <li>2、活动期间内，每个用户每天仅有一次砸南瓜机会，砸开南瓜获得对应奖励；</li>
                <li>3、活动期间内，每日会在当日投资九省心或九安心的出借人中随机抽选一名，获得当日的惊喜奖,次日11点公布昨日中奖信息；</li>
                <li>4、活动期间内，获奖者提现金额≥10000元，取消其领奖资格；</li>
                <li>5、活动所得奖品以实物形式发放，客服将在2016年11月30日之前，与您沟通联系确定发放奖品；</li>
                <li>6、活动期间如有任何疑问请致电九斗鱼官方客服：400-6686-568，或登录九斗鱼咨询在线客服；</li>

            </ul>
        </div>
    </div>

    <!-- 弹窗 -->
    <div class="vip_alert">
        <div class="vip-mask"></div>
        <div class="vip_box">
            <div class="nat-pop-title">
                <span class="nat-pop-close"></span>
                <i class="nat-pop-iconleft"></i>
                <i class="nat-pop-iconright"></i>
              <span id="alert-title">恭喜你</span>
            </div>
            <div class="vip-content">
                @if($userStatus == false && $activityStatus==\App\Http\Logics\Activity\HalloweenLogic::ACTIVITY_PADDING)
                <p class="vip-text">您还未登录!请登录后参与抽奖活动</p>
                <a href="/login" class="ha-btn1 close-pop">立即登录</a>
                @elseif( $activityStatus==\App\Http\Logics\Activity\HalloweenLogic::ACTIVITY_NOT_OPEN)
                <p class="vip-text1">万圣节活动在{{date('m.d',$activityTime['start'])}}号准时开启!<br>敬请期待!</p>
                    <p class="vip-text2"><a href="javascript:;" class="ha-btn1 close-pop">我知道了</a></p>
                @elseif($activityStatus==\App\Http\Logics\Activity\HalloweenLogic::ACTIVITY_IS_OVER)
                <p class="vip-text1">万圣节活动已经结束!!<br>谢谢参与!</p>
                    <p class="vip-text2"><a href="javascript:;" class="ha-btn1 close-pop">我知道了</a></p>
                @else
                <!-- 恭喜你 -->
                <p class="vip-text1">获得3%活期加息券</p>
                <p class="vip-text2"><a href="javascript:;" class="ha-btn1 close-pop">我知道了</a></p>
               @endif
            </div>
        </div>
    </div>




    <script type="text/javascript">
    //        滚动状态
        function AutoScroll(obj) {
            $(obj).find("ul:first").animate({
                marginTop: "-40px"
            }, 500, function() {

                $(this).css({ marginTop: "0px" }).find("li:first").appendTo(this);
            });
        }
        $(document).ready(function() {
            if($("#an li").length>5){
                var myar = setInterval('AutoScroll("#scrollDiv")', 1000);
            }
        });


        // 弹窗

        $(".ha-melon li").click(function(){

            var index   =   $(this).index();
            var userStatus = '{{$userStatus}}';
            var activity   =  '{{$activityStatus}}'

            if(userStatus == false || activity != {{ \App\Http\Logics\Activity\HalloweenLogic::ACTIVITY_PADDING }}) {
                $(".vip_alert").fadeIn();
                $("#alert-title").html("很抱歉")
                $(".vip_alert").show();
                return false
            }
            if($(this).hasClass("melon")){
                return false;
            }
            var lock    =   $(".ha-melon").attr("lock-status");
            if( lock == 'closed'){
                return false;
            }
            $(".ha-melon").attr("lock-status",'closed');
            $.ajax({
                url      :"/activity/halloween/doLottery",
                dataType :'json',
                type     :'post',
                success : function(json){
                    var html    =   '';
                    var title   =   '';
                    $(".vip_alert").fadeIn();
                    $(".vip-text1").html("");
                    if( json.status==true || json.code==200){

                        $(".ha-melon").find("li").eq(index).addClass('melon');
                        html    =   "获得"+json.data.name+"一张, <br>APP－资产－我的优惠券查看"
                        title   =   "恭喜您";
                    } else if( json.status == false || json.code ==500 ){
                        html    =   json.msg
                        title   =   "很抱歉";
                    }

                    $(".vip-text1").html(html);
                    $("#alert-title").html(title)
                    $("#vip_alert").show();
                    $(".ha-melon").attr("lock-status",'opened');
                    return false;
                },
                error : function(msg) {
                    alert('领取失败，请稍候再试');
                    $(".ha-melon").attr("lock-status",'opened');
                }
            })
        })

    $("document,.nat-pop-close,.close-pop").click(function(){
        $(".vip_alert").fadeOut();
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



