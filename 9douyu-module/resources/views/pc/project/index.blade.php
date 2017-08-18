@extends('pc.common.layout')

@section('title', '我要出借')

@section('content')
    <div class="wrap">
        <div class="web-project-tab">
            <ul class="clearfix">
                <li @if( $type == 'Preferred' ) class="on" @endif style="height:54px;"><a href="/project/index">优选项目</a><i></i></li>
                <!--<li @if( $type == 'JSX' ) class="on" @endif style="height:54px;"><a href="/project/index?type=JSX">九省心</a><i></i></li>
                <li @if( $type == 'JAX' ) class="on" @endif style="height:54px;"><a href="/project/index?type=JAX">九安心</a><i></i></li>-->
                {{--<li style="height:54px;"><a href="/project/sdf">闪电付息</a><i></i></li>--}}
                <li style="height:54px;"><a href="/project/current/detail">零钱计划</a><i></i></li>
                {{--<li @if( $type == 'BXB' ) class="on" @endif style="height:54px;"><a href="#">变现专区</a><i></i></li>--}}
            </ul>
        </div>
        <div class="web-project-main">
            <!--九省心-->
            <ul class="web-project-listitem">
                @if( !empty($projectList) )
                    @foreach( $projectList as $project )
                        <li onclick="window.location.href='/project/detail/{{ $project['id'] }}'">
                            <div class="web-listitem-title">
                                @if($project['product_line'] == \App\Http\Dbs\Project\ProjectDb::PROJECT_PRODUCT_LINE_JSX)
                                <span><strong>{{ $project['name'] }} · </strong>{{ $project['id'] }} </span>
                                @else
                                <span><strong>{{ $project['product_line_note'] }} · </strong>{{ $project['id'] }} </span>
                                @endif


                                @if ( $project['status'] == \App\Http\Dbs\Project\ProjectDb::STATUS_INVESTING && $project['publish_at'] >= \App\Tools\ToolTime::dbNow() )
                                <ins><em>{{ date('Y-m-d', strtotime($project['publish_at'])) }} </em> {{ date('H:i', strtotime($project['publish_at'])) }}开售</ins>
                                @endif

                            </div>
                            <div class="web-listitem-box web-listitem-rate">
                                <p>
                                    <strong>{{ (float)$project['profit_percentage'] }}</strong>%
                                </p>
                                <span>借款利率</span>
                            </div>
                            <div class="web-listitem-box web-listitem-date">
                                <p>{{ $project['format_invest_time'] . $project['invest_time_unit']}}</p>
                                <span>期限</span>
                            </div>
                            <div class="web-listitem-box web-listitem-sum">
                                <p>
                                    <ins>{{ $project['refund_type_note'] }}</ins>
                                </p>
                                <span>还款方式</span>
                            </div>
                            <div class="web-listitem-box web-listitem-profit">
                                <p><em>{{ number_format($project['left_amount']) }}</em>元</p>
                                <span>剩余可投</span>
                            </div>
                            <div class="web-listitem-box web-listitem-btn">
                                @if ( $project['status'] == \App\Http\Dbs\Project\ProjectDb::STATUS_FINISHED )
                                    <a class="btn btn-red disabled">已还款</a>
                                @elseif ( $project['status'] == \App\Http\Dbs\Project\ProjectDb::STATUS_REFUNDING )
                                    <a class="btn btn-red disabled">还款中</a>
                                @elseif ( $project['status'] == \App\Http\Dbs\Project\ProjectDb::STATUS_INVESTING && $project['publish_at'] >= \App\Tools\ToolTime::dbNow() )
                                    <a class="btn" href="#">敬请期待</a>
                                @else
                                    <a class="btn btn-red" href="/project/detail/{{ $project['id'] }}">立即出借</a>
                                @endif
                            </div>
                            <div class="clear"></div>
                        </li>
                    @endforeach
                @endif
            </ul>
            <div class="web-page">
                @include('scripts/paginate', ['paginate'=>$paginate])
            </div>




        </div>
        <div class="web-project-sidebar">
            
                <div class="web-sidebar-box">
                    <div class="web-listitem-summary web-listitem-jsx">
                        <h3>优选项目</h3>
                        <p>一款拥有固定还款期限的产品，借款期限1~12个月，出借利率9~12%，出借人可根据自己的实际情况分散投资。</p>
                    </div>
                    

                </div>
                <!-- ad right img -->
            @if( !empty($ad) )
                @foreach( $ad as $info )
                    <a href="{{ $info['param']['url'] }}" target="_blank" onclick="_czc.push(['_trackEvent','PC投资列表页','闪电付息广告']);">
                        <img alt="九斗鱼闪电付息项目" src="{{ $info['param']['file'] }}" class="ad-img-right">
                    </a>
                @endforeach
            @endif

            <div class="web-sidebar-title">
                <p>出借风云榜</p>
            </div>
            <div class="web-sidebar-box">
                <dl class="web-ranking">
                    <dt>
                    <div class="num">排名</div>
                    <div class="name">手机号</div>
                    <div class="sum">出借金额</div>
                    </dt>
                    @if( !empty($fullWinList) )
                        @foreach($fullWinList as $key => $info)
                            <dd class="web-ranking-bg">
                                @if($key == 0)<div class="num"><img src="{{assetUrlByCdn('static/images/new/web-ranking-1.png')}}"></div>@endif
                                @if($key == 1)<div class="num"><img src="{{assetUrlByCdn('static/images/new/web-ranking-2.png')}}"></div>@endif
                                @if($key == 2)<div class="num"><img src="{{assetUrlByCdn('static/images/new/web-ranking-3.png')}}"></div>@endif
                                @if($key > 2) <div class="num"><span>{{ $key+1 }}</span></div> @endif
                                <div class="name">@if(isset($info['phone'])){{ $info['phone'] }}@else 手机号 @endif</div>
                                <div class="sum">{{ $info['cash'] }}元</div>
                            </dd>
                        @endforeach
                    @endif
                </dl>

            </div>

        </div>
    </div>
    <div class="clear"></div>

@endsection
