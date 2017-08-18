@extends('wap.common.wapBase')

@section('title', '盛世华诞，一路向钱')

@section('keywords', env('META_KEYWORD'))

@section('description', env('META_DESCRIPTION'))

@section('css')
<meta name="format-detection" content="telephone=yes">
<link rel="stylesheet" type="text/css" href="{{assetUrlByCdn('/static/weixin/activity/national/css/national.css')}}">
@endsection

@section('content')
    <article>
        <!-- sign everyday -->
        <section class="nat-sign-wrap">
            <div class="nat-date">{{date("m.d", $start_time)}}-{{date("m.d", $end_time)}}</div>
            <div class="nat-sign-box">
                <span class=""></span>
                <span></span>
                <span class="nat-sign-num">10</span>
                <span></span>
                <p>
                    <span class="nat-sign-num">30</span>
                    <span></span>
                    <span class="nat-sign-num">100</span>
                </p>
            </div>
            <a class="nat-btn-sign">我要签到</a>
        </section>
        <!-- End sign everyday -->

        <!-- project lottery -->
        <section class="nat-pro-bg">
            <!-- project list -->
            @foreach($projectList as $key=> $project)
                @if($key == 'three')
            <div class="nat-project">
                <a href="javascript:;" class="investProject" attr-data-id="{{$project['id']}}" >
                    <table>
                        <tr>
                            <th colspan="3">{{$project['product_line_note']}} • {{$project['invest_time_note']}}</th>
                        </tr>
                        <tr>
                            <td>
                                <big>{{ floor($project['base_rate']) }}</big><span>％</span><span class="red">+</span><big class=red>{{ floor($project['after_rate'])}}</big><span class="red">%</span>

                            </td>
                            <td width="35%">{{$project['left_amount']}}元</td>
                            <td width="35%" rowspan="2">&nbsp;</td>
                        </tr>
                        <tr>
                            <td><small>借款利率</small></td>
                            <td><small>剩余可投</small></td>
                        </tr>
                    </table>
                </a>
            </div>
                @endif
            @endforeach
            @foreach($projectList as $key=> $project)
                @if($key == 'six')
                    <div class="nat-project sixth">
                        <a href="javascript:;" class="investProject" attr-data-id="{{$project['id']}}">
                            <table>
                                <tr>
                                    <th colspan="3">{{$project['product_line_note']}} • {{$project['invest_time_note']}}</th>
                                </tr>
                                <tr>
                                    <td>
                                        <big>{{ floor($project['base_rate']) }}</big><span>％</span><span class="red">+</span><big class=red>{{ floor($project['after_rate'])}}</big><span class="red">%</span>

                                    </td>
                                    <td width="35%">{{$project['left_amount']}}元</td>
                                    <td width="35%" rowspan="2">&nbsp;</td>
                                </tr>
                                <tr>
                                    <td><small>借款利率</small></td>
                                    <td><small>剩余可投</small></td>
                                </tr>
                            </table>
                        </a>
                    </div>
                @endif
            @endforeach
            <!-- End project list -->

            <!-- lottery -->
            <div class="nat-lottery">
                <div id="machine1">
                    <div class="slot slot0"></div>
                    <div class="slot slot1"></div>
                    <div class="slot slot2"></div>
                    <div class="slot slot3"></div>
                    <div class="slot slot4"></div>
                </div>
            </div>
            @if($time_status ==2 )
                @if( empty($user_id) )
                    <div id="slotMachineButton5" class="nat-btn-lottery"></div>
                @else
                    @if( $lottery['status'] ==true)
                    <!--可以抽奖-->
                        <div id="slotMachineButton1" class="nat-btn-lottery" attr-lottery-num="{{$lottery['data']['msg']}}"></div>
                    @else
                    <!--不可以抽奖-->
                        <div id="slotMachineButton4" class="nat-btn-lottery"></div>
                    @endif
                @endif

            @elseif($time_status ==3)

                <div id="slotMachineButton2" class="nat-btn-lottery"></div>
            @else
                <div id="slotMachineButton3" class="nat-btn-lottery"></div>
            @endif
            <!-- End lottery -->
        </section>
        <!-- End project lottery -->
        <div class="nat-btn-rule">
            <span></span>查看活动规则
        </div>
    </article>

    <!-- rule box -->
    <section class="nat-rule-box">
        <div class="nat-mask"></div>
        <div class="nat-rule">
            <div class="nat-rule-tile">活动规则<span class="nat-rule-close"></span></div>
            <div class="nat-rule-main">
                <p>1、活动时间：{{date("Y年m月d日", $start_time)}}-{{date("Y年m月d日", $end_time)}}；</p>
                <p>2、活动期间内，连续每日签到可获得对应奖励。如中断签到，再次签到重新计算，之前签到已获取的签到奖励仍然有效；</p>
                <p>3、活动期间内用户<span>新充值金额</span>单笔投资九省心3月期、6月期项目金额≥5万元即可获得一次抽奖机会，100%中奖；</p>
                <p>4、活动期间投资加息项目不能使用加息券、红包；</p>
                <p>5、活动所得奖品以实物形式发放，客服人员将在2016年10月31日之前，与您沟通联系确定发放奖品详情；</p>
                <p>6、活动期间如有任何疑问请致电九斗鱼官方客服：400-6686-568，或登录九斗鱼咨询在线客服。</p>
            </div>
        </div>
    </section>
    <!-- End rule box -->
    @if( empty($user_id))
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
                <p>别着急您还没有登录呢</p>
                <a href="javascript:;" class="nat-login-btn" id="userLogin">立即登录</a>
            </div>
        </div>
    </section>
    <!-- End not signed pop -->
    @endif

    @if( $lottery['status'] !=true)
        <!-- not signed pop -->
        <section class="nat-pop-box" id="invest">
            <div class="nat-mask"></div>
            <div class="nat-pop">
                <div class="nat-pop-title">
                    <span class="nat-pop-close"></span>
                    <i class="nat-pop-iconleft"></i>
                    <i class="nat-pop-iconright"></i>
                    十分抱歉
                </div>
                <div class="nat-pop-main">
                    <p>单笔投资定期≥5万,即可参与抽奖!100%中奖</p>
                    <a href="javascript:;" class="nat-login-btn investProject"  attr-data-id="{{isset($project['id'] ) ? $project['id'] : "0"}}">立即出借</a>
                </div>
            </div>
        </section>
        <!-- End not signed pop -->
        @endif
    <!-- signed pop -->
    <section class="nat-pop-box" id="sign_success">
        <div class="nat-mask"></div>
        <div class="nat-pop">
            <div class="nat-pop-title">
                <span class="nat-pop-close"></span>
                <i class="nat-pop-iconleft"></i>
                <i class="nat-pop-iconright"></i>
                已签到
            </div>
            <div class="nat-pop-main signed">
                <p>已连续签到 <big id="sign_success_num">0</big> 天</p>

            </div>
        </div>
    </section>
    <!-- End signed pop -->

    <!-- break signed pop -->
    <section class="nat-pop-box" id="error_box">
        <div class="nat-mask"></div>
        <div class="nat-pop">
            <div class="nat-pop-title">
                <span class="nat-pop-close"></span>
                <i class="nat-pop-iconleft"></i>
                <i class="nat-pop-iconright"></i>
                很抱歉
            </div>
            <div class="nat-pop-main">
                <p id="error_msg">只有连续签到才能获得奖励<br>感谢您的参与</p>
            </div>
        </div>
    </section>
    <!-- End break signed pop -->

    <!-- continue signed pop -->
    <section class="nat-pop-box" id="sign_award">
        <div class="nat-mask"></div>
        <div class="nat-pop">
            <div class="nat-pop-title">
                <span class="nat-pop-close"></span>
                <i class="nat-pop-iconleft"></i>
                <i class="nat-pop-iconright"></i>
                恭喜你
            </div>
            <div class="nat-pop-main">
                <p>已连续签到 <big id="sign_award_num">0</big> 天</p>
                <p class="nat-pop-tip">恭喜您获得<i id="sign_award_bonus">10元一张</i><br>APP-资产-我的优惠券查看</p>
            </div>
        </div>
    </section>
    <!-- End continue signed pop -->

    <!-- prize pop -->
    <section class="nat-pop-box" id="prize">
        <div class="nat-mask"></div>
        <div class="nat-pop">
            <div class="nat-pop-title">
                <span class="nat-pop-close prize-close"></span>
                <i class="nat-pop-iconleft"></i>
                <i class="nat-pop-iconright"></i>
                恭喜你
            </div>
            <div class="nat-pop-main">
                <p><img src="{{assetUrlByCdn('/static/weixin/activity/national/images/img1.jpg')}}" class="nat-pop-img"></p>
                <p class="nat-prize-txt">恭喜你获得小熊咖啡机</p>
            </div>
        </div>
    </section>
@endsection

@section('footer')

@endsection
    <!-- End prize pop -->
@section('jsScript')
    <script src="{{assetUrlByCdn('/static/weixin/activity/national/js/jquery.slotmachine.js')}}"></script>
    <script>
        $(document).ready(function(){
            var active  =   [];
            // lettery
            var client = getCookie('JDY_CLIENT_COOKIES');
            if( client == '' || !client ){
                 var client  =   '{{$client}}';
             }

            function onComplete(id, active){
                switch(id){
                    case 'machine1':
                        $(".nat-pop-img").attr('src','/static/weixin/activity/national/images/img'+active.index+'.jpg');
                        if( active.number ==0 || !active.number ){
                            var html    =   "恭喜你获得"+active.name+"奖品"
                        }else{
                            var html    =   '恭喜你获得'+active.name+'奖品<br/>您还剩下'+active.number+'次抽奖机会'

                        }

                        $(".nat-prize-txt").html(html);
                        setTimeout(function(){
                            $("#prize").show('400');
                        },2500);

                        break;

                }
            };

            $("#slotMachineButton1").click(function(){

                var active  =   [];
                var lock    =   $(this).attr("lock-status");
                if( lock == 'closed'){
                    return false;
                }
                var token   =   "{{$token_status}}";
                var number  =   $(this).attr("attr-lottery-num")
                $(this).attr("lock-status",'opened');

                $.ajax({
                    url      :"/activity/national/doLottery",
                    dataType :'json',
                    @if($token_status && $client ==\App\Http\Logics\RequestSourceLogic::SOURCE_ANDROID)
                    data: { from:'app',token: token,client:client},
                    @endif
                    type     :'get',
                    success : function(json){

                        if( json.status==true || json.code==200){

                            active.index  =  json.data.order_num

                            active.name   =  json.data.name

                            active.number =  number-1;
                            var machine1 = $("#machine1").slotMachine({
                                active  : 1,
                                delay   : 500,
                                //stopIndex: 4
                                stopIndex: active.index
                            });
                            machine1.shuffle(5, onComplete('machine1',active));
                            $("#slotMachineButton1").attr("lock-status",'opened');
                            return false;
                        }
                        if( json.status == false || json.code ==500 ){

                            $("#error_box").show();
                            $("#error_msg").html(json.msg);
                            $("#slotMachineButton1").attr("lock-status",'opened');
                            return false;
                        }
                    },
                    error : function(msg) {
                        alert('领取失败，请稍候再试');
                        $("#slotMachineButton1").attr("lock-status",'opened');
                    }
                })

            });
            $("#slotMachineButton2").click(function () {
                $("#error_box").show();
                $("#error_msg").html("国庆节抽奖活动已经结束!<br>谢谢参与!");
                return false;
            })
            $("#slotMachineButton3").click(function () {
                $("#error_box").show();
                $("#error_msg").html("国庆节抽奖活动在{{date('m.d',$start_time)}}号准时开启!<br>敬请期待!");
                return false;
            })
            $("#slotMachineButton4").click(function () {
                $("#invest").show();
                return false;
            })
            $("#slotMachineButton5").click(function () {
                $("#login").show();
                return false;
            })
            // nat-btn-rule
            $(".nat-btn-rule").click(function() {
                $(".nat-rule-box").show();
            });
            $(".nat-rule-close").click(function(){
                $(".nat-rule-box").hide();
            });

            // nat-pop
            $(".nat-pop-close").each(function(){
                $(this).click(function(){
                    $(this).parent(".nat-pop-title").parent(".nat-pop").parent(".nat-pop-box").hide();

                })
            })

            $(".prize-close").click(function () {

                $(this).parent(".nat-pop-title").parent(".nat-pop").parent(".nat-pop-box").hide();
                window.location.reload();
            })
            $('.investProject').click(function () {

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

            $("#userLogin").click(function () {
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
            //签到显示问题
            function signShow(sign_num){
                $(".nat-sign-box span").each(function(){
                    $(this).removeClass('nat-checked');
                });
                for(var i=1;i<=sign_num;i++){
                    $(".nat-sign-box span").eq(i-1).addClass('nat-checked');
                }
            }
            signShow('{{$sign_num}}');

            $(".nat-btn-sign").click(function(){

                var userStatus = '{{$user_id}}';

                if(userStatus == false) {
                    $("#login").show();
                    return false
                }
                var token   =   '{{$token_status}}'

                    $.ajax({
                        url: '/activity/national/signAjax',
                        dataType:'json',
                        async: false,  //同步发送请求
                        type: 'post',
                        @if($token_status && $client ==\App\Http\Logics\RequestSourceLogic::SOURCE_ANDROID)
                        data: { from:'app',token: token,client:client},
                        @endif
                        success: function(result) {
                           if(result.status == true){
                               signShow(result.sign_num);
                               if(result.sign_num ==3){
                                   $("#sign_award_num").html(result.sign_num);
                                   $("#sign_award_bonus").html('10元红包一张');
                                   $("#sign_award").show();
                               }else if(result.sign_num ==5){
                                   $("#sign_award_num").html(result.sign_num);
                                   $("#sign_award_bonus").html('30元红包一张');
                                   $("#sign_award").show();
                               }else if(result.sign_num ==7){
                                   $("#sign_award_num").html(result.sign_num);
                                   $("#sign_award_bonus").html('100元红包一张');
                                   $("#sign_award").show();
                               }else{
                                   $("#sign_success_num").html(result.sign_num);
                                   $("#sign_success").show();
                               }
                           }else if(result.status == false){
                               $("#error_msg").html(result.msg);
                               $("#error_box").show();
                           }
                        },
                        error: function (result) {
                            $("#error_msg").html(result.msg);
                            $("#error_box").show();
                        }
                    });
            });
        });
    </script>
@endsection

