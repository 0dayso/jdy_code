<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=gbk">
</head>
<body>

<form action="{{ $apiGateWay }}" method="post" name="orderForm">
    <input type="hidden" name="requestId"		value="{{ $requestId }}">
    <input type="hidden" name="tradeProcess"	value="{{ $tradeProcess }}">
    <input type="hidden" name="mersignature"	value="{{ $mersignature }}">
    <input type="hidden" name="totalBizType"	value="{{ $totalBizType }}">
    <input type="hidden" name="totalPrice"		value="{{ $totalPrice }}">
    <input type="hidden" name="bankcode"		value="{{ $bankcode }}">
    <input type="hidden" name="backurl"			value="{{ $backurl }}">
    <input type="hidden" name="returnurl"		value="{{ $returnurl }}">
    <input type="hidden" name="noticeurl"		value="{{ $noticeurl }}">
    <input type="hidden" name="description"		value="{{ $description }}">
    {{--
    <input type="hidden" name="rnaName"			value="{{ $rnaName }}">
    <input type="hidden" name="rnaIdNumber"		value="{{ $rnaIdNumber }}">
    <input type="hidden" name="rnaMobilePhone"	value="{{ $rnaMobilePhone }}">
    --}}
    <input type="hidden" name="goodsDesc"		value="{{ $goodsDesc }}">
    {{--<input type="hidden" name="userIdIdentity"	value="{{ $userIdIdentity }}">  --}}
    <input type="hidden" name="payType"			value="{{ $payType }}">
    <input type="hidden" name="allowRePay"		value="{{ $allowRePay }}">
    <input type="hidden" name="rePayTimeOut"	value="{{ $rePayTimeOut }}">
    <input type="hidden" name="bankCardType"	value="{{ $bankCardType }}">

    <input type="hidden" name="productId"		value="{{ $productId }}">
    <input type="hidden" name="productName"		value="{{ $productName }}">
    <input type="hidden" name="fund"			value="{{ $fund }}">
    <input type="hidden" name="bizType"			value="{{ $bizType }}">
    <input type="hidden" name="merAcct"			value="{{ $merAcct }}">
    <input type="hidden" name="productNumber"	value="{{ $productNumber }}">
</form>

<script type="text/javascript">
    document.orderForm.submit();
</script>
</body>
</html>