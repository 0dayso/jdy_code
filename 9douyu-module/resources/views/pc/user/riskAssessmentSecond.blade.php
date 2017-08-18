@extends('pc.common.layout')

@section('title','风险评估')

@section('content')

<div class="m-myuser">
    <!-- account begins -->
    @include('pc.common/leftMenu')

    <div class="m-content">
    	<div class="riskA-box">
    		<div class="riskA-title">风险评估</div>
    		<div class="riskA-main">
    			<div class="riskA-info">
    				<p>风险测试问卷能够帮助出借人准确的对自我风险承受能力，投资理念，投资性格等进行专业的认知测试，综合评估您的风险承受能力高低，是出借人进行投资理财之前重要的准备工作。</p><br>
    				<p class="red">本测评表涉及内容仅供九斗鱼评平台评估出借人风险承受能力，为客户提供适当的产品和服务时使用，九斗鱼平台将履行保密义务。请认真选择以下问题：</p>
				</div>
                <form id="riskAFormSec">
                    <meta name="csrf-token" content="{{ csrf_token() }}">
                <dl class="riskA-block">
                    <dt>6. 当您进行投资时（例如基金、股票），您能接受一年内损失多少？<span>请选择</span></dt>
                    <dd>
                        <input type="radio" name="question6" id="26" value="a"><label for="26">A. 我能承受25％以上亏损</label><br>
                        <input type="radio" name="question6" id="27" value="b"><label for="27">B. 我能承受10-20％的亏损</label><br>
                        <input type="radio" name="question6" id="28" value="c"><label for="28">C. 我最多只能承受5-10%的亏损</label><br>
                        <input type="radio" name="question6" id="29" value="d"><label for="29">D. 我最多只能承受5%以下的亏损</label><br>
                        <input type="radio" name="question6" id="30" value="e"><label for="30">E. 我几乎不能承受任何亏损</label>
                    </dd>
                    <dt>7. 目前您的投资主要是哪一品种？<span>请选择</span></dt>
                    <dd>
                        <input type="radio" name="question7" id="31" value="a"><label for="31">A. 外汇、期货、现货贵金属 等超高风险资产</label><br>
                        <input type="radio" name="question7" id="32" value="b"><label for="32">B.  股票、股票基金、私募股权基金 等高风险资产</label><br>
                        <input type="radio" name="question7" id="33" value="c"><label for="33">C. 混合基金、指数基金、结构类产品 等较高风险资产</label><br>
                        <input type="radio" name="question7" id="34" value="d"><label for="34">D. 银行理财产品、信托、固定收益类基金(有限合伙基金)、货币基金(如余额宝）</label><br>
                        <input type="radio" name="question7" id="35" value="e"><label for="35">E. 活期、定期存款、国债、保险</label>
                    </dd>
                    <dt>8. 如果您拥有50万用来建立资产组合，您会选择下面哪一个组合？<span>请选择</span></dt>
                    <dd>
                        <input type="radio" name="question8" id="36" value="a"><label for="36">A. 低风险投资、一般风险投资、高风险投资的比重分别为 5:15:80 </label><br>
                        
                        <input type="radio" name="question8" id="37" value="b"><label for="37">B. 低风险投资、一般风险投资、高风险投资的比重分别为 10:30:60</label><br>
                        
                        <input type="radio" name="question8" id="38" value="c"><label for="38">C. 低风险投资、一般风险投资、高风险投资的比重分别为 30:40:30</label><br>
                        
                        <input type="radio" name="question8" id="39" value="d"><label for="39">D. 低风险投资、一般风险投资、高风险投资的比重分别为 60:30:10</label><br>
                        
                        <input type="radio" name="question8" id="40" value="e"><label for="40">E. 低风险投资、一般风险投资、高风险投资的比重分别为 80:15:5</label>
                    </dd>
                    <dt>9. 长期风险承受水平：下面哪一种描述最符合您可接受的价值波动程度？<span>请选择</span></dt>
                    <dd>
                        <input type="radio" name="question9" id="41" value="a"><label for="41">A. 希望赚取最高回报潜力，能接受3年以上的负面波动，包括损失本金</label><br>
                        <input type="radio" name="question9" id="42" value="b"><label for="42">B. 希望赚取较高回报潜力，能接受3年以上的负面波动</label><br>
                        <input type="radio" name="question9" id="43" value="c"><label for="43">C. 寻求资金较高收益，可接受3年内负面波动，使回报显著高于定期存款 </label><br>
                        <input type="radio" name="question9" id="44" value="d"><label for="44">D. 保守投资，但愿意在2年内接受少许负面波动，使回报高于定期存款</label><br>
                        <input type="radio" name="question9" id="45" value="e"><label for="45">E. 不希望投资本金承担风险。我愿意接受的回报大约与定期存款一样</label>
                    </dd>
                    <dt>10. 假设现有以下几个投资品种，那您会选择哪一个？<span>请选择</span></dt>
                    <dd>
                        <input type="radio" name="question10" id="46" value="a"><label for="46">A.  收益率在30%以上，同时本金也有可能亏损20%以上</label><br>
                        
                        <input type="radio" name="question10" id="47" value="b"><label for="47">B. 本金不保证，收益率在20%以内，同时本金也有可能亏损10%以内</label><br>
                        
                        <input type="radio" name="question10" id="48" value="c"><label for="48">C. 本息保证，收益率在10%左右</label><br>
                        
                        <input type="radio" name="question10" id="49" value="d"><label for="49">D. 本息保证，收益率在4-6%之间</label><br>
                        
                        <input type="radio" name="question10" id="50" value="e"><label for="50">E. 银行定期存款，收益率在3%</label>
                    </dd>
                </dl>
                <div class="tc">
                <input type="button" value="确认提交" class="btn btn-blue btn-small" id="riskButton">
                </div>
                </form>
    		</div>
    	</div>
	    
    </div>
    <div class="clear"></div>
</div>

<div class="layer_wrap js-mask" data-modul="modul1" >
    <div class="Js_layer_mask layer_mask" data-toggle="mask" data-target="js-mask"></div>
    <div class="Js_layer layer">
         <div class="layer_title">评估提交成功<a href="javascript:;" class="layer_close Js_layer_close" data-toggle="mask" data-target="js-mask"></a></div>
         <div class="layer_con riskA-con">
            <!-- layer content here -->
            <p>亲爱的九斗鱼用户，根据风险评估结果，<br>您的风险承受能力为：</p>
            <p><strong id="assessmentType"></strong></p>
            <p><a href="/project/index" class="btn btn-blue btn-large">立即出借</a></p>
         </div>
    </div>
</div>

@endsection

@section('jspage')
<script type="text/javascript">
(function($){
    $(function(){
        $('.layer_mask,.layer_close').click(function(){
            window.location.href = '/user'
        })

        $('#riskButton').click(function(){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var arr = [];
            var val;
            $('#riskAFormSec input[type=radio]').each(function(){
                if($(this).prop("checked")){
                    val = $(this).val()
                    arr.push(val);
                    $(this).parent('dd').prev('dt').find('span').remove();
                }else{
                    $(this).parent('dd').prev('dt').find('span').addClass('error');
                }
            })
              console.log(arr)  

            
            if(arr.length<5){
                return false;
            }

            $.ajax({
                url : '/user/assessment',
                type: 'POST',
                dataType: 'json',
                data: {
                    'question6': arr[0],
                    'question7': arr[1],
                    'question8': arr[2],
                    'question9': arr[3],
                    'question10': arr[4]
                },
                success : function(result) {
                    if(result.status){
                        $('#assessmentType').html(result['data']);
                        $('.layer_wrap').show();
                    }else{
                        alert(result.msg);
                        window.location.href='/user/riskAssessment';
                    }
                },
            });
        })
    })
})(jQuery)
</script>
@endsection

