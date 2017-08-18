@if ( !empty($current) )
    <div class="web-title padt30">
        <i></i>
        <p class="title">我要出借</p>
        <p class="title1">风控团队精心为您挑选的产品</p>
    </div>
    <div class="new-web-current" onclick="window.location.href='/project/current/detail'">
        <div class="new-web-product1-intro">
            <h4>零钱计划</h4>
            <p>每日付息  灵活存取</p>
        </div>

        <div>
            <i class="web-box-decorate1"></i>
            <i class="web-box-decorate2"></i>
            <i class="web-box-decorate3"></i>
            <i class="web-box-decorate4"></i>

            <div class="new-web-product1-rate">

                <p><span>借款利率</span></p>
                <p class="new-product1-num">{{ $current['rate'] }}<em>%</em></p>
            </div>

            <div class="new-web-product1-sum ">
                <p><span>可投金额</span></p>
                <p class="new-web-product1-num"><i></i><strong>{{ number_format($current['total_amount'] - $current['invested_amount'])  }}</strong>元</p>
            </div>

            <div class="new-web-product1-sum ">
                <p><span>万元每日收益</span></p>
                <p class="new-web-product1-num"><i></i><strong>{{ round(10000*$current['rate']/100/365, 2) }}</strong>元</p>
            </div>

            <div class="new-web-product1-btn"><a class="btn btn-blue btn-block">立即出借</a></div>
            <div class="clear"></div>

        </div>
        {{--活动主题--}}
        <div class="theme-gap"></div>
    </div>
@endif
