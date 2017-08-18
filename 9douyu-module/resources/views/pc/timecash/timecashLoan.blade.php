@extends('pc.common.layout')

@section('title', '信用借款申请')

@section('keywords', env('META_KEYWORD'))

@section('description', env('META_DESCRIPTION'))

@section('csspage')
	<meta name="format-detection" content="telephone=yes">
	<link rel="stylesheet" type="text/css" href="{{ assetUrlByCdn('/static/css/timecash.css') }}">

@endsection
@section('content')
	<div class="timecash-loan">
		<div class="wrap">
			<div class="loan-form">
				<form method="post" id="timecashLoan">
					<h2>信用借款申请</h2>
					<div class="loan-group">
						<select name="quota" id="quota">
							<option selected="selected" value="">选择借款额度</option>
							@foreach($loanAmountArr as $kk1=>$vv1)
								<option value={{ $kk1 }}>{{ $vv1 }}</option>
							@endforeach
						</select>
						<i class="loan-arrow"></i>
					</div>
					<div class="loan-group">
						<select name="limit" id="limit">
							<option selected="selected" value="">选择借款期限</option>
							@foreach($loanTimeArr as $kk2=>$vv2)
								<option value={{ $kk2 }}>{{ $vv2 }}</option>
							@endforeach
						</select>
						<i class="loan-arrow"></i>
					</div>
					<div class="loan-group">
						<select name="repayment" id="repayment">
							<option selected="selected" value="">选择还款方式</option>
							@foreach($refundTypeArr as $kk3=>$vv3)
								<option value={{ $kk3 }}>{{ $vv3 }}</option>
							@endforeach
						</select>
						<i class="loan-arrow"></i>
					</div>

					<div class="loan-group">
						<span class="loan-icon username"></span>
						<input type="text" name="name" value="{{ $name }}" readonly>
						<i class="loan-bit">*</i>
					</div>
					<div class="loan-group">
						<span class="loan-icon phone"></span>
						<input type="text" name="phone" value="{{ $phone }}" readonly>
						<i class="loan-bit">*</i>
					</div>

					<div class="loan-info" id="showTips"></div>
					<input type="button" value="立即申请" class="loan-btn">
					<input type="hidden" name="_token"   	value="{{ csrf_token() }}">
					<input type="hidden" name="repeatHit"  	id="repeatHit"  value="closed">
				</form>
			</div>
			<div class="loan-tip">
				<p>温馨提示：</p>
				<p>您正在申请一款无抵押无担保押的线上信用借款产品，目前正处于内测阶段，请留下您的借款需求及联系方式，将会有专人与您联系，谢谢。</p>
			</div>
		</div>
	</div>
@endsection

@section('jspage')
	<script type="text/javascript">
		$(function(){
			var patten = /^(13\d|14[57]|15[012356789]|18\d|17[013678])\d{8}$/;
			$(".loan-btn").click(function(){
				var quotaValue = $('#quota').val();
				var limitValue = $('#limit').val();
				var repaymentValue = $('#repayment').val();
				var name 	= $('input[name=name]').val();
				var phone 	= $('input[name=phone]').val();
				var _token	= $('input[name=_token]').val()

				if(quotaValue == ''){
					$('#showTips').html("请选择您的借款额度").addClass('error');
					return false;
				}
				if(limitValue ==''){
					$('#showTips').html("请选择您的借款期限").addClass('error');
					return false;
				}
				if(repaymentValue ==''){
					$('#showTips').html("请选择您的还款方式").addClass('error');
					return false;
				}
				if(name == ''){
					$('#showTips').html("请填写您的姓名").addClass('error');
					return false;
				}
				if(phone == ''){
					$('#showTips').html("请填写您的手机号").addClass('error');
					return false;
				}else if(!patten.test(phone)){
					$('#showTips').html('请输入正确的手机号').addClass('error');
					return false;
				}
				var repeatHit 	= $("#repeatHit").val()

				//重复点击
				if( repeatHit=="opened"){
					$('#showTips').removeClass('loan-success');
					$('#showTips').html("请不要重复提交").addClass('error');
					return false;
				}
				$("#repeatHit").val("opened");
				//$('.loan-btn').attr('disabled',true);
				$.ajax({
					url	: "/timecash/dotimecashloan",
					type: 'POST',
					data: {'loan_amount':quotaValue,'loan_time':limitValue,'refund_type':repaymentValue,'name':name,'phone':phone,'_token':_token},
					dataType:'json',
					success : function(json){
						if( json.status==true || json.code==200){
							$('#showTips').removeClass('error');
							$('#showTips').html("您的预申请已提交").addClass('loan-success');
						} else if( json.status == false || json.code ==500 ){
							$('#showTips').html(json.msg).addClass('error');
							//$('.loan-btn').attr('disabled',false);
							$("#repeatHit").val("closed");
						}
						return false;
					},
					error : function(msg) {
						$('#showTips').html('抱歉网络出错了').addClass('error');
						//$('.loan-btn').attr('disabled',false);
						$("#repeatHit").val("closed");
						return false;
					}
				})

			})

		})
	</script>
@endsection