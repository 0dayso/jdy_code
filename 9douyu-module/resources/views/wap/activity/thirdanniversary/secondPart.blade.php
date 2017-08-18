@extends('wap.common.activity')

@section('title', '鱼你前行 耀我新生')

@section('keywords', env('META_KEYWORD'))

@section('description', env('META_DESCRIPTION'))

@section('css')
<meta name="format-detection" content="telephone=yes">
<link rel="stylesheet" type="text/css" href="{{ assetUrlByCdn('/static/weixin/activity/thirdanniversary/css/two/index.css') }}">
<script type="text/javascript" src="{{ assetUrlByCdn('/static/angular/angular.min.js')}}"></script>
<script type="text/javascript" src="{{ assetUrlByCdn('/static/angular/angular-route.js')}}"></script>
<script type="text/javascript" src="{{ assetUrlByCdn('/static/angular/angular-animate.min.js')}}"></script>
<script type="text/javascript" src="{{ assetUrlByCdn('/static/weixin/activity/thirdanniversary/js/anniversary.two.js')}}"></script>
@endsection

@section('content')
<div class="page-bg">
    <input type="hidden" name="_token"  value="{{csrf_token()}}">
    <!--顶部总数统计- -->
    @include('wap.activity.thirdanniversary.template.summation')
    <div class="page-center page-time"><p>{{date("Y年m月d日",$activityTime['start'])}}-{{date("m月d日",$activityTime['end'])}}</p></div>
    <div class="page-title page-title-invite">邀请人</div>
    <div class="page-red">
      <h5>呼朋唤友抢60元现金红包</h5>
      <p>活动期间每邀请一个好友注册九斗鱼<br>邀请人可获得3元现金红包</p>
    </div>

    <div class="page-wrap">
      <div class="page-corner-mark"><span>攻略</span></div>
      <h5 class="page-center">好友下载九斗鱼APP——注册后填写邀请人手机号</h5>
      <div class="page-app"></div>
    </div>


    <div class="page-title page-title-invest">投资赢豪礼</div>
    <div class="page-wrap">
      <div class="page-corner-mark"><span>奖品</span></div>
      <h4 class="page-center">活动期间邀请人／被邀请人累计投资优选项目<br>排名前五即可获得对应的豪礼大奖</h4>
      <ul class="page-center page-gift">
        <li>
          <p>第一名</p>
          <p>OPPO R9s</p>
        </li>
        <li>
          <p>第二名</p>
          <p>小米43英寸液晶电视</p>
        </li>
        <li>
          <p>第三名</p>
          <p>松下智能扫地机器人</p>
        </li>
        <li>
          <p>第四名</p>
          <p>小狗无线立式吸尘器</p>
        </li>
        <li>
          <p>第五名</p>
          <p>海氏40L智能电烤箱</p>
        </li>
      </ul>
    </div>

    @include('wap.activity.thirdanniversary.template.invite')

    @include('wap.activity.thirdanniversary.template.secondproject')
      <!-- random -->
      <div class="page-surprise">
        <h6 class="page-center">天天嗨购  惊喜不断</h6>
        <p class="page-center">每日在优选项目中，随机抽选3名买入者，获得九斗鱼三周年伴手礼一份</p>
        <img src="{{ assetUrlByCdn('/static/weixin/activity/thirdanniversary/images/two/page-surprise.png') }}" class="page-surprise-gift" alt="伴手礼">
      </div>
    @include('wap.activity.thirdanniversary.template.record')
      <div class="page-auto page-rule">
          <h6>活动规则：</h6>
          <p><span>1.  </span>活动时间：{{date("Y年m月d日",$activityTime['start'])}}-{{date("m月d日",$activityTime['end'])}}</p>
          <p><span>2.  </span>活动期间邀请人／被邀请人累计投资优选项目金额排名前5名，即可获得对应的实物奖励；<br>判定邀请人或被邀请的身份，以活动期间的第一份身份为主；</p>
          <p><span>3.  </span>活动期间内用户每邀请一名新用户注册九斗鱼，即可获得3元现金红包奖励（60元现金红包封顶）；</p>
          <p><span>4.  </span>所获的现金红包奖励将于7月5日之前以现金券的形式发放至账户；</p>
          <p><span>5.  </span>活动期间活动期间内邀请人和被邀请人，如有一方提现金额≥10000元，则取消双方的领奖资格；</p>
          <p><span>6.  </span>活动期间所得奖品均以实物发放，客服会在7月30日之前联系用户确定收货地址，如7月30日之前联系未果，则视为自动放弃奖品；</p>
          <p><span>7.  </span>活动期间如有任何疑问请致电九斗鱼官方客服：400-6686-568，或登录九斗鱼官网咨询在线客服；</p>
          <p><span>8.  </span>网贷有风险 投资需谨慎。</p>
      </div>
      <!--p class="page-center page-tip">网贷有风险 投资需谨慎</p-->
</div>
@if($jrttChanleValue !=0 || !empty($jrttChanleValue)  )
<script type="text/javascript">
        (function(root) {
            root._tt_config = true;
            var ta = document.createElement('script'); ta.type = 'text/javascript'; ta.async = true;
            ta.src = document.location.protocol + '//' + 's3.pstatp.com/bytecom/resource/track_log/src/toutiao-track-log.js';
            ta.onerror = function () {
                var request = new XMLHttpRequest();
                var web_url = window.encodeURIComponent(window.location.href);
                var js_url  = ta.src;
                var url = '//ad.toutiao.com/link_monitor/cdn_failed?web_url=' + web_url + '&js_url=' + js_url + '&convert_id={{$jrttChanleValue}}';
                request.open('GET', url, true);
                request.send(null);
            }
            var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ta, s);
        })(window);
    </script>
@endif
@endsection

@section('footer')

@endsection

@section('jsScript')

<script type="text/javascript">
    $(document).delegate(".investClick",'click',function () {

        var  projectId  =   $(this).attr("attr-data-id");
        if( !projectId ||projectId==0){
            return false;
        }
        var version     =    "{{$version or ''}}";
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
        if (client =='android' ){
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
    })
    $(document).delegate(".userLogin",'click',function () {
        userLoginByClient( client )
    })
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


</script>
@endsection
