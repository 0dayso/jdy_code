<section ng-controller="projectCtrl">
    <div class="anniversary-invest-item"   ng-repeat="project in projectList">
        <div class="anniversary-activity-body">
            <table class="">
                <tr>
                    <th colspan="3" class="anniversary-activity-head">
                        <h3 ng-bind="project.product_line_note + project.format_invest_time + project.invest_time_unit"></h3>
                        <p ng-bind="project.income_note"></p>
                    </th>
                </tr>
                <tr>
                    <td width="180" class="anniversary-table-item1">
                        <p><span ng-bind="project.profit_percentage | number:1">10</span>%</p>
                        <em>借款利率</em>
                    </td>
                    <td width="230">
                        <div>
                            <p ng-bind="project.left_amount | number:2">888,888元</p>
                            <em>剩余可投</em>
                            <i></i>
                        </div>
                    </td>
                    <td width="186" class="anniversary-table-item3">
                        <p ng-bind="project.format_invest_time + project.invest_time_unit">6个月</p>
                        <em>期限</em>
                    </td>
                </tr>
            </table>
        </div>
        <a href="javascript:;" class="anniversary-btn-table investClick" attr-data-id="<%project.id%>" ng-if="project.status==130">立即<br>出借</a>
        <a href="javascript:;" class="anniversary-btn-table investClick disable" attr-data-id="<%project.id%>" ng-if="project.status!=130" ng-bind="project.status_note"></a>
    </div>
</section>


