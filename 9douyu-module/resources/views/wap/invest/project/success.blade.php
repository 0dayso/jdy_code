@extends('wap.common.wapBase')

@section('title','出借成功')

@section('css')
<link rel="stylesheet" type="text/css" href="{{assetUrlByCdn('/static/weixin/css/bonus.css')}}">
@endsection

@section('content')
<article>
    <section class="wap2-dd-box clearfix">
        <div class="wap2-dd-info">
            <p class="tc mt1 mb1">
            <p class="wap2-success-txt">出借成功!</p>
            </p>
            @if ($project['refund_type'] == 30)
                <p><span class="blue">{{ number_format($profit,2) }}元</span>利息已到账</p>
            @endif
            <i class="wap2-arrow-2"></i>
        </div>
        <div class="wap-dd-block">
            <img src="{{assetUrlByCdn('/static/weixin/images/wap2/wap2-dd.png')}}" class="img">
        </div>
    </section>
    <section class="wap2-box box-pad">
        <table class="wap2-withdraw-info">
            <tr>
                <th colspan="3">{{ $project['name'] }}</th>
            </tr>
            <tr>
                <td>•</td>
                <td>出借金额</td>
                <td>{{ number_format($cash,2) }}元</td>
            </tr>
            <tr>
                <td>•</td>
                <td>借款利率</td>
                <td>
                    {{ $project['profit_percentage'] }}%
                    @if(!empty($rate))
                        +{{ (float)$rate }}%
                    @endif
                </td>
            </tr>
            <tr>
                <td>•</td>
                <td>项目期限</td>
                <td>{{ $project['format_invest_time'] }}{{ $project['invest_time_unit'] }}</td>
            </tr>
            @if ($project['refund_type'] == 30)
                <tr>
                    <td>•</td>
                    <td>出借利息</td>
                    <td>{{ number_format($profit,2) }}(今日到账)</td>
                </tr>
                <tr>
                    <td>•</td>
                    <td>还款方式</td>
                    <td>
                        {{ $project['refund_type_note'] }}
                    </td>
                </tr>
                <tr>
                    <td>•</td>
                    <td>到期日期</td>
                    <td>{{ $project['end_at'] }}</td>
                </tr>
            @else
            <tr>
                <td>•</td>
                <td>还款方式</td>
                <td>
                    {{ $project['refund_type_note'] }}
                </td>
            </tr>
            <tr>
                <td>•</td>
                <td>起息日期</td>
                <td>{{ \App\Tools\ToolTime::dbDate() }}</td>
            </tr>
            <tr>
                <td>•</td>
                <td>预计首次回款日</td>
                <td>{{ $refund_times }}</td>
            </tr>
            @endif

        </table>

    </section>

    <section class="wap2-btn-wrap clearfix">
        @if($project['refund_type'] == \App\Http\Dbs\Project\ProjectDb::REFUND_TYPE_FIRST_INTEREST)
            <a href="/project/sdf/detail/?id={{ $projectId }}" class="wap2-btn wap2-btn-half fl wap2-btn-blue">继续投</a>
        @else
            <a href="/project/detail/{{ $projectId }}" class="wap2-btn wap2-btn-half fl wap2-btn-blue">继续投</a>
        @endif
        <a href="/user" class="wap2-btn wap2-btn-half fr">去账户</a>
    </section>
</article>

@endsection

