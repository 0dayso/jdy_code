@extends('pc.common.layout')

@section('title', "$title")

@section('content')
    <link rel="stylesheet" type="text/css" href="{{assetUrlByCdn('/static/css/lightning.css')}}">
    <div class="lightning-banner"></div>
    <div class="lightning-box clearfix">
        <div class="lightning-adv">
            <img src="{{assetUrlByCdn('/static/images/activity/lightning-adver.png')}}" width="263" height="394" alt="不动本金,家电换新不肉疼">
        </div>
        <ul class="web-project-listitem lightning-list">
            @if(!empty($list))
            @foreach( $list as $project)
                <li onclick="_czc.push(['_trackEvent','{{ $project['product_line_note'] }}','{{ $project["type"] }}']);" class="bb0">
                    <div class="web-listitem-title">
                        <span><strong>{{ $project['product_line_note'] }} • {{ $project["invest_time_note"] }}</strong></span>
                        <em>投资{{ $project["em_money"]/10000 }}万元可立拿收益{{ $project["em_profit"] }}元</em>
                        @if($project['left_amount'] == 0)
                            <div class="lightning-icon">{{ $minInvestCash }}元起投</div>
                        @elseif($project['publish_at'] > \App\Tools\ToolTime::dbNow())
                        <div class="lightning-text">{{ date("Y-m-d",strtotime($project["publish_at"])) }}&nbsp;&nbsp;{{ date("H:i",strtotime($project["publish_at"])) }}开售</div>
                        @elseif(($status['name_checked'] == 'off' || $status['password_checked'] == 'off') && $status['is_login'] == 'on')
                        <div class="lightning-text">投资请先设置交易密码并实名认证</div>
                        @else
                        <div class="lightning-icon">{{ $minInvestCash }}元起投</div>
                        @endif
                    </div>
                    <!-- 借款利率 -->
                    <div class="web-listitem-box web-listitem-rate">
                        <p>
                            <strong>{{ (float)$project["profit_percentage"] }}</strong>%
                        </p>
                        <span>借款利率</span>
                    </div>
                    <!-- 剩余可投 -->
                    <div class="web-listitem-box web-listitem-profit">
                        <p><em>{{ number_format($project['left_amount'],0) }} </em>元</p>
                        <span>剩余可投</span>
                    </div>
                    <!-- 期限 -->
                    <div class="web-listitem-box web-listitem-date">
                        <p><strong>{{ $project["format_invest_time"] }}</strong>{{ $project["invest_time_unit"] }}</p>
                        <span>期限</span>
                    </div>
                    <!-- 状态 -->
                    <div class="web-listitem-box web-listitem-btn">
                        @if($project['left_amount'] == 0 && $project['status'] == \App\Http\Dbs\Project\ProjectDb::STATUS_INVESTING )
                            <a href="javascript:" class="web-btn disable">已售罄</a>
                        @elseif($project['left_amount'] == 0 || $project['status'] != \App\Http\Dbs\Project\ProjectDb::STATUS_INVESTING )
                            <!-- 项目其它状态 -->
                            <a href="javascript:" class="web-btn disable">{{ $project['status_note'] }}</a>
                        @elseif($project['publish_at'] > \App\Tools\ToolTime::dbNow() && $project['status'] == \App\Http\Dbs\Project\ProjectDb::STATUS_INVESTING)
                            <a href="javascript:" class="web-btn disable">敬请期待</a>
                        @elseif ( empty($status) || $status['is_login'] == 'off' )
                            <!-- 未登录 -->
                            <a href="/login" class="web-btn 1">立即出借</a>
                        @elseif ( $status['name_checked'] == 'off')
                            <!-- 实名认证和交易密码 -->
                            <a href="/user/setting/verify" class="web-btn 1">立即设置</a>
                        @elseif ( $status['password_checked'] == 'off' )
                            <!-- 实名认证 -->
                            <a href="/user/setting/tradingPassword" class="web-btn 1">立即设置</a>
                        @elseif ( $project['status'] == \App\Http\Dbs\Project\ProjectDb::STATUS_INVESTING )
                            <!-- 可投资状态 -->
                            <form method="post" action="/invest/sdf/investConfirm" name="form{{$project['id']}}" id="form{{ $project['id'] }}">
                                <input type="hidden" name="id" value="{{$project['id']}}">
                                <a href="javascript:void(0);" class="web-btn 1 submit" data-value="{{$project['id']}}">立即出借</a>
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            </form>
                        @else
                            <!-- 项目其它状态 -->
                            <a href="javascript:" class="web-btn disable">{{ $project['status_note'] }}</a>
                        @endif
                    </div>
                    <div class="clear"></div>
                </li>
            @endforeach
            @endif
        </ul>
    </div>
    <div class="lightning-content Js_tab_box">
        <ul class="lightning-tab Js_tab">
            <li class="cur">
                <a href="javascript:">闪电付息介绍</a>
            </li>
            <li>
                <a href="javascript:">安全保障</a>
            </li>
            <li>
                <a href="javascript:">常见问题</a>
            </li>
        </ul>
        <div class="js_tab_content">

            <dl class="Js_tab_main lightning-main-tab">
                <dt><span></span><a href=""></a>什么是闪电付息？</dt>
                <dd>闪电付息出借计划是九斗鱼为出借人精选的优质债权，均经过风控部门严格审核，包含中小企业债权、抵押债权等项目，闪电付息分为6月期和12月期，投资成功后可通过合同查看债权详情。闪电付息{{ $minInvestCash }}元起投，出借金额为{{ $minInvestCash }}元的整数倍增加，不限购，固定收益，投资当日返还收益，到期日期返还本金。</dd>
                <dt><span></span>闪电付息项目安全吗？</dt>
                <dd>闪电付息中的债权均为风控筛选推荐的优质债权，享受风险准备金、第三方担保公司及保理公司违约回购的本息安全措施，让您放心投资。</dd>
                <dt><span></span>投资闪电付息后，我能赚取多少利息？</dt>
                <dd>利息＝出借金额*借款利率*借款期限(天)/365。</dd>
                <dt><span></span>投资闪电付息后，利息和本金什么时候到账？</dt>
                <dd>利息在投资成功后立即到达账户余额；投资本金将于到期日期返还至账户余额。</dd>
            </dl>
            <dl class="Js_tab_main lightning-main-tab lightning-1" style="display:none">
                <dd>第一重：闪电付息中的债权均为国内唯一获得专利技术的中小企业信用评价体系RISKCALC为您推荐的优质债权</dd>
                <dd>第二重：东亚银行《资金管理协议》，千万风险准备金保障，查看<a href="/content/article/reservefund?id=815" target="_blank" >《风险准备金账户》</a> </dd>
                <dd>第三重： 合作的担保公司承担连带责任担保，查看<a href="{{assetUrlByCdn('/static/resource/heightGuaranteeAndFactorBuyback.pdf')}}" target="_blank" >《最高额担保合同》</a> </dd>
                <dd>第四重：耀盛商业保理有限公司对违约债权当日无条件回购，查看<a href="{{assetUrlByCdn('/static/resource/heightGuaranteeAndFactorBuyback.pdf')}}" target="_blank">《耀盛保理违约债权收购》</a></dd>
            </dl>
            <dl class="Js_tab_main lightning-main-tab" style=" display: none;">
                <dt><span></span>投资闪电付息后，是否可以在到期日期前赎回本金？</dt>
                <dd>闪电付息中债权暂不支持债权转让，因此在到期日期前不能赎回本金。</dd>
                <dt><span></span>我可以设置自动投资闪电付息吗？</dt>
                <dd>目前暂不支持该功能，仍需要出借人主动选择并投资闪电付息项目。</dd>
                <dt><span></span> 投资闪电付息项目，可以使用优惠券吗？</dt>
                <dd>目前暂不支持使用红包或加息券。</dd>
                <dt><span></span> 闪电付息到期后，如何继续投资或提现到个人银行卡？</dt>
                <dd>投资闪电付息的利息和本金到账后，用户可登录账户并利用账户可用余额进行投资或者申请提现，提现资金下一个工作日到账。</dd>
            </dl>
        </div>
    </div>

    <script type="text/javascript">

        (function($){
            $(document).ready(function(){

                $(".submit").click(function(){
                    var id = $(this).attr('data-value');
                    console.log('id='+id);
                    $("#form"+id).submit();
                });

            });
        })(jQuery);
    </script>
@endsection

