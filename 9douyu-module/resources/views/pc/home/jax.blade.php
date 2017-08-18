@if ( !empty($jaxProject) )

    <div class="new-flex-project-1" onclick="window.location.href='/project/detail/{{ $jaxProject['id'] }}'">
        <div class="new-web-product1-rate">
            <a href="#" class="new-flex-btn">推荐项目</a>
        </div>

        <div class="new-web-product1-rate new-project-1">
            <p><span>借款利率</span></p>
            <p class="new-product1-num">{{ $jaxProject['profit_percentage'] }}<em>%</em></p>
        </div>

        <div class="new-web-product1-sum new-project-1">
            <p><span>借款期限</span></p>
            <p class="new-web-product1-num"><strong>{{ $jaxProject['invest_time_note'] }}</strong></p>
        </div>
        <div class="new-web-product1-sum new-project-1">
            <p><span>可投金额</span></p>
            <p class="new-web-product1-num"><strong>{{ number_format($jaxProject['left_amount']) }}</strong>元</p>
        </div>
        <!--<a class="btn btn-blue disabled"></a>-->
        <div class="new-web-product1-btn new-btn">
            @if( $jaxProject['left_amount'] == 0 )
                <a class="btn btn-blue btn-block disabled">已售罄</a>
            @elseif ( $jaxProject['status'] == \App\Http\Dbs\Project\ProjectDb::STATUS_INVESTING && $jaxProject['publish_at'] > \App\Tools\ToolTime::dbNow() )
                <a class="btn btn-blue btn-block disabled">敬请期待</a>
            @else
                <a class="btn btn-blue btn-block">立即出借</a>
            @endif
        </div>
    </div>

@endif
