<section class="page-wrap page-wrap-mirror" ng-controller="prizeCtrl" attr-images-static="{{ env('STATIC_URL_HTTPS') }}"  id="jia-nian-hua-module">
    <div class="page-title"><span>嘉</span><span>年</span><span>华</span><span>惊</span><span>喜</span></div>
    <div class="page-today-gift">
        <div class="page-center"><h6>今日奖品</h6><p><small>每日随机抽选投资优选项目为3万整数倍的鱼客一名</small><p></div>
        <img ng-src='<%prizeInfo.imgUrl%>' class="page-today-img" alt="嘉年华惊喜">
        <p class="page-center"><%prizeInfo.name%></p>
        <table class="page-center">
            <thead>
            <tr class="title">
                <th>日期</th>
                <th>奖品</th>
                <th>中奖者</th>
            </tr>
            </thead>
        </table>
        <div class="page-table-scroll">
            <table>
                <tr ng-repeat="record in JnhRecordList">
                    <td ng-bind='record.time_note'>2017年4月2日</td>
                    <td ng-bind='record.award_name'>小米空气净化器</td>
                    <td ng-bind='record.phone_hide'>186****2456</td>
                </tr>
            </table>
        </div>
    </div>
</section>
