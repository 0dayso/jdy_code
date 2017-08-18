<ul ng-controller="projectCtrl">
    <li ng-repeat="project in projectList">
        <table>
            <tr>
                <th colspan="5">
                    <h3 ng-bind="project.product_line_note +' • '+ project.invest_time_note +' '+ project.id">九省心 • 6月期 4001</h3>
                </th>
            </tr>
            <tr>
                <td width="110">
                    <p class="red"><span ng-bind="project.profit_percentage | number:1">10.5</span>%</p><em>借款利率</em>
                </td>

                <td width="186">
                    <p ng-bind="project.format_invest_time + project.invest_time_unit">6个月</p><em>期限</em>
                </td>
                <td width="150">
                    <div><p ng-bind="project.refund_type_note">到期还本息 </p><em>还款方式</em><i></i></div>
                </td>
                <td width="230">
                    <div><p ng-bind="project.left_amount | number:2">888,888元</p><em>剩余可投</em><i></i></div>
                </td>
                <td>
                    <a href="javascript:;" class="autumn-btn-table clickInvest" attr-data-id="<%project.id%>" attr-act-token="<%project.act_token%>" ng-if="project.status==130">立即出借</a>
                    <a href="javascript:;" class="autumn-btn-table clickInvest disable " attr-data-id="<%project.id%>" attr-act-token="<%project.act_token%>" ng-if="project.status!=130" ng-bind="project.status_note">立即出借</a>
                </td>
            </tr>
        </table>
    </li>
</ul>


