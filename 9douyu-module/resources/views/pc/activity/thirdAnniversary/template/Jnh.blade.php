<div class="anniversary-box bottom2" ng-controller="prizeCtrl" attr-images-static="{{ env('STATIC_URL_HTTPS') }}" id="jia-nian-hua-module">
    <div class="anniversary-title">嘉年华惊喜</div>
    <div class="anniversary-title2">-&nbsp;&nbsp;今日奖品&nbsp;&nbsp;-<br><small>每日随机抽选投资优选项目为3万整数倍的鱼客一名</small></div>
    <div class="anniversary-img">
        <img ng-src='<%prizeInfo.imgUrl%>'  />
        <p><span><%prizeInfo.name%></span></p>
    </div>
    <div class="anniversary-img-list-title">
        <span class="anniversary-w1">日期</span>
        <span class="anniversary-w2">奖品</span>
        <span class="anniversary-w3">获奖者</span>
    </div>
    <ul class="anniversary-img-list">
        <li ng-repeat="record in JnhRecordList">
            <span class="anniversary-w1" ng-bind='record.time_note'>2017年4月2日</span>
            <span class="anniversary-w2" ng-bind='record.award_name'>小米空气净化器</span>
            <span class="anniversary-w3" ng-bind='record.phone_hide'>137****9823</span>
        </li>
    </ul>
</div>
