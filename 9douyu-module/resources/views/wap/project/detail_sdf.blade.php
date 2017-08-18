@extends('wap.common.wapBase')

@section('title','项目详情')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{assetUrlByCdn('/static/weixin/css/progress.css')}}">
@endsection

@section('content')
<article>
    <div class="lightning-detail">
        <div class="ln-bj"></div>
        <p class="lightning-detail-1"><span>{{$project['profit_percentage']}}</span><i class="ln-box">借款利率</i>% </p>
        <table class="lightning-detail-2">
            <tr>
                <td width="25%">{{$project['format_invest_time'].$project['invest_time_unit']}}</td>
                <td>{{$investMinCash}}元起投</td>
                <td>投资当日付息</td>
            </tr>
        </table>
    </div>
    <div class="ln-detail-title">
        <h3 class="ln-detail-title-1" id="ln-title">产品详情 <span id="ln-arrow"></span></h3>
        <div class="ln-detail" id="ln-box">
            <p>
                闪电付息出借计划是九斗鱼为出借人精选的优质债权，均经过风控部门严格审核，包含中小企业债权、抵押债权等项目，闪电付息分为6月期和12月期，投资成功后可通过合同查看债权详情。闪电付息10000元起投，出借金额为10000元整数倍增加，不限购，固定收益，投资当日返还收益，到期日期返还本金。
            </p>
            <p>
                利息在投资成功后立即到达账户余额；投资本金将于到期日期返还至账户余额。
            </p>
            <p>
                利息＝出借金额*借款利率*项目期限(天)/365。
            </p>
        </div>
        <h3 class="ln-detail-title-1" id="ln-title1">安全保障 <span id="ln-arrow1"></span></h3>
        <div class="ln-detail" id="ln-box1">
            <h3 class="ln-detail-3">四道措施为资金安全保驾护航</h3>
            <p>
                第一重：
            </p>
            <p>
                闪电付息中的债权均为国内唯一获得专利技术的中小企业信用评价体系RISKCALC为您推荐的优质债权
            </p>
            <p>
                第二重：
            </p>
            <p>
                东亚银行《资金管理协议》，千万风险准备金保障
            </p>
            <p>
                第三重：
            </p>
            <p>
                合作的担保公司承担连带责任担保
            </p>
            <p>
                第四重：
            </p>
            <p>
                耀盛商业保理有限公司对违约债权当日无条件回购。
            </p>
        </div>
        <h3 class="ln-detail-title-1" id="ln-title2">常见问题 <span id="ln-arrow2"></span></h3>
        <div class="ln-detail" id="ln-box2">
            <h3 class="ln-detail-3">投资闪电付息后，是否可以在到期日期前赎回本金</h3>
            <p>
                闪电付息中债权暂不支持债权转让，因此在到期日期前不能赎回本金。
            </p>
            <h3 class="ln-detail-3">我可以设置自动投资闪电付息吗</h3>
            <p>
                目前暂不支持自动投资闪电付息的功能，仍需要出借人主动选择并投资闪电付息项目。
            </p>
            <h3 class="ln-detail-3">投资闪电付息项目，可以使用优惠券吗</h3>
            <p>
                目前暂不支持使用红包或加息券。
            </p>
            <h3 class="ln-detail-3">闪电付息到期后，如何继续投资或提现到个人银行卡</h3>
            <p>
                投资闪电付息的利息和本金到账后，用户可登录账户并利用账户可用余额进行投资或者申请提现，提现资金下一个工作日到账。
            </p>
        </div>
    </div>
    <div class="ln-banner">
        <img src="{{assetUrlByCdn('/static/weixin/images/wap2/ln-banner.png')}}" class="img">
    </div>
    <p class="w3-text2 ln-mb"> <i></i><span> 账户资金享有银行级安全保障</span></p>
    <section class="ln-bottom">
        <table>
            <tr>
                <td>
                    <a href="/project/calculator" class="ln-calculation"><img src="{{assetUrlByCdn('/static/weixin/images/wap2/ln-calculation.png')}}" ></a>
                </td>
                <td width="100%">
                    <!-- 未登录 -->
                    @if ( empty($status) || $status['is_login'] == 'off' )
                            <!-- 未登录 -->
                    <a href="/login" class="ln-btn">立即出借</a>
                    @elseif ( $status['name_checked'] == 'off' || $status['password_checked'] == 'off' )
                            <!-- 实名认证 -->
                    <!-- 实名认证和交易密码 -->
                    <a href="/user/verify" class="ln-btn">立即设置</a>
                    @elseif ( $project['status'] == \App\Http\Dbs\Project\ProjectDb::STATUS_INVESTING )
                            <!-- 可投资状态 -->
                    <a href="/Invest/Project/preDoInvest?id={{$project['id']}}&from=app" class="ln-btn">立即出借</a>
                    @else<!-- 项目其它状态 -->
                    <a href="javascript:" class="ln-btn disable">{{ $project['status_note'] }}</a>

                    @endif

                </td>
            </tr>
        </table>
    </section>
</article>
@endsection
@section('jsScript')
    <script type="text/javascript">
        function shousuo(a,b,c){
            $(a).click(function(){
                if($(b).hasClass("t-block")){
                    $(b).removeClass("t-block");
                    $(c).removeClass("flipy");
                }else{
                    $(b).addClass("t-block");
                    $(c).addClass("flipy");
                }
            })
        }
        shousuo("#ln-title","#ln-box","#ln-arrow");
        shousuo("#ln-title1","#ln-box1","#ln-arrow1");
        shousuo("#ln-title2","#ln-box2","#ln-arrow2");
    </script>
@endsection
