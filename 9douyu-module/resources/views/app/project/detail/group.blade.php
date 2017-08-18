<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>九斗鱼，安全投资平台</title>
<meta name="keywords" content="" />
<meta name="description" content="" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no" />
<meta name="apple-mobile-web-app-capable" content="yes">
<link href="{{assetUrlByCdn('/static/images/favicon.ico')}}" rel="shortcut icon" type="image/vnd.microsoft.icon" />
<link rel="stylesheet" href="{{assetUrlByCdn('/static/app/css/app2base.css')}}" type="text/css" />
<link rel="stylesheet" href="{{assetUrlByCdn('/static/app/css/app2.css')}}" type="text/css" />

</head>
<body>
<article>
	<dl class="pp2-project-list">
		<dt class="pp2-project-list-dt"><span class="app2-icon app2-icon-1"></span>{{ $project["name"] }}</dt>
		<dd class="pp2-project-list-dd">
该期九省心项目是信贷类项目，是耀盛中国旗下耀盛信贷针对全国各地的中小企业提供的快速融资贷款的业务，所有的项目均经过RISKCALC风控技术评审，实地勘察。
</dd>
	</dl>
	<dl class="pp2-project-list">
		<dt class="pp2-project-list-dt"><span class="app2-icon app2-icon-6"></span>项目包含债权</dt>
		<dd class="">
			<table class="app2-project-table">
				@if($company)
                @foreach ( $company as $item )
				<tr>
					<td>{{ isset($item["plan_name"]) ? $item["plan_name"] : $item["company_name"] }}</td>
					<td>{{ $item['loan_amounts'] }}万</td>
					<td>{{ isset($item["program_area_location"]) ? $item["program_area_location"] : null }}</td>
                    <td>{{ isset($item["loan_use"]) ? $item["loan_use"] : null }}</td>
				</tr>
				@endforeach
				@endif
			</table>
		</dd>
	</dl>
	<dl class="pp2-project-list">
		<dt class="pp2-project-list-dt"><span class="app2-icon app2-icon-3"></span>风险控制</dt>
		<dd class="app2-project-source">
			平台对每个投资项目都有相应保障措施，同时建立了风险准备金账户，对平台每个投资项目提取 1%作为风险准备金。
		</dd>
	</dl>
	<dl class="pp2-project-list">
		<dt class="pp2-project-list-dt"><span class="app2-icon app2-icon-4"></span>资金安全</dt>
		<dd class="app2-project-secure">
			<dl>
				<dt>1.</dt>
				<dd>九斗鱼记录出借人的每笔投资，并生成符合法律法规的有效合同文件，且所有的
资金流向均由独立第三方机构代为管理，以确保用户资金安全；</dd>
			</dl>
			<dl>
				<dt>2.</dt>
				<dd>九斗鱼平台的所有投资项目均通过多重风险控制审核，并对投资项目进行全面风
险管理，以最大程度保障出借人的资金安全；</dd>
			</dl>
			<dl>
				<dt>3.</dt>
				<dd>九斗鱼平台全程采用 VeriSign256 位 SSL 强制加密证书进行数据加密传输，有效
保障银行账号、交易密码等机密信息在网络传输过程中不被查看、修改或窃取。</dd>
			</dl>
			<dl>
				<dt>4.</dt>
				<dd>平台所有的投资项目均交纳 1%作为风险准备金，由东亚银行监管；查看《风险准备金账户》</dd>
			</dl>
			
		</dd>
	</dl>
	<dl class="pp2-project-list">
		<dt class="pp2-project-list-dt"><span class="app2-icon app2-icon-5"></span>到期后如何赎回？</dt>
		<dd class="pp2-project-list-dd">
			本金和利息会自动存入您的九斗鱼账户，申请提现即可转入您绑定的银行卡中。
			
		</dd>
	</dl>
</article>
</body>
</html>
