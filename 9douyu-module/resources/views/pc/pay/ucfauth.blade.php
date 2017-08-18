<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
</head>
<body>

<form action="{{ $apiGateWay }}" method="post" name="orderForm">
    <input name="secId" type="hidden" id="secId" value="{{$secId}}" />
    <input name="version" type="hidden" id="version" value="{{$version}}" />
    <input name="reqSn" type="hidden" id="reqSn" value="{{$reqSn}}" />
    <input name="merchantId" type="hidden" id="merchantId" value="{{$merchantId}}" />
    <input name="mobileNo" type="hidden" id="mobileNo" value="{{$mobileNo}}" />
    <input name="outOrderId" type="hidden" id="outOrderId" value="{{$outOrderId}}" />
    <input name="userId" type="hidden" id="userId" value="{{$userId}}" />
    <input name="realName" type="hidden" id="realName" value="{{$realName}}" />
    <input name="cardNo" type="hidden" id="cardNo" value="{{$cardNo}}" />
    <input name="cardType" type="hidden" id="cardType" value="{{$cardType}}" />
    <input name="amount" type="hidden" id="amount" value="{{$amount}}" />
    <input name="returnUrl" type="hidden" id="returnUrl" value="{{$returnUrl}}" />
    <input name="noticeUrl" type="hidden" id="noticeUrl" value="{{$noticeUrl}}" />
    <input name="bankNo" type="hidden" id="bankNo" value="{{$bankNo}}" />
    <input name="bankCode" type="hidden" id="bankCode" value="{{$bankCode}}" />
    <input name="bankName" type="hidden" id="bankName" value="{{$bankName}}" />
    <input name="merchantName" type="hidden" id="merchantName" value="{{$merchantName}}" />
    <input name="productName" type="hidden" id="productName" value="{{$productName}}" />
    <input name="service" type="hidden" id="service" value="{{$service}}" />
    <input name="sign" type="hidden" id="sign" value="{{$sign}}" />
</form>

<script type="text/javascript">
    document.orderForm.submit();
</script>
</body>
</html>