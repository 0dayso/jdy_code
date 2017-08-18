<div class="anniversary-theme" ng-controller="summationCtrl">
    <div class="wrap">
        <a name="float-one"></a>
        <div class="theme-sum">
            <p>截止目前活动期间内累计投资金额</p>
            <p><big ng-bind="summation |number:2"></big></p>
        </div>
        <div class="theme-step step1">
            <p>累计定期投资额达5千万</p>
            <p><big>每人获得288现金红包</big></p>
        </div>
        <div class="theme-step step2">
            <p>累计定期投资额达8千万</p>
            <p><big>每人获得588现金红包</big></p>
        </div>
        <div class="theme-step step3">
            <p>累计定期投资额达1亿元</p>
            <p><big>每人获得888现金红包</big></p>
        </div>
        <div class="theme-fish <% percentage %>"></div>
        <div class="theme-limit">
            <p><small>周年庆活动期间</small></p>
            <p ng-bind="diffDay"></p>
        </div>
    </div>
</div>
