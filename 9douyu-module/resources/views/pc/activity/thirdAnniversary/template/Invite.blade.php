<section ng-controller="rankingCtrl">
    <div class="anniversary-gain" >
        <p class="anniversary-gain-txt1">活动期间邀请人／被邀请人累计投资优选项目<br>排名前五，即可获得对应的豪礼大奖</p>
        <img src="{{ assetUrlByCdn('/static/activity/thirdanniversary/images/two-gain-img.png') }}" class="anniversary-gain-img" />
        <h3>邀请人投资额最新排名</h3>
        <table>
            <tr>
                <th width="25%">排名</th>
                <th width="40%">邀请人手机号</th>
                <th>邀请人累计投资额</th>
            </tr>
            <tr ng-repeat='partner in partnerList'>
                <td ng-bind="$index+1">1</td>
                <td ng-bind='partner.phone'></td>
                <td ng-bind='partner.total'></td>
            </tr>
        </table>
        <span class="anniversary-gain-btn refresh">点我刷新</span>
    </div>

    <div class="anniversary-ranking" >
        <h3>被邀请人投资额最新排名</h3>
        <table>
            <tr>
                <th width="25%">排名</th>
                <th width="40%">被邀请人手机号</th>
                <th>被邀请人累计投资额</th>
            </tr>
            <tr ng-repeat='invite in inviteList'>
                <td ng-bind="$index+1">1</td>
                <td ng-bind='invite.phone'></td>
                <td ng-bind='invite.invest_cash'></td>
            </tr>
        </table>
        <span class="anniversary-gain-btn refresh">点我刷新</span>
    </div>
</section>