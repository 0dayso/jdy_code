@extends('wap.common.wapBase')

@section('title', $title)

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <article>
        <section class="w-bc hidden">
            <p class="center mt25px"><span class="gray-title-bj1 font15px plr15px">账户余额</span></p>
            <p class="center w-fff-color mt15px"><span class="font30px">{{ $balance }}</span><span>元</span></p> 
        </section>

        <section class="w-button-box">
            <table class="w-table w-bc1">
                <tr>
                    <td width="50%" class="br1px"><p class="lh2rem w-ye-pl"><span>累计提现</span></p><p class="w-bule-color w-ye-pl"><span class="font15px">{{ $withdraw }} </span>元</p></td>
                    <td><p class="lh2rem w-ye-pl1"><span>累计充值</span></p><p  class="w-bule-color  w-ye-pl1"><span class="font15px ">{{ $totalRecharge }} </span>元</p></td>
                </tr>
            </table>

        </section>
        <section class="w-box-show mt15px hidden bt-1px">
            <div class="mlr20px" id="balance_child_tab">
                <a class="blue-title-bj fl w-37" page="1" type="1">余额变动明细</a>
                <a class="blue-title-bj fr w-37 gray-title-bj2" page="1" type="2">充值提现记录</a>
            </div>
        </section>

        <section class="w-box-show mt15px pd-tb0px pd-lr0px" id="balance_child">
            <div id="data_list">
                @if ( empty($LogList['total']) )
                    <div class="w-dou-pd">
                        <p class="center"><img src="{{assetUrlByCdn('/static/weixin/images/wap2/w-logo.png')}}" class="no-img"></p>
                        <p class="w-zw">暂无记录</p>
                    </div>
                @endif
                @include('wap.user.AccountBalance._balance_child')
            </div>
            @if ( isset($LogList['total']) && $LogList['total'] > $LogList['size'] )
                <p class="center mt15px" id="balance_child_more"><a href="javascript:void(0)" class="gray-title-bj mb15px w-bule-color">查看更多记录</a></p>
            @endif
            <p class="center mt15px" id="balance_child_more_no" style="display: none;"><a href="javascript:void(0)" class="gray-title-bj mb15px w-bule-color">没有更多记录</a></p>
        </section>
        <section class="w-line"></section>
        <section class="w-bottom">
             <div class="w-bottom-btn">
                <a href="/withdraw"><input type="reset" value="提现" class="wap2-btn wap2-btn-half fl wap2-btn-blue"></a>
                <a href="/pay/index"><input type="submit" value="充值" class="wap2-btn wap2-btn-half fr"></a>
             </div>
        </section>
    </article>
@endsection

@section('jsScript')
    {{--<script type="text/javascript" src="{{ App\Tools\ToolCdnStatic::statics('/static/js/jquery-1.9.1.min.js') }}"></script>--}}
    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        (function ($) {
            $(document).ready(function () {
                var noHtml = '<div class="w-dou-pd" id="zwjl"><p class="center "><img src="{{assetUrlByCdn('/static/weixin/images/wap2/w-logo.png')}}" class="no-img"></p><p class="w-zw">暂无记录</p></div>';
                //get data
                function getData(type, page, add) {
                    $.ajax({
                        url: "/user/account/getLogList",
                        dataType: 'json',
                        type: 'post',
                        data: {'t': type, 'p': page},
                        success: function (result) {
                            /**
                             * 用户退出
                             */
                            if(result.redirectUrl !== undefined){
                                window.location.href = result.redirectUrl;
                                return;
                            }
                            if (add == 0) {
                                $('#data_list').html(result.content);
                            } else {
                                $('#data_list').append(result.content);
                            }

                            if (result.content != '') {
                                var eq = parseInt(result.type);
                                eq -= 1;
                                $now_tab = $('#balance_child_tab a').eq(eq);
                                $now_tab.attr('page', result.page);
                            } else {
                                $("#balance_child_more").hide();
                            }
                            if(page == 1 && result.content == ''){
                                $('#data_list').html(noHtml);
                            }
                            if(page != 1 && result.content == ''){
                                $("#balance_child_more_no").show();
                            }
                        },
                        error: function (msg) {
                            alert('获取失败，请稍候再试');
                        }
                    });
                }

                //reset
                function tabReset() {
                    var $tab0 = $('#balance_child_tab a').eq(0);
                    var $tab1 = $('#balance_child_tab a').eq(1);
                    $tab0.attr('page', 1);
                    $tab1.attr('page', 1);
                }

                //tab
                $('#balance_child_tab a').click(function () {
                    if ($(this).hasClass('gray-title-bj2')) {
                        tabReset();
                        //切换样式
                        $(this).siblings().addClass('gray-title-bj2');
                        $(this).removeClass('gray-title-bj2');
                        //拉取数据
                        var page = parseInt($(this).attr('page'));
                        var type = parseInt($(this).attr('type'));
                        getData(type, page, 0);
                        $("#balance_child_more").show();
                        $("#balance_child_more_no").hide();
                    }
                });
                //more
                $("#balance_child_more").click(function () {
                    $that = $('#balance_child_tab a.gray-title-bj2').siblings();
                    var page = parseInt($that.attr('page')) + 1;
                    var type = parseInt($that.attr('type'));
                    getData(type, page, 1);
                });
            });
        })(jQuery);
    </script>

@endsection
