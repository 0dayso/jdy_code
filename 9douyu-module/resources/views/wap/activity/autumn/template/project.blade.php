<section ng-controller="projectCtrl">
    <a href="javascript:;"  class="doInvest" attr-data-id="<%project.id%>" attr-act-token="<%project.act_token%>" ng-repeat="project in projectList">
        <div class="page-project">
            <h4 class="title" ng-bind="project.product_line_note + project.format_invest_time + project.invest_time_unit">九省心21天</h4>
            <table class="page-project-data">
                <tr>
                    <td width="27%">
                        <p><em ng-bind="project.profit_percentage | number:1"></em><span>%</span></p>
                        <p>借款利率</p>
                    </td>
                    <td width="36%">
                        <p ng-bind="project.left_amount | number:2">2,436,196元</p>
                        <p>剩余可投</p>
                    </td>
                    <td ng-if="project.status==130"><a href="javascript:;" class="page-project-btn doInvest " attr-data-id="<%project.id%>" attr-act-token="<%project.act_token%>">立即出借</a></td>
                    <td ng-if="project.status!=130"><a href="javascript:;" class="page-project-btn disable doInvest" attr-data-id="<%project.id%>" attr-act-token="<%project.act_token%>" ng-bind="project.status_note">立即出借</a></td>
                </tr>
            </table>
        </div>
    </a>
</section>

