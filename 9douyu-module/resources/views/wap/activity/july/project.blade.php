@if( !empty($projectList) )
@foreach($projectList as $key => $project)
    <div class="july-pro july-{{$key}}">
        <div class="july-pro-title">{{$project['name']}} • {{$project['id']}}</div>
        <a href="javascript:;" class="doInvest" attr-data-id="{{$project['id']}}" attr-act-token="{{$project['act_token']}}">
            <table>
                <tr>
                    <td width="40%"><big>{{$project['profit_percentage']}}％</big></td>
                    <td width="32%"><span>{{$project['left_amount']}}元</span></td>
                    <td rowspan="2">

                        @if( $project['status'] == \App\Http\Dbs\Project\ProjectDb::STATUS_INVESTING)
                            <em>立即出借</em>
                        @else
                            <em class="disable" >已售罄</em>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td>借款利率</td>
                    <td>剩余可投</td>
                </tr>
            </table>
        </a>
    </div>
@endforeach
@endif
