@extends('wap.common.activity')

@section('title', '立秋至收获时')

@section('keywords', env('META_KEYWORD'))

@section('description', env('META_DESCRIPTION'))

@section('css')
    <meta name="format-detection" content="telephone=yes">
    <link rel="stylesheet" type="text/css" href="{{ assetUrlByCdn('/static/weixin/activity/autumn/css/index.css') }}">
    <script type="text/javascript" src="{{ assetUrlByCdn('/static/angular/angular.min.js')}}"></script>
    <script type="text/javascript" src="{{ assetUrlByCdn('/static/angular/angular-route.js')}}"></script>
    <script type="text/javascript" src="{{ assetUrlByCdn('/static/angular/angular-animate.min.js')}}"></script>
    <script type="text/javascript" src="{{ assetUrlByCdn('/static/weixin/activity/autumn/js/autumn.js')}}"></script>
    <script type="text/javascript" src="{{ assetUrlByCdn('/static/weixin/activity/autumn/js/lottery.js')}}"></script>
@endsection

@section('content')
<div class="page-bg">
   <p class="time">{{date('Y年m月d日',$activityTime['start'])}}-{{date('m月d日',$activityTime['end'])}}</p>
   <div class="page-banner-text">温馨提示：投资抽奖活动仅限APP 4.1.0 之后的版本参加，请参与活动前及时更新升级</div>
    <!--加载抽奖模块-->
    @include('wap.activity.autumn.template.lottery')
    <!--加载项目-->
    <div class="project-1" id="invest-page">
    <div class="page-project-bt">投资尊享</div>
     @include('wap.activity.autumn.template.project')
    </div>
      <div class="page-rule">
          <h6>活动规则：</h6>
          <p>1、活动时间：{{date('Y年m月d日',$activityTime['start'])}}-{{date('m月d日',$activityTime['end'])}}</p>
          <p>2、在活动期间内，仅活动页面进行项目投资才有资格获得活动抽奖机会；</p>
          <p>3、活动期间内，投资3月期或者6月期项目，满足不同奖池的抽奖条件，即可获得一次抽奖机会；<br/>
              <span>• 5万元≤单笔投资额＜10万元，可获得一次初秋奖池抽奖机会；</span><br/>
              <span>• 单笔投资额≥10万元，可获得一次深秋奖池抽奖机会；</span><br/>
              <span>举例：如单笔投资20万元3月期或6月期，即可获得一次深秋奖池的抽奖机会；</span>
          </p>
          <p>4、投资所获得的抽奖机会仅限活动期间有效；抽奖所得奖品均以实物形式发放，客服将在2017年9月30日之前，与您沟通联系确定发放奖品。如在9月30日之前联系用户无回应，则视为自动放弃实物奖品；</p>
          <p>5、1%定期加息券，起投金额30000元，仅限投资3、6、12月期及九安心项目；1.5%定期加息券，起投金额50000元，仅限投资3、6、12月期及九安心项目；加息券自发放之日起，有效期15天；</p>
          <p>6、活动期间内，获奖者提现金额≥10000元，取消其领奖资格；</p>
          <p>7、活动期间如有任何疑问请致电九斗鱼官方客服：400-6686-568，或登录九斗鱼咨询在线客服；</p>
          <p>8、本活动最终解释权归九斗鱼所有；</p>
      </div>


      <!-- alert window -->
      <div class="page-layer3 anniversary-layer1" style="display: none;">
          <div class="page-mask" data-toggle="mask" data-target="page-layer3"></div>
          <div class="page-pop">
            <span class="page-pop-close" data-toggle="mask" data-target="page-layer3"></span>
            <div class="page-pop-content">
              <p>恭喜你获得了</p>
              <div class="page-pop-light">
<!--                 <img src="{{ assetUrlByCdn('/static/weixin/activity/thirdanniversary/images/one/page-gift1.png') }}" class="" alt="获奖">
 -->              </div>
              <span class="page-pop-winner">苏泊尔4L球釜内胆电饭煲</span>
              <span class="page-pop-winner">还有2次抽奖机会哦！</span>
              <a href="javascript:;" data-toggle="mask" data-target="page-layer3" class="page-pop-btn">朕知道了</a>
            </div>
          </div>
      </div>
</div>
<input type="hidden" name="_token"  value="{{csrf_token()}}">
@endsection

@section('footer')

@endsection

@section('jsScript')
<script type="text/javascript">

    var click=false;
    function doLotteryEvent(id) {
        var lotteryLevel    =   'lottery' +  id;
        lottery.init(lotteryLevel);
        if ( click ) {//click控制一次抽奖过程中不能重复点击抽奖按钮，后面的点击不响应
            return false;
        }
        lottery.speed=100;
        var activity    =   'autumn';
        lotteryEvent.doLottery(id,activity);
    }
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
