@extends('wap.common.wapBase')
@section('title', '我的零钱计划')

@section('keywords', env('META_KEYWORD'))

@section('description', env('META_DESCRIPTION'))

@section('content')
    <article>
        <section class="w-bc hidden">
            <p class="center mt25px"><span class="gray-title-bj1 font15px plr15px">零钱计划总额</span></p>
            <p class="center w-fff-color mt15px"><span class="font30px">{{!empty($account_info) ? $account_info['cash'] : 0}}</span><span>元</span></p>
        </section>
        <!--<div class="w-hz">万元每日收益{$currentEntity:demonstrate_daily_profit|moneyFormat=###,2,'.',','}元</div>-->
        {{--<div class="w-hz">万元每日收益{$currentInterest|moneyFormat=###,2,'.',','}元</div>--}}


        <section class="w-button-box">
            @if(!empty($bonusInfo['add_rate']))
                <table class="w-table w-bc1">
                    <tr>
                        <td width="50%" class="br1px"><p class="lh2rem w-ye-pl"><span>加息券生效中</span></p><p class="w-bule-color w-ye-pl"><span class="t-current2-4">{{ (float)$bonusInfo['base_rate_day'] }}</span>%<em class="t-current2-5">+</em><i class="t-current2-6">{{ (float)$bonusInfo['add_rate'] }}</i><em class="t-current2-5">%</em></p></td>
                        <td><p class="lh2rem w-ye-pl1"><span>加息时间</span></p><p class="w-bule-color w-ye-pl1"><span class="font14px">{{ date('n月j日',strtotime($bonusInfo['used_time'])) }}~{{ date('n月j日',strtotime($bonusInfo['rate_used_time'])) }}</span></p></td>
                    </tr>
                </table>
                <p class="t-current2-3"></p>
            @endif

            {{--<if condition="!$usedBonus && $bonusTotal gt 0">
                <!--点击使用加息券  -->
                <p class="t-current2-1">您有{$bonusTotal}张加息劵未使用<a href="javascript:" id="showBonus" class="gray-title-bj t-current2-2">点击使用</a></p>
                <elseif condition="$usedBonus" />
                <table class="w-table w-bc1">
                    <tr>
                        <td width="50%" class="br1px"><p class="lh2rem w-ye-pl"><span>加息劵生效中</span></p><p class="w-bule-color w-ye-pl"><span class="t-current2-4">{$rate}</span>%<em class="t-current2-5">+</em><i class="t-current2-6">{$usedBonus.addRate}</i><em class="t-current2-5">%</em></p></td>
                        <td><p class="lh2rem w-ye-pl1"><span>加息时间</span></p><p class="w-bule-color w-ye-pl1"><span class="font14px">{$usedBonus.used_time}~{$usedBonus.rate_used_time}</span></p></td>
                    </tr>
                </table>
                <p class="t-current2-3"></p>
            </if>--}}
            <!--点击使用加息券加息券生效-->


            <table class="w-table w-bc1">
                <tr>
                    <td width="50%" class="br1px"><p class="lh2rem w-ye-pl"><span>昨日收益</span></p><p class="w-bule-color w-ye-pl"><span class="font15px">{{!empty($account_info) ? $account_info['yesterday_interest'] : 0}}</span>元</p></td>
                    <td><p class="lh2rem w-ye-pl1"><span>累计收益</span></p><p class="w-bule-color w-ye-pl1"><span class="font15px">{{!empty($account_info) ? $account_info['interest'] : 0}}</span>元</p></td>
                </tr>
            </table>

            <p class="center mt15px t-bt-1px pt15px"><a href="/current/viewCredit" class="gray-title-bj w-bule-color">查看债权列表</a></p>


        </section>

        <section class="w-box-show mt15px pd-tb0px hidden">
            <h3 class="w-title pb15px bb-1px mb20px pt15px"><img src="{{assetUrlByCdn('/static/weixin/images/wap2/wap2-icon-10.png')}}">近一周收益</h3>

            <table class="wap2-table-1">

                <tr>
                    <th width="25%">时间</th>
                    <th width="50%"> 本金</th>
                    <th>收益</th>
                </tr>
                @if(!empty($interest_list))
                    @foreach($interest_list as $val)
                    <tr>
                        <td>{{$val['interest_date']}}</td>
                        <td>¥{{number_format($val['principal'],2)}}</td>
                        <td>¥{{number_format($val['interest'],2)}}</td>
                    </tr>
                    @endforeach
                @else
                    <tr><td colspan="4">暂无信息</td></tr>
                @endif

            </table>

        </section>

        <section class="w-box-show mt15px pd-tb0px hidden">
            <h3 class="w-title pb15px bb-1px mb20px pt15px"><img src="{{assetUrlByCdn('/static/weixin/images/wap2/w-icon11.png')}}">交易记录</h3>

            <table id="trade-records" class="wap2-table-1">
                <tr>
                    <th width="33%">时间</th>
                    <th width="33%">金额变化</th>
                    <th width="34%">交易类型</th>

                </tr>

                @if(!empty($fund_list))
                    @foreach($fund_list['data'] as $val)
                        <tr>
                            <td>{{date('Y-m-d',strtotime($val['created_at']))}}</td>
                            @if($val['event_id'] == \App\Http\Dbs\Fund\FundHistoryDb::INVEST_OUT_CURRENT)
                            <td style=" color: red; ">-¥{{number_format($val['balance_change'],2)}}</td>
                            @else
                            <td style=" color: #00a0dc;">+¥{{number_format($val['balance_change'],2)}}</td>
                            @endif
                            <td>{{$val['note']}}</td>
                        </tr>
                    @endforeach
                @else
                    <tr><td colspan="5">暂无信息</td></tr>
                @endif

            </table>

        </section>

        <section class="w-line"></section>
        <section  class="w-bottom">
            <div class="w-bottom-btn">
                <a href="/invest/current/investOut" class="wap2-btn wap2-btn-half fl wap2-btn-blue">立即转出</a>
                <a href="/invest/current/confirm"  class="wap2-btn wap2-btn-half fr">立即转入</a>
            </div>
        </section>

        <!-- 使用加息券弹层开始 -->
        {{--<section class="wap2-pop" id="bonusList" style="display:none;">--}}
            {{--<div class="wap2-pop-mask"></div>--}}
            {{--<div class="wap2-pop-main">--}}
                {{--<div class="t-current2-7">使用加息劵</div>--}}
                {{--<div class="t-current2-9" data-value="0">暂不使用<span class="t-current2-icon hide"></span></div>--}}
                {{--<if condition="$bonusTotal gt 0">--}}
                    {{--<foreach name="bonusList" item="r">--}}
                        {{--<div class="t-current2-9" data-value="{$r.id}">--}}
                            {{--<dl>--}}
                                {{--<dt><span>{$r.rate}</span>%加息券</dt>--}}
                                {{--<dd>--}}
                                    {{--<p><span>●</span> 连续加息{$r.period}天</p>--}}
                                    {{--<p><span>●</span> 使用截止日期：{$r.use_end_time}</p>--}}
                                {{--</dd>--}}
                            {{--</dl>--}}
                            {{--<if condition="$key eq 0">--}}
                                {{--<span class="t-current2-icon"></span>--}}
                                {{--<else />--}}
                                {{--<span class="t-current2-icon hide"></span>--}}
                            {{--</if>--}}
                        {{--</div>--}}
                    {{--</foreach>--}}
                {{--</if>--}}
                {{--<div class="t-current2-10">--}}
                    {{--<input type="hidden" id="bonus_id" name="bonus_id" value="{$bonusList[0][id]}">--}}
                    {{--<input type="button" id="sub-bonus" value="确 定" class="wap2-btn t-current2-11">--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</section>--}}
        {{--<!--使用加息券弹层结束 -->--}}


        {{--<!-- 零钱计划加息券使用失败请稍候再试！弹层开始 -->--}}
        {{--<section class="wap2-pop" style="display:none" id="divErr">--}}
            {{--<div class="wap2-pop-mask"></div>--}}
            {{--<div class="wap2-pop-main">--}}

                {{--<div class="t-current2-13">--}}
                    {{--<dl>--}}
                        {{--<dt><img src="/static/weixin/images/wap2/t-current-dd1.png"></dt>--}}
                        {{--<dd>哇额～<br/><span id="errMsg">加息券使用失败！请刷新重试吧！</span></dd>--}}
                    {{--</dl>--}}
                    {{--<input type="button" id="sub-err" value="确 定" class="wap2-btn t-current2-14">--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</section>--}}
        {{--<!--零钱计划加息券使用失败请稍候再试！弹层结束 -->--}}

        {{--<!-- 成功使用加息劵弹层开始 -->--}}
        {{--<section class="wap2-pop" id="doBonusSucc" <empty name='isSuccess'>style="display:none"</empty>>--}}
        {{--<div class="wap2-pop-mask"></div>--}}
        {{--<div class="wap2-pop-main">--}}
            {{--<div class="t-current2-7">成功使用加息劵</div>--}}
            {{--<div class="t-current2-15"><span>加息劵面额</span> {$usedBonus.addRate}%</div>--}}
            {{--<div class="t-current2-15"><span>加息期限</span> {$usedBonus.period}天（使用之日起算）</div>--}}
            {{--<div class="t-current2-15"><span>加息时间</span> {$usedBonus.used_time}至{$usedBonus.rate_used_time}</div>--}}

            {{--<div class="t-current2-18">--}}
                {{--<p class="t-current2-16">今日年利率<span>{$rate}</span>%<em>+</em><i>{$usedBonus.addRate}</i><em>%</em></p>--}}
                {{--<p  class="t-current2-17">(平台每日年利率＋加息劵面额)</p>--}}
                {{--<input type="button" id="sub-true" value="确 定" class="wap2-btn t-current2-11">--}}
            {{--</div>--}}
        {{--</div>--}}
        {{--</section>--}}
        <!--成功使用加息劵弹层结束 -->

    </article>
@endsection

@section('jsScript')
    <script>
        $(function(){
            $('#more-records').click(function(){
                var page = parseInt($(this).attr('data-page'))+1;
                var url  = $(this).attr('data-url')+'{:time()}';
                var total = parseInt($(this).attr('data-total'));
                var $this = $(this);

                $.ajax({
                    url : url,
                    dataType : 'html',
                    data : {
                        p : page
                    },
                    success : function(res){

                        var hash = parseInt($this.attr('data-page'));
                        if(page === hash+1 && page<=total) {
                            $(res).find('.item').appendTo('#trade-records');
                            $this.attr('data-page',page);
                        } else {
                            $this.html('没有更多记录');
                        }
                    }
                });

            });

            //显示加息券列表数据
            $('#showBonus').click(function(){
                $(document).scrollTop(0);
                $("#bonusList").show();
            });

            //选择使用加息券
            $('.t-current2-9').each(function(){
                $(this).click(function(){
                    var bonusId = $(this).attr('data-value');
                    $("#bonus_id").val(bonusId);
                    $(".t-current2-icon").hide();
                    $(this).find("span").show();
                });
            });

            //使用加息券点击确认按钮
            $('#sub-bonus').click(function(){
                var bonusId = $("input[name=bonus_id]").val();
                if(bonusId > 0){
                    $("#bonusList").hide();
                    $(document).scrollTop(0);
                    $.ajax({
                        url:'/Current/ajaxDoBonus',
                        type:'POST',
                        data:{bonus_id:bonusId},
                        dataType:'json',
                        async: false,  //同步发送请求
                        success:function(result){
                            if(result.status == false) {
                                $("#errMsg").html(result.msg);
                                $("#divErr").show();
                                return false;
                            } else {
                                location.reload();
                            }
                        }
                    });
                }else{
                    location.reload();
                }
            });
            //取消填写交易密码
//            $('.cancel').click(function(){
//                $('.project-tips').text('');
//                $('.wap2-pop').hide();
//            });
            //提交交易密码，确定使用加息券
//            $("#sub").click(function() {
//                var bonusId = $("input[name=bonus_id]").val();
//                $.ajax({
//                    url:'/Current/ajaxDoBonus',
//                    type:'POST',
//                    data:{bonus_id:bonusId},
//                    dataType:'json',
//                    async: false,  //同步发送请求
//                    success:function(result){
//                        if(result.status == false) {
//                            password.val('');
//                            password.attr("placeholder", result.msg);
//                            return false;
//                        } else {
//                            $("#trading-password").hide();
//                            $(document).scrollTop(0);
//                            $("#doBonusSucc").show();
//                        }
//                    }
//                });
//
//            });

//            //使用成功，点击确认按钮，刷新本页
//            $("#sub-true").click(function(){
//                location.reload();
//            });
//
//            $("#sub-err").click(function(){
//                $("#divErr").hide();
//            });
        });
    </script>
@endsection
