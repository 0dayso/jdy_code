@extends('wap.common.wapBase')

@section('title','项目详情')

@section('content')

<article>
    <section class="w-box-show">
        <p class="center"><span class="gray-title-bj font15px plr15px hidden">{{ $project['name'] }}</span></p>
        <table class="w-table mt20px">
            <tr>
                <td width="49%" class="br1px">
                    <p>
                        <span class="font50px w-bule-color">{{ (float)$project['profit_percentage'] }}</span>
                        <span class="font12px w-bule-color">%</span>
                    </p>
                    <p class="font12px w-999-color">借款利率</p>
                </td>
                <td width="49%" style="text-align: left; padding-left: 0.75rem;">
                    <p class="w-lh24px">
                        <span class="font12px w-999-color mr5px">项目期限</span>
                        <span class="font14px w-bule-color">{{ $project['format_invest_time'] . $project['invest_time_unit']}}</span>
                    </p>
                    <p  class="w-lh24px">
                        <span class="font12px w-999-color mr5px">还款方式</span>
                        <span class="font14px w-bule-color">{{ $project['refund_type_note'] }}</span>
                    </p>
                </td>
            </tr>
        </table>

        <div class="w-pro mt20px">
            <div class="w-t" style="width:{{ $process }}%;"></div>
            <div class="w-qi"><img src="{{assetUrlByCdn('/static/weixin/images/wap2/w-icon8.png')}}"></div>
        </div>
        <p class="fl font12px w-414141-color mt20px">
            <span class="font18px">{{ $project['total_amount']/10000 }}</span>万元
        </p>
        <p class="fr font16px w-bule-color mt20px">
            {{ number_format($project['left_amount'],2) }}元
        </p>
        <div class="clear"></div>
        <p class="fl font12px w-9d9d9d-color mt5px">融资金额</p>
        <p class="fr font12px w-9d9d9d-color mt5px">剩余可投金额</p>
        <div class="clear"></div>

        <table class="w-table1 mt20px">
            <tr class="bt1px">
                <td width="20%">
                    <span class="gray-title-bj">起息日</span>
                </td>
                <td width="30%">
                    <span>{{ \App\Tools\ToolTime::dbDate() }}</span>
                </td>
                <td width="50%">
                    <span>出借当日开始计息</span>
                </td>
            </tr>
            <tr>
                <td>
                    <span class="gray-title-bj">到期日</span>
                </td>
                <td>
                    <span>{{ $project['end_at'] }}</span>
                </td>
                <td><span>到期当日即可提现</span></td></tr>
        </table>
    </section>

    <section class="w-box-show mt15px pr0 w-ym-pd">
       {{-- <a href="/project/records/id/2579">--}}
            <div class="wap2-input-box p0px hrem">
                <h3 class="w-title"><img src="{{assetUrlByCdn('/static/weixin/images/wap2/w-icon9.png')}}">投资记录</h3>
                <p class="font12px w-9d9d9d-color">已有 <span class="blue">{{ $investBrief['num'] }}</span> 人投资</p>
               {{-- <span class="wap2-arrow-1"></span>--}}
            </div>
        {{--</a>--}}
        {{-- <h3 class="w-title lh2rem mr1 mt5px"><img src="{{assetUrlByCdn('/static/weixin/images/wap2/w-icon10.png')}}">风险控制<span class="blue font12px fr ">Riskcalc 安全认证</span></h3>
        <p class="pb10px w-9d9d9d-color mr1">平台对每个投资项目都有相应保障措施，同时建立了风险准备金账户，对平台每个投资项目
提取 1%作为风险准备金。</p> --}}

        <!-- 九省心一月期 -->
        @if($project['product_line'] == 100 && $project['type'] == 1)
        @else
            <?php
                if(in_array($projectWay, [\App\Http\Dbs\Credit\CreditDb::SOURCE_FACTORING, \App\Http\Dbs\Credit\CreditDb::SOURCE_HOUSING_MORTGAGE, \App\Http\Dbs\Credit\CreditDb::SOURCE_CREDIT_LOAN])){
            ?>
        <a href="/project/companyDetail/{{ $project['id'] }}">
            <div class="wap2-input-box p0px hrem mt10px">
                <h3 class="w-title"><img src="{{assetUrlByCdn('/static/weixin/images/wap2/w-icon11.png')}}">项目详情</h3>
                <p class="font12px w-9d9d9d-color">{{ $project['name'] }}</p>
                <span class="wap2-arrow-1"></span>
            </div>
        </a>
            <?php
                }
            ?>
        @endif
    </section>


    <section class="w-line"></section>

    <section id="invest-project" class="w-bottom">
        <div class="w-bottom-btn w-mt8px">
            <table class="w-table2">
                <tr >
                    <td class="w60px"><a href="/project/calculator"><img src="{{assetUrlByCdn('/static/weixin/images/wap2/w-icon7.png')}}" class="w-button"></a></td>
                    <td>

                        @if($project['left_amount'] == 0 && $project['status'] == \App\Http\Dbs\Project\ProjectDb::STATUS_INVESTING )
                            <a href="javascript:" class="w-btn-gray">已售罄</a>
                        @elseif($project['left_amount'] == 0 || $project['status'] != \App\Http\Dbs\Project\ProjectDb::STATUS_INVESTING )
                            <!-- 项目其它状态 -->
                            <a href="javascript:" class="w-btn-gray">已售罄</a>
                        @elseif($project['publish_at'] > \App\Tools\ToolTime::dbNow() && $project['status'] == \App\Http\Dbs\Project\ProjectDb::STATUS_INVESTING)
                            <a href="javascript:" class="w-btn"><span class=" pr15px">即将开始</span><span class="font12px">100元起投</span></a>
                        @elseif($status['is_login'] == 'off')
                            <a href="/login" class="w-btn"><span class=" pr15px">我要出借</span><span class="font12px">100元起投</span></a>
                        @elseif($status['name_checked'] == 'off')
                            <a href="/user/verify" class="w-btn"><span class=" pr15px">我要出借</span><span class="font12px">100元起投</span></a>
                        @else
                            <a href="/invest/project/confirm/{{ $project['id'] }}" class="w-btn"><span class=" pr15px">我要出借</span><span class="font12px">100元起投</span></a>
                        @endif
                    </td>
                </tr>
            </table>
        </div>
    </section>
</article>

@endsection
