<section ng-controller="summationCtrl">
    <p class="page-center page-banner-font1">截止目前活动期间内累计投资金额</p>
    <p class="page-center page-banner-font2" ng-bind="summation | number:2"></p>

    <div class="page-map page-<%percentage%>">
        <div class="page-fish"></div>
    </div>

    <ul class="page-add-up">
        <li>
            <p>累计定期投资额达5千万</p>
            <h6>每人获得288现金红包</h6>
        </li>
        <li>
            <p>累计定期投资额达8千万</p>
            <h6>每人获得588现金红包</h6>
        </li>
        <li>
            <p>累计定期投资额达1亿元</p>
            <h6>每人获得888现金红包</h6>
        </li>
    </ul>
    <p class="page-center page-banner-font3">周年庆活动期间</p>
    <p class="page-center page-banner-font4" ng-bind="diffDay"></p>
</section>
