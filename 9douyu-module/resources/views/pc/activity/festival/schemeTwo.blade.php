@extends('pc.common.layout')

@section('title', '双蛋嘉年华')

@section('keywords', env('META_KEYWORD'))

@section('description', env('META_DESCRIPTION'))

@section('css')
    <meta name="format-detection" content="telephone=yes">

@endsection
@section('content')
    <link rel="stylesheet" type="text/css" href="{{ assetUrlByCdn('/static/activity/festivalTwo/css/index.css') }}">
    <div class="page-time">{{date("Y.m.d",$activityTime['start'])}}-{{date("Y.m.d",$activityTime['end'])}}</div>
    <div class="page-section1"><p class="page-section1-txt">登录九斗鱼APP每天猛戳红包雨</p></div>

    <div class="page-section2-top page-auto"></div>
    <!-- section2 content -->
    <div class="page-section2-main page-auto">
        <!--3月期 -->
        @if(!empty($projectInfo))
        @foreach($projectInfo as $key => $project )
            <div class="project-wrap clearfix">
                <h6 class="project-title">{{$project['product_line_note']}}  •  {{$project['format_invest_time']}}{{$project['invest_time_unit']}}</h6>
                <div class="project-content">
                    <div class="project-rate fl">
                        <p class="project-bigtxt">{{(float)$project['profit_percentage']}}<span>％</span></p>
                        <p class="project-des">借款利率</p>
                    </div>
                    <div class="parject-text fl">
                        <p class="project-txt">先息后本</p>
                        <p class="project-des">还款方式</p>
                    </div>
                    <div class="parject-total fl">
                        <p class="project-txt">{{$project['left_amount']}}元</p>
                        <p class="project-des">剩余可投</p>
                    </div>
                    <div class="parject-btn fr">
                    @if($project['status'] == \App\Http\Dbs\Project\ProjectDb::STATUS_INVESTING && \App\Tools\ToolTime::getUnixTime($project['publish_at'],'default') >= time())
                        <a href="/project/detail/{{$project['id']}}" class="page-btn disable">敬请期待<img src="{{assetUrlByCdn('/static/activity/festivalTwo/images/page-icon-white.png')}}" alt="投资即送icon"></a>
                    @elseif($project['status'] == \App\Http\Dbs\Project\ProjectDb::STATUS_INVESTING)
                        <a href="/project/detail/{{$project['id']}}" class="page-btn">立即出借<img src="{{assetUrlByCdn('/static/activity/festivalTwo/images/page-icon-white.png')}}" alt="投资即送icon"></a>
                    @else
                        <a href="/project/detail/{{$project['id']}}" class="page-btn disable">{{$project['status_note']}}<img src="{{assetUrlByCdn('/static/activity/festivalTwo/images/page-icon-white.png')}}" alt="投资即送icon"></a>
                    @endif
                        {{--<a href="javascript:;" class="page-btn">立即出借<img src="{{assetUrlByCdn('/static/activity/festivalTwo/images/page-icon-white.png')}}" alt="投资即送icon"></a>--}}
                    </div>
                </div>
            </div>
        @endforeach

        @endif

    </div>
     <!-- section2 content -->
    <div class="page-section2-bottom page-auto"></div>
    <!-- section3 嘉年华惊喜-->
    <div class="page-section3">
        <h6 class="page-section3-title">今日奖品</h6>
        <div class="page-section3-main clearfix">
            <div class="page-section3-img fl">
                <!--2016年12月23日     MOBICOOL  车载冰箱  page-prize-1.png
                    2016年12月24日      MAOKING 蓝牙音箱   page-prize-2.png
                    2016年12月25日     小米空气净化器       page-prize-3.png
                    2016年12月26日     GMP 万向旅行箱20寸   page-prize-4.png
                    2016年12月27日     Liven 电饼铛        page-prize-5.png
                    2016年12月28日     BRITA  滤水壶       page-prize-6.png
                    2016年12月29日     SKG电磁炉           page-prize-7.png
                    2016年12月30日     Bear 电炖锅         page-prize-8.png
                    2016年12月31日     苏泊尔 球釜电压力锅   page-prize-9.png
                    2017年1月1日        小米电视            page-prize-10.png
                    2017年1月2日        Midea 电烤箱家用    page-prize-11.png
                    2017年1月3日        Midea 吸尘器       page-prize-12.png -->
                <img src="{{assetUrlByCdn('/static/activity/festivalTwo/imagespage-prize-'.$lotteryInfo['img'].'.png')}}" alt="每日奖品">
            </div>
            <div class="page-section3-info fl">
                <p class="page-section3-name">{{$lotteryInfo['lottery']}}</p>
                {{--注释刘菲要求--}}
                @if($activityTime['start'] > time())
                    <p class="page-section3-phone">活动未开始</br>敬请期待</p>
                @elseif($lotteryInfo['winner'] == "none")
                    <p class="page-section3-phone">{{$lotteryInfo['date']}}未开奖!!</br>敬请期待</p>
                @else
                    <p class="page-section3-phone">{{$lotteryInfo['date']}}中奖者</br>{{$lotteryInfo['winner']}}</p>
                @endif

            </div>
        </div>
        <div class="page-section-des">投资定期项目，每日随机抽选一名惊喜奖</div>
    </div>

    <div class="page-section4">
        <img src="{{assetUrlByCdn('/static/activity/festivalTwo/images/page-section4-head.png')}}" alt="中奖记录" class="page-section4-head">
        <div class="page-section4-table">
        @if(empty($lotteryList))
            <table>
                <tbody>
                <tr><td>暂无开奖数据</td></tr>
                </tbody>
            </table>

        @else
            <table class="db-content">
                <thead>
                    <tr><td>日期</td><td>奖品</td> <td>中奖者</td></tr>
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

        <img src="{{assetUrlByCdn('')}}/static/activity/festivalTwo/images/page-fish.png" class="page-fish" alt="定位的小鱼">
    </div>
    <!-- 活动规则 -->
    
    <div class="page-rule page-auto">
        <h4>活动规则</h4>
        <p><span>1、</span>活动时间：{{date("Y年m月d日",$activityTime['start'])}}-{{date("Y年m月d日",$activityTime['end'])}}；</p>
        <p><span>2、</span>活动期间内，每个九斗鱼ID每日有一次机会参加红包雨活动；</p>
        <p><span>3、</span>活动期间内，每日会在当日投资嘉年华专项定期项目的出借人中随机抽选一名，获得当日的惊喜奖；当天中奖信息将会在下一个工作日11点公布；</p>
        <p><span>4、</span>活动期间内，获奖者提现金额≥10000元，取消其领奖资格；</p>
        <p><span>5、</span>活动所得奖品以实物形式发放，客服将在2017年1月13日之前，与您沟通联系确定发放奖品。如在1月20日之前联系用户无回应，则视为自动放弃实物奖品；</p>
        <p><span>6、</span>活动期间如有任何疑问请致电九斗鱼官方客服：400-6686-568，或登录九斗鱼咨询在线客服；</p>
    </div>
    <canvas id="christmasCanvas" style="top: 0px; left: 0px; z-index: 5000; position: fixed; pointer-events: none;width:100%;" ></canvas>
    <script>
        var snow = function() {
            if(1==1) {
                var b = document.getElementById("christmasCanvas"), a = b.getContext("2d"), d = window.innerWidth, c = window.innerHeight;
                b.width = d;
                b.height = c;
                for(var e = [], b = 0;b < 70;b++) {
                    e.push({x:Math.random() * d, y:Math.random() * c, r:Math.random() * 4 + 1, d:Math.random() * 70})
                }
                var h = 0;
                window.intervral4Christmas = setInterval(function() {
                    a.clearRect(0, 0, d, c);
                    a.fillStyle = "rgba(255, 255, 255, 0.6)";
                    a.shadowBlur = 5;
                    a.shadowColor = "rgba(255, 255, 255, 0.9)";
                    a.beginPath();
                    for(var b = 0;b < 70;b++) {
                        var f = e[b];
                        a.moveTo(f.x, f.y);
                        a.arc(f.x, f.y, f.r, 0, Math.PI * 2, !0)
                    }
                    a.fill();
                    h += 0.01;
                    for(b = 0;b < 70;b++) {
                        if(f = e[b], f.y += Math.cos(h + f.d) + 1 + f.r / 2, f.x += Math.sin(h) * 2, f.x > d + 5 || f.x < -5 || f.y > c) {
                            e[b] = b % 3 > 0 ? {x:Math.random() * d, y:-10, r:f.r, d:f.d} : Math.sin(h) > 0 ? {x:-5, y:Math.random() * c, r:f.r, d:f.d} : {x:d + 5, y:Math.random() * c, r:f.r, d:f.d}
                        }
                    }
                }, 70)
            }
        }
        snow();
    </script>

    <!-- 活动开始结束状态 -->
    @if( $activityTime['start'] > time())
        @include('pc.common.activityStart')
    @endif
    <!-- End 活动开始结束状态 -->
    @if($activityTime['end'] < time())
        @include('pc.common.activityEnd')
    @endif

@endsection


