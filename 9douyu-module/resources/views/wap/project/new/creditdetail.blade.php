@extends('wap.common.wapBase')

@section('title','项目详情')

@section('content')

<article>
	<!-- style one -->
    <section class="w-box-show">
       <table class="App4-project-detail">
		   <tr>
			   <td>项目名称</td>
			   <td>{{ $project['name'].' '.$project['id'] }}  </td>
		   </tr>
		   <tr>
			   <td>借款利率</td>
			   <td>{{ (float)$project['profit_percentage'] }}%</td>
		   </tr>
		   <tr>
			   <td>借款期限</td>
			   <td>{{ $project['invest_time_note'] }}</td>
		   </tr>
		   <tr>
			   <td>还款方式</td>
			   <td>{{ $project['refund_type_note'] }}</td>
		   </tr>
		   <tr>
			   <td>到期还款日</td>
			   <td>{{ $project['end_at'] }}</td>
		   </tr>
		   <tr>
			   <td>借款总额</td>
			   <td>{{ number_format($project['total_amount'],2) }}元</td>
		   </tr>
		   <tr>
			   <td>募集开始时间</td>
			   <td>{{ date('Y-m-d', strtotime($project['publish_at'])) }}（募集时间最长不超过20天）</td>
		   </tr>
       	<tr>
       		<td>风险等级</td>
       		<td>保守型</td>
       	</tr>
       	<tr>
       		<td>出借条件</td>
       		<td>最低100元起投，最高不超过剩余项目总额</td>
       	</tr>
       	<tr>
       		<td>提前赎回方式</td>
       		<td>持有债权项目30天（含）即可申请债权转让，赎回时间以实际转让成功时间为准</td>
       	</tr>
       	<tr>
       		<td>费用</td>
       		<td>买入费用：0.00%<br>退出费用：0.00%<br>提前赎回费率：0.00%</td>
       	</tr>
       	<tr>
       		<td>项目介绍</td>
       		<td>{{!empty($creditDetail['companyView']) && $creditDetail['companyView']['founded_time'] != '0000-00-00 00:00:00' && isset($creditDetail['companyView']['background']) ? $creditDetail['companyView']['background'] : ' 债权借款人均为工薪精英人群，该人群有较高的教育背景、稳定的经济收入及良好的信用意识。'}}</td>
       	</tr>
       	{{--<tr>
       		<td>协议范本</td>
       		<td><a href="javascript:;">【点击查看】</a></td>
       	</tr>--}}
       </table>

       <div class="App4-company-detail">
       	<h6>债权企业信息</h6>
       	<table>
			<tr>
				<td>借款人姓名：{{isset($creditDetail['companyView']['loan_username'])  && !empty($creditDetail['companyView']['loan_username']) ? substr($creditDetail['companyView']['loan_username'] ,0,3).'**' : null }}</td>
				<td>性别：{{(isset($creditDetail['companyView']['sex']) &&$creditDetail['companyView']['sex'] == 1) ? '男' : '女'}}</td>
			</tr>
			<tr>
				<td>年龄：{{isset($creditDetail['companyView']['age']) ? $creditDetail['companyView']['age'] : null}}</td>
				<td>婚姻：{{isset($creditDetail['companyView']['home_stability']) ? $creditDetail['companyView']['home_stability'] : null}}</td>
			</tr>
			<tr>
				<td>身份证号码：{{isset($creditDetail['companyView']['loan_user_identity'])  && !empty($creditDetail['companyView']['loan_user_identity']) ? substr(explode(',',$creditDetail['companyView']['loan_user_identity'])[0] ,0,3) .'********'.substr(explode(',',$creditDetail['companyView']['loan_user_identity'])[0] ,-3) : null }}</td>
				<td>户籍：{{isset($creditDetail['companyView']['family_register']) ? $creditDetail['companyView']['family_register'] : null}}</td>
			<tr>
				<td>借款用途：{{isset($creditDetail['companyView']['loan_use']) ? $creditDetail['companyView']['loan_use'] : '资金周转'}}</td>
			</tr>
       	</table>
			
       </div>
    </section>
</article>

@endsection
