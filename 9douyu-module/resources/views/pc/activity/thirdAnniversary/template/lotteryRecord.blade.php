    <div class="anniversary-list" ng-controller="recordCtrl">
        <div class="anniversary-list-title">中奖名单</div>
        <div id="scrollDiv1"  class="scrollDiv">
            <ul>
                <li ng-repeat='(time,record) in recordList'><em ng-bind='time'></em><span ng-repeat='data in record' ng-bind='data.phone_hide'></span></li>
            </ul>
        </div>
    </div>

