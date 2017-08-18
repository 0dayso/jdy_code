<div class="t2-main-tab">
    <h3 class="t2-main-title"><i class="t2-icon1"></i>闪电付息 <span>全部收益一秒到账</span></h3>
    @if( !empty($sdfList) )
    @foreach($sdfList as $project)
        <a onclick="_czc.push(['_trackEvent','Wap列表页','闪电付息{{ $project["invest_time_note"] }}']);" href="javascript:void(0)" class="t2-block bb1">
            @if($project['publish_at'] > \App\Tools\ToolTime::dbNow())
                <h4 class="t2-main-title1">{{ $project["invest_time_note"] }}<span>{{ date("Y-m-d",strtotime($project["publish_at"])) }}&nbsp;{{ date("H:i",strtotime($project["publish_at"])) }}&nbsp;开售</span></h4>
            @else
                <h4 class="t2-main-title1">{{ $project["invest_time_note"] }}</h4>
            @endif
            <table class="t2-main-tab-1">
                <tr>
                    <td>
                        <p class="t2-project-1">借款利率</p>
                        <p class="t2-project-2"><span>{{ (float)$project["profit_percentage"] }}</span>%</p>
                    </td>
                    <td>
                        <p class="t2-project-1">剩余可投</p>
                        <p class="t2-project-3"><span>{{ number_format($project['left_amount']/10000,2) }}</span>万</p>
                    </td>
                    <td>
                        @if($project['left_amount'] == 0 && $project['status'] == \App\Http\Dbs\Project\ProjectDb::STATUS_INVESTING )
                            <a href="javascript:" class="t2-pro-btn disabled">已售罄</a>
                        @elseif($project['left_amount'] == 0 || $project['status'] != \App\Http\Dbs\Project\ProjectDb::STATUS_INVESTING )
                            <!-- 项目其它状态 -->
                            <a href="javascript:" class="t2-pro-btn disabled">{{ $project['status_note'] }}</a>
                        @elseif($project['publish_at'] > \App\Tools\ToolTime::dbNow() && $project['status'] == \App\Http\Dbs\Project\ProjectDb::STATUS_INVESTING)
                            <a href="javascript:" class="t2-pro-btn disabled">敬请期待</a>
                        @elseif ( empty($status) || $status['is_login'] == 'off' )
                                <!-- 未登录 -->
                            <a href="/project/sdf/detail?id={{$project['id']}}" class="t2-pro-btn">立即出借</a>
                        @elseif ( $status['name_checked'] == 'off')
                            <!-- 实名认证和交易密码 -->
                            <a href="/project/sdf/detail?id={{$project['id']}}" class="t2-pro-btn">立即出借</a>
                        @elseif ( $status['password_checked'] == 'off' )
                            <!-- 实名认证 -->
                            <a href="/project/sdf/detail?id={{$project['id']}}" class="t2-pro-btn">立即出借</a>
                        @elseif ( $project['status'] == \App\Http\Dbs\Project\ProjectDb::STATUS_INVESTING )
                            <!-- 可投资状态 -->
                            <a href="/project/sdf/detail?id={{$project['id']}}" class="t2-pro-btn">立即出借</a>
                        @else
                                <!-- 项目其它状态 -->
                        <a href="javascript:" class="web-btn disabled">{{ $project['status_note'] }}</a>
                        @endif
                    </td>
                </tr>
            </table>
        </a>
    @endforeach
    @endif
</div>