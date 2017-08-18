@extends('pc.common.activity')

@section('title', '全民争霸赛')

@section('keywords', env('META_KEYWORD'))

@section('description', env('META_DESCRIPTION'))

@section('css')
        <meta name="format-detection" content="telephone=yes">

@endsection
@section('content')
        <link rel="stylesheet" type="text/css" href="{{ assetUrlByCdn('/static/activity/investGame/css/secondphase.css') }}">

        <div class="page-banner">
            <span class="page-time">{{date('Y年m月d日',$activityTime['start'])}}-{{date('Y年m月d日',$activityTime['end'])}}</span>
        </div>
        <!-- 项目 -->
@if( !empty($projectList) )
@foreach($projectList as $key => $project )
        <div class="page-project-item">
            <h4 class="title">{{$project['product_line_note']}}{{$project['invest_time_note']}}<span>{{$project['id']}}</span></h4>
            <div class="page-project-inner clearfix">
                <p class="p1"><strong>{{(float)$project['profit_percentage']}}%</strong><span>借款利率</span></p>
                <p class="p2"><em>{{$project['format_invest_time']}}{{$project['invest_time_unit']}}</em><span>期限</span></p>
                <p class="p2"><em>{{$project['refund_type_note']}}</em><span>还款方式</span></p>
                <p class="p2"><em>{{$project['left_amount']}}元</em><span>剩余可投</span></p>
                <p class="p2">
@if( $userStatus == false)
                    <a href="javascript:;" class="page-project-btn" data-layer="layer-login" attr-bonus-value="login">立即出借</a>
@else
@if($project['status'] == \App\Http\Dbs\Project\ProjectDb::STATUS_INVESTING && \App\Tools\ToolTime::getUnixTime($project['publish_at'],'default') >= time())
                    <a href="/project/detail/{{$project['id']}}" class="page-project-active">敬请期待</a>
@elseif($project['status'] == \App\Http\Dbs\Project\ProjectDb::STATUS_INVESTING)
                    <a href="/project/detail/{{$project['id']}}" class="page-project-btn">立即出借</a>
@else
                    <a href="/project/detail/{{$project['id']}}" class="page-project-disabled">{{$project['status_note']}}</a>
@endif
@endif
                </p>

            </div>
        </div>
@endforeach
@endif
        <!-- 排名 -->
        <div class="page-ranking">
@if(empty($lotteryList['record']))
            <p class="first">{{isset($ranking[1])&&$ranking[1] ?\App\Tools\ToolStr::hidePhone($ranking[1]['phone']):'暂无第二'}}</p>
            <p class="second">{{isset($ranking[0])&&$ranking[0] ?\App\Tools\ToolStr::hidePhone($ranking[0]['phone']):'暂无第一'}}</p>
            <p class="third">{{isset($ranking[2])&&$ranking[2] ?\App\Tools\ToolStr::hidePhone($ranking[2]['phone']):'暂无第三'}}</p>
@else
            <p class="first">{{isset($lotteryList['record'][$lotteryList['lottery'][2]['id']]) ?\App\Tools\ToolStr::hidePhone($lotteryList['record'][$lotteryList['lottery'][2]['id']]['phone']):'暂无第二'}}</p>
            <p class="second">{{isset($lotteryList['record'][$lotteryList['lottery'][1]['id']]) ?\App\Tools\ToolStr::hidePhone($lotteryList['record'][$lotteryList['lottery'][1]['id']]['phone']):'暂无第一'}}</p>
            <p class="third">{{isset($lotteryList['record'][$lotteryList['lottery'][3]['id']]) ?\App\Tools\ToolStr::hidePhone($lotteryList['record'][$lotteryList['lottery'][3]['id']]['phone']):'暂无第三'}}</p>
@endif
 </div>

       <!-- 刷新 -->
       <a href="javascript:;" onclick="window.location.reload();" class="page-refresh-btn">每2小时更新数据</a>

       <ul class="page-prize">
@if(!empty($lotteryList['lottery']))
@foreach( $lotteryList['lottery'] as $key => $lottery )
               <li class="img{{$lottery['order_num']}}">
                   <p></p>
                   <span class="name">{{$lotteryList['word'][$lottery['order_num']]}}等奖：{{$lottery['name']}}</span>
               </li>
@endforeach
@endif
       </ul>


      <!-- 获奖名单 -->
      <div class="page-list page-list-invest " >
        <div class="page-list-inner">
          <h6>投资最新数据</h6>
          <div class="page-list-table">
          @if( !empty($ranking))
              <table>
          @foreach($ranking as $key => $rank)
                  @if($key <3)
                    <tr class='yellow'>
                  @else
                    <tr>
                  @endif
                      <td>第{{$lotteryList['word'][$key+1]}}名</td>
                      <td>{{\App\Tools\ToolStr::hidePhone($rank['phone'])}}</td>
                      <td>{{$rank['total']}} </td>
                  </tr>
          @endforeach
              </table>
          @else
              <table>
                  <tr>
                      <td>{{date('Y-m-d',time())}}</td>
                      <td>暂无PK数据!</td>
                      <td> </td>
                  </tr>
              </table>
          @endif
          </div>
        </div>
      </div>
        <div class="page-wrap">
                <div class="page-rule">
                    <h3 class="page-tag">活动规则</h3>
                    <p>1. 本次投资PK活动仅限活动页面的项目参与</p>
                    <p>2. 活动期间，选取活动参与项目累计投资额的前三名，获得对应奖品。如用户出借金额相同，则按照用户最后一笔投资额的时间，择先选取</p>
                    <p>3. 页面展示的投资数据仅为当前时间的投资数据，最终中奖名单以2017年7月24日公布的数据为准</p>
                    <p>4. 奖品获得者，活动期间提现金额≥10000元，取消其领奖资格； </p>
                    <p>5. 活动所得奖品以实物形式发放，将在2017年8月15日之前，与您沟通联系确定发放奖品。在8月15日之前联系用户仍无回应，则是为自动放弃奖品</p>
                    <p>6. 活动期间如有任何疑问请致电九斗鱼官方客服：400-6686-568，或登录九斗鱼咨询在线客服。</p>
                </div>
        </div>

        <!-- 弹窗 -->
        <div class="page-layer layer-login" style="display: none;">
            <div class="page-mask"></div>
            <div class="page-pop page-pop-login">
                <a href="javascript:;" class="page-pop-close" data-toggle="mask" data-target="layer-login">关闭</a>
                <a href="/login" class="page-pop-btn">登录</a>
            </div>
        </div>

        <script type="text/javascript">
            $(document).on("click", '[data-layer]',function(event){
                event.stopPropagation();
                var $this   = $(this);
                var target  = $this.attr("data-layer");
                var $target = $("."+target);
                $target.show();
            })
        </script>
        
@endsection


