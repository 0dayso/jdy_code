@if( !empty($projectList) )
@foreach( $projectList as $key => $project)
<div class="page-project-item {{$key}}">
    <div class="page-title">{{$project['name']}} •  {{$project['id']}}</div>
    <div class="page-project-inner clearfix">
        <div class="page2">
            <p class="p1"><strong>{{$project['profit_percentage']}}%</strong><span>借款利率</span></p>
            <p class="p2"><em>{{$project['invest_time_note']}}</em><span>项目期限</span></p>
            <p class="p2"><em>{{$project['refund_type_note']}}</em><span>还款方式</span></p>
            <p class="p2"><em>{{$project['left_amount']}}元</em><span>剩余可投</span></p>
            <p class="p3">
            @if( $project['status'] == \App\Http\Dbs\Project\ProjectDb::STATUS_INVESTING)
                <a href="javascript:;" class="page-project-btn clickInvest " attr-data-id="{{$project['id']}}" attr-act-token="{{$project['act_token']}}" >立即出借</a>
            @else
                <a href="javascript:;" class="page-project-btn clickInvest  disable" attr-data-id="{{$project['id']}}" attr-act-token="{{$project['act_token']}}">{{$project['status_note']}}</a>
            @endif
            </p>
        </div>
    </div>
</div>
@endforeach
@endif