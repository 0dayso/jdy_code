@extends('wap.common.activity')

@section('title', '全民争霸赛')

@section('keywords', env('META_KEYWORD'))

@section('description', env('META_DESCRIPTION'))

@section('css')
<meta name="format-detection" content="telephone=yes">
<link rel="stylesheet" type="text/css" href="{{assetUrlByCdn('/static/weixin/activity/investGame2/css/index.css')}}">
@endsection

@section('content')
    <article>
    	<!-- banner -->
    	<section class="banner">
            <p>活动时间：{{date('m月d日',$activityTime['start'])}}-{{date('m月d日',$activityTime['end'])}}</p>
    	</section>
    	<!-- End banner -->

        <div class="i-bj">
@if( !empty($projectList) )
    @foreach($projectList as $key => $project )
            <div class="i-box">
                <table>
                    <thead>
                        <tr>
                            <td colspan="3">{{$project['product_line_note']}}{{$project['invest_time_note']}} {{$project['id']}}</td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td width="38%">{{(float)$project['profit_percentage']}}%</td>
                            <td width="30%">{{$project['format_invest_time']}}{{$project['invest_time_unit']}}</td>
                            <td>{{$project['left_amount']}}元</td>
                        </tr>
                        <tr>
                            <td>借款利率</td>
                            <td>期限</td>
                            <td>剩余可投</td>
                        </tr>
                        <tr>
                            <td colspan="3">
                                @if( $userStatus == false)
                                    <a href="javascript:;" class="i-btn user-login" >立即出借</a>
                                @else
                                    @if($project['status'] == \App\Http\Dbs\Project\ProjectDb::STATUS_INVESTING && \App\Tools\ToolTime::getUnixTime($project['publish_at'],'default') >= time())
                                        <a href="javascript:;" class="i-btn repayment investProject" attr-project-id="{{$project['id']}}">敬请期待</a>
                                    @elseif($project['status'] == \App\Http\Dbs\Project\ProjectDb::STATUS_INVESTING)
                                        <a href="javascript:;" class="i-btn investProject" attr-project-id="{{$project['id']}}">立即出借</a>
                                    @else
                                        <a href="javascript:;" class="i-btn disable investProject" attr-project-id="{{$project['id']}}">{{$project['status_note']}}</a>
                                    @endif
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
    @endforeach
@endif
        <div class="i-bj1">
@if(empty($lotteryList['record']))
            <span class="i-span1">{{isset($ranking[0])&&$ranking[0] ?\App\Tools\ToolStr::hidePhone($ranking[0]['phone']):'暂无第一'}}</span>
            <span class="i-span2">{{isset($ranking[2])&&$ranking[2] ?\App\Tools\ToolStr::hidePhone($ranking[2]['phone']):'暂无第三'}}</span>
            <span class="i-span3">{{isset($ranking[1])&&$ranking[1] ?\App\Tools\ToolStr::hidePhone($ranking[1]['phone']):'暂无第二'}}</span>
@else
            <span class="i-span1">{{isset($lotteryList['record'][$lotteryList['lottery'][1]['id']]) ?\App\Tools\ToolStr::hidePhone($lotteryList['record'][$lotteryList['lottery'][1]['id']]['phone']):'暂无第一'}}</span>
            <span class="i-span2">{{isset($lotteryList['record'][$lotteryList['lottery'][3]['id']]) ?\App\Tools\ToolStr::hidePhone($lotteryList['record'][$lotteryList['lottery'][3]['id']]['phone']):'暂无第三'}}</span>
            <span class="i-span3">{{isset($lotteryList['record'][$lotteryList['lottery'][2]['id']]) ?\App\Tools\ToolStr::hidePhone($lotteryList['record'][$lotteryList['lottery'][2]['id']]['phone']):'暂无第二'}}</span>
@endif
 </div>
        <div class="i-bj2">
            <div class="i-btn1" onclick="window.location.reload();">每2小时更新数据</div>
            <div class="i-prize i-invest">
                <h4 class="i-title">投资最新记录</h4>
                <div class="i-list" style='min-height:4.0rem;'>
                    <ul>
            @if( !empty($ranking))
            @foreach($ranking as $key => $rank)
                    <li><span>第{{$lotteryList['word'][$key+1]}}名</span><span>{{\App\Tools\ToolStr::hidePhone($rank['phone'])}}</span><span>{{$rank['total']}} </span></li>
            @endforeach
            @else
                    <li><span>{{date('Y-m-d',time())}}></span><span>暂无PK数据!</span></li>
            @endif
                    </ul>
                </div>
            </div>
            <!-- rule -->
            <div class="rule" style="line-height: 0.88rem;">
                <h3>活动规则</h3>
                <p>1. 本次投资PK活动仅限活动页面的项目参与</p>
                <p>2. 活动期间，选取活动参与项目累计投资额的前三名，获得对应奖品。如用户出借金额相同，则按照用户最后一笔投资额的时间，择先选取</p>
                <p>3. 页面展示的投资数据仅为当前时间的投资数据，最终中奖名单以2017年7月24日公布的数据为准；</p>
                <p>4. 奖品获得者，活动期间提现金额≥10000元，取消其领奖资格；</p>
                <p>5. 活动所得奖品以实物形式发放，将在2017年8月15日之前，与您沟通联系确定发放奖品。在8月15日之前联系用户仍无回应，则是为自动放弃奖品</p>
                <p>6. 活动期间如有任何疑问请致电九斗鱼官方客服：400-6686-568，或登录九斗鱼咨询在线客服。</p>
            </div>
            <!-- End rule -->
        </div>
        <!-- pop  登 录-->
        <section class="pop-wrap">
            <div class="pop-mask"></div>
            <div class="pop">
                <span class="pop-close"></span>
                <div class="i-text"></div>
                <a href="javascript:;" class="pop-btn userDoLogin">登 录</a>
            </div>
        </section>
        <!-- End pop -->


@endsection
@section('jsScript')
    <script>
    $(function(){

        var client = getCookie('JDY_CLIENT_COOKIES');
        if( client == '' || !client ){
            var client  =   '{{$client}}';
        }
       $(".user-login").click(function(e){
            e.preventDefault();
            $(".pop-wrap").show();
         })

    	// 弹层关闭按钮
        $('.pop-close').click(function(){
            $('.pop-wrap').hide();
        })
        $('.investProject').click(function () {
            var version =   "{{$version}}";
            var  projectId  =   $(this).attr("attr-project-id");
            if( !projectId ||projectId==0){
                return false;
            }
            if( client =='ios'){
                if( version ){
                     window.location.href="objc:certificationOrInvestment("+projectId+",1)";
                    return false;
                }
                if(!version) {
                    var act_token = "{{time()}}" + '_108_' + projectId
                    window.location.href="objc:toProjectDetail("+projectId+",1,"+act_token+")";
                    return false;
                }

            }
            if (client =='android'){
                window.jiudouyu.fromNoviceActivity(projectId,1);
                return false;
            }
            window.location.href='/project/detail/'+projectId;

        })
        $(".userDoLogin").click(function () {

            if( client =='ios'){
                window.location.href = "objc:gotoLogin";
                return false;
            }
            if (client =='android'){
                window.jiudouyu.login();
                return false;
            }
            window.location.href='/login';
        })
    })
    </script>
@endsection
