<?php
$loginUser = isset($data['loginUser']) ? $data['loginUser'] : null;
$project   = isset($data['project']) ? $data['project'] : null;
$credit	   = isset($data['credit'][0]) ? $data['credit'][0] : null;
$credit	   = $credit == null && isset($data['credit']) ? $data['credit'] : $credit;
$signDay   = isset($data['signDay']) ? $data['signDay'] : null;
$invested  = isset($data['invested']) ? $data['invested'] : null;
$investing = isset($data['investing']) ? $data['investing'] : null;
$refundTime= isset($data['refundTime']) ? $data['refundTime'] : null;
$refundPlan= isset($data['refundPlan']) ? $data['refundPlan'] : null;
?>
<h2 style="text-align: center;">
	<span>债权转让协议</span>
</h2>
<p>
	<span>本《债权转让协议》（“本协议”）由以下双方于{{ $signDay }}签订：</span>
</p>
<p>
	<span>甲方（转让人/原债权人）：池洪英</span>
</p>
<p>
	<span>身份证号：110222195008065720</span>
</p>
<p>
	<span>乙方（受让人/新债权人）：{{ $loginUser['real_name'] or null }}</span>
</p>
<p>
	<span>九斗鱼用户名：{{ $loginUser['phone'] or null }}</span>
</p>
<p>
	<span>身份证号码：{{ $loginUser['identity_card'] or null }}</span>
</p>
<p>
	<span>电子邮件地址：{{ $loginUser['user_info']['email'] or null }}</span>
</p>
<p>
	<span>就甲方通过由星果时代信息技术有限公司运营管理的九斗鱼（域名为 www.9douyu.com，“九斗鱼”）平台向乙方转让债权事宜，双方根据平等、自愿的原则，达成协议如下：</span>
</p>
<p>
	<span>一、债权转让</span>
</p>
<p>
	<span>甲方同意通过九斗鱼将自身的债权（“标的债权”）转让给乙方，乙方同意受让该等债权：</span>
</p>
<p>
<?php
$loan_username = $loan_user_identity = [];
$realLoanUserNum   = 1;
$company_name 		= null;

if(!empty($credit['loan_username']) && !empty($credit['loan_user_identity'])){
	$loan_username 		 = explode(',', $credit['loan_username']);

	$loan_user_identity  = explode(',', $credit['loan_user_identity']);

	$realLoanUserNum	 = empty(count($loan_username) ) ? 1 : count($loan_username);
}

//企业名称
if(!empty($credit['company_name'])){
	$company_name =$credit['company_name'];
}
if(!empty($credit['plan_name'])){
	$company_name =$credit['plan_name'];
}
?>
<table border="1">
	<tbody>
		<tr>
			<td colspan="6" align="center">债权基本信息</td>
		</tr>
		<tr>
			<td align="center">项目名称</td>
			<td align="center">借款企业名称</td>
			<td align="center">借款人姓名</td>
			<td align="center">借款人证件号</td>
			<td align="center">初始借款金额</td>
			<td align="center">借款人借款用途</td>
		</tr>
		<tr>
			<td rowspan="{{ $realLoanUserNum }}" align="center">九省心 {{ $project['invest_time_note'] or null }} 月期 {{ $project['id'] or null }}</td>
			<td rowspan="{{ $realLoanUserNum }}" align="center">
				{{ $company_name }}</td>
			<td align="center">{{ $loan_username[0] or null }}</td>
			<td align="center">{{ $loan_user_identity[0] or null }}</td>
			<td rowspan="{{ $realLoanUserNum }}" align="center"><?php echo isset($project['total_amount']) ? \App\Tools\ToolMoney::moneyFormat($project['total_amount']) : null; ?></td>
			<td rowspan="{{ $realLoanUserNum }}" align="center">{{ $credit['loan_use'] or null }}</td>
		</tr>
		<?php
		if(count($loan_username) > 1){
		for ($i = 2; $i <= count($loan_username); $i++) {
		?>
		<tr><td align="center">{{ $loan_username[$i] or null }}</td><td align="center">{{ $loan_user_identity[$i] or null }}</td></tr>
		<?php
		}
		}
		?>
	</tbody>
</table>
<table class="ke-zeroborder" border="0">
	<tbody>
		<tr>
			<td>&nbsp;</td>
		</tr>
	</tbody>
</table>
<table border="1">
	<tbody>
		<tr>
			<td colspan="7" align="center">借款人月还款情况下债权收益信息</td>
		</tr>
		<tr>
			<td align="center">项目名称</td>
			<td align="center">初始借款金额</td>
			<td align="center">本次转让债权价值</td>
			<td align="center">需支付对价</td>
			<td align="center">还款起始日期</td>
			<td align="center">还款期限（月）</td>
			<td align="center">{{ $project['refund_type_note'] }}</td>
		</tr>
		<tr>
			<td align="center">九省心 {{ $project['invest_time_note'] }} {{ $project['id'] }}</td>
			<td align="center"><?php echo isset($project['total_amount']) ? \App\Tools\ToolMoney::moneyFormat($project['total_amount']) : null; ?></td>
			<td align="center"><?php echo isset($invested["cash"]) ? \App\Tools\ToolMoney::moneyFormat($invested["cash"]) : null;?></td>
			<?php
			$cash = 0.00;
			if(!empty($invested)){
				if($invested['bonus_type'] == 300){
					$cash = $invested["cash"] -  $invested['bonus_value'];
				}else{
					$cash = $invested["cash"];
				}
			}

			?>
			<td align="center"><?php echo \App\Tools\ToolMoney::moneyFormat($cash); ?></td>
			<td align="center">{{  $refundTime }}</td>
			<td align="center">{{ $project['format_invest_time'] or null }}</td>
			<td align="center"><?php echo isset($project['profit_percentage']) ? App\Tools\ToolMoney::moneyFormat($project['profit_percentage']) : null; ?>%</td>

		</tr>
	</tbody>
</table>
</p>
<p>
	<span>二、债权转让流程</span>
</p>
<p>
	<span>2.1
		乙方按照九斗鱼的规则，通过对甲方的债权（“标的债权”）转让需求点击“投标”按钮并点击确认订立本协议后，本协议即成立并立即生效。</span>
</p>
<p>
	<span>2.2
		同时，乙方对标的债权转让需求点击“投标”按钮，即不可撤销地授权九斗鱼，委托其合作的第三方支付机构及银行等，从在银行以耀盛汇融名义开立的资金监管账户（“监管账户”）中乙方名下虚拟账户（“乙方九斗鱼账户”）中，将金额等同于本协议第一条所列的合计“需支付对价”的金额划转至甲方名下虚拟账户（“甲方九斗鱼账户”）中。上述划转完成视为标的债权已转让成功。</span>
</p>
<p>
	<span>三、效力</span>
</p>
<p>
	<span>自标的债权转让成功之日起，乙方成为标的债权的债权人，承继甲方与标的债权借款人签订的相应《借款协议》项下出借人的权利并承担出借人的义务。如相应《借款协议》中约定了由第三方承担担保责任的，第三方应根据相应《借款协议》的约定继续对乙方承担连带保证责任。</span>
</p>
<p>
	<span>四、声明与保证</span>
</p>
<p>
	<span>乙方保证其所用于受让标的债权的资金来源合法，乙方是该资金的合法所有人，如果第三方对资金归属、合法性问题发生争议，由乙方自行负责解决。</span>
</p>
<p>
	<span>五、其他</span>
</p>
<p>
	<span>5.1 本协议的任何修改、补充均须以九斗鱼平台电子文本形式作出。</span>
</p>
<p>
	<span>5.2
		甲乙双方均确认，本协议的签订、生效和履行以不违反中国的法律法规为前提。如果本协议中的任何一条或多条违反适用的法律法规，则该条将被视为无效，但该无效条款并不影响本协议其他条款的效力。</span>
</p>
<p>
	<span>5.3
		如果甲乙双方在本协议履行过程中发生任何争议，应友好协商解决；如协商不成，则须提交甲方或乙方所在地人民法院进行诉讼。</span>
</p>
<p>
	<span>5.4 本协议双方委托九斗鱼保管所有与本协议有关的书面文件或电子信息。</span>
</p>
<p>
	<span>5.5
		本协议中所使用的定义，除非在上下文中另有定义外，应具有九斗鱼公布的《九斗鱼网站定义与释义规则》中定义的含义。本协议中，除非另有规定，否则应适用九斗鱼公布的《九斗鱼网站定义与释义规则》规定的释义规则。</span>
</p>