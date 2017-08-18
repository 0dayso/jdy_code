<div class="page-winner-list">
    <div class="page-center title">
        <h6>中奖名单</h6>
    </div>

    <div id="scrollDiv" class="hidden page-scroll">
        <ul class="page-scroll-list" ng-controller="recordCtrl">
            <li class="page-flex" ng-repeat='(key,record) in recordList'>
                <span class="data" ng-bind='key'>2017年5月27日</span><span ng-repeat='data in record' ng-bind="data.phone_hide">189****9835</span>
            </li>
        </ul>
    </div>

</div>