<section ng-controller="rankingCtrl">
    <div class="page-wrap">
        <div class="page-corner-mark"><span>排名</span></div>
        <h4 class="page-center">邀请人投资额最新排名</h4>
        <table>
            <tr>
                <th>排名</th>
                <th>邀请人手机号</th>
                <th>邀请人累计投资额</th>
            </tr>
            <tr ng-repeat='partner in partnerList'>
                <td ng-bind="$index+1">1</td>
                <td ng-bind='partner.phone'></td>
                <td ng-bind='partner.total'></td>
            </tr>
        </table>
        <a href="javascript:;" onclick="window.location.reload();" class="page-btn-refresh">点我刷新</a>
    </div>
    <div class="page-wrap">
        <div class="page-corner-mark"><span>排名</span></div>
        <h4 class="page-center">被邀请人投资额最新排名</h4>
        <table>
            <tr>
                <th>排名</th>
                <th>被邀请人手机号</th>
                <th>被邀请人累计投资额</th>
            </tr>
            <tr ng-repeat='invite in inviteList'>
                <td ng-bind="$index+1">1</td>
                <td ng-bind='invite.phone'></td>
                <td ng-bind='invite.invest_cash'></td>
            </tr>
        </table>
        <a href="javascript:;" onclick="window.location.reload()" class="page-btn-refresh">点我刷新</a>
    </div>
</section>