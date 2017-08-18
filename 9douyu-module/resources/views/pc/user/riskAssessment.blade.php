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
                <form id="riskAForm" method="post" action="/user/riskAssessmentSecond">
                    <meta name="csrf-token" content="{{ csrf_token() }}">
                <dl class="riskA-block">
                    <dt>1. 您的年龄在以下哪个范围内？<span>请选择</span></dt>
                    <dd class="inline">
                        <input type="radio" name="question1" id="1" value="a"><label for="1">A. 29岁以下</label>
                        <input type="radio" name="question1" id="2" value="b"><label for="2">B. 30-39岁</label>
                        <input type="radio" name="question1" id="3" value="c"><label for="3">C. 40-49岁</label>
                        <input type="radio" name="question1" id="4" value="d"><label for="4">D. 50-59岁</label>
                        <input type="radio" name="question1" id="5" value="e"><label for="5">E. 60岁以上</label>
                    </dd>
                    <dt>2. 您有过几年的投资经验？<span>请选择</span></dt>
                    <dd class="inline">
                        <input type="radio" name="question2" id="6" value="a"><label for="6">A. 10年以上</label>
                        <input type="radio" name="question2" id="7" value="b"><label for="7">B. 6-10年</label>
                        <input type="radio" name="question2" id="8" value="c"><label for="8">C. 3-5年</label>
                        <input type="radio" name="question2" id="9" value="d"><label for="9">D. 1-2年</label>
                        <input type="radio" name="question2" id="10" value="e"><label for="10">E. 1年以下 </label>
                    </dd>
                    <dt>3. 您是否有过投资经验？<span>请选择</span></dt>
                    <dd>
                        <input type="radio" name="question3" id="11" value="a"><label for="11">A. 有投资贵金属、外汇、期货、期权等高风险衍生品经验</label><br>
                        
                        <input type="radio" name="question3" id="12" value="b"><label for="12">B. 有投资股票、股票型基金的经验</label><br>
                        
                        <input type="radio" name="question3" id="13" value="c"><label for="13">C. 有购买过银行的理财产品、债券基金、分红型、投连险</label><br>
                        
                        <input type="radio" name="question3" id="14" value="d"><label for="14">D. 有购买过保本基金、货币基金（如余额宝）、信托等低风险产品</label><br>
                        
                        <input type="radio" name="question3" id="15" value="e"><label for="15">E. 从未有过投资经历，只存银行的定期或活期</label>
                    </dd>
                    <dt>4. 您的家庭目前全年收入状况如何？<span>请选择</span></dt>
                    <dd class="inline">
                        <input type="radio" name="question4" id="16" value="a"><label for="16">A. 50万元以上</label>
                        <input type="radio" name="question4" id="17" value="b"><label for="17">B. 30-50万元</label>
                        <input type="radio" name="question4" id="18" value="c"><label for="18">C. 15-30万元</label>
                        <input type="radio" name="question4" id="19" value="d"><label for="19">D. 5-15万元</label>
                        <input type="radio" name="question4" id="20" value="e"><label for="20">E. 5万元以下</label>
                    </dd>
                    <dt>5. 您投资的主要目的是什么？选择最符合您的一个描述：<span>请选择</span></dt>
                    <dd>
                        <input type="radio" name="question5" id="21" value="a"><label for="21">A. 关心长期的高回报，能够接受短期的资产价值波动</label><br>
                        
                        <input type="radio" name="question5" id="22" value="b"><label for="22">B. 倾向长期的成长，较少关心短期的回报以及波动</label><br>
                        
                        <input type="radio" name="question5" id="23" value="c"><label for="23">C. 希望投资能获得一定的增值，同时获得波动适度的年回报</label><br>
                        
                        <input type="radio" name="question5" id="24" value="d"><label for="24">D. 只想确保资产的安全性，同时希望能够得到固定的收益</label><br>
                        
                        <input type="radio" name="question5" id="25" value="e"><label for="25">E. 希望利用投资以及投资所获得的收益在短期内用于大额的购买计划</label>
                    </dd>
                </dl>
                <div class="tc">
                    <input type="button" value="下一步" id="doSubmit" class="btn btn-blue btn-small">
                </div>
                </form>
    		</div>
    	</div>

    </div>
    <div class="clear"></div>
</div>
@endsection

@section('jspage')
<script type="text/javascript">
(function($){
    $(function(){
        $('#doSubmit').on('click',function(){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var arr = [];
            var val;
            $('#riskAForm input[type=radio]').each(function(){
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
            }else{
                $.ajax({
                    url : '/user/riskAssessmentSecond',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        'question1': arr[0],
                        'question2': arr[1],
                        'question3': arr[2],
                        'question4': arr[3],
                        'question5': arr[4]
                    },
                    success : function(result) {
                        if(result.status){
                            window.location.href='/user/riskAssessment2';
                        }else{
                            alert(result.msg);
                        }
                    },
                });
            }
        })
    })
})(jQuery)
</script>
@endsection

