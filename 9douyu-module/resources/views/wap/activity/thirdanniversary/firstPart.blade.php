@extends('wap.common.activity')

@section('title', '鱼你前行 耀我新生')

@section('keywords', env('META_KEYWORD'))

@section('description', env('META_DESCRIPTION'))

@section('css')
    <meta name="format-detection" content="telephone=yes">
    <link rel="stylesheet" type="text/css" href="{{ assetUrlByCdn('/static/weixin/activity/thirdanniversary/css/one/index.css') }}">
    <script type="text/javascript" src="{{ assetUrlByCdn('/static/angular/angular.min.js')}}"></script>
    <script type="text/javascript" src="{{ assetUrlByCdn('/static/angular/angular-route.js')}}"></script>
    <script type="text/javascript" src="{{ assetUrlByCdn('/static/angular/angular-animate.min.js')}}"></script>
    <script type="text/javascript" src="{{ assetUrlByCdn('/static/weixin/activity/thirdanniversary/js/anniversary.one.js')}}"></script>
    <script type="text/javascript" src="{{ assetUrlByCdn('/static/weixin/activity/thirdanniversary/js/lottery.one.js')}}"></script>
@endsection

@section('content')
<div class="page-bg">
    <input type="hidden" name="_token"  value="{{csrf_token()}}">
    <!--顶部总数统计- -->
    @include('wap.activity.thirdanniversary.template.summation')
    <div class="page-center page-time">
        <p>好机会不常来呦，100%中奖，放肆投！</p>
        <p>{{date("Y年m月d日",$activityTime['start'])}}-{{date("m月d日",$activityTime['end'])}}</p>
    </div>

    <ul class="page-auto page-flex page-intro-bar">
        <li>优选项目</li>
        <li>任性抽奖</li>
        <li>豪礼抱回家</li>
    </ul>

    <div class="page-auto page-center page-login-tips" ng-controller="userCtrl">
        <div class="without-login-status"  ng-if="userStatus == false" >
            <p class="page-login-font1">还没账号？<a href="javascript:;" class="userLogin">马上注册</a></p>
            <a href="javascript:;" class="page-login-btn userLogin">立即登录</a>
            <p class="page-login-font2">请登录查看您的等级，不同等级对应的奖池是不一样哒！</p>
        </div>

        <div class="login-status" ng-if="userStatus == true && account >= min_invest" ng-style="setBlock(userStatus)">
            <p>截止{{date('Y年m月d日',min($activityTime['end'],max($activityTime['start'],time())))}}您在活动期间累计充值金额</p>
            <p>共计<span ng-bind="account |number:2">  </span>元</p>
            <p>开启了“<span ng-bind="levelNote">L3</span>”奖池</p>
            <p>单笔投资满足对应奖池的起投金额，即可获得一次抽奖机会</p>
            <a href="#invest-page" class="page-invest-btn">马上投资</a>
        </div>
        <div class="login-status" ng-if="userStatus == true && account < min_invest " ng-style="setBlock(userStatus)">
            <p>截止{{date('Y年m月d日',min($activityTime['end'],max($activityTime['start'],time())))}}您在活动期间累计充值金额</p>
            <p>共计<span ng-bind="account |number:2">  </span>元</p>
            <p>活动期间累计充值到<span ng-bind="grade_money"></span>可升级到L1奖池</p>
            <p>单笔投资<span ng-bind="grade.min_invest"></span>元，好礼升级，可参与“<span ng-bind="grade.grade_name"></span>”奖池投资抽奖呦</p>
            <a href="#invest-page" class="page-invest-btn">马上投资</a>
        </div>
    </div>

    <h6 class="page-lottery-bt"></h6>
    <!--加载抽奖模块-->
    @include('wap.activity.thirdanniversary.template.lottery')

    <div class="page-project-bt" id='invest-page'></div>
    <!--加载项目-->
    @include('wap.activity.thirdanniversary.template.project')

    <!--加载伴手礼记录-->
      <!-- random -->
    <div class="page-surprise">
        <h6></h6>
        <p class="page-center">每日在优选项目中，随机抽选3名投资者，获得九斗鱼三周年伴手礼一份</p>
        <img src="{{ assetUrlByCdn('/static/weixin/activity/thirdanniversary/images/one/page-surprise.png') }}" class="page-surprise-gift" alt="伴手礼">
    </div>
    @include('wap.activity.thirdanniversary.template.record')

      <div class="page-auto page-rule">
          <h6>活动规则：</h6>
          <p><span>1.</span>活动时间：{{date("Y年m月d日",$activityTime['start'])}}-{{date("m月d日",$activityTime['end'])}}</p>
          <p><span>2.</span>活动期间内根据用户新充值金额累计投资金额开启对应的活动奖池；活动期间内单笔投资优选项目满足对应奖池的起投金额，即可在对应奖池抽奖一次，100%中奖<br>
              L1奖池:20000≤新充值金额﹤50000且单笔投资金额≥2万元,<br>
              L2奖池:50000≤新充值金额﹤100000且单笔投资金额≥5万元，<br>
              L3奖池:100000≤新充值金额﹤150000且单笔投资金额≥8万元，<br>
              L4奖池:150000≤新充值金额﹤200000且单笔投资金额≥10万元，</p>
          <p><span>3.</span>活动期间内，每个用户参与每个奖池的抽奖机会最高为5次；</p>
          <p><span>4.</span>参与抽奖的有效金额不包含使用红包和加息券的额度；</p>
          <p><span>5.</span>参与领取奖品者，活动期间提现金额≥10000元，则取消其领奖资格；</p>
          <p><span>6.</span>活动期间所得奖品均以实物发放，客服会在7月30日之前联系用户确定收货地址，如7月30日之前联系未果，则视为自动放弃奖品；</p>
          <p><span>7.</span>每日在优选项目投资用户中，抽取3名用户，获得周年庆伴手礼，获奖名单将于第二个工作日12点公布；</p>
          <p><span>8.</span>优选项目即为：九省心1月期、3月期、6月期、12月期及九安心项目；</p>
          <p><span>9.</span>活动期间如有任何疑问请致电九斗鱼官方客服：400-6686-568，或登录九斗鱼官网咨询在线客服；</p>
          <p><span>10.</span>网贷有风险、投资需谨慎。</p>
      </div>


      <!-- alert window -->
      <div class="page-layer3 anniversary-layer1" style="display: none;">
          <div class="page-mask" data-toggle="mask" data-target="page-layer3"></div>
          <div class="page-pop">
            <span class="page-pop-close" data-toggle="mask" data-target="page-layer3"></span>
            <div class="page-pop-content">
              <p>恭喜你获得了</p>
              <div class="page-pop-light">
                <img src="{{ assetUrlByCdn('/static/weixin/activity/thirdanniversary/images/one/page-gift1.png') }}" class="" alt="获奖">
              </div>
              <span class="page-pop-winner">苏泊尔4L球釜内胆电饭煲</span>
              <span class="page-pop-winner">还有2次抽奖机会哦！</span>
              <a href="javascript:;" data-toggle="mask" data-target="page-layer3" class="page-pop-btn">朕知道了</a>
            </div>
          </div>
      </div>

</div>

@endsection

@section('footer')

@endsection

@section('jsScript')
<script type="text/javascript">
    //var client = getCookie('JDY_CLIENT_COOKIES');
    //if( client == '' || !client ){
     //   var client  =   '{{$client or "wap"}}';
    //}
    // 文字滚动
    function AutoScroll(obj) {
        $(obj).find("ul:first").animate({
            marginTop: "-1.275rem"
        }, 500, function() {
            $(this).css({ marginTop: "0px" }).find("li:first").appendTo(this);
        });
    }

    $(document).ready(function() {
        var myar = setInterval('AutoScroll("#scrollDiv")', 2000);
         $("#scrollDiv").hover(function() { clearInterval(myar); }, function() { myar = setInterval('AutoScroll("#scrollDiv")', 2000) });
    });

    var click=false;

    function doLotteryEvent(id) {
        var lotteryLevel    =   'lottery' +  id;

        lottery.init(lotteryLevel);
        if (click) {//click控制一次抽奖过程中不能重复点击抽奖按钮，后面的点击不响应
            return false;
        }

        lottery.speed=100;

        lotteryEvent.doLottery(id);

    }

    $(document).delegate(".investClick",'click',function () {
        var  projectId  =   $(this).attr("attr-data-id");
        if( !projectId ||projectId==0){
            return false;
        }
        var act_token   =   '{{$actToken}}_' + projectId;
        if( client =='ios'){
            if( version && version <'4.1.0'){
                window.location.href="objc:certificationOrInvestment("+projectId+",1)";
                return false;
            }
            if(!version || version >='4.1.0') {
                window.location.href="objc:toProjectDetail("+projectId+",1,"+act_token+")";
                return false;
            }
        }
        if (client =='android'){
            if( version <'4.1.0' ) {
                window.jiudouyu.fromNoviceActivity(projectId,1);
                return false;
            }
            if( version >='4.1.0' ) {
                window.jiudouyu.fromNoviceActivity(projectId,1,act_token);
                return false;
            }
        }

        if( act_token ) {
            var _token = $("input[name='_token']").val();
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
        }
        window.location.href='/project/detail/' + projectId;
        return false
        //investProjectByClient( client , projectId , version ,act_token );
    })
    $(document).delegate(".userLogin",'click',function () {
        userLoginByClient( client )
    })
    $(document).delegate(".investBtn",'click',function () {
        $(".anniversary-layer1").hide();
    })
  // pop
  var evclick = "ontouchend" in window ? "touchend" : "click";
  // 显示弹窗
  $(document).on("click", '[data-layer]',function(event){
      event.stopPropagation();
      var $this = $(this);
      var target = $this.attr("data-layer");
      var $target = $("."+target);
      $target.show();
  })

  $(document).on(evclick, '[data-toggle="mask"]', function (event) {
        event.stopPropagation();
        var target = $(this).attr("data-target");
        var $target = $("."+target);
        $target.hide();
        window.location.reload();
   })

</script>
@endsection
