<section class="page-wrap clearfix" ng-controller="projectCtrl" id="investPage">
    <div class="page-project" ng-repeat="project in projectList">
        <p class="title" ><em ng-bind="project.product_line_note">九省心</em> • <span ng-bind="project.invest_time_note">3月期 3207</span></p>
        <table>
            <tr>
                <td width="28%"><span ng-bind="project.profit_percentage | number:1">10</span>％</td>
                <td width="30%"><em ng-bind="project.left_amount | number:0">408，778</em>元</td>
                <td rowspan="2">
                    <a href="javascript:;" class="btn investClick" attr-data-id="<%project.id%>"  ng-if="project.status==130">立即出借</a>
                    <a href="javascript:;" class="btn disable"  attr-data-id="<%project.id%>" ng-if="project.status!=130" ng-bind="project.status_note"></a>
                </td>
            </tr>
            <tr><td>借款利率</td><td>剩余可投</td></tr>
        </table>
    </div>
</section>

