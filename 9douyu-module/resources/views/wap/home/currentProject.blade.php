<div class="wap3-current">
    <div class="wap3-current-1">
        <h3 class="wap3-current-title">零钱计划</h3>
        <div class="wap3-line"></div>
        <h4 class="wap3-current-title1">借款利率</h4>
        <p class="w3-number"> <span>{{ (float)$current['rateInfo']['rate'] }}</span>%</p>
        <div class="w3-date">
            <p>已加入 <span>{{$current['investUserNum'] }}</span> 人</p>
            @if($current['freeAmount'] < 10000)
                <p>剩余 <span>{{$current['freeAmount'] }}</span>元</p>
            @else
                <p>剩余 <span>{{$current['formatFreeAmount'] }}</span>万</p>
            @endif
        </div>
        <ul class="w3-text">
            <li>当日计息</li>|
            <li>灵活存取</li>|
            <li>1元起投</li>
        </ul>
        <div class="w3-btn">
            <a href="/project/current/detail" class="w3-btn-blue" onclick="_czc.push(['_trackEvent','WAP首页','零钱计划投资按钮']);">立即出借</a>
        </div>
        <p class="w3-text2"> <i></i><span>账户资金享有银行级安全保障</span></p>
    </div>
</div>