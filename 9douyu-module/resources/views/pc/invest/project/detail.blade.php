@extends('pc.common.layout')

@section('title','项目详情')

@section('content')


<div class="t-wrap">
    <div class="t-center-nav"><a href="/">九斗鱼</a> > <a href="/project/index"> 我要出借 </a> >
        <a class="t-blue" href="#">{{ $project['product_line_note'] }}</a>
    </div>
    <div class="t-center-left">
        <h4 class="t-center-title">

            @if($project['product_line']==\App\Http\Dbs\Project\ProjectDb::PROJECT_PRODUCT_LINE_JSX)
                {{ $project['name'] }}
            @else
                {{ $project['product_line_note'] }}
            @endif
            {{ $project['id'] }}
        </h4>
        <div class="t-center-left-1">
            <dl>
                <dt>{{ (float)$project['base_rate'] }}<span class="f20">%</span>
                    @if($project['after_rate']>0)
                        <span class="t-center-left-2">+{{ (float)$project['after_rate'] }}%</span>
                    @endif
                </dt>
                <dd>借款利率</dd>
            </dl>
            <dl>
                <dt>{{ $project['format_invest_time'] . $project['invest_time_unit']}}</dt>
                <dd>借款期限</dd>
            </dl>
            <dl>
                <dt>100<span class="f20">元</span></dt>
                <dd>起投金额</dd>
            </dl>
        </div>

        <div class="t-center-left-3">
            <ul>
                <li><span class="t-icon1"></span>融资总额：{{ $project['total_amount']/10000 }}万</li>
                <li><span class="t-icon2"></span>还款方式：{{ $project['refund_type_note'] }}</li>
                <li><span class="t-icon3"></span>计息日期：出借当日计息</li>
                @if( $project['new'] == 0 )
                <li><span class="t-icon4"></span>到期日期：{{ $project['end_at'] }}</li>
                @endif
            </ul>
        </div>

    </div>

    <div class="t-center-right">
        <!-- 未登录 -->
        @if($status == 'noLogin')
        <p class="t-center-right-1 t-center-right-mt2">剩余可投：{{ number_format($project['left_amount']) }}元</p>
        <p class="t-center-right-2">账户余额：<span><a href="/login">登录</a></em>后可查看</span></p>
        <p class="t-center-right-7 t-center-right-mt">
            <span class="detail-calculator" data-base="10000" data-month="{{ $project['invest_time'] }}" data-rate="{{ $project['profit_percentage'] }}"
                    @if($project['refund_type'] == $refundType['onlyInterest'])
                       data-type="onlyInterest"
                    @endif @if($project['refund_type'] == $refundType['baseInterest'])
                       data-type="baseInterest"
                    @endif
                    @if($project['refund_type'] == $refundType['firstInterest'])
                       data-type="cycleInvest"
                    @endif
                    @if($project['refund_type'] == $refundType['equalInterest'])
                       data-type="equalInterest"
                    @endif>
            </span>
            <a href="/login" class="btn btn-red btn-large w190px">登录并出借</a>
        </p>
        @endif
        <!-- 未实名认证 -->
        @if($status == 'noNameCheck')
        <p class="t-center-right-d">剩余可投：{{ number_format($project['left_amount']) }}元</p>
        <p class="t-center-right-d1-1">投资前请先实名认证</p>
        <p class="t-center-right-d2"><a href="/user/setting/verify" class="btn btn-red btn-large btn-block">立即设置</a></p>
        @endif
        <!-- 未实名认证-未设置交易密码 -->
        @if($status == 'noSetTrade')
        <p class="t-center-right-d">剩余可投：{{ number_format($project['left_amount']) }}元</p>
        <p class="t-center-right-d1-1">投资请先设置交易密码并实名认证</p>
        <p class="t-center-right-d2"><a href="/user/setting/tradingPassword" class="btn btn-red btn-large btn-block">立即设置</a></p>
        @endif

        <!-- 项目未开始 -->
        @if($status == 'notStart')
        <p class="t-center-right-d"><img src="{{assetUrlByCdn('/static/images/new/center-icon12.png')}}" width="19" height="22">开放投资时间</p>
        <p class="t-center-right-d4"><span>{{ $project['publish_at'] }}</span></p>
        <p class="t-center-right-d2"><a href="#" class="btn disabled btn-large btn-block">敬请期待</a></p>
        <p class="t-center-right-d3">投标未开始，您还可以<a href="/project/index" >关注其它项目</a></p>
        @endif

        <!-- 可投资状态 -->
        @if($status == 'canInvest')
        <form action="/invest/project/doInvest" method="post" id="investForm">
            <p class="t-center-right-1">剩余可投：<span>{{ number_format($project['left_amount']) }}</span>元</p>
            <p class="t-center-right-2-1 ">
                <span class="user-balance">账户余额：<em>@if( isset($user['balance']) ){{ number_format($user['balance'],2) }} @else 0.00 @endif</em> </span> 元
                <a href="/recharge/index">充值</a>
            </p>
            <div class="clear"></div>
            <p class="t-center-right-3">出借金额：<input name="cash" id="cash" type="text"> 元</p>
            <p class="error project-tips"> {{ $msg }} </p>
            <div class="t-center-right-5"><p class="fl">优惠券：</p>
                <div class="t-select-box">
                    <!-- 红包开始 -->
                    @if( !empty($bonus) )
                    <select name="userBonusId" class="bonus-items">
                        <option value="">请选择可使用的优惠券</option>
                            @foreach($bonus as $v)
                                <option value="{{ $v['user_bonus_id'] }}" data-min="{{ $v['min'] }}" data-using="{{ $v['using_range'] }}" data-rate="{{ $v['cash']>0 ? $v['bonus_type'].'-'.$v['cash'] : $v['bonus_type'].'-'.$v['rate'] }}">
                                    {{ $v['name'] }} ({{ $v['using_range'] }},{{ $v['end_time'] }})
                                </option>
                            @endforeach
                    </select>
                    @else
                        <h4><em>暂无可用的优惠券</em></h4>
                    @endif
                    <!-- 红包结束 -->
                </div>
            </div>
            <div class="clear"></div>
            <p class="t-center-right-7">
                <span class="detail-calculator" data-base="10000" data-month="{{ $project['invest_time'] }}" data-rate="{{ $project['profit_percentage'] }}"
                      @if($project['refund_type'] == $refundType['onlyInterest'])
                            data-type="onlyInterest"
                      @endif @if($project['refund_type'] == $refundType['baseInterest'])
                            data-type="baseInterest"
                      @endif @if($project['refund_type'] == $refundType['firstInterest'])
                            data-type="cycleInvest"
                      @endif @if($project['refund_type'] == $refundType['equalInterest'])
                            data-type="equalInterest"
                      @endif>
                </span>
                @if( isset($user['assessment']) && empty($user['assessment']))
                <input type="button" class="btn btn-red btn-large w230px" value="立即出借" onclick='window.location.href="/user"' />
                @else
                <input type="submit" class="btn btn-red btn-large w230px" value="立即出借" />
                @endif
            </p>
            <p class="t-prompt mt-40px">温馨提示：网贷有风险，投资需谨慎。</p>
            <input type="hidden" name="project[id]" value="{{ $project['id'] }}">
            <input type="hidden" name="project[product_line]" id="product_line" value="{{ $project['product_line'] }}">
            <input type="hidden" name="project[type]" id="project_type" value="{{ $project['type'] }}">
            <input type="hidden" name="project[status]" value="{{ $project['status'] }}">
            <input type="hidden" name="project[left_amount]" id="leftAmount" value="{{ $project['left_amount'] }}">
            <input type="hidden" name="balance" id="balance" value="{{ $user['balance'] }}">
            <input type="hidden" name="project[profit_percentage]" id="percentage" value="{{ $project['profit_percentage'] }}">
            <input type="hidden" name="bonus_profit" id="bonus_profit" value="0">
            <input type="hidden" name="bonus_money"  id="bonus_money" value="0">
            <input type="hidden" id="min_money" value="0">
            <input type="hidden" id="using_range" value="0">
            <input type="hidden" id="end_at" value="{{ $project['end_at'] }}">
            <input type="hidden" id="invest_time" value="{{ $project['invest_time'] }}">
            <input type="hidden" id="publish_at" value="{{ $project['publish_at'] }}">
            <input type="hidden" name="refund_type" id="refund_type" value="{{ $project["refund_type"] }}" />
            <input type="hidden" name="project_new" id="project_new" value="{{ $project["new"] }}" />
            <input type="hidden" name="format_invest_time" id="format_invest_time" value="{{ $project["format_invest_time"] }}" />
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
        </form>
        @endif
        <!-- 已经投满 -->
        @if($status == 'refund')
        <p class="t-center-right-d"><span>{{ $investBrief['num'] }}</span>人投资此项目</p>
        <p class="t-center-right-d1">平均投资额：<span>{{ number_format($investBrief['avg']) }}</span>元</p>
        <p class="t-center-right-d2"><a href="#" class="btn disabled btn-large btn-block">还款中</a></p>
        <p class="t-center-right-d3">投标已完成，您还可以<a href="/project/index">关注其它项目</a></p>
        @endif
        <!-- 已经还款 -->
        @if($status == 'finished')
        <p class="t-center-right-d"><span>{{ $investBrief['num'] }}</span>人投资此项目</p>
        <p class="t-center-right-d1">平均投资额：<span>{{ number_format($investBrief['avg']) }}</span>元</p>
        <p class="t-center-right-d2"><a href="#" class="btn disabled btn-large btn-block">已还款</a></p>
        <p class="t-center-right-d3">还款已完成，您还可以<a href="/project/index">关注其它项目</a></p>
        @endif

    </div>

    <div class="clear"></div>

    <!-- ad img -->
    @if( !empty($ad) )
        @foreach( $ad as $info )
            <a href="{{ $info['param']['url'] }}" target="_blank" onclick="_czc.push(['_trackEvent','PC项目详情页','闪电付息广告']);">
                <img alt="九斗鱼闪电付息项目" src="{{ $info['param']['file'] }}" class="mt20">
            </a>
        @endforeach
    @endif

    <?php
    $company = isset($creditDetail['companyView']) ? $creditDetail['companyView'] : null;
    ?>
    <div class="t-center x-ad-mt">
        <!-- 九省心一月期 -->
        @if($project['product_line'] == 100 && $project['type'] == 1)
            {{--保理--}}
            @if($creditDetail['projectWay'] == App\Http\Dbs\Credit\CreditDb::SOURCE_FACTORING && $project['id'] > $jsxOneMaxId)
                {{--@include('pc.invest.project.jsx_factory')--}}
                @include('pc.invest.project.newDetail.jsx_factory')
            @elseif($creditDetail['projectWay'] == App\Http\Dbs\Credit\CreditDb::SOURCE_THIRD_CREDIT  && $project['id'] > $jsxOneMaxId)
                @include('pc.invest.project.third')
            @elseif($creditDetail['projectWay'] ==  App\Http\Dbs\Credit\CreditDb::TYPE_CREDIT_LOAN_USER  && $project['id'] > $jsxOneMaxId) <!--新债权-->
                @include('pc.invest.project.new')
            @else
                @include('pc.invest.project.free')
            @endif
        @endif
        <!-- 九省心3/6/12月期 -->
        @if($project['product_line'] == 100 && $project['type'] > 1)
            <!--信贷债权里面有些老模板展示的-->
            @if($creditDetail['projectWay'] == App\Http\Dbs\Credit\CreditDb::SOURCE_CREDIT_LOAN) <!--信贷 ok-->
                {{--@include('pc.invest.project.jsx_loan')--}}
                @include('pc.invest.project.newDetail.jsx_loan')
            @elseif($creditDetail['projectWay'] == App\Http\Dbs\Credit\CreditDb::SOURCE_HOUSING_MORTGAGE) <!--房抵 ok-->
                {{--@include('pc.invest.project.jsx_housing_mortgage')--}}
                @include('pc.invest.project.newDetail.jsx_housing_mortgage')
            @elseif($creditDetail['projectWay'] == App\Http\Dbs\Credit\CreditDb::SOURCE_FACTORING) <!--保理-->
                {{--@include('pc.invest.project.jax_data')--}}
                @include('pc.invest.project.newDetail.jax_data')
            @elseif($creditDetail['projectWay'] == App\Http\Dbs\Credit\CreditDb::SOURCE_THIRD_CREDIT ) <!--第三方债权-->
                @include('pc.invest.project.third')
            @elseif($creditDetail['projectWay'] == App\Http\Dbs\Credit\CreditDb::TYPE_CREDIT_LOAN_USER  ) <!--第三方债权-->
                @include('pc.invest.project.new')
            @else
                @include('pc.invest.project.jsx')
            @endif
        @endif
        <!-- 九安心 -->
        @if($project['product_line'] == 200)
            @if($creditDetail['projectWay'] == App\Http\Dbs\Credit\CreditDb::SOURCE_FACTORING) <!--保理-->
                {{--@include('pc.invest.project.jax_data')--}}
                @include('pc.invest.project.newDetail.jax_data')
            @else
                @include('pc.invest.project.jax')
            @endif
        @endif


    <div class="t-center-right-8">
        <div class="t-center-right-9">
            <h4><i class="iconfont font-icon1">&#xe62c;</i>出借概况</h4>
            <p><span class="t-center-right-10">出借人数</span><span class="t-center-right-11">{{ $investBrief['num'] }} 人</span></p>
            <div class="clear"></div>
            <p><span class="t-center-right-10">人均出借</span><span class="t-center-right-11">{{ $investBrief['avg'] }} 元</span></p>
            <div class="clear"></div>
            <h5>此项目单笔出借排行榜</h5>
            <table class="t-center-right-12">
                @foreach($maxInvestTop as $key => $top)
                <tr>
                    <td><span @if($key<3)class="t-icon-{{ $key+1 }}" @endif></span></td>
                    <td>{{ $top['phone'] }}</td>
                    <td align="right">{{ number_format($top['cash']) }} 元</td>
                </tr>
               @endforeach
            </table>
        </div>


            <div class="t-center-right-13 t-pd">
                <h4><i class="iconfont font-icon2">&#xe62b;</i>出借动态</h4>
                <table class="t-center-right-14">
                    @foreach($investNew as $invest)
                    <tr>                                <td valign="top">
                            <span>●</span>
                        </td>
                        <td>
                            <p>{{ $invest['phone'] }}</p>
                            <p><em>{{ $invest['project_note'] }}</em></p>
                        </td>
                        <td align="right">
                            <p>{{ number_format($invest['cash']) }}元</p> <p><em>{{ $invest['time_note'] }} 前</em></p>
                        </td>
                    </tr>
                    @endforeach
                 </table>
            </div>
        </div>
    </div>
</div>
<!-- 计算器  -->
<div class="clearfix mb50" id="data_div" project_way = "{{ $project['refund_type'] }}"></div>
@include('pc.invest.project/calculator')

<script src="{{ assetUrlByCdn('/static/js/pc2/jquery.plugin.js') }}" type="text/javascript"></script>
{{--<script src="{{ assetUrlByCdn('/static/js/pc2/invest.js') }}" type="text/javascript"></script>--}}

{{--<script type="text/javascript">

    (function($) {
        $(document).ready(function () {

            //红包下拉选择
            $(".bonus-items").change(function(){
                var thisOption = $(this).find("option").eq($(this).get(0).selectedIndex);

                var rate = thisOption.attr("data-rate");

                if(rate) {

                    var rateArr = rate.split('-');

                    if (rateArr[0] == '1') {
                        $("#bonus_profit").val(0);
                        $("#bonus_money").val(rateArr[1]);
                    } else {
                        $("#bonus_money").val(0);
                        $("#bonus_profit").val(rateArr[1]);
                    }
                }else{
                    $("#bonus_money").val(0);
                    $("#bonus_profit").val(0);
                }
            });

            $("#cash").focus(function(){

                $(".error").html('');
            });

        });
    })(jQuery);
</script>--}}

@endsection
@section('jspage')
    <script type="text/javascript">
        $(document).ready(function() {
            $.nmProxy(".carousel img");

            $(".default .carousel").jCarouselLite({
                btnNext: ".default .next",
                btnPrev: ".default .prev",
                circular: false
            });
        });
    </script>
@endsection
