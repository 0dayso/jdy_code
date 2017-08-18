@extends('pc.common.activity')

@section('title', '九斗鱼欢庆香港回归20周年')

@section('keywords', env('META_KEYWORD'))

@section('description', env('META_DESCRIPTION'))

@section('csspage')
    <meta name="format-detection" content="telephone=yes">
    <link rel="stylesheet" type="text/css" href="{{assetUrlByCdn('/static/activity/twenty/css/index.css')}}">

@endsection
@section('content')
    <div class="page-banner">
        <div class="wrap">
            <input type="hidden" name="_token"  value="{{csrf_token()}}">
            <p class="page-time">{{date("Y年m月d日",$activityTime['start'])}}——{{date('d日',$activityTime['end'])}}</p>
        </div>
    </div>
    <div class="page-bg">
    <div class="page-wrap">
    <h2 class="page-title large">好礼随心意</h2>
    <p class="mother-info">每个用户 ID每日仅限领取一张优惠券</p>
    <div id="coupon-status" attr-receive-lock='opened'>
       <ul class="clearfix mother-coupon">
          <li class="mother-300 page-li1" data-layer="layer-coupon" attr-bonus-value="ten">
            <p class="mother-coupon-txt"><big attr-used-desc ='满3000元可用' attr-value-desc ='10现金券'>10</big>元</p>
            <p><small>满3000元可用</small></p>
          </li>
          <li class="mother-300 page-li2" data-layer="layer-coupon" attr-bonus-value="thirty">
            <p class="mother-coupon-txt"><big attr-used-desc ='满5000元可用' attr-value-desc ='30现金券'>30</big>元</p>
            <p><small>满5000元可用</small></p>
          </li>
          <li class="mother-300 page-li3" data-layer="layer-coupon" attr-bonus-value="fifty">
            <p class="mother-coupon-txt"><big attr-used-desc ='满8000元可用' attr-value-desc ='50现金券'>50</big>元</p>
            <p><small>满8000元可用</small></p>
          </li>
          <li class="mother-100 page-li1" data-layer="layer-coupon" attr-bonus-value="point5">
            <p class="mother-coupon-txt2"><big attr-value-desc ='1%加息券' attr-used-desc ='满30000元可用'>1</big>%定期</p>
            <p><small>满30000元可用</small></p>
          </li>
          <li class="mother-100 page-li5" data-layer="layer-coupon" attr-bonus-value="per1">
            <p class="mother-coupon-txt2"><big attr-value-desc ='1.5%加息券' attr-used-desc ='满50000元可用'>1.5</big>%定期</p>
            <p><small>满50000元可用</small></p>
          </li>
        </ul>
    </div>

    <h2 class="page-title">回归狂欢</h2>
    <div class="mother-pro">
@if( !empty( $projectList ) )
    @foreach( $projectList as $key => $project )
        <a href="/project/detail/{{$project['id']}}">
            <table>
                <tr>
                    <th colspan="5"><span>{{$project['product_line_note']}} • {{$project['format_invest_time']}}{{$project['invest_time_unit']}}</span></th>
                </tr>
                <tr>
                    <td>

                        <p class="mother-red"><big>{{(float)$project['profit_percentage']}}</big>%</p>
                        <p><small>借款利率</small></p>
                    </td>
                    <td>

                        <p>{{$project['invest_time_note']}}</p>
                        <p><small>期限</small></p>
                    </td>
                    <td>

                        <p>{{$project['refund_type_note']}}</p>
                        <p><small>还款方式</small></p>
                    </td>
                    <td>

                        <p>{{$project['left_amount']}} 元</p>
                        <p><small>剩余可投</small></p>
                    </td>
                    <td>
                        <p>
@if( $userStatus == true)
    @if($project['status'] == \App\Http\Dbs\Project\ProjectDb::STATUS_INVESTING && \App\Tools\ToolTime::getUnixTime($project['publish_at'],'default') >= time())
                            <a href="javascript:;" attr-data-id="{{$project['id']}}" class="mother-btn disable investClick" >敬请期待</a>
    @elseif($project['status'] == \App\Http\Dbs\Project\ProjectDb::STATUS_INVESTING)
                            <a href="javascript:;" attr-data-id="{{$project['id']}}" class="mother-btn investClick" >立即出借</a>
    @else
                            <a href="javascript:;" attr-data-id="{{$project['id']}}" class="mother-btn disable investClick" >{{$project['status_note']}}</a>
    @endif
@else
                            <a href="javascript:;" class="mother-btn" data-layer="layer-login" attr-bonus-value="login">立即出借</a>
@endif
                        </p>
                    </td>
                </tr>
            </table>
        </a>
    @endforeach
@endif
     </div>
    <div class="mother-day">
         <h2 class="page-title">颜值好物</h2>
         <p>每日投资定期项目即有机会获得惊喜奖</p>
     </div>
     <div class="mother-day-prize">
         <h3 class="mother-prize-title"><span></span><span></span><span></span>&nbsp;&nbsp;今日礼品&nbsp;&nbsp;<span></span><span></span><span></span></h3>
         <p class="mother-prize-bg">
         @if( !empty($lotteryInfo['lottery']))
            <img src="{{assetUrlByCdn("/static/activity/twenty/images/mother-prize-".$lotteryInfo['lottery']['order_num'].".png")}}" alt="">
            @else
            <img src="{{assetUrlByCdn("/static/activity/twenty/images/mother-prize-1.png")}}" alt="">
            @endif
        </p>
        @if( !empty($lotteryInfo['lottery']))
            <p class="name">{{$lotteryInfo['lottery']['name']}}</p>
            @else
            <p class="name">jbl蓝牙音箱</p>
            @endif
     </div>
     <h3 class="mother-prize-title"><span></span><span></span><span></span>&nbsp;&nbsp;获奖者名单&nbsp;&nbsp;<span></span><span></span><span></span></h3>
     <div class="mother-list">

@if($lotteryInfo['record']['lotteryNum'] >0)
@foreach( $lotteryInfo['record']['list'] as $key => $record )
            <p><span>{{date("m月d日",strtotime($record['created_at']))}}</span>{{\App\Tools\ToolStr::hidePhone($record['phone'],3,4)}}</p>
@endforeach
@else
            <p><span>{{date("m月d日",time())}}</span>暂无中奖数据</p>
@endif
     </div>
</div>
    </div>
    <!-- 活动规则 -->
    <div class="mother-rule-bg">
    <div class="page-wrap">
        <div class="mother-rule">
            <h4>活动规则：</h4>
            <p>1.活动时间：{{date("Y年m月d日",$activityTime['start'])}}——{{date('m月d日',$activityTime['end'])}};</p>
            <p>2.活动期间内，每个用户ID每日仅限领取一张优惠券，而非每个不同优惠券各领取一张;</p>
            <p>3.活动期间内，所有领取的现金券或加息券仅限投资优选项目使用（1月期除外），自领取之日起有效期15天；</p>
            <p>4.活动期间内，每日在投资优选项目的投资者中，随机抽取一名获奖者，获得当日对应的实物奖品；中奖信息将于下一个工作日11点开奖;</p>
            <p>5.活动期间内，获得实物奖品者如提现金额≥10000元，则取消其领奖资格;</p>
            <p>6.活动所得奖品以实物形式发放，将在2017年7月30日之前，与您沟通联系确定发放奖品。如联系用户无回应，视为自动放弃活动奖励;</p>
            <p>7.活动期间如有任何疑问请致电九斗鱼官方客服：400-6686-568，或登录九斗鱼咨询在线客服;</p>
            <p>8.本活动最终解释权归九斗鱼所有。</p>
        </div>
    </div>
    </div>
    @if( $userStatus == false)
    <!-- 弹窗 -->
    <div class="page-layer layer-login">
        <div class="page-mask"></div>
        <div class="page-pop page-pop-login">
            <a href="javascript:;" class="page-pop-close" data-toggle="mask" data-target="layer-login">关闭</a>
            <p class="page-pop-text2">您还未登录</p>
            <a href="/login" class="mother-btn">立即登录</a>
        </div>
    </div>
    @endif
    <div class="page-layer layer-coupon">
        <div class="page-mask"></div>
        <div class="page-pop" id="page-bonus-alert">
            <a href="javascript:;" class="page-pop-close" data-toggle="mask" data-target="layer-coupon">关闭</a>
            <p class="page-pop-text">确定领取<br>30元现金券？</p>
            <a href="javascript:;" class="mother-btn" id="receive">确定</a>
            <p class="page-pop-text-desc">满5000元可用</p>
        </div>
    </div>
    <!-- 领取成功 -->
    <div class="page-layer layer-success">
        <div class="page-mask"></div>
        <div class="page-pop page-pop-success">
            <a href="javascript:;" class="page-pop-close" data-toggle="mask" data-target="layer-success">关闭</a>
            <p class="page-pop-text1">领取成功!<br>请在<span>[资产－我的优惠券]</span>中查看</p>
            <a href="javascript:;" class="mother-btn mother-btn-close" >确定</a>
        </div>
    </div>

    <!-- 领取失败 -->
    <div class="page-layer layer-fail" >
        <div class="page-mask"></div>
        <div class="page-pop page-pop-fail">
            <a href="javascript:;" class="page-pop-close" data-toggle="mask" data-target="layer-fail">关闭</a>
            <p class="page-pop-text2">请刷新页面重新领取</p>
            <a href="javascript:;" class="mother-btn mother-btn-close">确定</a>
        </div>
    </div>

    <script>

	$(document).delegate(".investClick",'click',function () {
		var  projectId  =   $(this).attr("attr-data-id");
		if( !projectId ||projectId==0){
			return false;
		}
		var act_token   =   '{{$actToken}}_' + projectId;
		var _token      =   $("input[name='_token']").val();
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

	})
    //显示弹窗
    $(document).on("click", '[data-layer]',function(event){
        event.stopPropagation();
        var $this   = $(this);
        var target  = $this.attr("data-layer");
        var layer_value=$this.attr("attr-bonus-value");
        var userStatus = '{{$userStatus}}';
        if( userStatus == false ) {
            $(".layer-login").show();
            return false
        }
        var $target = $("."+target);
        if( layer_value !='login' ){
            $target.attr('attr-bonus-value',layer_value);
            var used_value = $this.find('big').attr('attr-value-desc');
            var used_desc = $this.find('big').attr('attr-used-desc');
            $("#page-bonus-alert").removeClass().addClass('page-pop page-pop-'+layer_value);
            if(used_desc !='' || used_value !=''){
                $("#page-bonus-alert").find('.page-pop-text').empty().html('确定领取<br>' + used_value)
                $("#page-bonus-alert").find('.page-pop-text-desc').empty().html(used_desc)
            }
            var $couponLock =   $('#coupon-status')
            var couponLock  =   $couponLock.attr('attr-receive-lock');
            if( couponLock  != 'opened'){
                return false
            }
            $couponLock.attr('attr-receive-lock','closed');
        }
        $target.show();
    })
    $(document).on("click", '.page-pop-close,.mother-btn-close',function(event){
        event.stopPropagation();
        $('.page-bonus-alert').hide();
        $('.page-layer').hide();
        $('#coupon-status').attr('attr-receive-lock','opened');
    })
    $(document).on("click", '#receive',function(event){
        event.stopPropagation();
        var $target =   $('.layer-coupon');
        var value   =   $target.attr('attr-bonus-value');
        $target.hide()
        receiveBonusControl(value);
    })
    var receiveBonusControl = function (value) {
        var userStatus = '{{$userStatus}}';
        if( userStatus == false ) {
            $(".layer-login").show();
            return false
        }
        var $receiveBtn =   $("#receive");
        var lock        =   $receiveBtn.attr("lock-status");
        if( lock == 'closed'){
            return false;
        }
        $receiveBtn.attr("lock-status",'closed');
        $.ajax({
            url      :"/activity/receive",
            dataType :'json',
            data: {custom_value:value,_token:'{{csrf_token()}}'},
            type     :'post',
            success : function(json){
                if( json.status==true || json.code==200){
                    $(".layer-success").show();
                } else if( json.status == false || json.code ==500 ){
                    var $targetLayer=$(".layer-fail");
                    $targetLayer.find('.page-pop-text2').html(json.msg)
                    $targetLayer.show();
                }
                $receiveBtn.attr("lock-status",'opened');
                $('#coupon-status').attr('attr-receive-lock','opened');
                return false;
            },
            error : function(msg) {
                $(".layer-fail").show();
                $receiveBtn.attr("lock-status",'opened');
                $('#coupon-status').attr('attr-receive-lock','opened');
            }
        })
    }
    </script>
@endsection


