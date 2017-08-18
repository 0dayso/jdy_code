<div class="anniversary-box" ng-controller="projectCtrl">
    <div class="anniversary-pro-block" ng-repeat="project in projectList">
        <a href="javascript:;" class="investClick" attr-data-id="<%project.id%>">
            <table>
                <tr>
                    <th colspan="5" ><span ng-bind="project.product_line_note"></span><i>•</i> <span><span ng-bind="project.invest_time_note">3月期</span> <span ng-bind="project.id"></span></span></th>
                </tr>
                <tr>
                    <td width="20%">
                        <p class="anniversary-orange"><big ng-bind="project.profit_percentage | number:1">11</big>％</p>
                        <p><small>借款利率</small></p>
                    </td>
                    <td width="18%">
                        <p ng-bind="project.invest_time_note">3月期</p>
                        <p><small>期限</small></p>
                    </td>
                    <td width="22%">
                        <p ng-bind="project.refund_type_note">先息后本</p>
                        <p><small>还款方式</small></p>
                    </td>
                    <td width="20%"><p ng-bind="project.left_amount | number:2">408,778元</p><p><small>剩余可投</small></p></td>
                    <td ng-if="project.status==130"><em class="anniversary-btn">立即出借</em></td>
                    <td ng-if="project.status!=130"><em class="anniversary-btn disable" ng-bind="project.status_note">立即出借</em></td>
                </tr>
            </table>
        </a>
    </div>
</div>