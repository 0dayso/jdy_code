@extends('wap.common.wapBase')

@section('title', '双蛋嘉年华 , 一次赚个够')

@section('keywords', env('META_KEYWORD'))

@section('description', env('META_DESCRIPTION'))

@section('css')
<meta name="format-detection" content="telephone=yes">
<link rel="stylesheet" type="text/css" href="{{ assetUrlByCdn('/static/weixin/activity/festival/css/doubleEgg.css') }}">
<link rel="stylesheet" type="text/css" href="{{ assetUrlByCdn('/static/weixin/activity/festival/css/jquery.min.js') }}">

@endsection

@section('content')

<div class="page_rain" style="display:none;">
	<div class="db-time"></div>
	<div class="div bg_1"></div>
		 <!-- 抽奖失败 -->
    <div class="kill-pop1 kill-pop1-2" id="not-lottery-thing" style="display: none;">
      <!-- 没有点击到红包 -->
          <h4 class="mt1">很抱歉</h4>
          <p>还差一点点哦～明天继续努力</p>
          <a href="#" class="db-btn-2 db-btn-1 btn-default-1">确定</a>
    </div>

    <!-- 实物 -->
    <div class="kill-pop1 kill-pop1-2" id="lottery-thing-kind" style="display: none;">
        <h4>恭喜你获得了</h4>
        <dl class="db-prize">
            <dt><img src="{{assetUrlByCdn('/static/weixin/activity/festival/images/prize-2.png')}}" id="lottery_img"></dt>
            <dd id="lottery_name">九斗鱼充电宝<!-- 九斗鱼特百惠水杯 --></dd>
        </dl>
        <a href="#" class="db-btn-2 db-btn-1 btn-default-1">确定</a>
    </div>

    <!-- 红包 -->
    <div class="kill-pop1 kill-pop1-3" id="lottery-thing-bonus" style="display: none;">
        <h4>恭喜你获得了</h4>
        <div class="db-coupon">
             <dl>
                <dt>￥<span>10</span><em class="db-line3"></em></dt>
                <dd>
                    <p>满3000元可用</p>
                    <p>投资九省心</p>
                    <p>及九安心项目</p>
                </dd>
            </dl>
        </div>
        <a href="#" class="db-btn-2 db-btn-1 btn-default-1">确定</a>
    </div>
</div>
<div class="db-banner" lottery-status="open">
	<p>{{date("Y.m.d",$activityTime['start'])}}-{{date("Y.m.d",$activityTime['end'])}}</p>
</div>
<div class="db-box-1">
@if($userStatus ==false)
    <a href="javascript:;" class="db-a disable" id="user_not_login"></a>
@elseif($lotteryStatus['status'] ==false)
    <a href="javascript:;" class="db-a disable" id="user_cannot_lottery"></a>
@else
    <a href="javascript:;" class="db-a" id="user_can_lottery"></a>
@endif
	<!-- 灰色按钮<a href="#" class="db-a disable"></a> -->
</div>
<section class="db-mg1">
	<div class="db-title db-title1"><span>嘉</span><span>年</span><span>华</span><span>专</span><span>享</span></div>
	@if(!empty($projectInfo))
		@foreach($projectInfo as $key => $project )
	<div class="db-box db-box4">
		<p class="antwo-sum">{{$project['product_line_note']}}  •  {{$project['format_invest_time']}}{{$project['invest_time_unit']}}</p>
		<table class="first-table">
			<tr>
				<td width="35%"><span>{{(float)$project['profit_percentage']}}</span>％ <!-- <em>+</em><span>2</span>% --></td>
				<td width="32%">{{$project['left_amount']}}元</td>
				<td rowspan="2">
					@if($project['status'] == \App\Http\Dbs\Project\ProjectDb::STATUS_INVESTING && \App\Tools\ToolTime::getUnixTime($project['publish_at'],'default') >= time())
						<a href="javascript:;" class="first-btn disable" attr-project-id="{{$project['id']}}">待售</a>
					@elseif($project['status'] == \App\Http\Dbs\Project\ProjectDb::STATUS_INVESTING)
						<a href="javascript:;" class="first-btn" attr-project-id="{{$project['id']}}">投资</a>
					@else
						<a href="javascript:;" class="first-btn disable" attr-project-id="{{$project['id']}}">{{$project['status_note']}}</a>
					@endif
				</td>
			</tr>
			<tr>
				<td>借款利率</td>
				<td>剩余可投</td>
			</tr>
		</table>
	</div>
		@endforeach
	@endif
</section>

<div class="db-box5">
	<div class="db-title db-title1 db-title2"><span>嘉</span><span>年</span><span>华</span><span>惊</span><span>喜</span></div>
	<h4>今日奖品</h4>
	<dl>
		<dt>
			<img src="{{assetUrlByCdn('/static/weixin/activity/festival/images/img-'.$lotteryInfo['img'].'.png')}}">
		</dt>
		<dd>
			<p>
				<strong> {{$lotteryInfo['lottery']}}</strong>
			</p>
			@if($activityTime['start'] > time())
				<p>暂未开奖</br>敬请期待</p>
			@elseif($lotteryInfo['winner'] == "none")
				<p>{{$lotteryInfo['date']}}未开奖!!</br>敬请期待</p>
			@else
				<p>{{$lotteryInfo['date']}}中奖者</br>{{$lotteryInfo['winner']}}</p>
			@endif
		</dd>
	</dl>

	<p class="db-ins">投资定期项目，每日随机抽选一名惊喜奖</p>
</div>

<div class="db-box5">
    <div class="db-title db-title1 db-title2 db-title3"><span>中</span><span>奖</span><span>记</span><span>录</span></div>
    @if(empty($lotteryList))
        <table class="db-content">
            <tbody>
            <tr>
                <td>暂无开奖数据</td>
            </tr>
            </tbody>
        </table>

    @else
        <table class="db-content">
            <thead>
            <tr>
                <td>日期</td>
                <td>奖品</td>
                <td>中奖者</td>
            </tr>
            </thead>
            <tbody>
            @foreach($lotteryList as $key => $info )
            <tr>
                <td>{{$info['date']}}</td>
                <td>{{$info['lottery']}}</td>
                <td>{{$info['winner']}}</td>
            </tr>
            @endforeach
            </tbody>
        </table>
    @endif

</div>

<div class="kill-tip"><span></span><a href="javascript:;">查看活动规则</a></div>

<!-- 弹窗 -->
    <div class="kill-pop-wrap">
        <div class="mask3"></div>
        <div class="kill-pop" id="kill-pop">
            <i></i>
            <h3>活动规则</h3>
            <p>1、活动时间：{{date("Y年m月d日",$activityTime['start'])}}-{{date("Y年m月d日",$activityTime['end'])}}；</p>
            <p>2、活动期间内，每个九斗鱼ID每日有一次机会参加红包雨活动；</p>
            <p>3、活动期间内，每日会在当日投资嘉年华专项定期项目的投资者中随机抽选一名，获得当日的惊喜奖；当天中奖信息将会在下一个工作日11点公布；</p>
            <p>4、活动期间内，获奖者提现金额≥10000元，取消其领奖资格；</p>
            <p>5、活动所得奖品以实物形式发放，客服将在2017年1月13日之前，与您沟通联系确定发放奖品。如在1月20日之前联系用户无回应，则视为自动放弃实物奖品；</p>
            <p>6、活动期间如有任何疑问请致电九斗鱼官方客服：400-6686-568，或登录九斗鱼咨询在线客服；</p>
        </div>
    </div>
    @if($userStatus ==false)
        <!-- 弹窗红包奖励1,未登录-->
        <div class="kill-pop-wrap-1">
            <div class="mask3"></div>
            <div class="kill-pop1">
                <h4 class="mt">您还没有登录哦</h4>
                <a href="javascript:;" class="db-btn-1" id="userLogin">登录</a>
            </div>
        </div>
    @else
        <!-- 图片倒计时 -->
    <div class="kill-pop-wrap1">
        <div class="mask3"></div>
        <!-- 登录后状态 -->
        <div class="kill-time">
            <img src="{{assetUrlByCdn('/static/weixin/activity/festival/images/time3.png')}}">
        </div>
    </div>

    @endif
@if($lotteryStatus['status'] != true && $userStatus !=false)
    <div class="kill-pop-wrap-1">
        <div class="mask3"></div>
        <div class="kill-pop1">
            <h4 class="mt">暂无抽奖的机会!<br>谢谢参与!</h4>
            <a href="javascript:;" class="db-btn-1"  >确定</a>
        </div>
    </div>
@endif
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

@section('jsScript')
<script type="text/javascript">
	$(".kill-tip a").click(function(){
        $(".kill-pop-wrap").show();
        var h = $("#kill-pop").outerHeight();
        var mt = parseInt(-h/2) + 'px';
        $("#kill-pop").css("margin-top",mt);
    });
    $(".kill-pop i,.mask3,.db-btn-1").click(function(){
        $(".kill-pop-wrap").hide();
        $(".kill-pop-wrap-1").hide();
    });
    $(".btn-default-1").click(function(){
        $(".kill-pop-wrap").hide();
        window.location.reload();
    });
    @if( $userStatus ==false)
    $("#user_not_login").click(function () {
    // 未登录状态
        $(".kill-pop-wrap-1").show();
    })
    $("#userLogin").click(function () {
        var  client     =   "{{ $client }}"
        var  version    =   "{{$version}}"
        if( client =='ios' && version == true){
            window.location.href = "objc:gotoLogin";;
            return false;
        }
        if (client =='android'){
            window.jiudouyu.login()
            return false;
        }
        window.location.href='/login';
    })
    @endif
    $("#user_cannot_lottery").click(function () {

        $(".kill-pop-wrap-1").show();
        return false
    })
	$('.first-btn').click(function () {

		var  client     =   "{{ $client }}"
        var  version    =   "{{$version}}"
		var  projectId  =   $(this).attr("attr-project-id");
		if( !projectId ||projectId==0){
			return false;
		}
		if( client =='ios' && version == true){
			window.location.href="objc:toProjectDetail("+projectId+",1)";
			return false;
		}
		if (client =='android'){
			window.jiudouyu.fromNoviceActivity(projectId,1);
			return false;
		}
		window.location.href='/project/detail/'+projectId;

		return false

	})
	
</script>
@if($userStatus ==true && $lotteryStatus['status'] == true)
    <script type="text/javascript">
        var time3="";

        // 点击捞金
        $("#user_can_lottery").click(function(e){
            e.preventDefault();
            // 红包雨倒计时
            $(".kill-pop-wrap1").show();
            var time_2  =   setInterval(count1,1000);
            var time2   =3;
            function count1(){
                time2--;
                $(".kill-time img").attr("src","/static/weixin/activity/festival/images/time"+time2+".png");
                if(time2<0){
                    $(".kill-pop-wrap1").hide();
                    $(".page_rain").show();
                    $(".kill-time img").attr("src","/static/weixin/activity/festival/images/time3.png");
                    time3=setInterval(count,1000);
                    clearInterval(time_2);
                }
            }
        });

        var num=8;
        function count() {
            num--;
            $(".db-time").html(num);
            $(document).on('touchstart', '.bonus_id', function(){
                $(this).css("background-position","0 -100px");
                a++;
            });

            if(num<=1){
                clearInterval(Timerr,20);
            }
            if(num<=0){
                $(".div").removeClass("bg_1");
            }

            if(num<0 ){
                clearInterval(time3);
                $(".db-time").html("");
                isDoLotteryThing(a);
            }
        }

        // 确定按钮
        $(".db-btn-2").click(function(){
            $(".page_rain").hide();
            $(".kill-time img").attr("src","/static/weixin/activity/festival/images/time3.png");

        });
        var a =0;
        var Timerr = setInterval(aa,450); //数量速度
        var removepackage = setInterval(function(){

            for(var jj=0;jj<$('.div>div').size()/4;jj++){

                $('.div>div').eq($('.div>div').size()-jj).remove();
            }
        },1200)
        function aa(){
            for(var i=0;i<3;i++){
                var j=parseInt(Math.random()*600+000); //红包起始右侧位置
                var j1=parseInt(Math.random()*100+300); //
                var n=parseInt(Math.random()*20+(-100)); //红包起始顶部位置
                $('.div').prepend('<div class="bonus_id"></div>');
                $('.div').children('div').eq(0).css({'right':j,'top':n});
                $('.div').children('div').eq(0).animate({'right':j-j1,'top':$(window).height()+200},3000);
            }
        }

        /**
         *
         * @param touchNumber
         * @desc 是否进行红包雨的点击
         */
        function isDoLotteryThing( touchNumber ) {

            if( touchNumber <= 0 || !touchNumber ){
                $("#not-lottery-thing").show();
                $("#not-lottery-thing p").html("一阵红雨飘过<br>客官却无动于衷!大气!")
                return false
            }else{

                doLuckDraw()
            }

            //$(".kill-pop1-2").show();
        }
        /**
         * @desc 执行红包雨对应的奖品数据
         */
        function doLuckDraw() {

            var lock    =   $(".db-banner").attr("lottery-status");
            if( lock == 'closed'){
                return false;
            }
            var token   =   "{{$token}}";
            var client  =   "{{$client}}";

            $(".db-banner").attr("lottery-status",'opened');

            $.ajax({
                url      :"/activity/festival/doLottery",
                dataType :'json',
                {{--@if($token && $client ==\App\Http\Logics\RequestSourceLogic::SOURCE_ANDROID)--}}
                data: { from:'app',token: token,client:client,_token:'{{csrf_token()}}'},
                {{--@endif--}}
                type     :'get',
                success : function(json){

                    if( json.status==true){

                        if(json.data.type == 1 ){
                            var bonus   =   '￥<span>'+json.bonus.money+'</span><em class="db-line3"></em>'
                            $("#lottery-thing-bonus dt").html(bonus)
                            $("#lottery-thing-bonus dd").html(json.bonus.use_desc)
                            $("#lottery-thing-bonus").show();
                            $(".db-banner").attr("lock-status",'opened');
                            return false;
                        }
                        if(json.data.type == 2 ||json.data.type == 4){
                            var bonus   =   '<span>'+json.bonus.rate+'</span>%<em class="db-line3"></em>'
                            $("#lottery-thing-bonus dt").html(bonus)
                            $("#lottery-thing-bonus dd").html(json.bonus.use_desc)
                            $("#lottery-thing-bonus").show();
                            $(".db-banner").attr("lock-status",'opened');
                            return false;
                        }

                        if( json.data.type == 3 ){
                            var lotteryImg  =   '/static/weixin/activity/festival/images/prize-'+json.data.order_num+'.png';
                            var lotteryName =   json.data.name;
                            $("#lottery_img").attr("src",lotteryImg)
                            $("#lottery_name").html(lotteryName)
                            $("#lottery-thing-kind").show();
                            $(".db-banner").attr("lock-status",'opened');
                            return false;
                        }
                        $(".db-banner").attr("lock-status",'opened');
                        return false;
                    }

                    if( json.status == false || json.code ==500 ){
                        $("#not-lottery-thing p").html(json.msg)
                        $("#not-lottery-thing").show();
                        $(".db-banner").attr("lock-status",'opened');
                        return false;
                    }
                },
                error : function(msg) {
                    $("#not-lottery-thing p").html(json.msg)
                    $("#not-lottery-thing").show();
                    $(".db-banner").attr("lock-status",'opened');
                    return false;
                }
            })

        }
    </script>
@endif

@endsection