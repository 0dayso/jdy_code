@if(!empty($projects))
@foreach ($projects as $project)
    <div class="t2-main-tab1">
        <h3 class="t2-main-title2"><span></span>
            @if($project['product_line'] == \App\Http\Dbs\Project\ProjectDb::PROJECT_PRODUCT_LINE_JAX)
                {{ $project['product_line_note'].'&nbsp;'.$project['id'] }}
            @else
                {{ $project['name'].'&nbsp;'.$project['id'] }}
            @endif
        </h3>
        <a href="/project/detail/{{$project['id']}}" class="t2-block">
            <table class="t2-main-tab-1">
                <tr>
                    <td width="31%" align="center">
                        <p class="t2-project-2"><span>{{$project['profit_percentage']}}</span>%</p>
                        <p class="t2-project-1">借款利率</p>
                    </td>
                    <td width="33%" align="center">
                        <p class="t2-project-3"><span>{{ $project['format_invest_time'] }}</span>{{ $project['invest_time_unit'] }}</p>
                        <p class="t2-project-1">期限</p>
                    </td>
                    <td>
                        <span class="ln-finish"></span>
                    </td>
                </tr>
            </table>
        </a>
    </div>
@endforeach
@endif
