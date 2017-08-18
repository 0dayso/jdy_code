@if( !empty($jsxProject) )

    <div class="web-product4">
        <div class="new-flex-cash">
            <div class="new-flex-img" onclick=window.location.href="/project/index">
                <h4>优选项目</h4>
                <p>年年省心  月月返息</p>
                <a href="#"></a>
                <span>查看更多</span>
            </div>

            <div class="new-flex-project">
                <!-- 九安心 -->
                @include('pc.home/jax')

                <div class="new-flex-project-2">

                    <ul class="new-web-project-list clearfix">
                        @foreach( $jsxProject as $project )
                            @if ( empty($project['id']) )
                                @continue
                            @endif
                            <li onclick="window.location.href='/project/detail/{{ $project['id'] }}'" class="on">
                                <div class="web-project-title">
                                    <h3>{{ $project['product_line_note'] }}
                                        @if($project['product_line'] == \App\Http\Dbs\Project\ProjectDb::PROJECT_PRODUCT_LINE_JSX)
                                            @if($project['type'] == 1)
                                                1月期
                                            @else
                                                {{ $project['invest_time_note'] }}
                                            @endif
                                        @endif</h3>
                                </div>
                                <div class="web-project-rate">
                                    <div class="web-project-rate-1">
                                        <p class="new-web-date"><strong>{{ (float)$project['profit_percentage'] }}</strong><span>%</span></p>
                                        <p class="new-web-text">借款利率</p>
                                    </div>
                                </div>
                                <div class="web-project-progress-box">
                                    <div class="web-project-progress"><p style="width: {{ number_format($project['invested_amount']/$project['total_amount'],2)*100 }}%;"></p></div>
                                </div>
                                <div class="web-project-date">
                                    <p class="web-pro-mb"><strong>{{ $project['invest_time_note'] }}</strong></p>
                                    <p><span>期限</span></p>
                                </div>
                                <div class="web-project-sum">
                                    <p class="web-pro-mb">
                                        <strong>{{ \App\Http\Models\Common\IncomeModel::getInterestPlan($project['profit_percentage'], $project['invest_time'],$project['refund_type']) }}</strong>
                                    </p>
                                    <p><span>万元收益（元）</span></p>
                                </div>
                                @if ( $project['status'] == \App\Http\Dbs\Project\ProjectDb::STATUS_REFUNDING )
                                    <a class="btn btn-blue disabled">已售罄</a>
                                @elseif ( $project['status'] == \App\Http\Dbs\Project\ProjectDb::STATUS_INVESTING && $project['publish_at'] > \App\Tools\ToolTime::dbNow() )
                                    <a class="btn btn-blue disabled">敬请期待</a>
                                @else
                                    <a class="btn btn-blue ">立即出借</a>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endif
