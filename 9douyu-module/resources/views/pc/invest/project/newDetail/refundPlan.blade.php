        <div class="Js_tab_main t-repayment" style="display: none;">
            <table class="table table-theadbg table-textcenter">
                <thead>
                <tr>
                    <td>预计还款时间</td>
                    <td>类型</td>
                    <td>预计还款金额（元）</td>
                </tr>
                </thead>
                <tbody>
                @foreach($refundPlan as $plan)
                    <tr>
                        <td>{{ $plan['refund_time'] }}</td>
                        <td>{{ $plan['refund_note'] }}</td>
                        <td>{{ number_format($plan['refund_cash'],2) }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
