@extends('pc.common.activity')

@section('title', '不劳而获开购吧')

@section('keywords', env('META_KEYWORD'))

@section('description', env('META_DESCRIPTION'))

@section('csspage')
    <meta name="format-detection" content="telephone=yes">
    <link rel="stylesheet" type="text/css" href="{{assetUrlByCdn('/static/activity/LabourDay/css/index.css')}}">
@endsection
@section('content')

    <div class="page-banner">
        <p class="page-time">{{date('Y年m月d日',$activityTime['start'])}}-{{date('m月d日',$activityTime['end'])}}</p>
    </div>

    <div class="page-sign page-auto">
            <p class="page-sign-time">签到时间：{{date('m月d日',$signTime['start'])}}--{{date('m月d日',$signTime['end'])}}</p>
            <a href="javascript:;" class="page-rule-btn" data-layer="page-layer-rule">活动规则</a>
            <img src="{{ assetUrlByCdn('/static/activity/LabourDay/images/page-sign-img.png')}}" class="page-sign-img"/>
@if($signTime['end'] < time())
             <p><strong>{{date('m月d日',$signTime['end'])}}</strong>签到结束 <br>谢谢参与!</p>
@else
            <p><strong>{{date('m月d日',$signTime['start'])}}</strong>开始签到 <br>领取签到红包</p>
@endif
            <div class="page-sign-btn-area">
@if( $signStatus['status'] == true  )
                <a href="javascript:;" class="page-sign-btn page-sign-btn1 sign-btn-sure" sign-data-lock="start">签到领两元</a>
@elseif($signStatus['data']['type'] =='notLogged' )
                <a href="javascript:;" class="page-sign-btn page-sign-btn1 sign-btn-login">登录签到</a>
@else
                 <a href="javascript:;" class="page-sign-btn page-sign-btn1 sign-btn-error">签到失败</a>
@endif
@if($exchangeStatus['status'] == true)
                <a href="javascript:;" class="page-sign-btn page-sign-btn2 exchange-btn-sure" exchange-data-lock="start">兑换红包</a>
@elseif( $exchangeStatus['data']['type'] =='notLogged')
                <a href="javascript:;" class="page-sign-btn page-sign-btn2 sign-btn-login-exchange">登录兑换红包</a>
@else
                <a href="javascript:;" class="page-sign-btn page-sign-btn2 exchange-btn-sure">兑换红包</a>
@endif
            </div>
     </div>


     <div class="page-lottery page-auto">
                <div class="page-speed-dial clearfix" id="lottery">
                    <div class="lottery-unit lottery-unit-0 page-speed-item active">
                        <img src="{{ assetUrlByCdn('/static/activity/LabourDay/images/page-item-img1.png')}}"/>
                    </div>
                    <div class="lottery-unit lottery-unit-1 page-speed-item">
                        <img src="{{ assetUrlByCdn('/static/activity/LabourDay/images/page-item-img2.png')}}"/>
                    </div>
                    <div class="lottery-unit lottery-unit-2 page-speed-item">
                        <img src="{{ assetUrlByCdn('/static/activity/LabourDay/images/page-item-img3.png')}}"/>
                    </div>
                    <div class="lottery-unit lottery-unit-7 page-speed-item">
                        <img src="{{ assetUrlByCdn('/static/activity/LabourDay/images/page-item-img4.png')}}"/>
                    </div>
@if($lotteryStatus['status'] == false)
                    <div class="page-speed-item" id="lottery-btn-{{$lotteryStatus['data']['type']}}">
                        <a href="javascript:;" ><img src="{{ assetUrlByCdn('/static/activity/springfestival/images/page-dial-btn.png')}}"/></a>
                    </div>
                    @else
                    <div class="page-speed-item" id="lottery-btn" lottery_can_used="{{isset($lotteryStatus['data']['lotteryNumber']) ?$lotteryStatus['data']['lotteryNumber'] :0}}" data-lock="start">
                        <a href="javascript:;" ><img src="{{ assetUrlByCdn('/static/activity/springfestival/images/page-dial-btn.png')}}"/></a>
                    </div>
@endif
                    <div class="lottery-unit lottery-unit-3 page-speed-item">
                        <img src="{{ assetUrlByCdn('/static/activity/LabourDay/images/page-item-img5.png')}}"/>
                    </div>
                    <div class="lottery-unit lottery-unit-6 page-speed-item">
                        <img src="{{ assetUrlByCdn('/static/activity/LabourDay/images/page-item-img6.png')}}"/>
                    </div>
                    <div class="lottery-unit lottery-unit-5 page-speed-item">
                        <img src="{{ assetUrlByCdn('/static/activity/LabourDay/images/page-item-img7.png')}}"/>
                    </div>
                    <div class="lottery-unit lottery-unit-4 page-speed-item">
                        <img src="{{ assetUrlByCdn('/static/activity/LabourDay/images/page-item-img8.png')}}"/>
                    </div>
                </div>
                <p class="page-speed-txt">活动期间单笔投资优选项目≥{{$minInvest}}元，即可获得1次抽奖机会</p>
            </div>



            <div class="page-project page-auto" id="invest_project" >
                <div class="page-project-title"></div>
                <div class="page-project-main">
@if(!empty($projectList))
@foreach($projectList as $key => $project)

                    <div class="page-project-item">
                        <h4 class="title">{{$project['name']}}</h4>
                        <div class="page-project-inner clearfix">
                            <p class="p1"><strong>{{(float)$project['profit_percentage']}}</strong>%<span>年化收益</span></p>
                            <p class="p2"><strong>{{$project['format_invest_time']}}</strong>{{$project['invest_time_unit']}}<span>期限</span></p>
@if($project['status'] == \App\Http\Dbs\Project\ProjectDb::STATUS_INVESTING && \App\Tools\ToolTime::getUnixTime($project['publish_at'],'default') >= time())
                            <a href="/project/detail/{{$project['id']}}" class="page-project-btn">敬请期待</a>
@elseif($project['status'] == \App\Http\Dbs\Project\ProjectDb::STATUS_INVESTING)
                            <a href="/project/detail/{{$project['id']}}" class="page-project-btn">立即投资</a>
@else
                            <a href="/project/detail/{{$project['id']}}" class="page-project-disabled">{{$project['status_note']}}</a>
@endif

                        </div>
                    </div>
@endforeach
@endif
                </div>
            </div>

            <div class="page-rule page-auto">
                <div class="page-dashed">
                    <h4>活动规则</h4>
                </div>
                <p>1、活动时间：{{date('Y年m月d日',$activityTime['start'])}}-{{date('m月d日',$activityTime['end'])}}</p>
                <p>2、活动期间内，{{date('Y年m月d日',$signTime['start'])}}-{{date('m月d日',$signTime['end'])}}坚持连续签到，每天都可领取2元红包。中途漏签，则无法继续签到，签到领取的红包活动期间内可以兑换。
                </p>
                <p>3、活动期间内，单笔投资优选项目（1月期除外)≥{{$minInvest}}元可获得一次抽奖机会，抽奖机会仅限活动期间有效；活动所得奖品以实物形式发放，客服将在2017年6月15日之前，与您沟通联系确定发放奖品。如在6月15日之前联系用户无回应，则视为自动放弃实物奖品；</p>
                <p>4、活动期间内，获奖者提现金额≥10000元，取消其领奖资格；</p>
                <p>5、活动期间如有任何疑问请致电九斗鱼官方客服：400-6686-568，或登录九斗鱼咨询在线客服；</p>
            </div>

<!-- pop 活动规则-->
    <div class="page-layer page-layer-rule"  style="display: none;">
        <div class="page-mask"></div>
        <div class="page-pop">
            {{--title--}}
            <img src="{{ assetUrlByCdn('/static/activity/LabourDay/images/page-pop-title1.png')}}" width="204" height="68" class="pop-title"/>
            {{--content--}}
            <div class="page-content">
                <p><span>1</span>活动时间：{{date('Y年m月d日',$signTime['start'])}}-{{date('Y年m月d日',$signTime['end'])}}</p>
                <p><span>2</span> 中途漏签，则无法继续签到</p>
                <p><span>3</span> 签到领取的红包随时可以兑换</p>
                <p><span>4</span> 红包兑换后则无法继续签到</p>
            </div>
            <a href="javascript:;" data-toggle="mask" data-target="page-layer-rule"><img src="{{ assetUrlByCdn('/static/activity/LabourDay/images/page-pop-btn-know.png')}}" width="164" height="53" class="pop-btn"/></a>
        </div>
    </div>
<!-- end 活动规则-->
@if($exchangeStatus['status'] == true)
    <div class="page-layer page-layer-redpacket" style="display: none;">
        <div class="page-mask"></div>
        <div class="page-pop">
            {{--title--}}
            <img src="{{ assetUrlByCdn('/static/activity/LabourDay/images/page-pop-title2.png')}}" width="213" height="73" class="pop-title"/>
            {{--content--}}
            <div class="page-content">
                <p>我的奖金：<em class="page-pop-em-color">{{$signDayList['exchange']}}</em>元<br>已连续签到{{isset($signDayList['recordList']['sign_num']) ? $signDayList['recordList']['sign_num'] : 0}}天<br><span class="page-pop-em-color">兑换红包后则无法继续签到<br>确认兑换红包？</span></p>
            </div>
            <div class="page-btn-area">
                <input type="button" value="兑换红包" class="pop-btn pop-btn-redpacket" exchange-data-lock="start">
                <input type="button" value="继续签到" class="pop-btn pop-btn-sign">
            </div>
        </div>
    </div>

    {{--成功兑换--}}
    <div class="page-layer page-layer-success" style="display: none;">
        <div class="page-mask"></div>
        <div class="page-pop">
            {{--title--}}
            <img src="{{ assetUrlByCdn('/static/activity/LabourDay/images/page-pop-title3.png')}}" width="213" height="73" class="pop-title"/>
            {{--content--}}
            <div class="page-content">
                <img src="{{ assetUrlByCdn('/static/activity/LabourDay/images/page-pop-face-gold.png')}}" width="134" height="125" class="pop-face"/>
                <p><span class="page-pop-em-color">成功兑换{{$signDayList['exchange']}}元现金红包</span><br>【资产-我的优惠券】中查看使用</p>
            </div>
            <div class="page-btn-area">
                <a href="javascript:;" data-toggle="mask" data-target="page-layer-success"><img src="{{ assetUrlByCdn('/static/activity/LabourDay/images/page-pop-btn-know-red.png')}}" width="167" height="53" class="pop-btn"/></a>
            </div>
        </div>
    </div>
@elseif($exchangeStatus['data']['type'] =='notLogged')
    <div class="page-layer page-layer-login-exchange" style="display: none;">
        <div class="page-mask"></div>
        <div class="page-pop">
            {{--title--}}
            <img src="{{ assetUrlByCdn('/static/activity/LabourDay/images/page-pop-title5.png')}}" width="213" height="73" class="pop-title"/>
            {{--content--}}
            <div class="page-content">
                <img src="{{ assetUrlByCdn('/static/activity/LabourDay/images/page-pop-face-amazed.png')}}" width="123" height="165" class="pop-face"/>
                <p>{{$exchangeStatus['msg']}}~</p>
            </div>
            <div class="page-btn-area">
                <a href="/login" data-toggle="mask" data-target="page-layer-login-exchange"><img src="{{ assetUrlByCdn('/static/activity/LabourDay/images/page-pop-btn-login.png')}}" width="167" height="53" class="pop-btn"/></a>
            </div>
        </div>
    </div>
@else
    <div class="page-layer page-layer-redpacket" style="display: none;">
        <div class="page-mask"></div>
        <div class="page-pop">
            {{--title--}}
            <img src="{{ assetUrlByCdn('/static/activity/LabourDay/images/page-pop-title9.png')}}" width="213" height="73" class="pop-title"/>
            {{--content--}}
            <div class="page-content">
                <img src="{{ assetUrlByCdn('/static/activity/LabourDay/images/page-pop-face-gold.png')}}" width="134" height="125" class="pop-face"/>
                <p>{{$exchangeStatus['msg']}}~</p>
            </div>
            <div class="page-btn-area">
                <a href="javascript:;" data-toggle="mask" data-target="page-layer-redpacket"><img src="{{ assetUrlByCdn('/static/activity/LabourDay/images/page-pop-btn-know-red.png')}}" width="167" height="53" class="pop-btn"/></a>
            </div>
        </div>
    </div>
@endif
@if($signStatus['status'] == true)
    <!-- 签到提示 -->
    <div class="page-layer page-layer-sign" style="display: none; ">
            <div class="page-mask"></div>
            <div class="page-pop">
                {{--title--}}
                <img src="{{ assetUrlByCdn('/static/activity/LabourDay/images/page-pop-title7.png')}}" width="204" height="68" class="pop-title"/>
                {{--content--}}
                <div class="page-content">
                    <p><strong>恭喜你签到成功!</strong><br>已连续签到<span class="page-pop-em-color">{{isset($signDayList['recordList']['sign_num']) ? $signDayList['recordList']['sign_num'] : 0}}</span>天~</p>
                    <ul class="page-sign-wrap">
@if(!empty($signDayList['signDay']))
@foreach($signDayList['signDay'] as $dayKey => $signName)
@if(isset($signDayList['recordList']['sign_record']) && in_array($dayKey,$signDayList['recordList']['sign_record']))
                        <li><span class="page-sign-default page-sign-active" id='{{$dayKey}}'><i></i></span><em class="colorgrey">{{$signName}}</em></li>
@else
                        <li><span class="page-sign-default" id='{{$dayKey}}'></span><em>{{$signName}}</em></li>
@endif
@endforeach
@else
                        <li><span class="page-sign-default"></span><em>先锋奖章</em></li>
                        <li><span class="page-sign-default"></span><em>先进奖章</em></li>
                        <li><span class="page-sign-default"></span><em>模范奖章</em></li>
                        <li><span class="page-sign-default"></span><em>敬业奖章</em></li>
                        <li><span class="page-sign-default"></span><em>劳模奖章</em></li>
                        <li><span class="page-sign-default"></span><em>爱心奖章</em></li>
                        <li><span class="page-sign-default"></span><em>团结奖章</em></li>
@endif
                    </ul>
                </div>
                <div class="page-btn-area">
                    <a href="javascript:;" data-toggle="mask" data-target="page-layer-sign"><img src="{{ assetUrlByCdn('/static/activity/LabourDay/images/page-pop-btn-affirm.png')}}" width="162" height="51" class="pop-btn"/></a>
                </div>
            </div>
        </div>
@elseif($signStatus['data']['type'] =='notLogged')
    {{--登录--}}
    <div class="page-layer page-layer-login" style="display: none;">
        <div class="page-mask"></div>
        <div class="page-pop">
            {{--title--}}
            <img src="{{ assetUrlByCdn('/static/activity/LabourDay/images/page-pop-title5.png')}}" width="213" height="73" class="pop-title"/>
            {{--content--}}
            <div class="page-content">
                <img src="{{ assetUrlByCdn('/static/activity/LabourDay/images/page-pop-face-amazed.png')}}" width="123" height="165" class="pop-face"/>
                <p>{{$signStatus['msg']}}~</p>
            </div>
            <div class="page-btn-area">
                <a href="/login" data-toggle="mask" data-target="page-layer-login"><img src="{{ assetUrlByCdn('/static/activity/LabourDay/images/page-pop-btn-login.png')}}" width="167" height="53" class="pop-btn"/></a>
            </div>
        </div>
    </div>
@endif
@if($lotteryStatus['status'] == true )
    {{--抽奖提示--}}
    <div class="page-layer page-layer-lottery" style="display: none;">
        <div class="page-mask"></div>
        <div class="page-pop">
            {{--title--}}
            <img src="{{ assetUrlByCdn('/static/activity/LabourDay/images/page-pop-title6.png')}}" width="163" height="67" class="pop-title"/>
            {{--content--}}
            <div class="page-content">
                <img src="{{ assetUrlByCdn('/static/activity/LabourDay/images/page-pop-face-smail.png')}}" width="191" height="124" class="pop-face"/>
                <p><strong>恭喜你获得了XXXX奖品</strong><br>还有<span class="page-pop-em-color">{{isset($lotteryStatus['data']['lotteryNumber']) ?$lotteryStatus['data']['lotteryNumber'] :0}}</span>次抽奖机会~</p>
            </div>
            <div class="page-btn-area lottery-return-btn">
                <a href="javascript:;" data-toggle="mask" data-target="page-layer-lottery"><img src="{{ assetUrlByCdn('/static/activity/LabourDay/images/page-pop-btn-know-red.png')}}" width="167" height="53" class="pop-btn"/></a>
            </div>
        </div>
    </div>
@elseif($lotteryStatus['data']['type'] =='notLogged')

    {{--登录提示--}}
    <div class="page-layer page-layer-{{$lotteryStatus['data']['type']}}" style="display: none;">
        <div class="page-mask"></div>
        <div class="page-pop">
            {{--title--}}
            <img src="{{ assetUrlByCdn('/static/activity/LabourDay/images/page-pop-title5.png')}}" width="213" height="73" class="pop-title"/>
            {{--content--}}
            <div class="page-content">
                <img src="{{ assetUrlByCdn('/static/activity/LabourDay/images/page-pop-face-login.png')}}" width="175" height="120" class="pop-face"/>
                <p>{{$lotteryStatus['msg']}}</p>
            </div>
            <div class="page-btn-area ">
                <a href="/login"><img src="{{ assetUrlByCdn('/static/activity/LabourDay/images/page-pop-btn-login.png')}}" width="167" height="53" class="pop-btn"/></a>
            </div>
        </div>
    </div>
@elseif($lotteryStatus['data']['type'] =='notLottery')

    {{--没有抽奖机会 马上投资--}}
    <div class="page-layer page-layer-{{$lotteryStatus['data']['type']}}" style="display: none;">
        <div class="page-mask"></div>
        <div class="page-pop">
            {{--title--}}
            <img src="{{ assetUrlByCdn('/static/activity/LabourDay/images/page-pop-title4.png')}}" width="213" height="73" class="pop-title"/>
            {{--content--}}
            <div class="page-content">
                <img src="{{ assetUrlByCdn('/static/activity/LabourDay/images/page-pop-face-cry.png')}}" width="244" height="137" class="pop-face"/>
                <p><strong>{{$lotteryStatus['msg']}}</strong><br>还有<span class="page-pop-em-color">{{isset($lotteryStatus['data']['lotteryNumber']) ?$lotteryStatus['data']['lotteryNumber'] :0}}</span>次抽奖机会~</p>
            </div>
            <div class="page-btn-area">
                <a href="#invest_project" data-toggle="mask" data-target="page-layer-{{$lotteryStatus['data']['type']}}"><img src="{{ assetUrlByCdn('/static/activity/LabourDay/images/page-pop-btn-invest.png')}}" width="167" height="53" class="pop-btn"/></a>
            </div>
        </div>
    </div>
@elseif($lotteryStatus['data']['type'] =='notInTime' )
    <div class="page-layer page-layer-{{$lotteryStatus['data']['type']}}" style="display: none;">
        <div class="page-mask"></div>
        <div class="page-pop">
            {{--title--}}
            <img src="{{ assetUrlByCdn('/static/activity/LabourDay/images/page-pop-title9.png')}}" width="213" height="73" class="pop-title" />
            {{--content--}}
            <div class="page-content">
                <img src="{{ assetUrlByCdn('/static/activity/LabourDay/images/page-pop-face-smail.png')}}" width="191" height="124" class="pop-face"/>
                <p><strong>{{$lotteryStatus['msg']}}</strong><br></p>
            </div>
            <div class="page-btn-area">
                <a href="javascript:;" data-toggle="mask" data-target="page-layer-{{$lotteryStatus['data']['type']}}"><img src="{{ assetUrlByCdn('/static/activity/LabourDay/images/page-pop-btn-know-red.png')}}" width="167" height="53" class="pop-btn"/></a>
            </div>
        </div>
    </div>
@endif
    <div class="page-layer page-layer-error" style="display: none;">
        <div class="page-mask"></div>
        <div class="page-pop">
            {{--title--}}
            <img src="{{ assetUrlByCdn('/static/activity/LabourDay/images/page-pop-title9.png')}}" width="213" height="73" class="pop-title"/>
            {{--content--}}
            <div class="page-content">
                <img src="{{ assetUrlByCdn('/static/activity/LabourDay/images/page-pop-face-cry.png')}}" width="244" height="137" class="pop-face"/>
                <p><strong>{{$signStatus['msg']}}~</strong></p>
            </div>
            <div class="page-btn-area">
                <a href="javascript:;" onclick='window.location.reload();'data-toggle="mask" data-target="page-layer-error"><img src="{{ assetUrlByCdn('/static/activity/LabourDay/images/page-pop-btn-know-red.png')}}" width="167" height="53" class="pop-btn"/></a>
            </div>
        </div>
    </div>
@endsection

@section('jspage')
<script type="text/javascript">
@if($lotteryStatus['status'] == true )
    var lottery={
                index:-1,	//当前转动到哪个位置，起点位置
                count:0,	//总共有多少个位置
                timer:0,	//setTimeout的ID，用clearTimeout清除
                speed:20,	//初始转动速度
                times:0,	//转动次数
                cycle:50,	//转动基本次数：即至少需要转动多少次再进入抽奖环节
                prize:-1,	//中奖位置
                init:function(id){
                    if ($("#"+id).find(".lottery-unit").length>0) {
                        $lottery = $("#"+id);
                        $units = $lottery.find(".lottery-unit");
                        this.obj = $lottery;
                        this.count = $units.length;
                        $lottery.find(".lottery-unit-"+this.index).addClass("active");
                    };
                },
                roll:function(){
                    var index = this.index;
                    var count = this.count;
                    var lottery = this.obj;
                    $(lottery).find(".lottery-unit-"+index).removeClass("active");
                    index += 1;
                    if (index>count-1) {
                        index = 0;
                    };
                    $(lottery).find(".lottery-unit-"+index).addClass("active");
                    this.index=index;
                    return false;
                },
                stop:function(index){
                    this.prize=index;
                    return false;
                },
                lottery:function (prizeName,prizeType,activeIndex,prizeNumber) {
                    //记录奖品的数据
                    $("#lottery-btn").attr("lottery_array_string",prizeName+"_"+prizeType+"_"+activeIndex).attr('lottery_can_used',prizeNumber-1)
                },
                lotteryShow:function (prizeName,prizeType) {

                    var prizeNumber     =   $("#lottery-btn").attr('lottery_can_used');
                    $(".page-pop-em-color").empty().html(prizeNumber);
                    if(prizeType == 5){
                        $(".page-layer-error").show();
                        $(".page-content p strong").html(prizeName)

                    }else{
                        $(".page-layer-lottery").show();
                        $(".page-content p strong").html("恭喜您获得了"+prizeName+"奖品");
                    }
                }

            };

    function roll(){
        lottery.times += 1;
        lottery.roll();
        var lotteryInfo =   $("#lottery-btn").attr('lottery_array_string');
        var prizeMsg    =   lotteryInfo.split("_");
        var prizeIndex  =   prizeMsg[2];

        if (lottery.times > lottery.cycle+10 && prizeIndex==lottery.index) {
            //这里添加奖品的信息
            lottery.lotteryShow(prizeMsg[0],prizeMsg[1]);
            clearTimeout(lottery.timer);
            lottery.prize=-1;
            lottery.times=0;
            click=false;
        }else{
            if (lottery.times<lottery.cycle) {
                lottery.speed -= 10;
            }else if(lottery.times==lottery.cycle) {
                var index = Math.random()*(lottery.count)|0;
                lottery.prize = index;
            }else{
                if (lottery.times > lottery.cycle+10 && ((lottery.prize==0 && lottery.index==7) || lottery.prize==lottery.index+1)) {
                    lottery.speed += 110;
                }else{
                    lottery.speed += 20;
                }
            }
            if (lottery.speed<40) {
                lottery.speed=40;
            };
            //console.log(lottery.times+'^^^^^^'+lottery.speed+'^^^^^^^'+lottery.prize);
            lottery.timer = setTimeout(roll,lottery.speed);
        }
        return false;
    }

    var click=false;

    window.onload=function(){
        lottery.init('lottery');
        $("#lottery-btn").click(function(){

            var lock    =   $(this).attr('data-lock');
            if( lock == 'stop'){
                return false;
            }
            $(this).attr('data-lock','stop');
            if (click) {
                return false;
            }else{
                lottery.speed=100;
                $.ajax({
                    url      :"/activity/LabourDay/lottery",
                    dataType :'json',
                    type     :'post',
                    data     : { _token:'{{csrf_token()}}'},
                    success : function(json) {

                        if(json.status == true){
                            //添加奖品信息
                            var prizeName   =   json.data.name;
                            var prizeType   =   json.data.type;
                            var activeIndex =   json.data.order_num;
                            var prizeUseNum =   $('#lottery-btn').attr('lottery_can_used');
                            lottery.lottery(prizeName,prizeType,activeIndex,prizeUseNum);
                            roll();
                            click=true;
                            return false;
                        }else{
                            $(".page-layer-error").show();
                            if(json.data.type){
                                $(".page-content p").html(json.msg);
                            }else{
                                $(".page-content p strong").html(json.msg);
                            }
                            click=true;
                            return false;
                        }
                        $(this).attr('data-lock','start');
                    },
                    error : function(msg) {
                        $(".page-layer-error").show();
                        $(".page-content p").html('抽奖失败，请稍候再试!');
                        $(this).attr('data-lock','start');
                    }
                });
            }
        });
        $(".lottery-return-btn a").click(function () {
            window.location.reload();
        })
    };
    @else
        $(document).on("click", '#lottery-btn-{{$lotteryStatus['data']['type']}}',function(){
        $('.page-layer-{{$lotteryStatus['data']['type']}}').show();
    })
    @endif

    @if($signStatus['status'] == true)
    $(document).on("click", '.sign-btn-sure',function(){
        var lock    =   $(this).attr('sign-data-lock');

        if( lock != 'start' ){
            return false
        }
        $(this).attr('sign-data-lock','stop');

        $.ajax({
            url      :"/activity/LabourDay/signIn",
            dataType :'json',
            type     :'post',
            data     : { _token:'{{csrf_token()}}'},
            success : function(json) {

                if(json.status == true){
                    //
                    $("#"+json.data.new_sign_day).addClass('page-sign-active').html('<i></i>');
                    $(".page-layer-sign .page-pop-em-color").html(json.data.sign_num);
                    $(".page-layer-sign").show();

                }else{
                    $(".page-layer-error").show();
                    $(".page-layer-error").find("p").html(json.msg);

                }
                $(".sign-btn-sure").attr('sign-data-lock','start');
            },
            error : function(msg) {
                $(".page-layer-error").show();
                $(".page-layer-error").find("p").html('签到失败,请稍后重试！');
                $(".sign-btn-sure").attr('sign-data-lock','start');
            }
        });



    })
    $(".page-layer-sign a").click(function () {
        window.location.reload();
    })
    @endif

    @if($exchangeStatus['status'] == true)

    $(document).on("click", '.pop-btn-redpacket',function(){
        var lock    =   $(this).attr('exchange-data-lock');

        if( lock != 'start' ){
            return false
        }
        $(this).attr('exchange-data-lock','stop');

        $.ajax({
            url      :"/activity/LabourDay/exchange",
            dataType :'json',
            type     :'post',
            data     : { _token:'{{csrf_token()}}'},
            success : function(json) {
                if(json.status == true){
                    $(".page-layer-success").show();
                }else{
                    $(".page-layer-error").show();
                    $(".page-layer-error").find("p").html(json.msg);
                }
                $('.pop-btn-redpacket').attr('exchange-data-lock','start');
            },
            error : function(msg) {
                $(".page-layer-error").show();
                $(".page-layer-error").find("p").html('兑换失败,请稍后重试！');
                $(".pop-btn-redpacket").attr('exchange-data-lock','start');
            }
        });
    })

    @endif
    $(".page-layer-sign a").click(function () {
        window.location.reload();
    })
    $(document).on("click", '.exchange-btn-sure',function(){
        $('.page-layer-redpacket').show();
    })
    $(document).on("click", '.page-layer-redpacket input',function(){
        $('.page-layer-redpacket').hide();
    })
    $(document).on("click", '.sign-btn-login',function(){
        $('.page-layer-login').show();
    })
    $(document).on("click", '.sign-btn-login-exchange',function(){
        $('.page-layer-login-exchange').show();
    })

    $(document).on("click", '.sign-btn-error',function(){
        $('.page-layer-error').show();
    })
    // 显示弹窗
    $(document).on("click", '[data-layer]',function(event){
        event.stopPropagation();
        var $this = $(this);
        var target = $this.attr("data-layer");
        var $target = $("."+target);
        $target.show();
    })

</script>
@endsection
