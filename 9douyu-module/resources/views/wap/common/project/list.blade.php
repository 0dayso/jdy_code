@if( !empty($projectList) )
@foreach($projectList as $project)
    @if( !empty($project) )
    <div class="t2-main-tab1">
        <h3 class="t2-main-title2"><span></span>
            @if($project['product_line'] == \App\Http\Dbs\Project\ProjectDb::PROJECT_PRODUCT_LINE_JAX)
                {{ $project['product_line_note'].'&nbsp;'.$project['id'] }}
            @else
                {{ $project['name'].'&nbsp;'.$project['id'] }}
            @endif
            @if($project['publish_at'] > \App\Tools\ToolTime::dbNow())
            <em>{{ date("Y-m-d",strtotime($project["publish_at"])) }}&nbsp;{{ date("H:i",strtotime($project["publish_at"])) }}&nbsp;开售</em>
            @endif
        </h3>
        <a onclick="_czc.push(['_trackEvent','Wap列表页','{{ $project['invest_time_note'] }}']);"href="/project/detail/{{$project['id']}}" class="t2-block">
            <table class="t2-main-tab-1">
                <tr>
                    <td width="31%" align="center">
                        <p class="t2-project-2"><span>{{$project['profit_percentage']}}</span>%</p>
                        <p class="t2-project-1">借款利率</p>
                    </td>
                    <td width="33%" align="center">
                        <p class="t2-project-3"><span>{{ $project['format_invest_time']}}</span>{{ $project['invest_time_unit'] }}</p>
                        <p class="t2-project-1">期限</p>
                    </td>
                    <td>
                        @if($project['publish_at'] > \App\Tools\ToolTime::dbNow())
                        <a href="/project/detail/{{$project['id']}}" class="t2-pro-btn">准时开抢</a>
                        @elseif($project['status'] == \App\Http\Dbs\Project\ProjectDb::STATUS_INVESTING && $project['left_amount'] > 0)
                        <a href="/project/detail/{{$project['id']}}" class="t2-pro-btn blue">立即出借</a>
                        @else
                        <span class="ln-finish"></span>
                        @endif
                    </td>
                </tr>
            </table>
        </a>
    </div>
    @endif
@endforeach
@endif