@extends('wap.common.wapBase')

@section('title', 'App新手活动')

@section('keywords', env('META_KEYWORD'))

@section('description', env('META_DESCRIPTION'))

@section('css')
<meta name="format-detection" content="telephone=yes">
<link rel="stylesheet" type="text/css" href="{{ assetUrlByCdn('/static/weixin/css/novice.css') }}">
@endsection

@section('content')
<article>
    <section class="w-box-show">
        <div class="kill-tip"><a href="/Bonus/bonusDesc" id="a1">优惠券怎么使用</a><span></span></div>
    </section>
    <if condition="count($list) gt 0">
        <foreach name='list' key='k' item='v'>
            <if condition="($v.bonus_type eq 1) ">
                <div class="wap2-coupon-box wap2-coupon-bonus" utype="unused" id="w-alert{$k}" key="{$v.bonus_type}" show="{$v.user_bonus_id}" btype="{$v.type_bonus}">
                    <div class="wap2-coupon-wave"></div>
                    <div class="wap2-coupon-title">
                        <p><strong>红包</strong><span>{$v.name}</span></p>
                        <p>有效期至:{$v.use_end_time|date='Y-m-d',###} </p>
                    </div>
                    <div class="wap2-coupon-txt">
                        <if condition="($v.bonus_type eq 1) ">
                            <p>单笔购买满{$v.min_amount|round=###}元可用</p>
                        </if>
                        <p>
                            <if condition="$v.project_type_limit neq ''">
                                {$v.project_type_limit}
                            </if>
                        </p>
                    </div>
                    <div class="wap2-coupon-num">
                        <span>¥</span>
                        <strong>{$v.money|round=###}</strong>
                    </div>
                    <if condition="$v.status_text eq 'used'"><span class="wap2-coupon-icon2"></span></if>
                    <if condition="$v.status_text eq 'expired'"><span class="wap2-coupon-icon1"></span></if>
                </div>
                <elseif condition="$v.bonus_type eq 2"/>
                <section class="wap2-coupon-box wap2-coupon-add" utype="unused" key="{$v.bonus_type}"  show="{$v.user_bonus_id}" btype="{$v.type_bonus}">
                    <div class="wap2-coupon-wave"></div>
                    <div class="wap2-coupon-title">
                        <p><strong>加息券</strong><span>{$v.name}</span></p>
                        <p>有效期至:{$v.use_end_time|date='Y-m-d',###} </p>
                    </div>
                    <if condition="$v[type_bonus]">
                        <div class="wap2-coupon-txt">
                            <p>
                                <if condition="$v.project_type_limit neq ''">
                                    {$v.project_type_limit}
                                </if>
                            </p>
                            <p></p>
                        </div>
                        <else />
                        <div class="wap2-coupon-txt">
                            <p>零钱计划账户可享受连续{$v.period}天加息</p>
                            <p>限投零钱计划</p>
                        </div>
                    </if>
                    <div class="wap2-coupon-num">
                        <strong>{$v['rate']}</strong>
                        <span>%</span>
                    </div>
                    <if condition="$v.status_text eq 'used'"><span class="wap2-coupon-icon2"></span></if>
                    <if condition="$v.status_text eq 'expired'"><span class="wap2-coupon-icon1"></span></if>
                </section>
            </if>
        </foreach>
    <else/>
        <div class="w-dou-pd">
            <p class="center"><img src="{{assetUrlByCdn('/static/weixin/images/wap2/w-logo.png')}}" class="no-img"></p>
            <p class="w-zw">暂无优惠券</p>
        </div>
    </if>
</article>
@endsection

@section('jsScript')
<script type="text/javascript">
        function a(a,b,c,d){
            $("#"+a).click(function(){
                $("#"+b).show();
                var h = $("#"+c).outerHeight();
                var mt = parseInt(-h/2) + 'px';
                $("#"+c).css("margin-top",mt);
            });
            if(d){
                $("body").delegate("."+d ,"click",function () {
                    //$("."+d).click(function(){
                    $(".kill-pop-wrap").hide();
                });
            }
            $(".kill-pop i,.mask3").click(function(){
                $(".kill-pop-wrap").hide();
            });
        }
        //a("a1","a2","kill-pop");
 </script>
@endsection


