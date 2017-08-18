@extends('wap.common.wapBase')

@section('title', '不给糖，就捣蛋－天天惊喜送给你')

@section('keywords', env('META_KEYWORD'))

@section('description', env('META_DESCRIPTION'))

@section('css')
<meta name="format-detection" content="telephone=yes">
<link rel="stylesheet" type="text/css" href="{{assetUrlByCdn('/static/weixin/activity/halloween/css/halloween.css')}}">
@endsection

@section('content')
    <article>
        <!-- banner  -->
        <section class="hallo-banner">
            <img src="{{assetUrlByCdn('/static/weixin/activity/halloween/images/banner.png')}}" class="img">
            <p>{{date("m.d",$activityTime['start'])}}-{{date("m.d",$activityTime['end'])}}</p>
        </section>
        <!-- End banner  -->

        <!-- pumpkin -->
        <section class="pumpkin-wrap">
            <div class="pumpkin" id="pumpkin" lock-status="opened">
                <p>天天砸南瓜</p>
                <span></span>
                <span></span>
                <span></span>
                <span></span>
                <span></span>
                <span></span>
            </div>
            <div class="list">
                <p>获奖记录</p>
                <div id="scrollDiv">
                    <ul>
                    @if( !empty($lotteryList))

                    @foreach($lotteryList as $key => $record )
                        <li><span>{{\App\Tools\ToolStr::hidePhone($record['phone'],3,3)}}</span>获得了{{$record['award_name']}}一张</li>
                    @endforeach
                     @else
                        <li>暂无抽奖数据!</li>
                    @endif
                    </ul>
                </div>  
            </div>
        </section>
        <!-- End pumpkin -->

        <!-- ranking -->
        <section class="ranking-wrap">
            <div class="ranking-title">天天幸运榜</div>

            <!-- 三月期 -->
        @if( !empty($projectList))
            @foreach($projectList as $key => $project)
                @if($project['status'] == \App\Http\Dbs\Project\ProjectDb::STATUS_INVESTING && \App\Tools\ToolTime::getUnixTime($project['publish_at'],'default') < time())
            <a href="javascript:;" class="ranking-block investProject" attr-data-id="{{$project['id']}}">
                @else
            <a href="javascript:;" class="ranking-block">
                @endif
            <table class="ranking">
                <tr>
                    <th colspan="3">{{$project['product_line_note']}} • {{$project['format_invest_time']}}{{$project['invest_time_unit']}}</th>
                </tr>
                @if($project['status'] == \App\Http\Dbs\Project\ProjectDb::STATUS_INVESTING)
                    <tr>
                @else
                    <tr class="sold">
                @endif
                    <td>
                        <p class="yellow lh"><big>{{(float)$project['profit_percentage']}}</big><em>％</em></p>
                        <p><small>借款利率</small></p>
                    </td>
                    <td>
                        <p class="lh">{{$project['left_amount']}}元</p>
                        <p><small>剩余可投</small></p>
                    </td>
                    @if($project['status'] == \App\Http\Dbs\Project\ProjectDb::STATUS_INVESTING && \App\Tools\ToolTime::getUnixTime($project['publish_at'],'default') >= time())
                        <td><span>待售</span></td>
                    @elseif($project['status'] == \App\Http\Dbs\Project\ProjectDb::STATUS_INVESTING)
                        <td><span>投资</span></td>
                    @else
                        <td><span>售罄</span></td>
                    @endif

                </tr>
            </table>
            </a>
            @endforeach
        <!-- End 三月期 -->
        @endif

            <!-- 六月期 -->
            {{--<a href="#" class="ranking-block">--}}
            {{--<table class="ranking">--}}
                {{--<tr>--}}
                    {{--<th colspan="3">九省心  •  6月期</th>--}}
                {{--</tr>--}}

                {{--<tr>--}}
                    {{--<td>--}}
                        {{--<p class="yellow lh"><big>12</big><em>％</em></p>--}}
                        {{--<p><small>借款利率</small></p>--}}
                    {{--</td>--}}
                    {{--<td>--}}
                        {{--<p class="lh">00000000元</p>--}}
                        {{--<p><small>剩余可投</small></p>--}}
                    {{--</td>--}}
                    {{--<td><span>投资</span></td>--}}
                {{--</tr>--}}
            {{--</table>--}}
            {{--</a>--}}
            <!-- End 六月期 -->

            <!-- 今日上榜 -->
            <div class="today-title">今日奖品</div>
            <dl class="today-prize">
                <dt><img src="{{assetUrlByCdn('/static/weixin/activity/halloween/images/prize-'.$everyDayPrize['number'].'.png')}}"></dt>
                <dd>
                    <p><em>{{$everyDayPrize['name']}}</em></p>
                </dd>
            </dl>
            <div class="today-txt">投资定期项目，每日随机抽选一名惊喜奖</div>
            <!-- End 今日上榜 -->
        </section>
        <!-- End ranking -->

        <!-- record -->
        <section class="record-wrap">
            <div class="record-title">中奖记录</div>
            <ul>
                @if( !empty($timeList))
                @foreach($timeList as $key => $list )
                <li>
                    <span>{{date("m月d日",strtotime($list['time']))}}</span>
                    <span>{{$list['lottery']}}</span>
                    <span>{{$list['user']}} </span>
                </li>
                @endforeach
                @endif
            </ul>
            <div class="rule">
                <span></span>查看活动规则
            </div>
        </section>
        <!-- End record -->

    </article>

    <!-- rule box -->
    <section class="nat-rule-box">
        <div class="nat-mask"></div>
        <div class="nat-rule">
            <div class="nat-rule-tile">活动规则<span class="nat-rule-close"></span></div>
            <div class="nat-rule-main">
                <p>1、活动时间：{{date("Y年m月d日",$activityTime['start'])}}-{{date("m月d日",$activityTime['end'])}}；</p>
                <p>2、活动期间内，每个用户每天仅有一次砸南瓜机会，砸开南瓜获得对应奖励；</p>
                <p>3、活动期间内，每日会在当日投资九省心或九安心的投资者中随机抽选一名，获得当日的惊喜奖,次日11点公布昨日中奖信息；</p>
                <p>4、活动期间内，获奖者提现金额≥10000元，取消其领奖资格；</p>
                <p>5、活动所得奖品以实物形式发放，客服将在2016年11月30日之前，与您沟通联系确定发放奖品；</p>
                <p>6、活动期间如有任何疑问请致电九斗鱼官方客服：400-6686-568，或登录九斗鱼咨询在线客服。</p>
            </div>
        </div>
    </section>
    <!-- End rule box -->

    @if( $userStatus == false && $activityStatus==\App\Http\Logics\Activity\HalloweenLogic::ACTIVITY_PADDING)
    <!-- not signed pop -->
    <section class="nat-pop-box" id="login">
        <div class="nat-mask"></div>
        <div class="nat-pop">
            <div class="nat-pop-title">
                <span class="nat-pop-close"></span>
                <i class="nat-pop-iconleft"></i>
                <i class="nat-pop-iconright"></i>
                未登录
            </div>
            <div class="nat-pop-main">
                <p>您还未登录!请登录后参与抽奖活动</p>
                <a href="javascript:;" class="nat-login-btn" id="userLogin">立即登录</a>
            </div>
        </div>
    </section>
    @else
    <!-- End not signed pop -->
    <section class="nat-pop-box" id="login">
        <div class="nat-mask"></div>
        <div class="nat-pop">
            <div class="nat-pop-title">
                <span class="nat-pop-close"></span>
                <i class="nat-pop-iconleft"></i>
                <i class="nat-pop-iconright"></i>
                <span id="nat-pop-title">恭喜您</span>
            </div>
            <div class="nat-pop-main">
                @if( $activityStatus==\App\Http\Logics\Activity\HalloweenLogic::ACTIVITY_NOT_OPEN)
                <p>万圣节活动在{{date('m.d',$activityTime['start'])}}号准时开启!<br>敬请期待!</p>
                @else
                <p>万圣节活动已经结束!!<br>谢谢参与!</p>
                @endif
                <a href="javascript:;" class="nat-login-btn close-pop" >我知道了</a>
            </div>
        </div>
    </section>
    @endif
    <!--   gain  pop -->
    <section class="nat-pop-box" id="lottery-pop">
        <div class="nat-mask"></div>
        <div class="nat-pop">
            <div class="nat-pop-title">
                <span class="nat-pop-close"></span>
                <i class="nat-pop-iconleft"></i>
                <i class="nat-pop-iconright"></i>
                <span id="nat-pop-title">恭喜您</span>
            </div>
            <div class="nat-pop-main">
                <p id="lottery-info">获得2%活期加息券一张,<br>APP－资产－我的优惠券查看</p>
                <a href="javascript:;" class="nat-login-btn close-pop" >我知道了</a>
            </div>
        </div>
    </section>
    <!-- End gain pop -->
    <!-- End  done pop -->
@endsection

@section('footer')
    <!-- 活动开始结束状态 -->
    @if( $activityTime['start'] > time())
        @include('wap.common.activityStart')
    @endif
    <!-- End 活动开始结束状态 -->
    @if($activityTime['end'] < time())
        @include('wap.common.activityEnd')
    @endif
@endsection
    <!-- End prize pop -->
@section('jsScript')
    <script>
    document.body.addEventListener('touchstart', function () { });
    function AutoScroll(obj) {
                $(obj).find("ul:first").animate({
                    marginTop: "-1.275rem"
                }, 500, function() {
                    $(this).css({ marginTop: "0px" }).find("li:first").appendTo(this);
                });
            }

        $(document).ready(function(){

           $('.rule').click(function() {
               $('.nat-rule-box').show();
           });
           $('.nat-mask,.nat-rule-close ').click(function(){
                $('.nat-rule-box').hide();
           });


           // pumpkin
           $('#pumpkin span').each(function(){
                $(this).click(function(){
                    var index   =   $(this).index();
                    var userStatus = '{{$userStatus}}';
                    var activity    =   '{{$activityStatus}}'
                    if(userStatus == false || activity != {{ \App\Http\Logics\Activity\HalloweenLogic::ACTIVITY_PADDING }}) {
                        $("#login").show();
                        return false
                    }
                    var lock    =   $("#pumpkin").attr("lock-status");
                    if( lock == 'closed'){
                        return false;
                    }
                    var token   =   "{{$token_status}}";
                    var client  =   "{{$client}}";

                    $("#pumpkin").attr("lock-status",'closed');

                    $.ajax({
                        url      :"/activity/halloween/doLottery",
                        dataType :'json',
                        @if($token_status && $client ==\App\Http\Logics\RequestSourceLogic::SOURCE_ANDROID)
                        data: { from:'app',token: token,client:client},
                        @endif
                        type     :'post',
                        success : function(json){
                            var html    =   '';
                            var title   =   '';
                            if( json.status==true || json.code==200){

                                $("#pumpkin").find("span").eq(index-1).addClass('broken');
                                html    =   "获得"+json.data.name+"一张, <br/>APP－资产－我的优惠券查看"
                                title   =   "恭喜您";
                            } else if( json.status == false || json.code ==500 ){
                                html    =   json.msg
                                title   =   "很抱歉";
                            }

                            $("#lottery-info").empty().html(html);
                            $("#nat-pop-title").empty().html(title)
                            $("#lottery-pop").show();
                            $("#pumpkin").attr("lock-status",'opened');
                            $('#gain').fadeIn();
                            return false;
                        },
                        error : function(msg) {
                            alert('领取失败，请稍候再试');
                            $("#slotMachineButton1").attr("lock-status",'opened');
                        }
                    })

                    
                })
           });
           // nat-pop
            $(".nat-pop-close,.close-pop").each(function(){
                $(this).click(function(){
                    $(this).parent(".nat-pop-title").parent(".nat-pop").parent(".nat-pop-box").hide();
                    $(this).parent(".nat-pop-main").parent(".nat-pop").parent(".nat-pop-box").hide();

                })
            });
            // list scroll
             if($('#scrollDiv li').length>3){
                var myar = setInterval('AutoScroll("#scrollDiv")', 1000)
             }

             $("#userLogin").click(function () {
                var  client     =   "{{ $client }}"
                if( client =='ios'){
                    window.location.href = "objc:gotoLogin";;
                    return false;
                }
                if (client =='android'){
                    window.jiudouyu.login()
                    return false;
                }
                window.location.href='/login';
            })
             $('.investProject').click(function () {

                var  client     =   "{{ $client }}"
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
        });
    </script>
@endsection

