@extends('wap.common.wapBase')
@section('title', '转出零钱计划')

@section('keywords', env('META_KEYWORD'))

@section('description', env('META_DESCRIPTION'))

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <article>
        <form action='/invest/current/doInvestOut' method="post" id="doInvestOut">
            <section class="wap2-input-group w-q-mt">
                <input type="hidden" name="invest_out_max" value="{{ $invest_out_max }}" />
                <input type="hidden" name="current_cash" value="{{$current_cash}}" />
                <div class="wap2-input-box2">
                    <p class="fr"><span>{{$current_cash}}</span> 元</p>
                    <p>零钱计划总额</p>
                </div>
            </section>
            <section class="wap2-input-group">
                <div class="wap2-input-box2">
                    <p class="fr">
                        <input type="text" name="cash" placeholder="请输入转出金额" class="wap2-input-1" autocomplete="off">元
                    </p>
                    <p>转出金额</p>
                </div>
            </section>
            <section class="wap2-tip error">
                <p class="project-tips">@if(Session::has('errors')) {{ Session::get('errors') }} @endif</p>
            </section>
            <section class="wap2-btn-wrap">
                <input type="button"   class="wap2-btn next" value="下一步">
            </section>
            <!-- 交易密码弹层开始 -->
            <section class="wap2-pop" style="display:none">
                <div class="wap2-pop-mask"></div>
                <div class="wap2-pop-main">
                    <div class="wap2-pop-tpw-title">
                        <ins>转出金额</ins>
                        ¥ <span></span>
                    </div>

                    <div class="wap2-pop-tpw-box clearfix">
                        <input type="password" name="trading_password"  placeholder="请输入交易密码" class="wap2-input-2 mb1">
                        <input type="reset" value="取消" class="wap2-btn wap2-btn-half fl wap2-btn-blue cancel">
                        <input type="button" id="sub"  value="确定" class="wap2-btn wap2-btn-half fr">
                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                    </div>
                    <div class="wap2-tip error">
                        <p class="m-tips"></p>
                    </div>
                </div>

            </section>
            <!-- 交易密码弹层结束 -->
        </form>
    </article>
@endsection

@section('jsScript')
    @include('wap.common.js')
    {{--<script src="{{ assetUrlByCdn('/') }}js/principalInterest.js"></script>--}}
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        (function($){

            $(document).ready(function(){
            $(".next").click(function() {
                var cash  = $.toFixed($.trim($("input[name=cash]").val()));
                var balance  = $.toFixed($.trim($("input[name=current_cash]").val()));
                var maxOut  = $.toFixed($.trim($("input[name=invest_out_max]").val()));

                if( cash<0 || cash==''){
                    $(".project-tips").html("请输入正确金额！");
                    $(".project-tips").show();
                    return false;
                }
                if( cash<0.01){
                    $(".project-tips").html("最小金额不能小于0.01");
                    $(".project-tips").show();
                    return false;
                }

                if( cash>balance ){
                    $(".project-tips").html("转出金额不能超过零钱计划总资产！");
                    $(".project-tips").show();
                    return false;
                }

                $('.wap2-pop').show();
                $('.wap2-pop-tpw-title span').html(cash);
            });
            //2015-10-9 13:26:14 赵思洋  使用此sub
            $("#sub").click(function() {
                var password = $("input[name=trading_password]");
                var passwordV = $.trim($("input[name=trading_password]").val());
                if(passwordV==''){
                    $(".m-tips").html("请输入交易密码！");
                    $(".m-tips").show();
                    return false;
                }
                $('#sub').attr("disabled",true);

                $.ajax({
                    url:'/user/checkTradePassword',
                    type:'POST',
                    data:{trading_password:passwordV},
                    dataType:'json',
                    async: false,  //同步发送请求
                    success:function(result){
                        if(result==false) {
                            password.val('');
                            password.attr("placeholder", result.msg);
                            $('#sub').attr("disabled",false);
                            return false;
                        } else {
                            $('#doInvestOut').submit();
                        }
                    }
                });
            });

            $(".pop-btn").click(function(){
                $(this).parents(".pop-wrap").hide();
            });
            $(".pop-mask").click(function(){
                $(this).parent(".pop-wrap").hide();
            });

            $('.cancel').click(function(){
                $('.project-tips').text('');
                $('.wap2-pop').hide();
            });
        });
    })(jQuery);
    </script>

@endsection



