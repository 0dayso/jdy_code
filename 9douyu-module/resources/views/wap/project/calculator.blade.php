@extends('wap.common.wapBase')

@section('title','收益计算器')

@section('content')

<article>
    <section class="w-box-show bb-1px">
        <p class="center"><span  class="gray-title-bj font15px plr15px">预期收益</span></p>
        <p class="w-bule-color center mt10px"><span class=" font25px" id="principal-profit">1000.00</span>元</p>
        <p class="center mt10px"><span href="#" class="w-b8-color">● 每投资<em id="principal">10000.00</em>元，可获得收益<em id="profit" >324.99</em>元</span></p>
    </section>
    <section class="w-cc">

        <div class="wap2-input-box w-fff-bj w-cc1">
            <div class="w-cc2">还款方式</div>
            <div class="w-cc3">
                <select name="investType" class="w-cc6">
                    <option value="baseinterest">到期还本息</option>
                    <option value="equalInterest">等额本息</option>
                    <option value="onlyinterest">按月付息到期还本</option>
                    <option value="firstinterest">投资当日付息</option>
                </select>
                <span></span>
            </div>
        </div>

        <div class="wap2-input-box w-fff-bj w-cc1 mt20px">
            <div class="w-cc2">出借金额</div>
            <input name="cash" type="text" placeholder="大于0的整数" class="w-cc4"  value="10000" >
            <div class="w-cc3">元</div>
        </div>

        <div class="wap2-input-box w-fff-bj w-cc1 mt20px">
            <div class="w-cc2">项目期限</div>
            <input name="times" type="text" placeholder="请输入项目期限" class="w-cc4" value="30" ><div id="timeUnit" class="w-cc3 ">天</div>
        </div>

        <div class="wap2-input-box w-fff-bj w-cc1 mt20px">
            <div class="w-cc2">借款利率</div>
            <input   name="yearRate" type="text" placeholder="请输入借款利率" class="w-cc4" value="10" >
            <div class="w-cc3" >%</div>
        </div>

    </section>

    <section class="mt20px plr15px mb2">
        <input id="submit" type="button" class="w-btn calculator-submit"  value="计  算" />
    </section>
</article>

@endsection

@section('jsScript')
<script src="{{assetUrlByCdn('/static/js/principalInterest.js')}}" type="text/javascript"></script>
<script src="{{assetUrlByCdn('/static/js/jquery.plugin.js')}}" type="text/javascript"></script>


<script>
    $('[name=investType]').change(function(){
        var val = $(this).val();

        if(val === 'baseinterest' ||val == 'firstinterest') {
            $('#timeUnit').html('天');
        } else {
            $('#timeUnit').html('月');
        }
    });
    $('#submit').click(function(){
        var investType  = $('[name=investType]').val();
        var investCash  = $('[name=cash]').val();
        var times       = $('[name=times]').val();
        var yearRate    = $('[name=yearRate]').val();
        var result;

        result = $.getPrincipalInterestList(yearRate, Math.abs(times) , Math.abs(investCash) , investType);
        console.log(result);

        $('#principal').html(result.principal);
        $('#principal-profit').html($.toFixed(result.interest,2));
        $('#profit').html($.toFixed(result.interest,2));
    });
    $('#submit').click();
</script>
@endsection
