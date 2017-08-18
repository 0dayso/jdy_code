@extends('pc.common.layout')

@section('title','出借成功')

@section('content')

<!-- project begins -->
<div class="t-wrap t-mt30px">
    <div class="t-invest">
        <div class="t-invest-left">
            <div class="t-invest-1">
                <p>恭喜您，出借成功！</p>
            </div>

            <div class="t-invest-4">
                <table class="t-invest-2">
                    <thead>
                    <tr>
                        <td>出借金额</td>
                        <td>期限</td>
                        <td>年利率</td>
                        @if($refund_type == \App\Http\Dbs\Project\ProjectDb::REFUND_TYPE_FIRST_INTEREST)
                            <td>预期收益</td>
                            <td>到期日期</td>
                        @else
                            @if($rate == 0)
                            <td>预期收益</td>
                            @else
                            <td>预期总收益</td>
                            @endif
                            @if($refund_type == \App\Http\Dbs\Project\ProjectDb::REFUND_TYPE_ONLY_INTEREST || $refund_type == \App\Http\Dbs\Project\ProjectDb::REFUND_TYPE_EQUAL_INTEREST)
                                <td>首次回款金额</td>
                                <td>预计首次回款日</td>
                            @else
                                <td>到期日期</td>
                            @endif
                        @endif

                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>{{ $cash }}元</td>
                        <td>{{ $invest_time }}{{ $unit }}</td>
                        <td>
                            {{ (float)$profit }}%
                            @if(!empty($rate))
                                +{{ (float)$rate }}%
                            @endif
                        </td>
                        @if($refund_type == \App\Http\Dbs\Project\ProjectDb::REFUND_TYPE_FIRST_INTEREST)
                            <td>{{ $refund_interest }}元(今日到账)</td>
                            <td>{{ $end_time }}</td>
                        @else
                            <td>{{ $interest }}元</td>
                            @if($refund_type == \App\Http\Dbs\Project\ProjectDb::REFUND_TYPE_ONLY_INTEREST || $refund_type == \App\Http\Dbs\Project\ProjectDb::REFUND_TYPE_EQUAL_INTEREST)
                            <td>{{ $refund_interest }}元</td>
                            @endif
                            <td>{{ $refund_times }}</td>
                        @endif
                    </tr>
                    </tbody>
                </table>
            </div>

            <div class="t-invest-3">
                @if($refund_type == \App\Http\Dbs\Project\ProjectDb::REFUND_TYPE_FIRST_INTEREST)
                    <a href="/project/sdf" class="btn btn-red btn-large btn-block fl t-w236px">继续出借</a>
                @else
                    <a href="/project/index" class="btn btn-red btn-large btn-block fl t-w236px">继续出借</a>
                @endif
                <a href="/user" class="btn btn-blue btn-large btn-block fr t-w236px">返回我的账户</a>
            </div>
        </div>

        <div class="t-invest-right invest-success-coupon " >
            @if(!empty($ad_info))<img src="{{ $ad_info['param']['file'] }}" width="280" height="370">@endif
        </div>
    </div>
</div>

@endsection
